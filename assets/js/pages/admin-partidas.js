const s = Auth.proteger('admin');
montarSidebar(s, 'partidas');
cabecalho('Partidas', s, '<button class="btn btn-escuro" id="novo">+ Registrar partida</button>');
const dlg = ligarDialogo();
if (!DB.ler('partidas').length)
    DB.salvar('partidas', [
        {
            id: 1,
            adversario: 'Palmeirinha FC',
            data: '2026-07-26',
            local: 'Ginásio Municipal',
            nos: 5,
            eles: 3,
        },
    ]);
const res = (p) =>
    p.nos > p.eles
        ? ['V', 'b-v', 'Vitória']
        : p.nos < p.eles
          ? ['D', 'b-d', 'Derrota']
          : ['E', 'b-e', 'Empate'];
function desenhar() {
    const l = DB.ler('partidas'),
        c = (k) => l.filter((p) => res(p)[0] === k).length;
    $('#resumo').innerHTML = [
        ['Jogos', l.length],
        ['Vitórias', c('V')],
        ['Empates', c('E')],
        ['Derrotas', c('D')],
    ]
        .map(([n, v]) => `<div class="card stat"><span>${n}:</span><b>${v}</b></div>`)
        .join('');
    $('#lista').innerHTML =
        l
            .map(
                (p) =>
                    `<tr><td>${fmtData(p.data)}</td><td>${esc(p.adversario)}</td><td>${esc(p.local || '—')}</td><td>${p.nos} x ${p.eles}</td><td><span class="badge ${res(p)[1]}">${res(p)[2]}</span></td><td><button class="btn btn-p" data-x="${p.id}">Excluir</button></td></tr>`
            )
            .join('') || '<tr><td colspan="6" class="vazio">Nenhuma partida registrada.</td></tr>';
}
document.addEventListener('click', (e) => {
    const x = +e.target.dataset.x;
    if (x && confirm('Excluir esta partida?')) {
        DB.salvar(
            'partidas',
            DB.ler('partidas').filter((p) => p.id !== x)
        );
        desenhar();
    }
});
$('#form').addEventListener('submit', (e) => {
    e.preventDefault();
    DB.add('partidas', {
        adversario: $('#adv').value.trim(),
        data: $('#data').value,
        local: $('#local').value.trim(),
        nos: +$('#nos').value,
        eles: +$('#eles').value,
    });
    dlg.close();
    desenhar();
});
desenhar();
