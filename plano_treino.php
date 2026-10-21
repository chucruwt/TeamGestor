<?php
include "dados.php";
include "template.php";
$pagina_atual = "plano";

$total_plano = count($plano);


// ---------- Monta a página (o HTML está em html/plano_treino.html) ----------
include "menu_atleta.php";

$html = carregar_html("plano_treino");
$modelo_sessao = extrair_bloco($html, "item_sessao");

$lista_sessoes = "";
for ($i = 0; $i < $total_plano; $i++) {
    $t = $tipos[$plano[$i][1]];

    $lista_sessoes = $lista_sessoes . preencher($modelo_sessao, [
        "cor" => $t[2],
        "icone" => $t[1],
        "tipo_nome" => $t[0],
        "data" => $plano[$i][0],
        "hora" => $plano[$i][3],
        "foco" => $plano[$i][2]
    ]) . "\n            ";
}

echo preencher($html, [
    "menu" => $menu_html,
    "time_completo" => $time_completo,
    "plano_resumo" => $plano_resumo,
    "item_sessao" => $lista_sessoes
]);
?>
