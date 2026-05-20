<?php
require 'sessao.php';
exigirLogin();
require 'funcoes.php';
 
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['acao'])) {
    if ($_POST['acao'] === 'zerar') {
        $_SESSION['transacoes'] = [];
    }
 
    if ($_POST['acao'] === 'remover' && isset($_POST['indice'])) {
        $idx = intval($_POST['indice']);
        if (isset($_SESSION['transacoes'][$idx])) {
            array_splice($_SESSION['transacoes'], $idx, 1);
        }
    }
 
    header('Location: historico.php');
    exit;
}
 
$transacoes   = $_SESSION['transacoes'];
$totais       = calcularTotais($transacoes);
$totalDespesas = $totais['despesas'];
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Finan+ – Histórico</title>
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
        }
 
        .container { max-width: 1000px; margin: 30px auto; padding: 0 20px; }
 
        .historico-card {
            background: #fff;
            border-radius: 12px;
            padding: 28px;
        }
 
        .historico-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 22px;
        }
 
        .historico-header h2 { font-size: 1.1rem; font-weight: 700; color: #333; }
 
        .actions { display: flex; gap: 10px; }
 
        .btn-voltar {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 8px 16px;
            background: #fff;
            border: 1px solid #ccc;
            border-radius: 8px;
            font-size: 0.85rem;
            text-decoration: none;
            color: #333;
            cursor: pointer;
        }
 
        .btn-voltar:hover { background: #f5f5f5; }
 
        .btn-zerar {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 8px 16px;
            background: #e74c3c;
            color: #fff;
            border: none;
            border-radius: 8px;
            font-size: 0.85rem;
            font-weight: 600;
            cursor: pointer;
        }
 
        .btn-zerar:hover { background: #c0392b; }
 
        table { width: 100%; border-collapse: collapse; }
 
        thead th {
            text-align: left;
            font-size: 0.8rem;
            font-weight: 700;
            color: #777;
            padding: 10px 14px;
            border-bottom: 1px solid #eee;
        }
 
        tbody tr { border-bottom: 1px solid #f3f3f3; }
        tbody tr:last-child { border-bottom: none; }
 
        tbody td { padding: 14px; font-size: 0.9rem; color: #333; vertical-align: middle; }
 
        .data { color: #999; font-size: 0.82rem; }
 
        .badge {
            display: inline-block;
            padding: 3px 10px;
            border-radius: 20px;
            font-size: 0.78rem;
            font-weight: 600;
        }
 
        .badge-receita { background: #e8f8f0; color: #27ae60; }
        .badge-despesa { background: #fdecea; color: #e74c3c; }
 
        .valor-receita { color: #27ae60; font-weight: 600; }
        .valor-despesa { color: #e74c3c; font-weight: 600; }
 
        .pct { font-size: 0.75rem; color: #aaa; margin-top: 2px; }
 
        .btn-remover {
            background: none;
            border: none;
            cursor: pointer;
            font-size: 1.2rem;
            color: #e74c3c;
            padding: 4px;
        }
 
        .btn-remover:hover { opacity: .7; }
 
        .vazio { text-align: center; color: #bbb; padding: 40px 0; }
 
        @media (max-width: 600px) {
            .pct { display: none; }
            .data { display: none; }
        }
    </style>
</head>
<body>
 
<?php require 'menu.php'; ?>
 
<div class="container">
    <div class="historico-card">
        <div class="historico-header">
            <h2>Histórico de Movimentações</h2>
            <div class="actions">
                <a href="index.php" class="btn-voltar">← Voltar</a>
                <form method="POST" action="historico.php" style="display:inline;">
                    <input type="hidden" name="acao" value="zerar">
                    <button type="submit" class="btn-zerar"
                            onclick="return confirm('Zerar todo o histórico?')">
                        🗑 Zerar
                    </button>
                </form>
            </div>
        </div>
 
        <?php if (empty($transacoes)): ?>
            <div class="vazio">Nenhuma transação registrada ainda.</div>
        <?php else: ?>
            <table>
                <thead>
                    <tr>
                        <th>Data</th>
                        <th>Descrição</th>
                        <th>Categoria</th>
                        <th style="text-align:right;">Valor</th>
                        <th style="text-align:center;">Ações</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($transacoes as $i => $t): ?>
                        <tr>
                            <td class="data"><?= htmlspecialchars($t['data']) ?></td>
                            <td><strong><?= htmlspecialchars($t['nome']) ?></strong></td>
                            <td>
                                <?php if ($t['tipo'] === 'Receita'): ?>
                                    <span class="badge badge-receita">Receita</span>
                                <?php else: ?>
                                    <span class="badge badge-despesa">Despesa</span>
                                <?php endif; ?>
                            </td>
                            <td style="text-align:right;">
                                <?php if ($t['tipo'] === 'Receita'): ?>
                                    <span class="valor-receita">+ <?= formatarMoeda($t['valor']) ?></span>
                                <?php else: ?>
                                    <span class="valor-despesa">- <?= formatarMoeda($t['valor']) ?></span>
                                    <div class="pct">
                                        <?= calcularPorcentagemDespesa($t['valor'], $totalDespesas) ?> das despesas
                                    </div>
                                <?php endif; ?>
                            </td>
                            <td style="text-align:center;">
                                <form method="POST" action="historico.php" style="display:inline;">
                                    <input type="hidden" name="acao" value="remover">
                                    <input type="hidden" name="indice" value="<?= $i ?>">
                                    <button type="submit" class="btn-remover"
                                            onclick="return confirm('Remover esta transação?')">
                                        ❌
                                    </button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>
    </div>
</div>
 
</body>
</html>