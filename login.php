<?php
require 'sessao.php';
 
if (!empty($_SESSION['usuario_logado'])) {
    header('Location: index.php');
    exit;
}
 
$usuario_correto = 'admin';
$senha_hash      = password_hash('123456', PASSWORD_DEFAULT);
 
$erro = '';
 
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $usuario = trim($_POST['usuario'] ?? '');
    $senha   = $_POST['senha'] ?? '';
 
    if ($usuario === $usuario_correto && password_verify($senha, $senha_hash)) {
        $_SESSION['usuario_logado'] = $usuario;
        header('Location: index.php');
        exit;
    } else {
        $erro = 'Usuário ou senha inválidos.';
    }
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Finan+ – Login</title>
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }

    body {
        min-height: 100vh;
        display: flex;
        align-items: center;
        justify-content: center;
        background: linear-gradient(135deg, #111111 0%, #2a2a2a 100%);
        font-family: 'Segoe UI', sans-serif;
    }

    .card {
        width: 100%;
        max-width: 420px;
        border-radius: 16px;
        overflow: hidden;
        box-shadow: 0 20px 60px rgba(0,0,0,0.5);
    }

    .card-header {
        background: #111111;
        color: #fff;
        text-align: center;
        padding: 40px 30px 30px;
        border-bottom: 3px solid #C9A84C;
    }

    .card-header .icon { font-size: 2.5rem; margin-bottom: 10px; }
    .card-header h1 { font-size: 1.8rem; font-weight: 700; color: #fff; }
    .card-header p  { font-size: 0.85rem; color: #C9A84C; margin-top: 4px; }

    .card-body {
        background: #1c1c1c;
        padding: 35px 30px 30px;
    }

    .form-group { margin-bottom: 20px; }

    label {
        display: block;
        font-size: 0.75rem;
        font-weight: 700;
        letter-spacing: 1px;
        text-transform: uppercase;
        color: #C9A84C;
        margin-bottom: 8px;
    }

    .input-wrapper {
        display: flex;
        align-items: center;
        background: #111;
        border: 1px solid #3a3a3a;
        border-radius: 8px;
        padding: 0 14px;
        gap: 10px;
        transition: border-color .2s;
    }

    .input-wrapper:focus-within { border-color: #C9A84C; }
    .input-wrapper span { color: #C9A84C; font-size: 1.1rem; }

    .input-wrapper input {
        border: none;
        outline: none;
        background: transparent;
        padding: 14px 0;
        width: 100%;
        font-size: 0.95rem;
        color: #f0f0f0;
    }

    .btn-login {
        display: block;
        width: 100%;
        padding: 15px;
        background: #C9A84C;
        color: #111;
        font-size: 0.9rem;
        font-weight: 700;
        letter-spacing: 1px;
        text-transform: uppercase;
        border: none;
        border-radius: 8px;
        cursor: pointer;
        margin-top: 10px;
        transition: background .2s;
    }

    .btn-login:hover { background: #e0bc68; }

    .erro {
        background: #2e1010;
        color: #f08080;
        border-radius: 8px;
        padding: 10px 14px;
        font-size: 0.88rem;
        margin-bottom: 16px;
        border: 0.5px solid #6b2222;
    }

    .card-footer {
        background: #111;
        text-align: center;
        padding: 14px;
        font-size: 0.78rem;
        color: #555;
        border-top: 1px solid #2a2a2a;
    }

    .hint {
        margin-top: 14px;
        font-size: 0.78rem;
        color: #666;
        text-align: center;
    }
    </style>
</head>
<body>
 
<div class="card">
    <div class="card-header">
        <div class="icon">👜</div>
        <h1>Finan+</h1>
        <p>Gestão Financeira Pessoal</p>
    </div>
 
    <div class="card-body">
        <?php if ($erro): ?>
            <div class="erro"><?= htmlspecialchars($erro) ?></div>
        <?php endif; ?>
 
        <form method="POST" action="login.php">
            <div class="form-group">
                <label for="usuario">Utilizador</label>
                <div class="input-wrapper">
                    <span>👤</span>
                    <input type="text" id="usuario" name="usuario"
                           placeholder="admin"
                           value="<?= htmlspecialchars($_POST['usuario'] ?? '') ?>"
                           required>
                </div>
            </div>
 
            <div class="form-group">
                <label for="senha">Palavra-passe</label>
                <div class="input-wrapper">
                    <span>🔒</span>
                    <input type="password" id="senha" name="senha"
                           placeholder="••••••" required>
                </div>
            </div>
 
            <button type="submit" class="btn-login">Entrar no sistema</button>
        </form>
 
        <p class="hint">Usuário: <strong>admin</strong> &nbsp;|&nbsp; Senha: <strong>123456</strong></p>
    </div>
 
    <div class="card-footer">Universidade Positivo ©2026</div>
</div>
 
</body>
</html>