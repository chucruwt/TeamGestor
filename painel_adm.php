<?php
include "dados.php";
include "template.php";
$pagina_atual = "painel";

// ---------- Números do topo ----------
$total_atletas = count($atletas);
$total_pendentes = count($pendentes);
$total_eventos = count($eventos);
$total_comunicados = count($comunicados);

// Frequência do elenco = média das frequências de todos os atletas
$soma = 0;
for ($i = 0; $i < $total_atletas; $i++) {
    $soma = $soma + $atletas[$i][3];
}
$frequencia_media = round($soma / $total_atletas);

// Próximo jogo = primeiro evento que seja do tipo 0 (jogo)
$proximo_jogo = "-";
$i = 0;
while ($i < $total_eventos && $proximo_jogo == "-") {
    if ($eventos[$i][1] == 0) {
        $proximo_jogo = $eventos[$i][3] . ", " . $eventos[$i][4] . " " . $eventos[$i][5];
    }
    $i = $i + 1;
}

// Quantos itens mostrar em cada lista do painel (no máximo 3)
$mostrar_eventos = 3;
if ($total_eventos < $mostrar_eventos) {
    $mostrar_eventos = $total_eventos;
}

$mostrar_comunicados = 3;
if ($total_comunicados < $mostrar_comunicados) {
    $mostrar_comunicados = $total_comunicados;
}

// ---------- Monta a página (o HTML está em html/painel_adm.html) ----------
include "menu_adm.php";

$html = carregar_html("painel_adm");
$modelo_evento = extrair_bloco($html, "item_evento");
$modelo_comunicado = extrair_bloco($html, "item_comunicado");
$modelo_pendente = extrair_bloco($html, "item_pendente");

$lista_eventos = "";
for ($i = 0; $i < $mostrar_eventos; $i++) {
    $e = $eventos[$i];
    $t = $tipos[$e[1]];

    $lista_eventos = $lista_eventos . preencher($modelo_evento, [
        "cor" => $t[2],
        "icone" => $t[1],
        "titulo" => $e[2],
        "dia_semana" => $e[3],
        "dia" => $e[4],
        "mes" => $e[5],
        "hora" => $e[6]
    ]) . "\n                    ";
}

$lista_comunicados = "";
// percorre do mais recente para o mais antigo
for ($i = $total_comunicados - 1; $i >= $total_comunicados - $mostrar_comunicados; $i--) {
    $lista_comunicados = $lista_comunicados . preencher($modelo_comunicado, [
        "texto" => htmlspecialchars($comunicados[$i][0])
    ]) . "\n                    ";
}

$lista_pendentes = "";
for ($i = 0; $i < $total_pendentes; $i++) {
    $lista_pendentes = $lista_pendentes . preencher($modelo_pendente, [
        "nome" => $pendentes[$i][0]
    ]) . "\n                    ";
}

echo preencher($html, [
    "menu" => $menu_html,
    "primeiro_nome" => $admin_primeiro_nome,
    "time_completo" => $time_completo,
    "total_atletas" => $total_atletas,
    "frequencia_media" => $frequencia_media,
    "proximo_jogo" => $proximo_jogo,
    "total_pendentes" => $total_pendentes,
    "item_evento" => $lista_eventos,
    "item_comunicado" => $lista_comunicados,
    "item_pendente" => $lista_pendentes
]);
?>
