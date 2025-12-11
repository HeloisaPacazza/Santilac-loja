<?php
include '../header.php';
include '../database/db.class.php';

$db = new db('venda');
$conn = $db->conn();
helo

if (!empty($_GET['delete'])) {
    $id = (int)$_GET['delete'];
    
    try {
        $conn = $db->conn();
        $conn->beginTransaction();


        $st = $conn->prepare("SELECT produto_id, qtd FROM itens_venda WHERE venda_id = ?");
        $st->execute([$id]);
        $itens = $st->fetchAll(PDO::FETCH_ASSOC);

        
        $upd = $conn->prepare("UPDATE produto SET qtd = qtd + ? WHERE id = ?");
        foreach ($itens as $it) {
            $upd->execute([(int)$it['qtd'], (int)$it['produto_id']]);
        }

        $conn->prepare("DELETE FROM itens_venda WHERE venda_id = ?")->execute([$id]);
        
       
        $db->destroy($id);

        $conn->commit();

        echo "<div style='background: #E8F5E9; color: #43A047; padding: 1rem; border-radius: 15px; margin: 1rem 0; border-left: 4px solid #43A047;'>
                Venda excluída e estoque restaurado!
              </div>";

        echo "<script>
                setTimeout(() => {
                    window.location.href = 'vendasList.php';
                }, 1500);
              </script>";

    } catch (Exception $e) {
        if (isset($conn)) {
            $conn->rollBack();
        }
        echo "<div style='background: #FFEBEE; color: #F44336; padding: 1rem; border-radius: 15px; margin: 1rem 0; border-left: 4px solid #F44336;'>
                 Erro: " . htmlspecialchars($e->getMessage()) . "
              </div>";
    }
}


if (!empty($_POST['tipo']) && !empty($_POST['valor'])) {

    $campo = $_POST['tipo'];
    $valor = trim($_POST['valor']);
    

    $mapCampos = [
        'id' => 'v.id',
        'funcionario' => 'f.nome',
        'data_venda' => 'v.data_venda',
        'valor_total' => 'v.valor_total'
    ];
    
   
    if (isset($mapCampos[$campo])) {
        $coluna = $mapCampos[$campo];
        
      
        $sql = "SELECT v.*, f.nome AS funcionario 
                FROM venda v 
                LEFT JOIN funcionario f ON v.funcionario_id = f.id";
        
      
        if ($campo === 'id') {
        
            $sql .= " WHERE v.id = ?";
            $params = [(int)$valor];
        } elseif ($campo === 'data_venda') {
        
            if (preg_match('/^\d{2}\/\d{2}\/\d{4}$/', $valor)) {
             
                $data_sql = DateTime::createFromFormat('d/m/Y', $valor)->format('Y-m-d');
                $sql .= " WHERE DATE(v.data_venda) = ?";
                $params = [$data_sql];
            } else {
               
                $sql .= " WHERE DATE(v.data_venda) = ?";
                $params = [$valor];
            }
        } else {
           
            $sql .= " WHERE $coluna LIKE ?";
            $params = ["%$valor%"];
        }
        
        $sql .= " ORDER BY v.id DESC";
        
        $st = $conn->prepare($sql);
        $st->execute($params);
        $vendas = $st->fetchAll(PDO::FETCH_OBJ);
        
    } else {
        $sql = "SELECT v.*, f.nome AS funcionario 
                FROM venda v 
                LEFT JOIN funcionario f ON v.funcionario_id = f.id 
                ORDER BY v.id DESC";
        $st = $conn->prepare($sql);
        $st->execute();
        $vendas = $st->fetchAll(PDO::FETCH_OBJ);
    }
    
} else {
    $sql = "SELECT v.*, f.nome AS funcionario 
            FROM venda v 
            LEFT JOIN funcionario f ON v.funcionario_id = f.id 
            ORDER BY v.id DESC";
    $st = $conn->prepare($sql);
    $st->execute();
    $vendas = $st->fetchAll(PDO::FETCH_OBJ);
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Vendas - Santi'Lac</title>
    <style>
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }
        
        body {
            background: linear-gradient(135deg, #FFF9F0 0%, #E3F2FD 100%);
            min-height: 100vh;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            padding: 0;
            margin: 0;
        }
        
        .admin-container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 2rem;
        }
        
        .admin-header {
            text-align: center;
            margin-bottom: 2rem;
            padding: 2rem;
            background: rgba(255, 255, 255, 0.95);
            border-radius: 20px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            border: 1px solid #ECEFF1;
        }
        
        .admin-title {
            color: #263238;
            font-size: 2.5rem;
            margin-bottom: 0.5rem;
            font-weight: 700;
        }
        
        .admin-subtitle {
            color: #78909C;
            font-size: 1.2rem;
            font-weight: 400;
        }
        
        .search-form {
            background: rgba(255, 255, 255, 0.95);
            border-radius: 15px;
            padding: 1.5rem;
            margin-bottom: 2rem;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.08);
            border: 1px solid #ECEFF1;
        }
        
        .btn-admin {
            padding: 12px 30px;
            border-radius: 50px;
            font-weight: 600;
            text-decoration: none;
            display: inline-block;
            transition: all 0.3s ease;
            border: none;
            cursor: pointer;
            font-family: inherit;
            margin-right: 10px;
        }
        
        .btn-primary-admin {
            background: linear-gradient(135deg, #1E88E5, #43A047);
            color: white;
        }
        
        .btn-primary-admin:hover {
            transform: scale(1.05);
            box-shadow: 0 5px 15px rgba(30, 136, 229, 0.4);
        }
        
        .btn-success-admin {
            background: linear-gradient(135deg, #4CAF50, #43A047);
            color: white;
        }
        
        .btn-success-admin:hover {
            transform: scale(1.05);
            box-shadow: 0 5px 15px rgba(76, 175, 80, 0.4);
        }
        
        .btn-secondary-admin {
            background: #78909C;
            color: white;
        }
        
        .btn-secondary-admin:hover {
            background: #263238;
            transform: scale(1.05);
        }
        
        .admin-table-container {
            background: rgba(255, 255, 255, 0.95);
            border-radius: 20px;
            padding: 2rem;
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.08);
            border: 1px solid #ECEFF1;
            margin-bottom: 2rem;
            overflow: hidden;
        }
        
        .admin-table {
            width: 100%;
            border-collapse: collapse;
            border-radius: 15px;
            overflow: hidden;
        }
        
        .admin-table thead {
            background: linear-gradient(135deg, #1E88E5, #43A047);
        }
        
        .admin-table th {
            color: white;
            padding: 1rem;
            text-align: left;
            font-weight: 600;
            font-size: 1rem;
        }
        
        .admin-table td {
            padding: 1rem;
            border-bottom: 1px solid #ECEFF1;
            color: #263238;
        }
        
        .admin-table tbody tr {
            transition: all 0.3s ease;
        }
        
        .admin-table tbody tr:hover {
            background: #E3F2FD;
            transform: translateX(5px);
        }
        
        .btn-action {
            padding: 8px 16px;
            border-radius: 25px;
            font-size: 0.875rem;
            font-weight: 500;
            text-decoration: none;
            display: inline-block;
            transition: all 0.3s ease;
            border: none;
            margin: 0 2px;
            font-family: inherit;
        }
        
        .btn-edit {
            background: #1E88E5;
            color: white;
        }
        
        .btn-edit:hover {
            background: #1565C0;
            transform: scale(1.05);
        }
        
        .btn-delete {
            background: #F44336;
            color: white;
        }
        
        .btn-delete:hover {
            background: #d32f2f;
            transform: scale(1.05);
        }
        
        .btn-details {
            background: #43A047;
            color: white;
        }
        
        .btn-details:hover {
            background: #388E3C;
            transform: scale(1.05);
        }
        
        .row {
            display: flex;
            flex-wrap: wrap;
            margin: 0 -10px;
        }
        
        .col {
            flex: 1;
            padding: 0 10px;
            margin-bottom: 1rem;
        }
        
        .form-control, .form-select {
            border: 2px solid #ECEFF1;
            border-radius: 10px;
            padding: 12px 15px;
            font-size: 1rem;
            transition: all 0.3s ease;
            background: #F8F9FA;
            width: 100%;
            font-family: inherit;
        }
        
        .form-control:focus, .form-select:focus {
            border-color: #1E88E5;
            box-shadow: 0 0 0 3px rgba(30, 136, 229, 0.1);
            outline: none;
        }
        
        .d-flex {
            display: flex;
        }
        
        .justify-content-between {
            justify-content: space-between;
        }
        
        .align-items-center {
            align-items: center;
        }
        
        .mb-3 {
            margin-bottom: 1rem;
        }
        
        .mb-4 {
            margin-bottom: 1.5rem;
        }
        
        .text-center {
            text-align: center;
        }
    </style>
</head>

<body class="admin-body">
    <div class="admin-container">
        <div class="admin-header">
            <h1 class="admin-title">Lista de Vendas</h1>
            <p class="admin-subtitle">Gerencie todas as vendas do sistema</p>
        </div>

        <form action="./vendasList.php" method="post" class="search-form">
            <div class="row">
                <div class="col">
                    <select name="tipo" class="form-select" id="campoBusca">
                        <option value="">Selecione o campo...</option>
                        <option value="funcionario" <?= (!empty($_POST['tipo']) && $_POST['tipo'] === 'funcionario') ? 'selected' : '' ?>>Funcionário</option>
                        <option value="data_venda" <?= (!empty($_POST['tipo']) && $_POST['tipo'] === 'data_venda') ? 'selected' : '' ?>>Data (dd/mm/aaaa)</option>
                        <option value="valor_total" <?= (!empty($_POST['tipo']) && $_POST['tipo'] === 'valor_total') ? 'selected' : '' ?>>Valor Total</option>
                        <option value="id" <?= (!empty($_POST['tipo']) && $_POST['tipo'] === 'id') ? 'selected' : '' ?>>ID da Venda</option>
                    </select>
                </div>

                <div class="col">
                    <input type="text" name="valor" placeholder="Digite para buscar..." class="form-control" 
                           id="valorBusca"
                           value="<?= !empty($_POST['valor']) ? htmlspecialchars($_POST['valor']) : '' ?>"
                           title="Para data: use dd/mm/aaaa">
                </div>

                <div class="col">
                    <button type="submit" class="btn-admin btn-primary-admin">Buscar</button>
                    <a href="./vendaForm.php" class="btn-admin btn-success-admin">Nova Venda</a>
                    <!--<a href="./vendasList.php" class="btn-admin btn-secondary-admin">Limpar</a>-->
                </div>
            </div>
        </form>

        <div class="admin-table-container">
            
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Data</th>
                        <th>Funcionário</th>
                        <th>Valor Total</th>
                        <th>Ações</th>
                    </tr>
                </thead>

                <tbody>
                    <?php if (empty($vendas)): ?>
                        <tr>
                            <td colspan="5" class="text-center">
                                <?php if (!empty($_POST['tipo']) && !empty($_POST['valor'])): ?>
                                    Nenhuma venda encontrada para essa busca.
                                <?php else: ?>
                                    Nenhuma venda cadastrada ainda.
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($vendas as $v): ?>
                            <tr>
                                <td><?= $v->id ?></td>
                                <td><?= date('d/m/Y H:i', strtotime($v->data_venda)) ?></td>
                                <td><?= htmlspecialchars($v->funcionario ?? 'N/A') ?></td>
                                <td>R$ <?= number_format($v->valor_total, 2, ',', '.') ?></td>
                                <td>
                                    <a class="btn-action btn-edit" href="vendaForm.php?id=<?= $v->id ?>">Editar</a>
                                    <a class="btn-action btn-delete" href="?delete=<?= $v->id ?>" 
                                    onclick="return confirm('Tem certeza que deseja excluir esta venda? O estoque será restaurado.')">
                                    Excluir
                                    </a>
                                </td>
                            </tr>   
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        <div class="text-center">
            <a href="../main.php" class="btn-admin btn-secondary-admin">Voltar ao Painel</a>
        </div>
    </div>
    
    <script>
    document.getElementById('campoBusca').addEventListener('change', function() {
        const campo = this.value;
        const input = document.getElementById('valorBusca');
        
        switch(campo) {
            case 'id':
                input.placeholder = 'Digite o número da venda...';
                input.title = 'Busca pelo número exato da venda';
                break;
            case 'data_venda':
                input.placeholder = 'Digite a data (dd/mm/aaaa)...';
                input.title = 'Formato: dd/mm/aaaa (ex: 25/12/2023)';
                break;
            case 'valor_total':
                input.placeholder = 'Digite o valor (use . para decimal)...';
                input.title = 'Ex: 150.50 para R$ 150,50';
                break;
            case 'funcionario':
                input.placeholder = 'Digite parte do nome...';
                input.title = 'Busca por parte do nome do funcionário';
                break;
            default:
                input.placeholder = 'Digite para buscar...';
                input.title = '';
        }
    });
    </script>
</body>
</html>

<?php include '../footer.php'; ?>