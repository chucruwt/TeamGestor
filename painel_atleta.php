<?php
include "dados.php";
include "template.php";
$pagina_atual = "painel";

// ---------- Resposta do atleta ao próximo compromisso ----------
// Os botões mandam ?presenca=sim ou ?presenca=nao pela URL
$presenca = "";
if (isset($_GET["presenca"])) {
    $presenca = $_GET["presenca"];
}

// Próximo compromisso = primeiro evento da matriz
$proximo = $eventos[0];
$tipo_proximo = $tipos[$proximo[1]];

$total_eventos = count($eventos);
$total_comunicados = count($comunicados);

// Mostra no máximo 3 eventos e 2 comunicados
$mostrar_eventos = 3;
if ($total_eventos < $mostrar_eventos) {
    $mostrar_eventos = $total_eventos;
}

$mostrar_comunicados = 2;
if ($total_comunicados < $mostrar_comunicados) {
    $mostrar_comunicados = $total_comunicados;
}


// ---------- Monta a página (o HTML está em html/painel_atleta.html) ----------
include "menu_atleta.php";

$html = carregar_html("painel_atleta");
$modelo_presenca_sim = extrair_bloco($html, "presenca_sim");
$modelo_presenca_nao = extrair_bloco($html, "presenca_nao");
$modelo_presenca_botoes = extrair_bloco($html, "presenca_botoes");
$modelo_evento = extrair_bloco($html, "item_evento");
$modelo_comunicado = extrair_bloco($html, "item_comunicado");

// mostra só uma das três versões, conforme a resposta do atleta
$parte_sim = "";
$parte_nao = "";
$parte_botoes = "";

if ($presenca == "sim") {
    $parte_sim = $modelo_presenca_sim;
} elseif ($presenca == "nao") {
    $parte_nao = $modelo_presenca_nao;
} else {
    $parte_botoes = $modelo_presenca_botoes;
}

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
// do mais recente para o mais antigo (só os que o atleta pode ver)
$mostrados = 0;
for ($i = $total_comunicados - 1; $i >= 0; $i--) {
    if ($mostrados < $mostrar_comunicados && ($comunicados[$i][1] == "Todos" || $comunicados[$i][1] == "Atletas")) {
        $mostrados = $mostrados + 1;
        $lista_comunicados = $lista_comunicados . preencher($modelo_comunicado, [
            "texto" => htmlspecialchars($comunicados[$i][0])
        ]) . "\n                        ";
    }
}

echo preencher($html, [
    "menu" => $menu_html,
    "primeiro_nome" => $atleta_primeiro_nome,
    "time_completo" => $time_completo,
    "cor_proximo" => $tipo_proximo[2],
    "icone_proximo" => $tipo_proximo[1],
    "titulo_proximo" => $proximo[2],
    "dia_semana_proximo" => $proximo[3],
    "dia_proximo" => $proximo[4],
    "mes_proximo" => $proximo[5],
    "hora_proximo" => $proximo[6],
    "local_proximo" => $proximo[7],
    "presenca_sim" => $parte_sim,
    "presenca_nao" => $parte_nao,
    "presenca_botoes" => $parte_botoes,
    "item_evento" => $lista_eventos,
    "plano_resumo" => $plano_resumo,
    "item_comunicado" => $lista_comunicados
]);
?>
