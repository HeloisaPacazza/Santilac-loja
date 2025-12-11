<?php
include 'admin/database/db.class.php';

// Buscar produtos do banco de dados
$db = new db('produto');
$produtos = $db->all();
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Santi'Lac - Laticínio Artesanal</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --primary-cream: #FFF9F0;
            --primary-blue: hsla(228, 70%, 26%, 1.00);
            --primary-green: #247296ff;
            --secondary-blue: #E3F2FD;
            --secondary-green: #E8F5E9;
            --neutral-dark: #263238;
            --neutral-gray: #78909C;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            background: linear-gradient(135deg, var(--primary-cream) 0%, var(--secondary-blue) 100%);
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            min-height: 100vh;
        }

        /* Header */
        .loja-header {
            background:var(--primary-blue);
            padding: 1.5rem 0;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
            border-bottom: 4px solid var(--primary-blue);
        }

        .logo {
            font-size: 2.5rem;
            font-weight: 700;
            color: white;
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.3);
        }

        .logo-subtitle {
            font-size: 0.9rem;
            color: rgba(255, 255, 255, 0.9);
            font-weight: 300;
        }

        .header-actions .btn {
            border-radius: 25px;
            padding: 8px 20px;
            font-weight: 600;
            transition: all 0.3s ease;
            margin-left: 10px;
        }

        .btn-outline-light:hover {
            background: white;
            color: var(--primary-blue);
            transform: translateY(-2px);
        }

        .btn-success {
            background: var(--primary-blue);
            border: white;
        }

        .btn-success:hover {
            background: #05153bff;
            transform: translateY(-2px);
        }

        /* Hero Section */
        .hero-section {
            background: linear-gradient(var(--primary-blue), rgba(255, 252, 246, 0.9));
            color: white;
            padding: 4rem 0;
            text-align: center;
            margin-bottom: 3rem;
        }

        .hero-title {
            font-size: 3rem;
            font-weight: 700;
            margin-bottom: 1rem;
        }

        .hero-subtitle {
            font-size: 1.2rem;
            margin-bottom: 2rem;
            opacity: 0.9;
        }

        /* Produtos Section */
        .produtos-section {
            padding: 3rem 0;
        }

        .section-title {
            text-align: center;
            color: var(--neutral-dark);
            font-size: 2.2rem;
            font-weight: 700;
            margin-bottom: 2rem;
        }

        /* Cards de Produtos */
        .product-card {
            border: none;
            border-radius: 15px;
            background: white;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.08);
            margin-bottom: 1.5rem;
            height: 100%;
            transition: all 0.3s ease;
        }

        .product-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.12);
        }

        .product-icon {
            background: linear-gradient(135deg, var(--primary-blue), var(--primary-green));
            color: white;
            padding: 2rem;
            text-align: center;
            border-radius: 15px 15px 0 0;
        }

        .product-icon i {
            font-size: 2.5rem;
        }

        .product-body {
            padding: 1.5rem;
        }

        .product-title {
            color: var(--neutral-dark);
            font-size: 1.3rem;
            font-weight: 600;
            margin-bottom: 0.5rem;
        }

        .product-description {
            color: var(--neutral-gray);
            font-size: 0.9rem;
            margin-bottom: 1rem;
            line-height: 1.5;
        }

        .product-price {
            color: var(--primary-green);
            font-size: 1.5rem;
            font-weight: 700;
            margin-bottom: 1rem;
            text-align: center;
        }

        .product-details {
            display: flex;
            justify-content: space-between;
            margin-bottom: 1.5rem;
            font-size: 0.85rem;
        }

        .product-weight {
            background: var(--secondary-blue);
            color: var(--primary-blue);
            padding: 4px 12px;
            border-radius: 12px;
            font-weight: 600;
        }

        .product-stock {
            background: var(--secondary-green);
            color: var(--primary-green);
            padding: 4px 12px;
            border-radius: 12px;
            font-weight: 600;
        }

        .product-actions {
            display: flex;
            gap: 10px;
        }

        .btn-whatsapp {
            background: #129147ff;
            color: white;
            border: none;
            border-radius: 20px;
            padding: 10px 15px;
            font-weight: 600;
            flex: 1;
            transition: all 0.3s ease;
            text-decoration: none;
            text-align: center;
            font-size: 0.9rem;
        }

        .btn-whatsapp:hover {
            background: #075015ff;
            color: white;
            transform: translateY(-2px);
        }

        .btn-info {
            background: var(--primary-blue);
            color: white;
            border: none;
            border-radius: 20px;
            padding: 10px 15px;
            font-weight: 600;
            flex: 1;
            transition: all 0.3s ease;
            text-decoration: none;
            text-align: center;
            font-size: 0.9rem;
        }

        .btn-info:hover {
            background: #0c4382ff;
            color: white;
            transform: translateY(-2px);
        }

        /* Footer */
        .store-footer {
            background: linear-gradient(135deg, var(--neutral-dark), #041955);
            color: white;
            padding: 2rem 0;
            margin-top: 3rem;
        }

        .footer-content {
            text-align: center;
        }

        .footer-logo {
            font-size: 1.8rem;
            font-weight: 700;
            margin-bottom: 1rem;
            color: white;
        }

        .footer-links {
            margin: 1rem 0;
        }

        .footer-links a {
            color: #bedbfd;
            text-decoration: none;
            margin: 0 10px;
            transition: color 0.3s ease;
            font-size: 0.9rem;
        }

        .footer-links a:hover {
            color: white;
        }
        @media (max-width: 768px) {
            .hero-title {
                font-size: 2.2rem;
            }
            
            .section-title {
                font-size: 1.8rem;
            }
            
            .logo {
                font-size: 2rem;
            }
            
            .product-actions {
                flex-direction: column;
            }
            
            .header-actions .btn {
                padding: 6px 12px;
                font-size: 0.8rem;
            }
        }
    </style>
</head>
<body>
    <!-- Header -->
    <header class="loja-header">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-md-6">
                    <div class="logo">Santi'Lac</div>
                    <div class="logo-subtitle">Laticínio Artesanal • Desde 1985</div>
                </div>
                <div class="col-md-6 text-end header-actions">
                        <a href="admin/login.php" class="btn btn-outline-light">
                            <i class="fas fa-sign-in-alt"></i> Entrar
                        </a>
                        <a href="admin/usuario/usuarioForm.php" class="btn btn-success">
                            <i class="fas fa-user-plus"></i> Cadastrar
                        </a>
                </div>
            </div>
        </div>
    </header>

    <!-- Hero Section -->
    <section class="hero-section">
        <div class="container">
            <h1 class="hero-title">Queijos Artesanais Premium</h1>
            <p class="hero-subtitle">Tradição, qualidade e amor em cada pedaço desde 1985</p>
            <a href="#produtos" class="btn btn-light btn-lg">
                <i class="fas fa-cheese"></i> Conheça Nossos Produtos
            </a>
        </div>
    </section>

    <!-- Produtos Section -->
    <section id="produtos" class="produtos-section">
        <div class="container">
            <h2 class="section-title">Nossos Produtos</h2>
            
            <?php if (empty($produtos)): ?>
                <div class="text-center py-4">
                    <i class="fas fa-cheese fa-3x text-muted mb-3"></i>
                    <p class="text-muted">Em breve novidades no nosso catálogo!</p>
                </div>
            <?php else: ?>
                <div class="row">
                    <?php foreach ($produtos as $produto): ?>
                        <div class="col-lg-4 col-md-6 mb-4">
                            <div class="product-card">
                                <div class="product-icon">
                                    <i class="fas fa-cheese"></i>
                                </div>
                                <div class="product-body">
                                    <h3 class="product-title"><?= htmlspecialchars($produto->tipo) ?></h3>
                                    
                                    <p class="product-description">
                                        Queijo artesanal produzido com leite fresco da mais alta qualidade, 
                                        seguindo receitas tradicionais da família Santi'Lac.
                                    </p>
                                    
                                    <div class="product-price">
                                        R$ <?= number_format($produto->preco, 2, ',', '.') ?>
                                    </div>
                                    
                                    <div class="product-details">
                                        <span class="product-weight"><?= $produto->peso ?> g</span>
                                        <span class="product-stock">
                                            <?= $produto->qtd > 0 ? $produto->qtd . ' disponíveis' : 'Esgotado' ?>
                                        </span>
                                    </div>
                                    
                                    <div class="product-actions">
                                        <a href="https://wa.me/5548999999999?text=Olá! Gostaria de mais informações sobre: <?= urlencode($produto->tipo) ?> - R$ <?= number_format($produto->preco, 2, ',', '.') ?>" 
                                           class="btn-whatsapp" target="_blank">
                                            <i class="fab fa-whatsapp"></i> WhatsApp
                                        </a>
                                        <a href="tel:+5548999999999" class="btn-info">
                                            <i class="fas fa-phone"></i> Ligar
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>
    </section>

    <!-- Footer -->
    <footer class="store-footer">
        <div class="container">
            <div class="footer-content">
                <div class="footer-logo">Santi'Lac</div>
                <div class="footer-links">
                    <a href="#"><i class="fab fa-instagram"></i> Instagram</a>
                    <a href="#"><i class="fab fa-facebook"></i> Facebook</a>
                    <a href="https://wa.me/5548999999999"><i class="fab fa-whatsapp"></i> WhatsApp</a>
                    <a href="mailto:contato@santilac.com"><i class="fas fa-envelope"></i> E-mail</a>
                </div>
                <p class="mt-3" style="font-size: 0.9rem;">
                    Coronel Freitas - SC • Todos os Direitos Reservados • Desde 1985
                </p>
            </div>
        </div>
    </footer>

    <script>
 
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();
                const target = document.querySelector(this.getAttribute('href'));
                if (target) {
                    target.scrollIntoView({
                        behavior: 'smooth',
                        block: 'start'
                    });
                }
            });
        });
    </script>
</body>
</html>