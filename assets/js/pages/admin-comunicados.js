const s = Auth.proteger('admin');
montarSidebar(s, 'comunicados');
cabecalho('Comunicados', s);
function desenhar() {
    $('#lista').innerHTML =
        DB.ler('comunicados')
            .map(
                (c, i) =>
                    `<div class="linha"><div class="info-ev" style="font-weight:400">${esc(c)}</div><button class="btn btn-p" data-i="${i}">Excluir</button></div>`
            )
            .reverse()
            .join('') || '<p class="vazio">Nenhum comunicado enviado.</p>';
}
$('#form').addEventListener('submit', (e) => {
    e.preventDefault();
    const l = DB.ler('comunicados');
    l.push($('#msg').value.trim());
    DB.salvar('comunicados', l);
    $('#form').reset();
    desenhar();
});
document.addEventListener('click', (e) => {
    const i = e.target.dataset.i;
    if (i === undefined) return;
    const l = DB.ler('comunicados');
    l.splice(+i, 1);
    DB.salvar('comunicados', l);
    desenhar();
});
desenhar();
