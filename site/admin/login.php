<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

include 'database/db.class.php';

$db = new db('usuario');
$data = null;

if (!empty($_POST)) {
    try {
        $errors = [];

        if (empty($_POST['login'])) {
            $errors[] = 'O login é obrigatório';
        }

        if (empty($_POST['senha'])) {
            $errors[] = 'A senha é obrigatória';
        }

        if (empty($errors)) {

            $result = $db->login($_POST);

            if ($result !== 'error') {
                session_start();
                $_SESSION['usuario_id'] = $result->id;
                $_SESSION['login'] = $result->login ?? '';
                $_SESSION['senha'] = $result->senha ?? '';

                echo "<div class='alert-admin login-success'>Login realizado com sucesso! <script>
                            setTimeout(function(){
                                window.location.href = 'main.php';
                            },2000);
                        </script></div>";
             } else {
                echo "<div class='alert-admin alert-danger-admin'>Login ou senha incorretos!</div>";
            }
        } else {
            foreach ($errors as $e) {
                echo "<div class='alert-admin alert-danger-admin'>$e</div>";
            }
        }

    } catch (Exception $e) {
        echo "<div class='alert-admin alert-danger-admin'>Erro: " . $e->getMessage() . "</div>";
    }
}
?>

<head>
    <meta charset="UTF-8">
    <title>Login - Santi'Lac</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .login-success {
            background: linear-gradient(135deg, #1E88E5, #43A047);
            color: white;
            padding: 1.5rem;
            border-radius: 15px;
            margin-top: 25px;
            margin-bottom: 2rem;
            text-align: center;
            justify-self: center;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
            width: 20%;
        }
        .login-container {
            min-height: 100vh;
            background: linear-gradient(135deg, var(--primary-cream) 0%, var(--secondary-light-blue) 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 2rem;
        }
        
        .login-card {
            background: rgba(255, 255, 255, 0.95);
            border-radius: 20px;
            padding: 3rem;
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.1);
            border: 1px solid var(--neutral-light);
            width: 100%;
            max-width: 450px;
            position: relative;
            overflow: hidden;
        }
        
        .login-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: linear-gradient(90deg, var(--primary-blue), var(--primary-green));
        }
        
        .login-logo {
            text-align: center;
            margin-bottom: 2rem;
        }
        
        .login-title {
            color: var(--neutral-dark);
            font-size: 2rem;
            font-weight: 700;
            text-align: center;
            margin-bottom: 0.5rem;
        }
        
        .login-subtitle {
            color: var(--neutral-gray);
            text-align: center;
            margin-bottom: 2rem;
        }
        
        .btn-login {
            width: 100%;
            padding: 12px;
            background: linear-gradient(135deg, var(--primary-blue), var(--primary-green));
            color: white;
            border: none;
            border-radius: 50px;
            font-weight: 600;
            font-size: 1.1rem;
            transition: all 0.3s ease;
            margin-bottom: 1rem;
        }
        
        .btn-login:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(30, 136, 229, 0.4);
        }
        
        .login-links {
            text-align: center;
            margin-top: 1.5rem;
        }
        
        .login-link {
            color: var(--primary-blue);
            text-decoration: none;
            margin: 0 10px;
            font-weight: 500;
            transition: color 0.3s ease;
        }
        
        .login-link:hover {
            color: var(--primary-green);
            text-decoration: underline;
        }
    </style>
</head>

<body class="admin-body">
    <div class="login-container">
        <div class="login-card">
            <div class="login-logo">
                <h1 class="login-title">Santi'Lac</h1>
                <p class="login-subtitle">Sistema Administrativo</p>
            </div>

            <form action="" method="post">
                <div class="form-row mb-3">
                    <label class="form-label">Login</label>
                    <input class="form-control" type="text" name="login" placeholder="Digite seu login" required>
                </div>

                <div class="form-row mb-4">
                    <label class="form-label">Senha</label>
                    <input class="form-control" type="password" name="senha" placeholder="Digite sua senha" required>
                </div>
                <div>
                    <button type="submit" class="btn-login text-dark">Entrar</button>
                </div>
                

                
                <div class="login-links">
                    <a href="./usuario/usuarioForm.php" class="login-link">Cadastre-se</a>
                    <a href="../index.php" class="login-link">Voltar ao Site</a>
                </div>
            </form>
        </div>
    </div>
</body>
