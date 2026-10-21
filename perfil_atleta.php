<?php
include "dados.php";
include "template.php";
$pagina_atual = "perfil";


// ---------- Monta a página (o HTML está em html/perfil_atleta.html) ----------
include "menu_atleta.php";

$html = carregar_html("perfil_atleta");

echo preencher($html, [
    "menu" => $menu_html,
    "time_completo" => $time_completo,
    "nome" => $atleta_nome,
    "numero" => $atleta_numero,
    "posicao" => $atleta_posicao,
    "email" => $atleta_email,
    "frequencia" => $atleta_frequencia
]);
?>
