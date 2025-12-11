<?php
include './header.php';
include './database/db.class.php';

$db = new db('usuario');
$db->checkLogin();
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Painel Admin - Santi'Lac</title>
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
            margin-bottom: 3rem;
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
        
        .welcome-message {
            background: linear-gradient(135deg, #1E88E5, #43A047);
            color: white;
            padding: 1.5rem;
            border-radius: 15px;
            margin-bottom: 2rem;
            text-align: center;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        }
        
        .welcome-text {
            font-size: 1.2rem;
            margin: 0;
            font-weight: 500;
        }
        
        .dashboard-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 2rem;
            margin-bottom: 2rem;
        }
        
        .dashboard-card {
            background: rgba(255, 255, 255, 0.95);
            border-radius: 20px;
            padding: 2rem;
            text-align: center;
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.08);
            transition: all 0.3s ease;
            border: 1px solid #ECEFF1;
            position: relative;
            overflow: hidden;
        }
        
        .dashboard-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: linear-gradient(90deg, #1E88E5, #43A047);
        }
        
        .dashboard-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.15);
        }
        
        .card-icon {
            width: 80px;
            height: 80px;
            margin: 0 auto 1.5rem;
            background: #E3F2FD;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 2rem;
            color: #1E88E5;
        }
        
        .card-title {
            font-size: 1.5rem;
            color: #263238;
            margin-bottom: 1rem;
            font-weight: 600;
        }
        
        .card-description {
            color: #78909C;
            margin-bottom: 1.5rem;
            line-height: 1.6;
        }
        
        .card-link {
            display: inline-block;
            padding: 12px 30px;
            background: linear-gradient(135deg, #1E88E5, #43A047);
            color: white;
            text-decoration: none;
            border-radius: 50px;
            font-weight: 600;
            transition: all 0.3s ease;
            border: none;
            cursor: pointer;
        }
        
        .card-link:hover {
            transform: scale(1.05);
            box-shadow: 0 5px 15px rgba(30, 136, 229, 0.4);
        }
        
        .quick-stats {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 1.5rem;
            margin-top: 2rem;
        }
        
        .stat-card {
            background: white;
            padding: 1.5rem;
            border-radius: 15px;
            text-align: center;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.08);
            border-left: 4px solid #1E88E5;
        }
        
        .stat-number {
            font-size: 2rem;
            font-weight: 700;
            color: #1E88E5;
            margin-bottom: 0.5rem;
        }
        
        .stat-label {
            color: #78909C;
            font-size: 0.9rem;
        }
        
        @media (max-width: 768px) {
            .admin-container {
                padding: 1rem;
            }
            
            .admin-title {
                font-size: 2rem;
            }
            
            .dashboard-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>

<body class="admin-body">
    <div class="admin-container">
        <div class="admin-header">
            <h1 class="admin-title">Painel Administrativo Santi'Lac</h1>
            <p class="admin-subtitle">Gerencie todos os aspectos do seu laticínio</p>
        </div>

        <div class="welcome-message">
            <p class="welcome-text">Bem-vindo ao sistema de gestão Santi'Lac</p>
        </div>

        <div class="dashboard-grid">
            <!-- Card Usuários -->
            <div class="dashboard-card">
                <div class="card-icon">👥</div>
                <h3 class="card-title">Gestão de Usuários</h3>
                <p class="card-description">Administre usuários, permissões e acessos ao sistema</p>
                <a href="./usuario/UsuarioList.php" class="card-link">Acessar</a>
            </div>

            <!-- Card Produtos -->
            <div class="dashboard-card">
                <div class="card-icon">🥛</div>
                <h3 class="card-title">Catálogo de Produtos</h3>
                <p class="card-description">Gerencie produtos, estoque, preços e categorias</p>
                <a href="./produto/produtoList.php" class="card-link">Acessar</a>
            </div>

            <!-- Card Funcionários -->
            <div class="dashboard-card">
                <div class="card-icon">👨‍💼</div>
                <h3 class="card-title">Equipe de Funcionários</h3>
                <p class="card-description">Controle da equipe, cargos e informações dos colaboradores</p>
                <a href="./funcionario/funcionarioList.php" class="card-link">Acessar</a>
            </div>

            <!-- Card Entregas -->
            <div class="dashboard-card">
                <div class="card-icon">🚚</div>
                <h3 class="card-title">Controle de Entregas</h3>
                <p class="card-description">Acompanhe e gerencie pedidos e entregas</p>
                <a href="./entrega/entregaList.php" class="card-link">Acessar</a>
            </div>

            <!-- Card Vendas -->
            <div class="dashboard-card">
                <div class="card-icon">💰</div>
                <h3 class="card-title">Relatório de Vendas</h3>
                <p class="card-description">Visualize vendas, relatórios e métricas de desempenho</p>
                <a href="./venda/vendasList.php" class="card-link">Acessar</a>
            </div>
        </div>
    </div>
</body>
</html>

<?php include './footer.php'; ?>