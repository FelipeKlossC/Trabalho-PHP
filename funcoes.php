<?php
 
function calcularTotais(array $transacoes): array {
    $receitas = 0;
    $despesas = 0;
    foreach ($transacoes as $t) {
        if ($t['tipo'] === 'Receita') {
            $receitas += $t['valor'];
        } else {
            $despesas += $t['valor'];
        }
    }
    return [
        'receitas' => $receitas,
        'despesas' => $despesas,
        'saldo'    => $receitas - $despesas,
    ];
}
 
function formatarMoeda(float $valor): string {
    return 'R$ ' . number_format($valor, 2, ',', '.');
}
 
function calcularPorcentagemDespesa(float $valorDespesa, float $totalDespesas): string {
    if ($totalDespesas <= 0) return '0,00%';
    return number_format(($valorDespesa / $totalDespesas) * 100, 2, ',', '.') . '%';
}
 