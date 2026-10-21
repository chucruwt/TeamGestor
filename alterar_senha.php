<?php
include "dados.php";
include "template.php";

// Como não existe envio de e-mail de verdade, o código é sempre este (modo demonstração)
$codigo_correto = "123456";

$email = "";
$erro = "";
$mensagem = "";
$alterada = 0;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $etapa = $_POST["etapa"];
    $email = $_POST["email"];

    // Confere se o e-mail existe na matriz $usuarios (que vem de dados/usuarios.json)
    $achou = 0;
    $total_usuarios = count($usuarios);

    for ($i = 0; $i < $total_usuarios; $i++) {
        if ($usuarios[$i][1] == $email) {
            $achou = 1;
        }
    }

    if ($etapa == "1") {
        // Etapa 1: botão "Enviar" (pede o código)
        if ($email == "") {
            $erro = "Digite o seu e-mail de cadastro.";
        } elseif ($achou == 0) {
            $erro = "Não encontramos esse e-mail no sistema.";
        } else {
            $mensagem = "Código enviado para " . htmlspecialchars($email) . ". (Modo demonstração: use o código " . $codigo_correto . ")";
        }
    } elseif ($etapa == "2") {
        // Etapa 2: botão "Alterar senha"
        $codigo = $_POST["codigo"];
        $nova = $_POST["nova"];
        $confirma = $_POST["confirma"];

        if ($email == "" || $achou == 0) {
            $erro = "Informe o e-mail de cadastro e clique em Enviar para receber o código.";
        } elseif ($codigo != $codigo_correto) {
            $erro = "Código inválido. Confira o código enviado ao seu e-mail.";
        } elseif ($nova == "") {
            $erro = "Digite a nova senha.";
        } elseif ($nova != $confirma) {
            $erro = "As senhas não são iguais.";
        } else {
            // Grava a nova senha em dados/usuarios.json
            if (trocar_senha_json($email, $nova)) {
                $alterada = 1;
            } else {
                $erro = "Não foi possível salvar a nova senha. Verifique se a pasta dados tem permissão de escrita.";
            }
        }
    }
}

// ---------- Monta a página (o HTML está em html/alterar_senha.html) ----------
$html = carregar_html("alterar_senha");

$modelo_erro = extrair_bloco($html, "aviso_erro");
$modelo_mensagem = extrair_bloco($html, "aviso_mensagem");
$modelo_formulario = extrair_bloco($html, "formulario");
$modelo_sucesso = extrair_bloco($html, "sucesso");

if ($alterada == 1) {

    $parte_sucesso = $modelo_sucesso;
    $parte_formulario = "";

} else {

    $aviso_erro = "";
    if ($erro != "") {
        $aviso_erro = preencher($modelo_erro, ["erro" => $erro]);
    }

    $aviso_mensagem = "";
    if ($mensagem != "") {
        $aviso_mensagem = preencher($modelo_mensagem, ["mensagem" => $mensagem]);
    }

    $parte_sucesso = "";
    $parte_formulario = preencher($modelo_formulario, [
        "aviso_erro" => $aviso_erro,
        "aviso_mensagem" => $aviso_mensagem,
        "email" => htmlspecialchars($email)
    ]);
}

echo preencher($html, [
    "sucesso" => $parte_sucesso,
    "formulario" => $parte_formulario
]);
?>
