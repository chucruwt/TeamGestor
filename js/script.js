// =====================================================
// TeamGestor - script.js
// Só três coisas são feitas em JavaScript:
//   1. mostrar/ocultar a senha (ícone do olho)
//   2. buscar atleta na tabela enquanto digita
//   3. abrir/fechar os detalhes de um evento (setinha)
// Todo o resto do sistema é feito em PHP.
// =====================================================

// ---------- 1. Mostrar/ocultar senha ----------
var botoesOlho = document.querySelectorAll(".btn-olho");

for (var i = 0; i < botoesOlho.length; i++) {
    botoesOlho[i].addEventListener("click", function () {
        // o botão guarda o id do campo no atributo data-alvo
        var campo = document.getElementById(this.getAttribute("data-alvo"));

        if (campo.type == "password") {
            campo.type = "text";
        } else {
            campo.type = "password";
        }
    });
}

// ---------- 2. Buscar atleta na tabela ----------
var campoBusca = document.getElementById("busca");

if (campoBusca) {
    campoBusca.addEventListener("input", function () {
        var texto = campoBusca.value.toLowerCase();
        var linhas = document.querySelectorAll(".linha-atleta");

        for (var j = 0; j < linhas.length; j++) {
            var conteudoLinha = linhas[j].textContent.toLowerCase();

            if (conteudoLinha.indexOf(texto) >= 0) {
                linhas[j].style.display = "";
            } else {
                linhas[j].style.display = "none";
            }
        }
    });
}

// ---------- 3. Abrir/fechar detalhes do evento ----------
var botoesExpandir = document.querySelectorAll(".btn-expandir");

for (var k = 0; k < botoesExpandir.length; k++) {
    botoesExpandir[k].addEventListener("click", function () {
        var cartao = this.closest(".evento");
        cartao.classList.toggle("aberto");

        if (cartao.classList.contains("aberto")) {
            this.setAttribute("aria-expanded", "true");
        } else {
            this.setAttribute("aria-expanded", "false");
        }
    });
}
