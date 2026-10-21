<?php
include "dados.php";
include "template.php";
$pagina_atual = "painel";

$motivos = ["Saúde", "Trabalho ou estudo", "Viagem", "Compromisso familiar", "Outro"];
$total_motivos = count($motivos);
$total_eventos = count($eventos);

$erro = "";
$sucesso = 0;
$f_evento = 0;
$f_motivo = "";
$f_detalhes = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $f_evento = (int) $_POST["evento"];
    $f_motivo = $_POST["motivo"];
    $f_detalhes = $_POST["detalhes"];

    if ($f_evento < 0 || $f_evento >= $total_eventos) {
        $f_evento = 0;
    }

    if ($f_motivo == "") {
        $erro = "Escolha o motivo da ausência.";
    } elseif ($f_detalhes == "") {
        $erro = "Explique rapidamente o que aconteceu.";
    } else {
        // Sem banco de dados, a justificativa não é gravada em lugar nenhum.
        $sucesso = 1;
    }
}


// ---------- Monta a página (o HTML está em html/justificar_ausencia.html) ----------
include "menu_atleta.php";

$html = carregar_html("justificar_ausencia");

// recorta os blocos: os de dentro do formulário primeiro
$modelo_erro = extrair_bloco($html, "aviso_erro");
$modelo_evento = extrair_bloco($html, "opcao_evento");
$modelo_motivo = extrair_bloco($html, "opcao_motivo");
$modelo_formulario = extrair_bloco($html, "formulario");
$modelo_sucesso = extrair_bloco($html, "sucesso");

if ($sucesso == 1) {

    $parte_sucesso = $modelo_sucesso;
    $parte_formulario = "";

} else {

    $aviso_erro = "";
    if ($erro != "") {
        $aviso_erro = preencher($modelo_erro, ["erro" => $erro]);
    }

    $opcoes_evento = "";
    for ($i = 0; $i < $total_eventos; $i++) {
        if ($f_evento == $i) {
            $marcar = " selected";
        } else {
            $marcar = "";
        }
        $texto_evento = $eventos[$i][2] . ' (' . $eventos[$i][3] . ', ' . $eventos[$i][4] . ' ' . $eventos[$i][5] . ')';
        $opcoes_evento = $opcoes_evento . preencher($modelo_evento, ["valor" => $i, "marcar" => $marcar, "texto" => $texto_evento]) . "\n                        ";
    }

    $opcoes_motivo = "";
    for ($i = 0; $i < $total_motivos; $i++) {
        if ($f_motivo == $motivos[$i]) {
            $marcar = " selected";
        } else {
            $marcar = "";
        }
        $opcoes_motivo = $opcoes_motivo . preencher($modelo_motivo, ["valor" => $motivos[$i], "marcar" => $marcar]) . "\n                        ";
    }

    $parte_sucesso = "";
    $parte_formulario = preencher($modelo_formulario, [
        "aviso_erro" => $aviso_erro,
        "opcao_evento" => $opcoes_evento,
        "opcao_motivo" => $opcoes_motivo,
        "detalhes" => htmlspecialchars($f_detalhes)
    ]);
}

echo preencher($html, [
    "menu" => $menu_html,
    "time_completo" => $time_completo,
    "sucesso" => $parte_sucesso,
    "formulario" => $parte_formulario
]);
?>
