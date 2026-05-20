<?php
require 'sessao.php';
exigirLogin();
require 'funcoes.php';
 
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['acao']) && $_POST['acao'] === 'adicionar') {
    $nome  = trim($_POST['nome'] ?? '');
    $valor = floatval(str_replace(',', '.', $_POST['valor'] ?? '0'));
    $tipo  = $_POST['tipo'] ?? 'Receita';
 
    if ($nome !== '' && $valor > 0 && in_array($tipo, ['Receita', 'Despesa'])) {
        $_SESSION['transacoes'][] = [
            'nome'  => $nome,
            'valor' => $valor,
            'tipo'  => $tipo,
            'data'  => date('d/m/Y H:i'),
        ];
    }
    header('Location: index.php');
    exit;
}
 
$totais = calcularTotais($_SESSION['transacoes']);
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Finan+ – Dashboard</title>
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }
 
        body {
            font-family: 'Segoe UI', sans-serif;
            background: #f0f2f5;
            min-height: 100vh;
        }
 
        .navbar {
            background: #1a1a2e;
            color: #fff;
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 30px;
            height: 60px;
        }
 
        .navbar-brand { display: flex; align-items: center; gap: 10px; font-size: 1.1rem; font-weight: 700; }
        .navbar-user  { display: flex; align-items: center; gap: 14px; font-size: 0.9rem; }
 
        .btn-sair {
            background: #e74c3c;
            color: #fff;
            border: none;
            border-radius: 6px;
            padding: 6px 14px;
            font-size: 0.85rem;
            font-weight: 600;
            text-decoration: none;
            cursor: pointer;
        }
 
        .container { max-width: 1100px; margin: 30px auto; padding: 0 20px; }
 
        .summary {
            display: grid;
            grid-template-columns: 1fr 1fr 1.3fr;
            gap: 20px;
            margin-bottom: 28px;
        }
 
        .summary-card {
            background: #fff;
            border-radius: 12px;
            padding: 24px 28px;
            border-left: 4px solid #ccc;
        }
 
        .summary-card.receitas { border-color: #27ae60; }
        .summary-card.despesas { border-color: #e74c3c; }
        .summary-card.saldo    { background: #2563eb; color: #fff; border: none; }
 
        .summary-card .label { font-size: 0.85rem; color: #777; margin-bottom: 8px; }
        .summary-card.saldo .label { color: rgba(255,255,255,.75); }
 
        .summary-card .amount { font-size: 1.7rem; font-weight: 700; }
        .summary-card.receitas .amount { color: #27ae60; }
        .summary-card.despesas .amount { color: #e74c3c; }
        .summary-card.saldo    .amount { color: #fff; }
 
        .form-card {
            background: #fff;
            border-radius: 12px;
            padding: 28px;
            margin-bottom: 20px;
        }
 
        .form-card h2 { font-size: 1rem; font-weight: 700; margin-bottom: 20px; color: #333; }
 
        .form-row {
            display: grid;
            grid-template-columns: 2fr 1fr 1fr auto;
            gap: 14px;
            align-items: end;
        }
 
        .form-group label { display: block; font-size: 0.8rem; color: #666; margin-bottom: 6px; }
 
        .form-group input,
        .form-group select {
            width: 100%;
            padding: 11px 14px;
            border: 1px solid #ddd;
            border-radius: 8px;
            font-size: 0.95rem;
            outline: none;
            transition: border-color .2s;
        }
 
        .form-group input:focus,
        .form-group select:focus { border-color: #2563eb; }
 
        .btn-add {
            background: #1a1a2e;
            color: #fff;
            border: none;
            border-radius: 8px;
            padding: 11px 22px;
            font-size: 0.9rem;
            font-weight: 600;
            cursor: pointer;
            white-space: nowrap;
            transition: background .2s;
        }
 
        .btn-add:hover { background: #2563eb; }
 
        .btn-historico {
            display: block;
            width: fit-content;
            margin: 0 auto;
            padding: 12px 30px;
            background: #fff;
            color: #333;
            border: 1px solid #ccc;
            border-radius: 8px;
            font-size: 0.9rem;
            text-decoration: none;
            cursor: pointer;
            transition: background .2s;
        }
 
        .btn-historico:hover { background: #f0f2f5; }
 
        @media (max-width: 700px) {
            .summary { grid-template-columns: 1fr; }
            .form-row { grid-template-columns: 1fr; }
        }
    </style>
</head>
<body>
 
<?php require 'menu.php'; ?>
 
<div class="container">
 
    <div class="summary">
        <div class="summary-card receitas">
            <div class="label">Total Receitas</div>
            <div class="amount"><?= formatarMoeda($totais['receitas']) ?></div>
        </div>
        <div class="summary-card despesas">
            <div class="label">Total Despesas</div>
            <div class="amount"><?= formatarMoeda($totais['despesas']) ?></div>
        </div>
        <div class="summary-card saldo">
            <div class="label">Saldo Disponível</div>
            <div class="amount"><?= formatarMoeda($totais['saldo']) ?></div>
        </div>
    </div>
 
    <div class="form-card">
        <h2>Nova Transação</h2>
        <form method="POST" action="index.php">
            <input type="hidden" name="acao" value="adicionar">
            <div class="form-row">
                <div class="form-group">
                    <label for="nome">Descrição</label>
                    <input type="text" id="nome" name="nome"
                           placeholder="Ex: Salário, Aluguel..." required>
                </div>
                <div class="form-group">
                    <label for="valor">Valor</label>
                    <input type="number" id="valor" name="valor"
                           step="0.01" min="0.01" placeholder="0,00" required>
                </div>
                <div class="form-group">
                    <label for="tipo">Tipo</label>
                    <select id="tipo" name="tipo">
                        <option value="Receita">Receita</option>
                        <option value="Despesa">Despesa</option>
                    </select>
                </div>
                <button type="submit" class="btn-add">Adicionar</button>
            </div>
        </form>
    </div>
 
    <a href="historico.php" class="btn-historico">Ver Detalhes do Histórico</a>
 
</div>
</body>
</html>