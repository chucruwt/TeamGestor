<?php
// =====================================================
// dados.php - o "banco de dados" do projeto
//
// Como o projeto não usa banco de dados, tudo fica aqui em
// vetores e matrizes. Cada LINHA da matriz é um registro e
// cada COLUNA é um campo (o comentário acima de cada matriz
// diz qual campo está em qual coluna).
//
// Para trocar os dados de exemplo, é só editar este arquivo.
// =====================================================

$time_nome = "Guarani Futsal Clube";
$time_categoria = "Sub 17";
$time_completo = $time_nome . " - " . $time_categoria;

// ---------- Usuários que podem entrar no sistema ----------
// Eles ficam no arquivo dados/usuarios.json (cada cadastro novo é gravado lá).
// Aqui o JSON é lido e montado na mesma matriz de antes, então o resto
// do código continua igual.
// colunas: 0 nome | 1 e-mail | 2 senha | 3 tipo (admin ou atleta)
include_once "json.php";

$usuarios_json = ler_json("usuarios");
$usuarios = [];

for ($i = 0; $i < count($usuarios_json); $i++) {
    $u = $usuarios_json[$i];

    if (isset($u["nome"]) && isset($u["email"]) && isset($u["senha"]) && isset($u["tipo"])) {
        $usuarios[] = [$u["nome"], $u["email"], $u["senha"], $u["tipo"]];
    }
}

// Dados de quem está logado (como não há login de verdade, ficam fixos)
$admin_nome = "Rafael Costa";
$admin_primeiro_nome = "Rafael";

$atleta_nome = "João Pedro Alves";
$atleta_primeiro_nome = "João Pedro";
$atleta_posicao = "Atacante";
$atleta_numero = 10;
$atleta_email = "joao@guarani.com.br";
$atleta_frequencia = 81;

// ---------- Times que aparecem no cadastro do atleta ----------
$times = [
    "Guarani Futsal Clube - Sub 17",
    "Vila Nova Futsal - Sub 15",
    "Atlético Rio Sul - Sub 20"
];

// ---------- Posições (usadas no filtro e no cadastro) ----------
$posicoes = ["Goleiro", "Fixo", "Ala-Direito", "Ala-Esquerdo", "Pivô", "Atacante", "Lateral"];

// ---------- Elenco ----------
// colunas: 0 nome | 1 posição | 2 número | 3 frequência (%)
$atletas = [
    ["Davi Oliveira Souza", "Fixo", 7, 92],
    ["Daniel E. Hirsch", "Ala-Direito", 10, 74],
    ["Pedro Guerreira", "Ala-Esquerdo", 12, 88],
    ["Lucas Bragança", "Pivô", 11, 95],
    ["Matheus Rocha", "Goleiro", 31, 97],
    ["Gabriel Martins", "Fixo", 5, 81],
    ["Enzo Ferreira", "Ala-Direito", 8, 86],
    ["Thiago Lima", "Pivô", 9, 78],
    ["Bruno Carvalho", "Ala-Esquerdo", 14, 90],
    ["Caio Santos", "Goleiro", 13, 84],
    ["Rafael Menezes", "Fixo", 4, 89],
    ["Vinícius Duarte", "Ala-Direito", 15, 93],
    ["Henrique Pires", "Pivô", 17, 85],
    ["Leonardo Faria", "Ala-Esquerdo", 18, 91],
    ["Arthur Nogueira", "Fixo", 3, 87],
    ["Felipe Barros", "Goleiro", 22, 96],
    ["Gustavo Teixeira", "Ala-Direito", 19, 79],
    ["Murilo Andrade", "Pivô", 20, 88],
    ["Samuel Ribeiro", "Ala-Esquerdo", 21, 83],
    ["Nicolas Moreira", "Fixo", 2, 90],
    ["Otávio Cardoso", "Ala-Direito", 16, 82],
    ["Rodrigo Pinto", "Pivô", 23, 94],
    ["Eduardo Lopes", "Ala-Esquerdo", 24, 77],
    ["Igor Batista", "Fixo", 28, 92],
    ["Leandro Dias", "Ala-Direito", 25, 86],
    ["Yuri Castro", "Pivô", 26, 89],
    ["Kauã Mendes", "Goleiro", 27, 85]
];

// ---------- Solicitações de entrada pendentes ----------
// colunas: 0 nome | 1 CPF | 2 posição | 3 número
$pendentes = [
    ["João Pedro Alves", "123.456.789-00", "Atacante", 10],
    ["Marcos Vinícius", "234.567.890-11", "Goleiro", 1],
    ["Lucas Fernandes", "345.678.901-22", "Lateral", 6]
];

// ---------- Tipos de evento ----------
// colunas: 0 nome | 1 ícone | 2 cor (classe do CSS)
// A posição na matriz é o "código" do tipo: 0 = jogo, 1 = treino tático, 2 = treino físico
$tipos = [
    ["Jogo", "bola.png", "azul"],
    ["Treino tático", "agenda.png", "verde"],
    ["Treino físico", "corrida.png", "roxo"]
];

// ---------- Eventos da agenda ----------
// colunas: 0 id | 1 tipo (código de $tipos) | 2 título | 3 dia da semana | 4 dia | 5 mês
//          6 hora | 7 local | 8 confirmados | 9 pendentes
$eventos = [
    [0, 0, "Jogo vs. Palmeirinha FC", "Sáb", 8, "ago", "15:00", "Ginásio Municipal", 21, 3],
    [1, 1, "Treino tático - definição de escalação", "Qua", 12, "ago", "19:00", "Quadra do clube", 18, 6],
    [2, 2, "Treino físico", "Sex", 14, "ago", "18:00", "Academia do clube", 16, 8],
    [3, 1, "Treino tático - saída de bola", "Qua", 19, "ago", "19:00", "Quadra do clube", 12, 12],
    [4, 2, "Treino físico", "Sex", 21, "ago", "18:00", "Academia do clube", 10, 14],
    [5, 0, "Jogo vs. Atlético Rio Sul", "Sáb", 22, "ago", "16:00", "Ginásio Municipal", 9, 15]
];

// ---------- Partidas ----------
// colunas: 0 adversário | 1 data | 2 local | 3 gols do time | 4 gols do adversário
//          5 situação (1 = já jogou, 0 = agendada)
$partidas = [
    ["Operário Futsal", "01 jul", "Ginásio do Operário", 5, 0, 1],
    ["Vila Nova Futsal", "11 jul", "Quadra Vila Nova", 1, 1, 1],
    ["Juventus Futsal", "18 jul", "Ginásio Municipal", 2, 3, 1],
    ["Esporte Clube Aliança", "25 jul", "Ginásio Municipal", 4, 2, 1],
    ["Palmeirinha FC", "08 ago", "Ginásio Municipal", 0, 0, 0],
    ["Atlético Rio Sul", "22 ago", "Ginásio Municipal", 0, 0, 0]
];

// ---------- Comunicados ----------
// colunas: 0 texto | 1 destinatário | 2 data
$destinos = ["Todos", "Atletas", "Comissão técnica"];

$comunicados = [
    ["Confirmar presença até sexta às 12h", "Atletas", "04 ago"],
    ["Treino de quinta remarcado para sexta.", "Todos", "05 ago"],
    ["Levar uniforme reserva no sábado.", "Todos", "06 ago"]
];

// ---------- Plano de treino do atleta ----------
$plano_resumo = "Foco em finalização e resistência. 4 sessões previstas nas próximas duas semanas.";

// colunas: 0 data | 1 tipo (código de $tipos) | 2 foco | 3 hora
$plano = [
    ["Qua, 12 ago", 1, "Definição de escalação e saída de bola", "19:00"],
    ["Sex, 14 ago", 2, "Resistência e finalização", "18:00"],
    ["Qua, 19 ago", 1, "Bolas paradas e transição defensiva", "19:00"],
    ["Sex, 21 ago", 2, "Velocidade e finalização sob cansaço", "18:00"]
];

// ---------- Comissão técnica e permissões ----------
$permissoes_nomes = ["Criar eventos", "Enviar comunicados", "Aprovar atletas", "Lançar resultados"];

// colunas: 0 nome | 1 função | 2, 3, 4 e 5 = permissões (1 = pode, 0 = não pode),
//          na mesma ordem de $permissoes_nomes
$comissao = [
    ["Rafael Costa", "Administrador", 1, 1, 1, 1],
    ["Marcelo Tavares", "Treinador", 1, 1, 0, 1],
    ["Fernanda Lima", "Preparadora física", 1, 0, 0, 0],
    ["Carlos Nunes", "Auxiliar técnico", 0, 1, 0, 0]
];
?>
