const s = Auth.proteger('atleta');
montarSidebar(s, 'agenda');
cabecalho('Agenda', s);
let f = 'todos';
const ST = {
    confirmado: 'Presença confirmada',
    ausente: 'Ausência informada',
    pendente: 'Sem resposta',
};
function desenhar() {
    $('#lista').innerHTML =
        DB.ler('eventos')
            .filter((e) => f === 'todos' || e.tipo === f)
            .map((e) =>
                linhaEvento(
                    e,
                    `<span class="sub">${ST[statusDe(e.id, s.id)]}</span>`,
                    `<button class="btn btn-verde btn-p" data-c="${e.id}">Confirmar presença</button><button class="btn btn-p" data-n="${e.id}">Não poderei ir</button>`
                )
            )
            .join('') || '<p class="vazio">Nenhum evento nesta categoria.</p>';
}
document.addEventListener('click', (e) => {
    const t = e.target.dataset;
    if (t.c) responder(+t.c, s.id, 'confirmado');
    else if (t.n) {
        const m = prompt('Qual o motivo da ausência?');
        if (m === null) return;
        responder(+t.n, s.id, 'ausente', m.trim());
    } else return;
    desenhar();
});
ligarChips((v) => {
    f = v;
    desenhar();
});
desenhar();
