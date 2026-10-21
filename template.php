<?php
// =====================================================
// template.php - separa o HTML do PHP
//
// Cada página tem DOIS arquivos:
//   login.php        -> só a lógica (decisões, repetições, contas)
//   html/login.html  -> só o HTML da página
//
// O HTML guarda "buracos" no formato {{nome}} e o PHP preenche
// cada buraco com o valor certo.
//
// Para trechos que se repetem (linhas de tabela, opções de um
// select) ou que só aparecem às vezes (mensagem de erro), o HTML
// marca o trecho com dois comentários:
//
//     <!-- inicio:linha -->
//     <tr><td>{{nome}}</td></tr>
//     <!-- fim:linha -->
//
// O PHP "recorta" esse trecho com extrair_bloco(), preenche quantas
// vezes precisar (dentro de um for, por exemplo) e coloca o
// resultado de volta no lugar do bloco.
// =====================================================

// Lê o arquivo html/NOME.html e devolve o texto dele
function carregar_html($nome) {
    return file_get_contents(__DIR__ . "/html/" . $nome . ".html");
}

// Recorta do HTML o trecho entre <!-- inicio:NOME --> e <!-- fim:NOME -->.
// - devolve o trecho recortado (para o PHP preencher e repetir);
// - no lugar dele, o HTML passa a ter o buraco {{NOME}}.
// Se o bloco tiver blocos dentro, recorte primeiro os de dentro.
function extrair_bloco(&$html, $nome) {
    $marca_inicio = "<!-- inicio:" . $nome . " -->";
    $marca_fim = "<!-- fim:" . $nome . " -->";

    $pos_inicio = strpos($html, $marca_inicio);
    $pos_fim = strpos($html, $marca_fim);

    if ($pos_inicio === false || $pos_fim === false) {
        return "";
    }

    $comeco = $pos_inicio + strlen($marca_inicio);
    $trecho = substr($html, $comeco, $pos_fim - $comeco);

    $html = substr($html, 0, $pos_inicio) . "{{" . $nome . "}}" . substr($html, $pos_fim + strlen($marca_fim));

    return trim($trecho);
}

// Troca cada {{chave}} do HTML pelo valor da matriz $valores.
// Exemplo: preencher($html, ["nome" => "Rafael"]) troca {{nome}} por Rafael.
// (strtr troca tudo de uma vez só, então um valor digitado pelo usuário
// que contenha {{algo}} nunca é interpretado como outro buraco.)
function preencher($html, $valores) {
    $trocas = [];

    foreach ($valores as $chave => $valor) {
        $trocas["{{" . $chave . "}}"] = $valor;
    }

    return strtr($html, $trocas);
}
?>
