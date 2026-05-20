<nav class="navbar">
    <div class="navbar-brand">
        <span class="navbar-icon">👜</span>
        <span class="navbar-title">Finan+</span>
    </div>
    <div class="navbar-user">
        <span>Olá, <?= htmlspecialchars($_SESSION['usuario_logado'] ?? 'Usuário') ?></span>
        <a href="logout.php" class="btn-sair">Sair</a>
    </div>
</nav>
 