<?php
include '../database/db.class.php';
include '../header.php';

$db = new db('funcionario');
$data = null;

if (!empty($_POST)) {
    try {
        $errors = [];

        if (empty($_POST['nome'])) {
            $errors[] = 'O nome é obrigatório';
        }

        if (empty($_POST['cargo'])) {
            $errors[] = 'O cargo é obrigatório';
        }
        if (empty($_POST['idade'])) {
            $errors[] = 'A idade é obrigatório';
        }

        if (!empty($_POST)) {
             $dados = $db->search($_POST);
            } else {
            $dados = $db->all();
        }

        if (!empty($_GET['id'])) {
            $data = $db->find($_GET['id']);
        }

        if (empty($errors)) {
            if (empty($_POST['id'])) {
                unset($_POST['id']);
                $db->store($_POST);
                echo "<div class='sucesso-msg'>Funcionário cadastrado com sucesso!</div>";
            } else {
                $db->update($_POST);
                echo "<div class='sucesso-msg'>Funcionário atualizado com sucesso!</div>";
            }

            echo "<script>setTimeout(() => window.location.href = 'funcionarioList.php', 2000);</script>";
        } else {
            foreach ($errors as $error) {
                echo "<div style='background: #FFEBEE; color: #F44336; padding: 1rem; border-radius: 15px; margin: 1rem 0; border-left: 4px solid #F44336;'>$error</div>";
            }
        }
    } catch (Exception $e) {
        echo "<div style='background: #FFEBEE; color: #F44336; padding: 1rem; border-radius: 15px; margin: 1rem 0; border-left: 4px solid #F44336;'>Erro: " . $e->getMessage() . "</div>";
    }
}

if (!empty($_GET['id'])) {
    $data = $db->find($_GET['id']);
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Funcionário - Santi'Lac</title>
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
        .sucesso-msg{
            background: linear-gradient(135deg, #1E88E5, #43A047);
            color: white;
            padding: 1.5rem;
            border-radius: 15px;
            margin: 15px;
            text-align: center;
            justify-self: center;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
            width: %;
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
        
        .form-row {
            margin-bottom: 1.5rem;
        }
        
        .row {
            display: flex;
            flex-wrap: wrap;
            margin: 0 -10px;
        }
        
        .col-md-6 {
            flex: 0 0 50%;
            padding: 0 10px;
        }
        
        .mt-4 {
            margin-top: 1.5rem;
        }
        
        .mt-3 {
            margin-top: 1rem;
        }
        
        .col {
            flex: 1;
            padding: 0 10px;
        }
    </style>
</head>

<body class="admin-body">
    <div class="admin-container">
        <div class="admin-header">
            <h1 class="admin-title"><?= (!empty($data) ? 'Editar Funcionário' : 'Cadastrar Funcionário') ?></h1>
            <p class="admin-subtitle"><?= (!empty($data) ? 'Atualize os dados do funcionário' : 'Preencha os dados do novo funcionário') ?></p>
        </div>

        <form action="" method="post" class="admin-form">
            <input type="hidden" name="id" value="<?= $data->id ?? '' ?>">

            <div class="row form-row">
                <div class="col-md-6">
                    <label class="form-label">Nome</label>
                    <input class="form-control" type="text" name="nome" value="<?= $data->nome ?? '' ?>" required>
                </div>
                 <div class="col-md-6">
                    <label class="form-label">Idade</label>
                    <input class="form-control" type="text" name="idade" value="<?= $data->idade ?? '' ?>" required>
                </div>
            </div>

            <div class="row form-row">
                <div class="col-md-6">
                    <label class="form-label">Cargo</label>
                    <select name="cargo" class="form-select" required>
                        <option value="">Selecione...</option>
                        <option value="operador de máquina" <?= isset($data->cargo) && $data->cargo == "operador de máquina" ? "selected" : "" ?>>Operador de máquina</option>
                        <option value="queijeiro" <?= isset($data->cargo) && $data->cargo == "queijeiro" ? "selected" : "" ?>>Queijeiro</option>
                        <option value="embalador" <?= isset($data->cargo) && $data->cargo == "embalador" ? "selected" : "" ?>>Embalador</option>
                        <option value="gerente de produção" <?= isset($data->cargo) && $data->cargo == "gerente de produção" ? "selected" : "" ?>>Gerente de produção</option>
                        <option value="analista de laboratório" <?= isset($data->cargo) && $data->cargo == "analista de laboratório" ? "selected" : "" ?>>Analista de laboratório</option>
                        <option value="auxiliar de expedição" <?= isset($data->cargo) && $data->cargo == "auxiliar de expedição" ? "selected" : "" ?>>Auxiliar de expedição</option>
                        <option value="promotor de vendas" <?= isset($data->cargo) && $data->cargo == "promotor de vendas" ? "selected" : "" ?>>Promotor de vendas</option>
                    </select>
                </div>
            </div>

            <div class="row">
                <div class="col mt-4">
                    <button type="submit" class="btn-admin btn-success-admin">Salvar Funcionário</button>
                    <a href="funcionarioList.php" class="btn-admin btn-secondary-admin">Voltar</a>
                </div>
            </div>
        </form>
    </div>
</body>
</html>