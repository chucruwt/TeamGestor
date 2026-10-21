<?php
include "dados.php";
include "template.php";

$erro = "";
$sucesso = 0;

// Valores do formulário (começam vazios)
$nome = "";
$cpf = "";
$telefone = "";
$email = "";
$time_digitado = "";
$modalidade = "";
$categoria = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nome = $_POST["nome"];
    $cpf = $_POST["cpf"];
    $telefone = $_POST["telefone"];
    $email = $_POST["email"];
    $senha = $_POST["senha"];
    $confirma = $_POST["confirma"];
    $time_digitado = $_POST["nome_time"];
    $modalidade = $_POST["modalidade"];
    $categoria = $_POST["categoria"];

    // Confere se já existe uma conta com esse e-mail em dados/usuarios.json
    $email_existe = 0;
    $total_usuarios = count($usuarios);

    for ($i = 0; $i < $total_usuarios; $i++) {
        if (strtolower($usuarios[$i][1]) == strtolower($email)) {
            $email_existe = 1;
        }
    }

    // Validações: a primeira que falhar mostra a mensagem de erro
    if ($nome == "" || $cpf == "" || $telefone == "" || $email == "" || $senha == "") {
        $erro = "Preencha todos os seus dados.";
    } elseif ($senha != $confirma) {
        $erro = "As senhas não são iguais.";
    } elseif ($time_digitado == "" || $modalidade == "" || $categoria == "") {
        $erro = "Preencha os dados do time.";
    } elseif (!isset($_POST["responsavel"])) {
        $erro = "Confirme que você é o responsável autorizado pelo time.";
    } elseif ($email_existe == 1) {
        $erro = "Já existe uma conta com esse e-mail.";
    } else {
        // Grava o pedido completo em dados/cadastros_admins.json
        // e a conta de login (nome, e-mail, senha, tipo) em dados/usuarios.json
        $gravou_cadastro = adicionar_json("cadastros_admins", [
            "nome" => $nome,
            "cpf" => $cpf,
            "telefone" => $telefone,
            "email" => $email,
            "nome_time" => $time_digitado,
            "modalidade" => $modalidade,
            "categoria" => $categoria,
            "status" => "pendente",
            "cadastrado_em" => date("Y-m-d H:i:s")
        ]);

        $gravou_conta = false;
        if ($gravou_cadastro) {
            $gravou_conta = adicionar_json("usuarios", [
                "nome" => $nome,
                "email" => $email,
                "senha" => $senha,
                "tipo" => "admin"
            ]);
        }

        if ($gravou_conta) {
            $sucesso = 1;
        } else {
            $erro = "Não foi possível salvar o cadastro. Verifique se a pasta dados tem permissão de escrita.";
        }
    }
}

// ---------- Monta a página (o HTML está em html/cadastro_admin.html) ----------
$html = carregar_html("cadastro_admin");

$modelo_erro = extrair_bloco($html, "aviso_erro");
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

    $parte_sucesso = "";
    $parte_formulario = preencher($modelo_formulario, [
        "aviso_erro" => $aviso_erro,
        "nome" => htmlspecialchars($nome),
        "cpf" => htmlspecialchars($cpf),
        "telefone" => htmlspecialchars($telefone),
        "email" => htmlspecialchars($email),
        "nome_time" => htmlspecialchars($time_digitado),
        "modalidade" => htmlspecialchars($modalidade),
        "categoria" => htmlspecialchars($categoria)
    ]);
}

echo preencher($html, [
    "sucesso" => $parte_sucesso,
    "formulario" => $parte_formulario
]);
?>
