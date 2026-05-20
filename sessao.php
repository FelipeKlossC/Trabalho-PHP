<?php
session_start();
 
if (!isset($_SESSION['transacoes'])) {
    $_SESSION['transacoes'] = [];
}
 
function exigirLogin(): void {
    if (empty($_SESSION['usuario_logado'])) {
        header('Location: login.php');
        exit;
    }
}
 