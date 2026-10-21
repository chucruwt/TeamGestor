<?php
include "dados.php";
include "template.php";

$erro = "";
$sucesso = 0;

// Valores do formulário (começam vazios)
$nome = "";
$cpf = "";
$nascimento = "";
$email = "";
$posicao = "";
$numero = "";
$time_escolhido = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nome = $_POST["nome"];
    $cpf = $_POST["cpf"];
    $nascimento = $_POST["nascimento"];
    $email = $_POST["email"];
    $posicao = $_POST["posicao"];
    $numero = $_POST["numero"];
    $time_escolhido = $_POST["time"];
    $senha = $_POST["senha"];
    $confirma = $_POST["confirma"];

    // Confere se já existe uma conta com esse e-mail em dados/usuarios.json
    $email_existe = 0;
    $total_usuarios = count($usuarios);

    for ($i = 0; $i < $total_usuarios; $i++) {
        if (strtolower($usuarios[$i][1]) == strtolower($email)) {
            $email_existe = 1;
        }
    }

    // Validações: a primeira que falhar mostra a mensagem de erro
    if ($nome == "" || $cpf == "" || $nascimento == "" || $email == "" || $posicao == "" || $numero == "" || $senha == "") {
        $erro = "Preencha todos os campos.";
    } elseif ($time_escolhido == "") {
        $erro = "Selecione o time que deseja entrar.";
    } elseif ($senha != $confirma) {
        $erro = "As senhas não são iguais.";
    } elseif (!isset($_POST["termos"])) {
        $erro = "Aceite os termos de uso e a política de privacidade para continuar.";
    } elseif ($email_existe == 1) {
        $erro = "Já existe uma conta com esse e-mail.";
    } else {
        // Grava o cadastro completo em dados/cadastros_atletas.json
        // e a conta de login (nome, e-mail, senha, tipo) em dados/usuarios.json
        $gravou_cadastro = adicionar_json("cadastros_atletas", [
            "nome" => $nome,
            "cpf" => $cpf,
            "nascimento" => $nascimento,
            "email" => $email,
            "posicao" => $posicao,
            "numero" => (int) $numero,
            "time" => $time_escolhido,
            "status" => "pendente",
            "cadastrado_em" => date("Y-m-d H:i:s")
        ]);

        $gravou_conta = false;
        if ($gravou_cadastro) {
            $gravou_conta = adicionar_json("usuarios", [
                "nome" => $nome,
                "email" => $email,
                "senha" => $senha,
                "tipo" => "atleta"
            ]);
        }

        if ($gravou_conta) {
            $sucesso = 1;
        } else {
            $erro = "Não foi possível salvar o cadastro. Verifique se a pasta dados tem permissão de escrita.";
        }
    }
}

// ---------- Monta a página (o HTML está em html/cadastro_atleta.html) ----------
$html = carregar_html("cadastro_atleta");

// recorta os blocos: os de dentro do formulário primeiro
$modelo_erro = extrair_bloco($html, "aviso_erro");
$modelo_posicao = extrair_bloco($html, "opcao_posicao");
$modelo_time = extrair_bloco($html, "opcao_time");
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

    $opcoes_posicao = "";
    $total_posicoes = count($posicoes);
    for ($i = 0; $i < $total_posicoes; $i++) {
        if ($posicao == $posicoes[$i]) {
            $marcar = " selected";
        } else {
            $marcar = "";
        }
        $opcoes_posicao = $opcoes_posicao . preencher($modelo_posicao, ["valor" => $posicoes[$i], "marcar" => $marcar]) . "\n                    ";
    }

    $opcoes_time = "";
    $total_times = count($times);
    for ($i = 0; $i < $total_times; $i++) {
        if ($time_escolhido == $times[$i]) {
            $marcar = " selected";
        } else {
            $marcar = "";
        }
        $opcoes_time = $opcoes_time . preencher($modelo_time, ["valor" => $times[$i], "marcar" => $marcar]) . "\n                    ";
    }

    $parte_sucesso = "";
    $parte_formulario = preencher($modelo_formulario, [
        "aviso_erro" => $aviso_erro,
        "nome" => htmlspecialchars($nome),
        "cpf" => htmlspecialchars($cpf),
        "nascimento" => htmlspecialchars($nascimento),
        "email" => htmlspecialchars($email),
        "opcao_posicao" => $opcoes_posicao,
        "numero" => htmlspecialchars($numero),
        "opcao_time" => $opcoes_time
    ]);
}

echo preencher($html, [
    "sucesso" => $parte_sucesso,
    "formulario" => $parte_formulario
]);
?>
