<?php
include "dados.php";
include "template.php";
$pagina_atual = "permissoes";

$mensagem = "";
$total_membros = count($comissao);
$total_permissoes = count($permissoes_nomes);

// ---------- Salvar permissões ----------
// Cada checkbox tem o nome "perm_LINHA_COLUNA" (ex.: perm_1_0 = membro 1, permissão 0).
// Se o checkbox veio marcado no formulário, a permissão vira 1; senão, vira 0.
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    for ($i = 0; $i < $total_membros; $i++) {
        for ($j = 0; $j < $total_permissoes; $j++) {
            if ($i == 0) {
                // a linha 0 é o administrador: sempre tem todas as permissões
                $comissao[$i][$j + 2] = 1;
            } elseif (isset($_POST["perm_" . $i . "_" . $j])) {
                $comissao[$i][$j + 2] = 1;
            } else {
                $comissao[$i][$j + 2] = 0;
            }
        }
    }

    // Sem banco de dados, as permissões não ficam gravadas depois que a página é recarregada.
    $mensagem = "Permissões atualizadas.";
}


// ---------- Monta a página (o HTML está em html/permissoes_adm.html) ----------
include "menu_adm.php";

$html = carregar_html("permissoes_adm");

// recorta os blocos: os de dentro primeiro
$modelo_mensagem = extrair_bloco($html, "aviso_mensagem");
$modelo_titulo = extrair_bloco($html, "titulo_permissao");
$modelo_celula = extrair_bloco($html, "celula_permissao");
$modelo_membro = extrair_bloco($html, "linha_membro");

$aviso_mensagem = "";
if ($mensagem != "") {
    $aviso_mensagem = preencher($modelo_mensagem, ["mensagem" => $mensagem]);
}

$titulos = "";
for ($j = 0; $j < $total_permissoes; $j++) {
    $titulos = $titulos . preencher($modelo_titulo, ["nome" => $permissoes_nomes[$j]]) . "\n                                ";
}

$linhas_membros = "";
for ($i = 0; $i < $total_membros; $i++) {
    $celulas = "";

    for ($j = 0; $j < $total_permissoes; $j++) {
        // marca o checkbox se a permissão for 1
        if ($comissao[$i][$j + 2] == 1) {
            $marcado = " checked";
        } else {
            $marcado = "";
        }

        // o administrador (linha 0) não pode ter permissões removidas
        if ($i == 0) {
            $bloqueado = " disabled";
        } else {
            $bloqueado = "";
        }

        $celulas = $celulas . preencher($modelo_celula, [
            "linha" => $i,
            "coluna" => $j,
            "membro" => $comissao[$i][0],
            "permissao" => $permissoes_nomes[$j],
            "marcado" => $marcado,
            "bloqueado" => $bloqueado
        ]) . "\n                                ";
    }

    $linhas_membros = $linhas_membros . preencher($modelo_membro, [
        "nome" => $comissao[$i][0],
        "funcao" => $comissao[$i][1],
        "celula_permissao" => $celulas
    ]) . "\n                            ";
}

echo preencher($html, [
    "menu" => $menu_html,
    "time_completo" => $time_completo,
    "aviso_mensagem" => $aviso_mensagem,
    "titulo_permissao" => $titulos,
    "linha_membro" => $linhas_membros
]);
?>
