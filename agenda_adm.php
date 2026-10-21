<?php
include "dados.php";
include "template.php";
$pagina_atual = "agenda";

$total_tipos = count($tipos);

// ---------- Filtro (Todos, Jogos, Treinos táticos, Treinos físicos) ----------
// -1 = todos; 0, 1, 2 = código do tipo em $tipos
$filtro = -1;
if (isset($_GET["filtro"])) {
    $filtro = (int) $_GET["filtro"];
}

// colunas: 0 texto do botão | 1 valor do filtro
$chips = [
    ["Todos", -1],
    ["Jogos", 0],
    ["Treinos táticos", 1],
    ["Treinos físicos", 2]
];
$total_chips = count($chips);

// ---------- Formulário "Criar evento" ----------
$mostrar_form = 0;
if (isset($_GET["novo"])) {
    $mostrar_form = 1;
}

$dias_semana = ["Seg", "Ter", "Qua", "Qui", "Sex", "Sáb", "Dom"];
$meses = ["jan", "fev", "mar", "abr", "mai", "jun", "jul", "ago", "set", "out", "nov", "dez"];

$erro = "";
$mensagem = "";

// valores que aparecem no formulário
$f_tipo = 0;
$f_titulo = "";
$f_dia_semana = "Sáb";
$f_dia = "";
$f_mes = "ago";
$f_hora = "";
$f_local = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $mostrar_form = 1;

    $f_tipo = (int) $_POST["tipo"];
    $f_titulo = $_POST["titulo"];
    $f_dia_semana = $_POST["dia_semana"];
    $f_dia = $_POST["dia"];
    $f_mes = $_POST["mes"];
    $f_hora = $_POST["hora"];
    $f_local = $_POST["local"];

    if ($f_tipo < 0 || $f_tipo >= $total_tipos) {
        $f_tipo = 0;
    }

    if ($f_titulo == "" || $f_dia == "" || $f_hora == "" || $f_local == "") {
        $erro = "Preencha o título, o dia, o horário e o local.";
    } elseif ($f_dia < 1 || $f_dia > 31) {
        $erro = "Informe um dia entre 1 e 31.";
    } else {
        // Novo evento entra no fim da matriz $eventos.
        // Confirmados começa em 0 e todo o elenco aparece como pendente.
        $novo_id = count($eventos);
        $eventos[] = [$novo_id, $f_tipo, $f_titulo, $f_dia_semana, (int) $f_dia, $f_mes, $f_hora, $f_local, 0, count($atletas)];

        $mensagem = "Evento criado: " . $f_titulo;
        $mostrar_form = 0;

        // limpa o formulário
        $f_titulo = "";
        $f_dia = "";
        $f_hora = "";
        $f_local = "";
    }
}

$total_eventos = count($eventos);
$grupos = ["Esta semana", "Próximas semanas"];
$exibidos = 0;

// ---------- Monta a página (o HTML está em html/agenda_adm.html) ----------
include "menu_adm.php";

$html = carregar_html("agenda_adm");

// recorta os blocos: os de dentro do formulário primeiro
$modelo_erro = extrair_bloco($html, "aviso_erro");
$modelo_tipo = extrair_bloco($html, "opcao_tipo");
$modelo_dia_semana = extrair_bloco($html, "opcao_dia_semana");
$modelo_mes = extrair_bloco($html, "opcao_mes");
$modelo_form = extrair_bloco($html, "formulario_evento");
$modelo_mensagem = extrair_bloco($html, "aviso_mensagem");
$modelo_chip = extrair_bloco($html, "chip");
$modelo_grupo = extrair_bloco($html, "titulo_grupo");
$modelo_evento = extrair_bloco($html, "evento");
$modelo_vazio = extrair_bloco($html, "vazio");

$aviso_mensagem = "";
if ($mensagem != "") {
    $aviso_mensagem = preencher($modelo_mensagem, ["mensagem" => htmlspecialchars($mensagem)]);
}

$bloco_form = "";
if ($mostrar_form == 1) {
    $aviso_erro = "";
    if ($erro != "") {
        $aviso_erro = preencher($modelo_erro, ["erro" => $erro]);
    }

    $opcoes_tipo = "";
    for ($i = 0; $i < $total_tipos; $i++) {
        if ($f_tipo == $i) {
            $marcar = " selected";
        } else {
            $marcar = "";
        }
        $opcoes_tipo = $opcoes_tipo . preencher($modelo_tipo, ["valor" => $i, "marcar" => $marcar, "texto" => $tipos[$i][0]]) . "\n                        ";
    }

    $opcoes_dia_semana = "";
    for ($i = 0; $i < 7; $i++) {
        if ($f_dia_semana == $dias_semana[$i]) {
            $marcar = " selected";
        } else {
            $marcar = "";
        }
        $opcoes_dia_semana = $opcoes_dia_semana . preencher($modelo_dia_semana, ["valor" => $dias_semana[$i], "marcar" => $marcar]) . "\n                            ";
    }

    $opcoes_mes = "";
    for ($i = 0; $i < 12; $i++) {
        if ($f_mes == $meses[$i]) {
            $marcar = " selected";
        } else {
            $marcar = "";
        }
        $opcoes_mes = $opcoes_mes . preencher($modelo_mes, ["valor" => $meses[$i], "marcar" => $marcar]) . "\n                            ";
    }

    $bloco_form = preencher($modelo_form, [
        "aviso_erro" => $aviso_erro,
        "opcao_tipo" => $opcoes_tipo,
        "titulo" => htmlspecialchars($f_titulo),
        "opcao_dia_semana" => $opcoes_dia_semana,
        "hora" => htmlspecialchars($f_hora),
        "dia" => htmlspecialchars($f_dia),
        "opcao_mes" => $opcoes_mes,
        "local" => htmlspecialchars($f_local)
    ]);
}

$lista_chips = "";
for ($i = 0; $i < $total_chips; $i++) {
    if ($chips[$i][1] == $filtro) {
        $classe = "chip ativo";
        $atual = ' aria-current="true"';
    } else {
        $classe = "chip";
        $atual = "";
    }
    $lista_chips = $lista_chips . preencher($modelo_chip, [
        "classe" => $classe,
        "filtro" => $chips[$i][1],
        "atual" => $atual,
        "texto" => $chips[$i][0]
    ]) . "\n                ";
}

$lista_eventos = "";

// Dois grupos: "Esta semana" (g = 0) e "Próximas semanas" (g = 1)
for ($g = 0; $g < 2; $g++) {
    $imprimiu_titulo = 0;

    for ($i = 0; $i < $total_eventos; $i++) {
        $e = $eventos[$i];

        // Em que grupo este evento está?
        if ($e[5] == "ago" && $e[4] <= 14) {
            $grupo_do_evento = 0;
        } else {
            $grupo_do_evento = 1;
        }

        if ($grupo_do_evento == $g && ($filtro == -1 || $e[1] == $filtro)) {
            $t = $tipos[$e[1]];

            // porcentagem de confirmados (evita dividir por zero)
            $respostas = $e[8] + $e[9];
            if ($respostas > 0) {
                $porcentagem = round($e[8] / $respostas * 100);
            } else {
                $porcentagem = 0;
            }

            if ($imprimiu_titulo == 0) {
                $imprimiu_titulo = 1;
                $lista_eventos = $lista_eventos . preencher($modelo_grupo, ["grupo" => $grupos[$g]]) . "\n            ";
            }

            $lista_eventos = $lista_eventos . preencher($modelo_evento, [
                "dia_semana" => $e[3],
                "dia" => $e[4],
                "cor" => $t[2],
                "icone" => $t[1],
                "tipo_nome" => $t[0],
                "titulo" => htmlspecialchars($e[2]),
                "hora" => $e[6],
                "local" => htmlspecialchars($e[7]),
                "confirmados" => $e[8],
                "pendentes" => $e[9],
                "mes" => $e[5],
                "porcentagem" => $porcentagem
            ]) . "\n            ";

            $exibidos = $exibidos + 1;
        }
    }
}

$aviso_vazio = "";
if ($exibidos == 0) {
    $aviso_vazio = $modelo_vazio;
}

echo preencher($html, [
    "menu" => $menu_html,
    "time_completo" => $time_completo,
    "aviso_mensagem" => $aviso_mensagem,
    "formulario_evento" => $bloco_form,
    "chip" => $lista_chips,
    "titulo_grupo" => "",   // o título de cada grupo já foi colocado dentro de $lista_eventos
    "evento" => $lista_eventos,
    "vazio" => $aviso_vazio
]);
?>
