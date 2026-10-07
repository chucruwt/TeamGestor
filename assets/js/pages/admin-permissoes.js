const s = Auth.proteger('admin');
montarSidebar(s, 'permissoes');
cabecalho('Permissões', s);
const PERMS = ['eventos', 'partidas', 'comunicados'];
function desenhar() {
    const p = DB.ler('permissoes');
    $('#lista').innerHTML =
        `<tr><td><b>${esc(s.nome)}</b> (Admin)</td><td colspan="3">Todas as permissões</td></tr>` +
        atletasDe(s)
            .filter((u) => u.status === 'ativo')
            .map((u) => {
                const m = (p.find((x) => x.userId === u.id) || {}).perms || [];
                return (
                    `<tr><td>${esc(u.nome)}</td>` +
                    PERMS.map(
                        (k) =>
                            `<td><input type="checkbox" data-u="${u.id}" data-k="${k}" ${m.includes(k) ? 'checked' : ''}></td>`
                    ).join('') +
                    '</tr>'
                );
            })
            .join('');
}
document.addEventListener('change', (e) => {
    const t = e.target.dataset;
    if (!t.u) return;
    const p = DB.ler('permissoes');
    let r = p.find((x) => x.userId === +t.u);
    if (!r) {
        r = { userId: +t.u, perms: [] };
        p.push(r);
    }
    r.perms = e.target.checked ? [...new Set([...r.perms, t.k])] : r.perms.filter((k) => k !== t.k);
    DB.salvar('permissoes', p);
});
desenhar();
