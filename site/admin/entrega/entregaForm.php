<?php
include '../database/db.class.php';
include '../header.php';

$db = new db('entrega');
$data = null;

if (!empty($_POST)) {
    try {
        $errors = [];

        if (empty($_POST['nome_motorista'])) $errors[] = 'O nome do motorista é obrigatório';
        if (empty($_POST['placa'])) $errors[] = 'A placa é obrigatória';
        if (empty($_POST['origem'])) $errors[] = 'A origem é obrigatória';
        if (empty($_POST['destino'])) $errors[] = 'O destino é obrigatório';
        if (empty($_POST['data_inicio'])) $errors[] = 'A data de início é obrigatória';

        if (empty($errors)) {
            if (empty($_POST['id'])) {
                unset($_POST['id']);
                $db->store($_POST);
                echo "<div class='msg-sucesso'>Entrega cadastrada com sucesso!</div>";
            } else {
                $db->update($_POST);
                echo "<div class='msg-sucesso'>Entrega atualizada com sucesso!</div>";
            }

            echo "<script>setTimeout(()=> window.location.href='entregaList.php', 2000);</script>";
        } else {
            foreach ($errors as $error) {
                echo "<div class='error-msg'>$error</div>";
            }
        }
    } catch (Exception $e) {
        echo "<div class='error-msg'>Erro: " . $e->getMessage() . "</div>";
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
    <title>Entrega - Santi'Lac</title>
    <style>
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }
        .msg-sucesso{
            background: linear-gradient(135deg, #1E88E5, #43A047);
            color: white;
            padding: 1.5rem;
            border-radius: 15px;
            margin: 15px;
            text-align: center;
            justify-self: center;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
            width: 70%;
        }
        .error-msg{
            background: #FFEBEE;
            color: #F44336;
            padding: 1rem; 
            border-radius: 15px; 
            margin: 1rem 0; border-left: 4px 
            solid #F44336;
        }
        .sucesso-msg{
            style='background: #E8F5E9;
            color: #43A047; 
            padding: 1rem; 
            border-radius: 15px; 
            margin: 1rem 2rem; 
            border-left: 4px solid #43A047;
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
            <h1 class="admin-title"><?= (!empty($data) ? 'Editar Entrega' : 'Cadastrar Entrega') ?></h1>
            <p class="admin-subtitle"><?= (!empty($data) ? 'Atualize os dados da entrega' : 'Preencha os dados da nova entrega') ?></p>
        </div>

        <form action="" method="post" class="admin-form">
            <input type="hidden" name="id" value="<?= $data->id ?? '' ?>">

            <div class="row form-row">
                <div class="col-md-6">
                    <label class="form-label">Nome do Motorista</label>
                    <input class="form-control" type="text" name="nome_motorista" value="<?= $data->nome_motorista ?? '' ?>" required>
                </div>

                <div class="col-md-6">
                    <label class="form-label">Placa</label>
                    <input class="form-control" type="text" name="placa" value="<?= $data->placa ?? '' ?>" required>
                </div>
            </div>

            <div class="row form-row">
                <div class="col-md-6">
                    <label class="form-label">Origem</label>
                    <input class="form-control" type="text" name="origem" value="<?= $data->origem ?? '' ?>" required>
                </div>

                <div class="col-md-6">
                    <label class="form-label">Destino</label>
                    <input class="form-control" type="text" name="destino" value="<?= $data->destino ?? '' ?>" required>
                </div>
            </div>

            <div class="row form-row">
                <div class="col-md-6">
                    <label class="form-label">Data de Início</label>
                    <input class="form-control" type="date" name="data_inicio" value="<?= $data->data_inicio ?? '' ?>" required>
                </div>

                <div class="col-md-6">
                    <label class="form-label">Data de Fim</label>
                    <input class="form-control" type="date" name="data_fim" value="<?= $data->data_fim ?? '' ?>">
                </div>
            </div>

            <div class="row">
                <div class="col mt-4">
                    <button type="submit" class="btn-admin btn-success-admin">Salvar Entrega</button>
                    <a href="entregaList.php" class="btn-admin btn-secondary-admin">Voltar</a>
                </div>
            </div>
        </form>
    </div>
</body>
</html>