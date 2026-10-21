<?php
include "dados.php";
include "template.php";
$pagina_atual = "partidas";

$erro = "";
$mensagem = "";

// valores do formulário "Registrar resultado"
$f_adversario = "";
$f_data = "";
$f_local = "";
$f_gols_time = "";
$f_gols_adv = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $f_adversario = $_POST["adversario"];
    $f_data = $_POST["data"];
    $f_local = $_POST["local"];
    $f_gols_time = $_POST["gols_time"];
    $f_gols_adv = $_POST["gols_adv"];

    if ($f_adversario == "" || $f_data == "" || $f_local == "" || $f_gols_time == "" || $f_gols_adv == "") {
        $erro = "Preencha todos os campos do resultado.";
    } elseif ($f_gols_time < 0 || $f_gols_adv < 0) {
        $erro = "O número de gols não pode ser negativo.";
    } else {
        // Resultado novo entra no fim da matriz (situação 1 = já jogou)
        $partidas[] = [$f_adversario, $f_data, $f_local, (int) $f_gols_time, (int) $f_gols_adv, 1];
        $mensagem = "Resultado registrado: " . $f_adversario . " " . $f_gols_time . " x " . $f_gols_adv;

        // limpa o formulário
        $f_adversario = "";
        $f_data = "";
        $f_local = "";
        $f_gols_time = "";
        $f_gols_adv = "";
    }
}

$total_partidas = count($partidas);

// ---------- Resumo da campanha ----------
// Percorre todas as partidas que já foram jogadas e conta vitórias, empates e derrotas
$jogos = 0;
$vitorias = 0;
$empates = 0;
$derrotas = 0;
$gols_pro = 0;
$gols_contra = 0;

for ($i = 0; $i < $total_partidas; $i++) {
    if ($partidas[$i][5] == 1) {
        $jogos = $jogos + 1;
        $gols_pro = $gols_pro + $partidas[$i][3];
        $gols_contra = $gols_contra + $partidas[$i][4];

        if ($partidas[$i][3] > $partidas[$i][4]) {
            $vitorias = $vitorias + 1;
        } elseif ($partidas[$i][3] == $partidas[$i][4]) {
            $empates = $empates + 1;
        } else {
            $derrotas = $derrotas + 1;
        }
    }
}

$saldo = $gols_pro - $gols_contra;
if ($saldo > 0) {
    $saldo_texto = "+" . $saldo;
} else {
    $saldo_texto = $saldo;
}

$proximas = 0;


// ---------- Monta a página (o HTML está em html/partidas_adm.html) ----------
include "menu_adm.php";

$html = carregar_html("partidas_adm");
$modelo_mensagem = extrair_bloco($html, "aviso_mensagem");
$modelo_proxima = extrair_bloco($html, "proxima_partida");
$modelo_sem_proximas = extrair_bloco($html, "sem_proximas");
$modelo_selo_vitoria = extrair_bloco($html, "selo_vitoria");
$modelo_selo_empate = extrair_bloco($html, "selo_empate");
$modelo_selo_derrota = extrair_bloco($html, "selo_derrota");
$modelo_linha = extrair_bloco($html, "linha_resultado");
$modelo_erro = extrair_bloco($html, "aviso_erro");

$aviso_mensagem = "";
if ($mensagem != "") {
    $aviso_mensagem = preencher($modelo_mensagem, ["mensagem" => htmlspecialchars($mensagem)]);
}

$lista_proximas = "";
for ($i = 0; $i < $total_partidas; $i++) {
    if ($partidas[$i][5] == 0) {
        $proximas = $proximas + 1;
        $lista_proximas = $lista_proximas . preencher($modelo_proxima, [
            "adversario" => $partidas[$i][0],
            "data" => $partidas[$i][1],
            "local" => $partidas[$i][2]
        ]) . "\n            ";
    }
}

$aviso_sem_proximas = "";
if ($proximas == 0) {
    $aviso_sem_proximas = $modelo_sem_proximas;
}

$linhas_resultados = "";
// do jogo mais recente para o mais antigo
for ($i = $total_partidas - 1; $i >= 0; $i--) {
    if ($partidas[$i][5] == 1) {
        // mostra só um dos três selos (o HTML de cada um está em html/partidas_adm.html)
        $selo_vitoria = "";
        $selo_empate = "";
        $selo_derrota = "";

        if ($partidas[$i][3] > $partidas[$i][4]) {
            $selo_vitoria = $modelo_selo_vitoria;
        } elseif ($partidas[$i][3] == $partidas[$i][4]) {
            $selo_empate = $modelo_selo_empate;
        } else {
            $selo_derrota = $modelo_selo_derrota;
        }

        $linhas_resultados = $linhas_resultados . preencher($modelo_linha, [
            "data" => htmlspecialchars($partidas[$i][1]),
            "adversario" => htmlspecialchars($partidas[$i][0]),
            "local" => htmlspecialchars($partidas[$i][2]),
            "gols_time" => $partidas[$i][3],
            "gols_adv" => $partidas[$i][4],
            "selo_vitoria" => $selo_vitoria,
            "selo_empate" => $selo_empate,
            "selo_derrota" => $selo_derrota
        ]) . "\n                        ";
    }
}

$aviso_erro = "";
if ($erro != "") {
    $aviso_erro = preencher($modelo_erro, ["erro" => $erro]);
}

echo preencher($html, [
    "menu" => $menu_html,
    "time_completo" => $time_completo,
    "aviso_mensagem" => $aviso_mensagem,
    "jogos" => $jogos,
    "vitorias" => $vitorias,
    "empates" => $empates,
    "derrotas" => $derrotas,
    "saldo_texto" => $saldo_texto,
    "proxima_partida" => $lista_proximas,
    "sem_proximas" => $aviso_sem_proximas,
    "linha_resultado" => $linhas_resultados,
    "aviso_erro" => $aviso_erro,
    "f_adversario" => htmlspecialchars($f_adversario),
    "f_data" => htmlspecialchars($f_data),
    "f_local" => htmlspecialchars($f_local),
    "time_nome" => $time_nome,
    "f_gols_time" => htmlspecialchars($f_gols_time),
    "f_gols_adv" => htmlspecialchars($f_gols_adv)
]);
?>
