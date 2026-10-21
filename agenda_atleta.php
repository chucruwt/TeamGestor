<?php
include "dados.php";
include "template.php";
$pagina_atual = "agenda";

// Resposta do atleta a um evento. Os botões mandam, por exemplo:
// agenda_atleta.php?evento=2&resp=sim
$evento_respondido = -1;
$resposta = "";

if (isset($_GET["evento"]) && isset($_GET["resp"])) {
    $evento_respondido = (int) $_GET["evento"];
    $resposta = $_GET["resp"];
}

$total_eventos = count($eventos);


// ---------- Monta a página (o HTML está em html/agenda_atleta.html) ----------
include "menu_atleta.php";

$html = carregar_html("agenda_atleta");

// recorta os blocos: os de dentro de cada evento primeiro
$modelo_acoes_sim = extrair_bloco($html, "acoes_sim");
$modelo_acoes_nao = extrair_bloco($html, "acoes_nao");
$modelo_acoes_botoes = extrair_bloco($html, "acoes_botoes");
$modelo_evento = extrair_bloco($html, "evento");

$lista_eventos = "";
for ($i = 0; $i < $total_eventos; $i++) {
    $e = $eventos[$i];
    $t = $tipos[$e[1]];

    // mostra só uma das três versões dos botões, conforme a resposta do atleta
    $parte_sim = "";
    $parte_nao = "";
    $parte_botoes = "";

    if ($evento_respondido == $e[0] && $resposta == "sim") {
        $parte_sim = $modelo_acoes_sim;
    } elseif ($evento_respondido == $e[0] && $resposta == "nao") {
        $parte_nao = $modelo_acoes_nao;
    } else {
        $parte_botoes = preencher($modelo_acoes_botoes, ["id" => $e[0]]);
    }

    $lista_eventos = $lista_eventos . preencher($modelo_evento, [
        "dia_semana" => $e[3],
        "dia" => $e[4],
        "cor" => $t[2],
        "icone" => $t[1],
        "tipo_nome" => $t[0],
        "titulo" => htmlspecialchars($e[2]),
        "mes" => $e[5],
        "hora" => $e[6],
        "local" => htmlspecialchars($e[7]),
        "acoes_sim" => $parte_sim,
        "acoes_nao" => $parte_nao,
        "acoes_botoes" => $parte_botoes
    ]) . "\n            ";
}

echo preencher($html, [
    "menu" => $menu_html,
    "time_completo" => $time_completo,
    "evento" => $lista_eventos
]);
?>
