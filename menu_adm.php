<?php
// Menu lateral do ADMINISTRADOR (o HTML dele está em html/menu_adm.html).
// A página que usa este arquivo define antes a variável $pagina_atual
// para o menu saber qual item deve ficar destacado.
// O resultado fica pronto na variável $menu_html, que a página coloca no lugar de {{menu}}.

// colunas: 0 código da página | 1 texto do menu | 2 arquivo | 3 ícone
$menu_itens = [
    ["painel", "Painel", "painel_adm.php", "painel.png"],
    ["atletas", "Atletas", "atletas_adm.php", "atletas.png"],
    ["agenda", "Agenda", "agenda_adm.php", "agenda.png"],
    ["partidas", "Partidas", "partidas_adm.php", "bola.png"],
    ["comunicados", "Comunicados", "comunicados_adm.php", "sino.png"],
    ["permissoes", "Permissões", "permissoes_adm.php", "permissoes.png"]
];
$total_menu = count($menu_itens);

$menu_html = carregar_html("menu_adm");
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
    "nome" => $admin_nome
]);
?>
