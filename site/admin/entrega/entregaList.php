<?php
include '../database/db.class.php';
include '../header.php';

$db = new db('entrega');
$data = null;

if (!empty($_GET['delete'])) {
    try {
        $db->destroy($_GET['delete']);
        echo "<div class='background: #E8F5E9; color: #43A047; padding: 1rem; border-radius: 15px; margin: 1rem 0; border-left: 4px solid #43A047;'>Entrega excluída com sucesso!</div>";
        echo "<script>setTimeout(() => window.location.href='entregaList.php', 1500);</script>";
    } catch (Exception $e) {
        echo "<div style='background: #FFEBEE; color: #F44336; padding: 1rem; border-radius: 15px; margin: 1rem 0; border-left: 4px solid #F44336;'>Erro ao excluir: {$e->getMessage()}</div>";
    }
}
if (!empty($_POST)) {
    $entregas = $db->search($_POST);
} else {
    $entregas = $db->all();
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Entregas - Santi'Lac</title>
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
        
        .text-center {
            text-align: center;
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
        
        .mb-4 {
            margin-bottom: 1.5rem;
        }
        .table, .table-bordered, .table-striped {
            all: unset !important;
        }
        
        .btn, .btn-success, .btn-primary, .btn-danger {
            all: unset !important;
        }
    </style>
</head>

<body class="admin-body">
    <div class="admin-container">
        <div class="admin-header">
            <h1 class="admin-title">Lista de Entregas</h1>
            <p class="admin-subtitle">Gerencie todas as entregas do sistema</p>
        </div>

        <form action="./entregaList.php" method="post" class="search-form">
            <div class="row">
                <div class="col">
                    <select name="tipo" class="form-select">
                        <option value="nome_motorista">Motorista</option>
                        <option value="placa">Placa</option>
                        <option value="origem">Origem</option>
                        <option value="destino">Destino</option>
                        <option value="data_inicio">Data de Início</option>
                        <option value="data_fim">Data do Fim</option>
                    </select>
                </div>

                <div class="col">
                    <input type="text" name="valor" placeholder="Pesquisar" class="form-control" value="<?= !empty($_POST['valor']) ? htmlspecialchars($_POST['valor']) : '' ?>">
                </div>

                <div class="col">
                    <button type="submit" class="btn-admin btn-primary-admin">Buscar</button>
                    <a href="./entregaForm.php" class="btn-admin btn-success-admin">Cadastrar</a>
                </div>
            </div>
        </form>

        <div class="admin-table-container">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Motorista</th>
                        <th>Placa</th>
                        <th>Origem</th>
                        <th>Destino</th>
                        <th>Data Início</th>
                        <th>Data Fim</th>
                        <th>Ações</th>
                    </tr>
                </thead>

                <tbody>
                <?php if (empty($entregas)): ?>
                    <tr>
                        <td colspan="8" class="text-center">Nenhuma entrega encontrada</td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($entregas as $e): ?>
                    <tr>
                        <td><?= $e->id ?></td>
                        <td><?= $e->nome_motorista ?></td>
                        <td><?= $e->placa ?></td>
                        <td><?= $e->origem ?></td>
                        <td><?= $e->destino ?></td>
                        <td><?= date('d/m/Y', strtotime($e->data_inicio)) ?></td>
                        <td><?= $e->data_fim ? date('d/m/Y', strtotime($e->data_fim)) : '-' ?></td>
                        <td>
                            <a class="btn-action btn-edit" href="entregaForm.php?id=<?= $e->id ?>">Editar</a>
                            <a class="btn-action btn-delete" href="?delete=<?= $e->id ?>" onclick="return confirm('Tem certeza que deseja excluir esta entrega?');">Excluir</a>
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
</body>
</html>