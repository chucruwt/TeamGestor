const s = Auth.proteger('admin');
montarSidebar(s, 'agenda');
cabecalho('Agenda', s, '<button class="btn btn-escuro" id="novo">+ Criar evento</button>');
let f = 'todos';
const dlg = ligarDialogo();
const nomes = (a) => (a.length ? a.map((u) => esc(u.nome)).join(', ') : '—');
function desenhar() {
    const at = atletasDe(s).filter((u) => u.status === 'ativo');
    $('#lista').innerHTML =
        DB.ler('eventos')
            .filter((e) => f === 'todos' || e.tipo === f)
            .map((e) => {
                const por = (st) => at.filter((u) => statusDe(e.id, u.id) === st),
                    ok = por('confirmado'),
                    no = por('ausente'),
                    pd = por('pendente');
                const mot = (u) =>
                    `${esc(u.nome)} (${esc((presencas().find((p) => p.eventoId === e.id && p.userId === u.id) || {}).motivo || 'sem motivo')})`;
                return (
                    `<div class="ev">` +
                    linhaEvento(
                        e,
                        `<div class="conf"><span class="ok">${ok.length} Confirmados</span><br><span class="pd">${pd.length} Pendentes</span></div>`,
                        `<button class="btn btn-p" data-d="${e.id}">▾</button><button class="btn btn-p" data-x="${e.id}">Excluir</button>`
                    ) +
                    `<div class="detalhe" id="d${e.id}" hidden><b>Confirmados:</b> ${nomes(ok)}<br><b>Ausências:</b> ${no.length ? no.map(mot).join(', ') : '—'}<br><b>Sem resposta:</b> ${nomes(pd)}</div></div>`
                );
            })
            .join('') || '<p class="vazio">Nenhum evento nesta categoria.</p>';
}
document.addEventListener('click', (e) => {
    const t = e.target.dataset;
    if (t.d) {
        const d = $('#d' + t.d);
        d.hidden = !d.hidden;
    }
    if (t.x && confirm('Excluir este evento?')) {
        DB.salvar(
            'eventos',
            DB.ler('eventos').filter((v) => v.id !== +t.x)
        );
        desenhar();
    }
});
$('#form').addEventListener('submit', (e) => {
    e.preventDefault();
    DB.add('eventos', {
        tipo: $('#tipo').value,
        titulo: $('#titulo').value.trim(),
        data: fmtData($('#data').value),
        hora: $('#hora').value,
        local: $('#local').value.trim(),
    });
    dlg.close();
    desenhar();
});
ligarChips((v) => {
    f = v;
    desenhar();
});
desenhar();
