const s = Auth.proteger('admin');
montarSidebar(s, 'atletas');
cabecalho('Atletas', s);
function desenhar() {
    const us = atletasDe(s),
        pend = us.filter((u) => u.status === 'pendente'),
        q = $('#q').value.toLowerCase();
    $('#pend').innerHTML = pend.length
        ? `<div class="pend-box"><h3>Solicitações pendentes (${pend.length})</h3>` +
          pend
              .map(
                  (u) =>
                      `<div class="pend-item"><span class="av"></span><div>${esc(u.nome)}<small>CPF ${esc(u.cpf || '—')}</small></div><button class="btn btn-verde btn-p" data-a="${u.id}">Aprovar</button><button class="btn btn-p" data-r="${u.id}">Recusar</button></div>`
              )
              .join('') +
          '</div>'
        : '';
    const l = us.filter(
        (u) =>
            u.status === 'ativo' &&
            (u.nome.toLowerCase().includes(q) || (u.posicao || '').toLowerCase().includes(q))
    );
    $('#lista').innerHTML =
        l
            .map(
                (u) =>
                    `<tr><td><span class="av" style="display:inline-block;vertical-align:middle;margin-right:8px"></span>${esc(u.nome)}</td><td>${esc(u.posicao || '—')}</td><td>${u.numero || '—'}</td><td>${frequencia(u.id)}</td><td><button class="btn btn-p" data-e="${u.id}">Editar</button></td></tr>`
            )
            .join('') || '<tr><td colspan="5" class="vazio">Nenhum atleta encontrado.</td></tr>';
}
document.addEventListener('click', (e) => {
    const t = e.target.dataset,
        id = +(t.a || t.r || t.e || 0);
    if (!id) return;
    const us = DB.ler('usuarios'),
        u = us.find((x) => x.id === id);
    if (t.r) {
        DB.salvar(
            'usuarios',
            us.filter((x) => x.id !== id)
        );
        return desenhar();
    }
    if (t.a) u.status = 'ativo';
    if (t.e) {
        const p = prompt('Posição:', u.posicao || '');
        if (p === null) return;
        const n = prompt('Número da camisa:', u.numero || '');
        u.posicao = p.trim();
        u.numero = n ? +n : '';
    }
    DB.salvar('usuarios', us);
    desenhar();
});
$('#q').addEventListener('input', desenhar);
desenhar();
