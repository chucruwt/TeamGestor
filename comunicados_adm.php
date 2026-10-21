<?php
include "dados.php";
include "template.php";
$pagina_atual = "comunicados";

$erro = "";
$mensagem = "";
$f_texto = "";
$f_destino = 0;

$total_destinos = count($destinos);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $f_texto = $_POST["texto"];
    $f_destino = (int) $_POST["destino"];

    if ($f_destino < 0 || $f_destino >= $total_destinos) {
        $f_destino = 0;
    }

    if ($f_texto == "") {
        $erro = "Escreva a mensagem do comunicado.";
    } else {
        // O comunicado entra no fim da matriz $comunicados.
        // Sem banco de dados, ele aparece só nesta visualização da página.
        $comunicados[] = [$f_texto, $destinos[$f_destino], "hoje"];
        $mensagem = "Comunicado enviado para: " . $destinos[$f_destino] . ".";
        $f_texto = "";
        $f_destino = 0;
    }
}

$total_comunicados = count($comunicados);


// ---------- Monta a página (o HTML está em html/comunicados_adm.html) ----------
include "menu_adm.php";

$html = carregar_html("comunicados_adm");
$modelo_mensagem = extrair_bloco($html, "aviso_mensagem");
$modelo_erro = extrair_bloco($html, "aviso_erro");
$modelo_destino = extrair_bloco($html, "opcao_destino");
$modelo_comunicado = extrair_bloco($html, "item_comunicado");

$aviso_mensagem = "";
if ($mensagem != "") {
    $aviso_mensagem = preencher($modelo_mensagem, ["mensagem" => $mensagem]);
}

$aviso_erro = "";
if ($erro != "") {
    $aviso_erro = preencher($modelo_erro, ["erro" => $erro]);
}

$opcoes_destino = "";
for ($i = 0; $i < $total_destinos; $i++) {
    if ($f_destino == $i) {
        $marcar = " selected";
    } else {
        $marcar = "";
    }
    $opcoes_destino = $opcoes_destino . preencher($modelo_destino, ["valor" => $i, "marcar" => $marcar, "texto" => $destinos[$i]]) . "\n                        ";
}

$lista_comunicados = "";
// do mais recente (fim da matriz) para o mais antigo (começo)
for ($i = $total_comunicados - 1; $i >= 0; $i--) {
    $lista_comunicados = $lista_comunicados . preencher($modelo_comunicado, [
        "texto" => htmlspecialchars($comunicados[$i][0]),
        "destino" => $comunicados[$i][1],
        "data" => $comunicados[$i][2]
    ]) . "\n            ";
}

echo preencher($html, [
    "menu" => $menu_html,
    "time_completo" => $time_completo,
    "aviso_mensagem" => $aviso_mensagem,
    "aviso_erro" => $aviso_erro,
    "opcao_destino" => $opcoes_destino,
    "f_texto" => htmlspecialchars($f_texto),
    "item_comunicado" => $lista_comunicados
]);
?>
