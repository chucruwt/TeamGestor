<?php
// Menu lateral do ATLETA (mesma ideia do menu_adm.php; o HTML está em html/menu_atleta.html).

// colunas: 0 código da página | 1 texto do menu | 2 arquivo | 3 ícone
$menu_itens = [
    ["painel", "Painel", "painel_atleta.php", "painel.png"],
    ["perfil", "Meu perfil", "perfil_atleta.php", "perfil.png"],
    ["agenda", "Agenda", "agenda_atleta.php", "agenda.png"],
    ["plano", "Plano de treino", "plano_treino.php", "agenda.png"],
    ["comunicados", "Comunicados", "comunicados_atleta.php", "sino.png"]
];
$total_menu = count($menu_itens);

$menu_html = carregar_html("menu_atleta");
$modelo_item = extrair_bloco($menu_html, "item_menu");

$itens_html = "";
for ($i = 0; $i < $total_menu; $i++) {
    if ($menu_itens[$i][0] == $pagina_atual) {
        $classe = "menu-item ativo";
        $atual = ' aria-current="page"';
    } else {
        $classe = "menu-item";
        $atual = "";
    }

    $itens_html = $itens_html . preencher($modelo_item, [
        "classe" => $classe,
        "arquivo" => $menu_itens[$i][2],
        "atual" => $atual,
        "icone" => $menu_itens[$i][3],
        "texto" => $menu_itens[$i][1]
    ]) . "\n        ";
}

$menu_html = preencher($menu_html, [
    "item_menu" => $itens_html,
    "nome" => $atleta_nome,
    "numero" => $atleta_numero
]);
?>
