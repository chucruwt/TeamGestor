<?php
include "dados.php";
include "template.php";
$pagina_atual = "comunicados";

$total_comunicados = count($comunicados);
$mostrados = 0;


// ---------- Monta a página (o HTML está em html/comunicados_atleta.html) ----------
include "menu_atleta.php";

$html = carregar_html("comunicados_atleta");
$modelo_comunicado = extrair_bloco($html, "item_comunicado");
$modelo_vazio = extrair_bloco($html, "vazio");

$lista_comunicados = "";
// do mais recente para o mais antigo
for ($i = $total_comunicados - 1; $i >= 0; $i--) {
    // o atleta só vê comunicados para "Todos" ou para "Atletas"
    if ($comunicados[$i][1] == "Todos" || $comunicados[$i][1] == "Atletas") {
        $mostrados = $mostrados + 1;
        $lista_comunicados = $lista_comunicados . preencher($modelo_comunicado, [
            "texto" => htmlspecialchars($comunicados[$i][0]),
            "data" => $comunicados[$i][2]
        ]) . "\n            ";
    }
}

$aviso_vazio = "";
if ($mostrados == 0) {
    $aviso_vazio = $modelo_vazio;
}

echo preencher($html, [
    "menu" => $menu_html,
    "time_completo" => $time_completo,
    "item_comunicado" => $lista_comunicados,
    "vazio" => $aviso_vazio
]);
?>
