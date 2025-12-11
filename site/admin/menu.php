<nav class="navbar navbar-expand-lg admin-navbar">
    <div class="container">
        <a class="navbar-brand navbar-brand-custom" href="main.php">Admin Santi'Lac</a>

        <button class="navbar-toggler navbar-toggler-custom" type="button" data-bs-toggle="collapse"
            data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false"
            aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav navbar-nav-custom ms-auto">
                <!-- Início -->
                <li class="nav-item">
                    <a class="nav-link <?= ($currentPage == 'main.php') ? 'active' : '' ?>" href="/Santilac-loja-main/site/admin/main.php">Início</a>
                </li>

                <!-- Usuários -->
                <li class="nav-item">
                    <a class="nav-link <?= (strpos($currentPage, 'usuario') !== false) ? 'active' : '' ?>" href="/Santilac-loja-main/site/admin//usuario/UsuarioList.php">Usuários</a>
                </li>

                <!-- Produtos -->
                <li class="nav-item">
                    <a class="nav-link <?= (strpos($currentPage, 'produto') !== false) ? 'active' : '' ?>" href="/Santilac-loja-main/site/admin/produto/produtoList.php">Produtos</a>
                </li>

                <!-- Funcionários -->
                <li class="nav-item">
                    <a class="nav-link <?= (strpos($currentPage, 'funcionario') !== false) ? 'active' : '' ?>" href="/Santilac-loja-main/site/admin/funcionario/funcionarioList.php">Funcionários</a>
                </li>

                <!-- Entregas -->
                <li class="nav-item">
                    <a class="nav-link <?= (strpos($currentPage, 'entrega') !== false) ? 'active' : '' ?>" href="/Santilac-loja-main/site/admin/entrega/entregaList.php">Entregas</a>
                </li>

                <!-- Vendas -->
                <li class="nav-item">
                    <a class="nav-link <?= (strpos($currentPage, 'venda') !== false) ? 'active' : '' ?>" href="/Santilac-loja-main/site/admin/venda/vendasList.php">Vendas</a>
                </li>
                <li class="nav-item">
                     <a href="/Santilac-loja-main/site/admin/login.php" class="btn btn-outline-dark"> Sair</a>
                </li>
            </ul>
        </div>
    </div>
</nav>