<?php
// =====================================================
// json.php - leitura e gravação dos arquivos .json
//
// Os dados dos usuários cadastrados ficam na pasta dados/:
//   dados/usuarios.json            contas que podem entrar (nome, e-mail, senha, tipo)
//   dados/cadastros_atletas.json   tudo o que o atleta preencheu no cadastro
//   dados/cadastros_admins.json    tudo o que o administrador preencheu no cadastro
//
// Cada arquivo é uma lista de registros. Para ver ou editar os
// usuários, é só abrir o arquivo .json em qualquer editor de texto.
// =====================================================

// Caminho completo do arquivo dados/NOME.json
function caminho_json($nome) {
    return __DIR__ . "/dados/" . $nome . ".json";
}

// Lê dados/NOME.json e devolve a lista (vetor). Se o arquivo
// não existir ou estiver vazio/quebrado, devolve uma lista vazia.
function ler_json($nome) {
    $caminho = caminho_json($nome);

    if (!file_exists($caminho)) {
        return [];
    }

    $lista = json_decode(file_get_contents($caminho), true);

    if (!is_array($lista)) {
        return [];
    }

    return $lista;
}

// Abre o arquivo travado (para duas pessoas não gravarem ao mesmo tempo).
// Devolve o "ponteiro" do arquivo ou false se não conseguir abrir.
function abrir_json_travado($nome) {
    $ponteiro = @fopen(caminho_json($nome), "c+");

    if ($ponteiro === false) {
        return false;
    }

    flock($ponteiro, LOCK_EX);
    return $ponteiro;
}

// Lê a lista de um arquivo que já foi aberto com abrir_json_travado().
// Arquivo vazio = lista vazia. Se o arquivo tiver texto que não é um JSON
// válido (alguém editou errado, por exemplo), devolve false: assim nada é
// gravado por cima e os dados que estavam lá não se perdem.
function ler_json_aberto($ponteiro) {
    $texto = stream_get_contents($ponteiro);

    if (trim($texto) == "") {
        return [];
    }

    $lista = json_decode($texto, true);

    if (!is_array($lista)) {
        return false;
    }

    return $lista;
}

// Solta o arquivo sem gravar nada
function fechar_json_aberto($ponteiro) {
    flock($ponteiro, LOCK_UN);
    fclose($ponteiro);
}

// Apaga o conteúdo antigo, grava a lista nova e fecha o arquivo.
// Devolve true se deu certo.
function gravar_json_aberto($ponteiro, $lista) {
    $texto = json_encode($lista, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);

    ftruncate($ponteiro, 0);
    rewind($ponteiro);
    $gravou = fwrite($ponteiro, $texto . "\n");
    fflush($ponteiro);
    flock($ponteiro, LOCK_UN);
    fclose($ponteiro);

    return $gravou !== false;
}

// Coloca um registro novo no FIM de dados/NOME.json.
// Devolve true se gravou, false se não conseguiu (ex.: sem permissão na pasta).
function adicionar_json($nome, $registro) {
    $ponteiro = abrir_json_travado($nome);

    if ($ponteiro === false) {
        return false;
    }

    $lista = ler_json_aberto($ponteiro);

    if ($lista === false) {
        fechar_json_aberto($ponteiro);
        return false;
    }

    $lista[] = $registro;

    return gravar_json_aberto($ponteiro, $lista);
}

// Troca a senha do usuário que tem esse e-mail em dados/usuarios.json.
// Devolve true se gravou, false se não conseguiu.
function trocar_senha_json($email, $nova_senha) {
    $ponteiro = abrir_json_travado("usuarios");

    if ($ponteiro === false) {
        return false;
    }

    $lista = ler_json_aberto($ponteiro);

    if ($lista === false) {
        fechar_json_aberto($ponteiro);
        return false;
    }

    $total = count($lista);

    for ($i = 0; $i < $total; $i++) {
        if (isset($lista[$i]["email"]) && $lista[$i]["email"] == $email) {
            $lista[$i]["senha"] = $nova_senha;
        }
    }

    return gravar_json_aberto($ponteiro, $lista);
}
?>
