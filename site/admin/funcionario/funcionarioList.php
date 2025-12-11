<?php
include '../header.php';
include '../database/db.class.php';

$db = new db('funcionario');
$db->checkLogin();

if (!empty($_GET['id'])) {
    $db->destroy($_GET['id']);
    echo "<div style='background: #E8F5E9; color: #43A047; padding: 1rem; border-radius: 15px; margin: 1rem 0; border-left: 4px solid #43A047;'>Funcionário excluído com sucesso!</div>";
    echo "<script>setTimeout(() => window.location.href='FuncionarioList.php', 1500);</script>";
}

if (!empty($_POST)) {
    $dados = $db->search($_POST);
} else {
    $dados = $db->all();
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Funcionários - Santi'Lac</title>
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
    </style>
</head>

<body class="admin-body">
    <div class="admin-container">
        <div class="admin-header">
            <h1 class="admin-title">Lista de Funcionários</h1>
            <p class="admin-subtitle">Gerencie os funcionários do sistema</p>
        </div>

        <form action="./FuncionarioList.php" method="post" class="search-form">
            <div class="row">
                <div class="col">
                    <select name="tipo" class="form-select">
                        <option value="nome">Nome</option>
                        <option value="cargo">Cargo</option>
                    </select>
                </div>

                <div class="col">
                    <input type="text" name="valor" placeholder="Pesquisar" class="form-control" value="<?= !empty($_POST['valor']) ? htmlspecialchars($_POST['valor']) : '' ?>">
                </div>

                <div class="col">
                    <button type="submit" class="btn-admin btn-primary-admin">Buscar</button>
                    <a href="./FuncionarioForm.php" class="btn-admin btn-success-admin">Cadastrar</a>
                </div>
            </div>
        </form>

        <div class="admin-table-container">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Nome</th>
                        <th>Cargo</th>
                        <th>Idade</th>
                        <th>Ações</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($dados as $item) {
                        echo "<tr>
                            <td>$item->id</td>
                            <td>$item->nome</td>
                            <td>$item->idade</td>
                            <td>$item->cargo</td>
                            <td>
                            <a class='btn-action btn-edit' href='FuncionarioForm.php?id=$item->id'>Editar</a>
                                <a class='btn-action btn-delete' href='./FuncionarioList.php?id=$item->id' onclick='return confirm(\"Deseja Excluir?\")'>Excluir</a>
                            </td>
                        </tr>";
                    } ?>
                </tbody>
            </table>
        </div>
        
        <div class="text-center">
            <a href="../main.php" class="btn-admin btn-secondary-admin">Voltar ao Painel</a>
        </div>
    </div>
</body>
</html>

<?php include '../footer.php'; ?>