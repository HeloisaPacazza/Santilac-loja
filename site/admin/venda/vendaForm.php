<?php
include '../header.php';
include '../database/db.class.php';

$dbVenda = new db('venda');
$dbItens = new db('itens_venda');
$dbProd = new db('produto');
$dbFunc = new db('funcionario');

$data = null;
$editing = false;
$venda_id = null;

$produtos = $dbProd->all();
$funcionarios = $dbFunc->all();

if (!empty($_GET['id'])) {
    $editing = true;
    $venda_id = (int)$_GET['id'];

    $conn = $dbVenda->conn();
    $st = $conn->prepare("SELECT * FROM venda WHERE id = ?");
    $st->execute([$venda_id]);
    $data = $st->fetch(PDO::FETCH_OBJ);

    $st = $conn->prepare("SELECT iv.*, p.tipo, p.preco FROM itens_venda iv JOIN produto p ON iv.produto_id = p.id WHERE iv.venda_id = ?");
    $st->execute([$venda_id]);
    $itens_edit = $st->fetchAll(PDO::FETCH_ASSOC);
} else {
    $itens_edit = [];
}

if (!empty($_POST)) {
    try {
        $conn = $dbVenda->conn();
        $conn->beginTransaction();

        if (empty($_POST['funcionario_id'])) throw new Exception("Selecione um funcionário.");
        if (empty($_POST['produto_id']) || !is_array($_POST['produto_id'])) throw new Exception("Adicione pelo menos um item.");

        $funcionario_id = (int)$_POST['funcionario_id'];
        $produto_ids = $_POST['produto_id'];
        $qtds = $_POST['qtd'];

        if ($editing) {
            $st = $conn->prepare("SELECT produto_id, qtd FROM itens_venda WHERE venda_id = ?");
            $st->execute([$venda_id]);
            $oldItens = $st->fetchAll(PDO::FETCH_ASSOC);

            $updateStmt = $conn->prepare("UPDATE produto SET qtd = qtd + ? WHERE id = ?");
            foreach ($oldItens as $oi) {
                $updateStmt->execute([(int)$oi['qtd'], (int)$oi['produto_id']]);
            }

            $delItens = $conn->prepare("DELETE FROM itens_venda WHERE venda_id = ?");
            $delItens->execute([$venda_id]);
        }

        if (!$editing) {
            $st = $conn->prepare("INSERT INTO venda (data_venda, funcionario_id, valor_total) VALUES (NOW(), ?, 0)");
            $st->execute([$funcionario_id]);
            $venda_id = $conn->lastInsertId();
        } else {
            $st = $conn->prepare("UPDATE venda SET funcionario_id = ?, data_venda = NOW() WHERE id = ?");
            $st->execute([$funcionario_id, $venda_id]);
        }

        $total = 0.0;
        $selectProduto = $conn->prepare("SELECT id, preco, qtd FROM produto WHERE id = ?");
        $insertItem = $conn->prepare("INSERT INTO itens_venda (venda_id, produto_id, qtd, preco_unitario, subtotal) VALUES (?, ?, ?, ?, ?)");
        $updateStock = $conn->prepare("UPDATE produto SET qtd = qtd - ? WHERE id = ?");

        for ($i = 0; $i < count($produto_ids); $i++) {
            $pid = (int)$produto_ids[$i];
            $q = (int)$qtds[$i];
            if ($q <= 0) continue;

            $selectProduto->execute([$pid]);
            $p = $selectProduto->fetch(PDO::FETCH_ASSOC);
            if (!$p) throw new Exception("Produto ID $pid não encontrado.");

            if ((int)$p['qtd'] < $q) throw new Exception("Estoque insuficiente para produto ID $pid.");

            $preco = (float)$p['preco'];
            $subtotal = $preco * $q;
            $total += $subtotal;

            $insertItem->execute([$venda_id, $pid, $q, $preco, $subtotal]);
            $updateStock->execute([$q, $pid]);
        }

        $upd = $conn->prepare("UPDATE venda SET valor_total = ? WHERE id = ?");
        $upd->execute([$total, $venda_id]);

        $conn->commit();

        echo "<div style='background: #E8F5E9; color: #43A047; padding: 1rem; border-radius: 15px; margin: 1rem 0; border-left: 4px solid #43A047;'>Venda salva com sucesso! Total: R$ " . number_format($total, 2, ',', '.') . "</div>";
        echo "<script>setTimeout(()=>window.location.href='vendasList.php', 1500)</script>";
        exit;

    } catch (Exception $e) {
        if ($conn && $conn->inTransaction()) $conn->rollBack();
        echo "<div style='background: #FFEBEE; color: #F44336; padding: 1rem; border-radius: 15px; margin: 1rem 0; border-left: 4px solid #F44336;'>Erro: " . htmlspecialchars($e->getMessage()) . "</div>";
    }
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Venda - Santi'Lac</title>
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
        
        .admin-form {
            background: rgba(255, 255, 255, 0.95);
            border-radius: 20px;
            padding: 2rem;
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.08);
            border: 1px solid #ECEFF1;
            margin-bottom: 2rem;
        }
        
        .form-label {
            font-weight: 600;
            color: #263238;
            margin-bottom: 0.5rem;
            display: block;
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
        
        .btn-success-admin {
            background: linear-gradient(135deg, #1E88E5, #43A047);
            color: white;
        }
        
        .btn-success-admin:hover {
            transform: scale(1.05);
            box-shadow: 0 5px 15px rgba(30, 136, 229, 0.4);
        }
        
        .btn-secondary-admin {
            background: #78909C;
            color: white;
        }
        
        .btn-secondary-admin:hover {
            background: #263238;
            transform: scale(1.05);
        }
        
        .btn-sm {
            padding: 8px 16px;
            font-size: 0.875rem;
        }
        
        .btn-danger {
            background: #F44336;
            color: white;
        }
        
        .btn-danger:hover {
            background: #d32f2f;
        }
        
        .admin-table {
            width: 100%;
            border-collapse: collapse;
            border-radius: 10px;
            overflow: hidden;
            margin-bottom: 1rem;
        }
        
        .admin-table th {
            background: #E3F2FD;
            color: #263238;
            padding: 1rem;
            text-align: left;
            font-weight: 600;
        }
        
        .admin-table td {
            padding: 1rem;
            border-bottom: 1px solid #ECEFF1;
            color: #263238;
        }
        
        .preco-cell, .subtotal-cell {
            font-weight: 600;
            color: #43A047;
        }
        
        .row {
            display: flex;
            flex-wrap: wrap;
            margin: 0 -10px;
        }
        
        .col-6 {
            flex: 0 0 50%;
            padding: 0 10px;
            margin-bottom: 1rem;
        }
        
        .col {
            flex: 1;
            padding: 0 10px;
        }
        
        .mb-2 {
            margin-bottom: 0.5rem;
        }
        
        .mb-3 {
            margin-bottom: 1rem;
        }
        
        .mt-4 {
            margin-top: 1.5rem;
        }
        
        h5 {
            color: #263238;
            margin-bottom: 1rem;
            font-size: 1.25rem;
        }
    </style>
</head>

<body class="admin-body">
    <div class="admin-container">
        <div class="admin-header">
            <h1 class="admin-title"><?= $editing ? "Editar Venda #{$venda_id}" : "Cadastrar Venda" ?></h1>
            <p class="admin-subtitle"><?= $editing ? "Atualize os dados da venda" : "Registre uma nova venda" ?></p>
        </div>

        <form method="post" action="" class="admin-form">
            <div class="row mb-2">
                <div class="col-6">
                    <label class="form-label">Funcionário</label>
                    <select name="funcionario_id" class="form-select" required>
                        <option value="">-- selecione --</option>
                        <?php foreach ($funcionarios as $f) {
                            $sel = (!empty($data) && $data->funcionario_id == $f->id) ? 'selected' : '';
                            echo "<option value='{$f->id}' $sel>{$f->nome}</option>";
                        } ?>
                    </select>
                </div>
            </div>

            <h5>Itens da Venda</h5>
            <table class="admin-table" id="itensTable">
                <thead>
                    <tr>
                        <th>Produto</th>
                        <th>Preço (R$)</th>
                        <th>Quantidade</th>
                        <th>Subtotal</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    if (!empty($itens_edit)) {
                        foreach ($itens_edit as $it) {
                            $preco = number_format($it['preco'], 2, ',', '.');
                            $subtotal = number_format($it['preco'] * $it['qtd'], 2, ',', '.');
                            echo "<tr>
                                <td>
                                    <select name='produto_id[]' class='form-select produto-select'>
                                        <option value='{$it['produto_id']}' data-preco='{$it['preco']}' selected>{$it['tipo']}</option>
                                    </select>
                                </td>
                                <td class='preco-cell'>R$ <span>{$preco}</span></td>
                                <td><input type='number' name='qtd[]' value='{$it['qtd']}' class='form-control qtd-input' min='1'></td>
                                <td class='subtotal-cell'>R$ <span>{$subtotal}</span></td>
                                <td><button type='button' class='btn-admin btn-danger btn-sm remove-row'>X</button></td>
                            </tr>";
                        }
                    }
                    ?>
                </tbody>
            </table>

            <button type="button" id="addItem" class="btn-admin btn-secondary-admin btn-sm mb-3">+ Adicionar item</button>

            <div class="row">
                <div class="col">
                    <button class="btn-admin btn-success-admin" type="submit"><?= $editing ? "Atualizar Venda" : "Finalizar Venda" ?></button>
                    <a href="vendasList.php" class="btn-admin btn-secondary-admin">Voltar</a>
                </div>
            </div>
        </form>
    </div>

    <script>
    const produtos = <?php echo json_encode($produtos); ?>;

    function buildProductOptions(selectedId = null) {
        let html = "<option value=''>-- selecione --</option>";
        produtos.forEach(p => {
            const preco = parseFloat(p.preco).toFixed(2).replace('.', ',');
            const sel = (selectedId && selectedId == p.id) ? "selected" : "";
            html += `<option value="${p.id}" data-preco="${p.preco}" ${sel}>${p.tipo} — R$ ${preco}</option>`;
        });
        return html;
    }

    document.getElementById('addItem').addEventListener('click', () => {
        const tbody = document.querySelector('#itensTable tbody');
        const row = document.createElement('tr');
        row.innerHTML = `
            <td>
                <select name="produto_id[]" class="form-select produto-select">
                    ${buildProductOptions()}
                </select>
            </td>
            <td class="preco-cell">R$ <span>0,00</span></td>
            <td><input type="number" name="qtd[]" value="1" class="form-control qtd-input" min="1"></td>
            <td class="subtotal-cell">R$ <span>0,00</span></td>
            <td><button type="button" class="btn-admin btn-danger btn-sm remove-row">X</button></td>
        `;
        tbody.appendChild(row);
    });

    document.addEventListener('change', function(e){
        if (e.target.classList.contains('produto-select')) {
            const option = e.target.selectedOptions[0];
            const preco = parseFloat(option.dataset.preco || 0);
            const precoText = preco.toFixed(2).replace('.', ',');
            const row = e.target.closest('tr');
            row.querySelector('.preco-cell span').textContent = precoText;
            const qtd = parseInt(row.querySelector('.qtd-input').value) || 1;
            row.querySelector('.subtotal-cell span').textContent = (preco * qtd).toFixed(2).replace('.', ',');
        }
    });

    document.addEventListener('input', function(e){
        if (e.target.classList.contains('qtd-input')) {
            const row = e.target.closest('tr');
            const preco = parseFloat(row.querySelector('.produto-select').selectedOptions[0]?.dataset.preco || 0);
            const qtd = parseInt(e.target.value) || 0;
            row.querySelector('.subtotal-cell span').textContent = (preco * qtd).toFixed(2).replace('.', ',');
        }
    });

    document.addEventListener('click', function(e){
        if (e.target.classList.contains('remove-row')) {
            e.target.closest('tr').remove();
        }
    });

    document.querySelectorAll('.produto-select').forEach(select => {
        if (!select.querySelectorAll('option').length || select.querySelectorAll('option').length < produtos.length) {
            select.innerHTML = buildProductOptions(select.value);
            select.dispatchEvent(new Event('change'));
        }
    });
    </script>
</body>
</html>

<?php include '../footer.php'; ?>