<?php
include "dados.php";
include "template.php";

$erro = "";
$email = "";

// Só confere o login quando o formulário foi enviado (método POST)
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST["email"];
    $senha = $_POST["senha"];

    // Procura o e-mail e a senha na matriz $usuarios (que vem de dados/usuarios.json)
    $tipo = "";
    $total_usuarios = count($usuarios);

    for ($i = 0; $i < $total_usuarios; $i++) {
        if ($usuarios[$i][1] == $email && $usuarios[$i][2] == $senha) {
            $tipo = $usuarios[$i][3];
        }
    }

    // Manda cada tipo de usuário para a sua tela
    if ($tipo == "admin") {
        header("Location: painel_adm.php");
        exit;
    } elseif ($tipo == "atleta") {
        header("Location: painel_atleta.php");
        exit;
    } else {
        $erro = "E-mail ou senha incorretos.";
    }
}

// ---------- Monta a página (o HTML está em html/login.html) ----------
$html = carregar_html("login");
$modelo_erro = extrair_bloco($html, "aviso_erro");

$aviso_erro = "";
if ($erro != "") {
    $aviso_erro = preencher($modelo_erro, ["erro" => $erro]);
}

echo preencher($html, [
    "aviso_erro" => $aviso_erro,
    "email" => htmlspecialchars($email)
]);
?>
