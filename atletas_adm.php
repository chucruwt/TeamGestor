<?php
include "dados.php";
include "template.php";
$pagina_atual = "atletas";

// ---------- O que o administrador clicou? ----------
// Os botões Aprovar, Recusar e Remover mandam o número da linha pela URL
// (exemplo: atletas_adm.php?aprovar=0). Aqui lemos esse número.
$acao = "";
$id = -1;

if (isset($_GET["aprovar"])) {
    $acao = "aprovar";
    $id = (int) $_GET["aprovar"];
} elseif (isset($_GET["recusar"])) {
    $acao = "recusar";
    $id = (int) $_GET["recusar"];
} elseif (isset($_GET["remover"])) {
    $acao = "remover";
    $id = (int) $_GET["remover"];
}

// Filtro de posição (vem do formulário "Filtrar")
$posicao_filtro = "";
if (isset($_GET["posicao"])) {
    $posicao_filtro = $_GET["posicao"];
}

$total_pendentes = count($pendentes);
$total_atletas = count($atletas);

$mensagem = "";
$pendente_tratado = -1;   // linha de $pendentes que foi aprovada ou recusada
$atleta_removido = -1;    // linha de $atletas que foi removida

if ($acao == "aprovar" && $id >= 0 && $id < $total_pendentes) {
    $pendente_tratado = $id;
    // coloca o atleta aprovado no fim da matriz $atletas (frequência ainda não existe)
    $atletas[] = [$pendentes[$id][0], $pendentes[$id][2], $pendentes[$id][3], "-"];
    $mensagem = $pendentes[$id][0] . " foi aprovado e entrou no elenco.";
} elseif ($acao == "recusar" && $id >= 0 && $id < $total_pendentes) {
    $pendente_tratado = $id;
    $mensagem = "A solicitação de " . $pendentes[$id][0] . " foi recusada.";
} elseif ($acao == "remover" && $id >= 0 && $id < $total_atletas) {
    $atleta_removido = $id;
    $mensagem = $atletas[$id][0] . " foi removido do elenco.";
}

// Recalcula, pois alguém pode ter entrado no elenco
$total_atletas = count($atletas);

$restantes = $total_pendentes;
if ($pendente_tratado >= 0) {
    $restantes = $restantes - 1;
}

$total_posicoes = count($posicoes);
$linhas_mostradas = 0;

// ---------- Monta a página (o HTML está em html/atletas_adm.html) ----------
include "menu_adm.php";

$html = carregar_html("atletas_adm");

// recorta os blocos: os de dentro primeiro
$modelo_solicitacao = extrair_bloco($html, "item_solicitacao");
$modelo_pendentes = extrair_bloco($html, "pendentes");
$modelo_mensagem = extrair_bloco($html, "aviso_mensagem");
$modelo_posicao = extrair_bloco($html, "opcao_posicao");
$modelo_linha = extrair_bloco($html, "linha_atleta");
$modelo_vazio = extrair_bloco($html, "vazio");

$aviso_mensagem = "";
if ($mensagem != "") {
    $aviso_mensagem = preencher($modelo_mensagem, ["mensagem" => $mensagem]);
}

$bloco_pendentes = "";
if ($restantes > 0) {
    $lista_solicitacoes = "";

    for ($i = 0; $i < $total_pendentes; $i++) {
        // não mostra a solicitação que acabou de ser aprovada/recusada
        if ($i != $pendente_tratado) {
            $lista_solicitacoes = $lista_solicitacoes . preencher($modelo_solicitacao, [
                "nome" => $pendentes[$i][0],
                "cpf" => $pendentes[$i][1],
                "posicao" => $pendentes[$i][2],
                "numero" => $pendentes[$i][3],
                "indice" => $i
            ]) . "\n                ";
        }
    }

    $bloco_pendentes = preencher($modelo_pendentes, [
        "restantes" => $restantes,
        "item_solicitacao" => $lista_solicitacoes
    ]);
}

$opcoes_posicao = "";
for ($i = 0; $i < $total_posicoes; $i++) {
    if ($posicao_filtro == $posicoes[$i]) {
        $marcar = " selected";
    } else {
        $marcar = "";
    }
    $opcoes_posicao = $opcoes_posicao . preencher($modelo_posicao, ["valor" => $posicoes[$i], "marcar" => $marcar]) . "\n                        ";
}

$linhas = "";
for ($i = 0; $i < $total_atletas; $i++) {
    $a = $atletas[$i];

    // mostra só quem não foi removido e bate com o filtro de posição
    if ($i != $atleta_removido && ($posicao_filtro == "" || $a[1] == $posicao_filtro)) {
        $linhas_mostradas = $linhas_mostradas + 1;

        if ($a[3] == "-") {
            $freq_texto = "-";
            $classe_freq = "";
        } else {
            $freq_texto = $a[3] . "%";
            if ($a[3] < 80) {
                $classe_freq = "freq-baixa";
            } else {
                $classe_freq = "";
            }
        }

        $linhas = $linhas . preencher($modelo_linha, [
            "nome" => $a[0],
            "posicao" => $a[1],
            "numero" => $a[2],
            "classe_freq" => $classe_freq,
            "freq_texto" => $freq_texto,
            "indice" => $i
        ]) . "\n                        ";
    }
}

$aviso_vazio = "";
if ($linhas_mostradas == 0) {
    $aviso_vazio = $modelo_vazio;
}

echo preencher($html, [
    "menu" => $menu_html,
    "time_completo" => $time_completo,
    "aviso_mensagem" => $aviso_mensagem,
    "pendentes" => $bloco_pendentes,
    "opcao_posicao" => $opcoes_posicao,
    "linha_atleta" => $linhas,
    "vazio" => $aviso_vazio
]);
?>
