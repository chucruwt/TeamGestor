// Auxiliares das páginas de gestão. Depende de storage.js e utils.js (não altera nenhum deles).
const DIAS = ['Dom', 'Seg', 'Ter', 'Qua', 'Qui', 'Sex', 'Sáb'],
    MESES = ['jan', 'fev', 'mar', 'abr', 'mai', 'jun', 'jul', 'ago', 'set', 'out', 'nov', 'dez'];
const fmtData = (iso) => {
    const d = new Date(iso + 'T12:00');
    return `${DIAS[d.getDay()]}, ${d.getDate()} ${MESES[d.getMonth()]}`;
};
const esc = (t) =>
    String(t ?? '').replace(
        /[&<>"]/g,
        (c) => ({ '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;' })[c]
    );
const timeDe = (s) => DB.ler('times').find((t) => t.id === s.timeId) || { nome: '', categoria: '' };
const atletasDe = (s) =>
    DB.ler('usuarios').filter((u) => u.timeId === s.timeId && u.tipo === 'atleta');
const presencas = () => DB.ler('presencas');
const statusDe = (ev, uid) =>
    (presencas().find((p) => p.eventoId === ev && p.userId === uid) || {}).status || 'pendente';
function responder(ev, uid, status, motivo = '') {
    const l = presencas().filter((p) => !(p.eventoId === ev && p.userId === uid));
    l.push({ eventoId: ev, userId: uid, status, motivo });
    DB.salvar('presencas', l);
}
const frequencia = (uid) => {
    const r = presencas().filter((p) => p.userId === uid);
    return r.length
        ? Math.round((r.filter((p) => p.status === 'confirmado').length / r.length) * 100) + '%'
        : '—';
};
function cabecalho(t, s, acao = '') {
    $('#topo').innerHTML =
        `<div><h1>${t}</h1><p class="sub">${esc(timeDe(s).nome)} - ${esc(timeDe(s).categoria)}</p></div><div>${acao}</div>`;
}
function ligarChips(cb) {
    document.querySelectorAll('.chip').forEach(
        (b) =>
            (b.onclick = () => {
                document.querySelectorAll('.chip').forEach((c) => c.classList.remove('ativo'));
                b.classList.add('ativo');
                cb(b.dataset.f);
            })
    );
}
function ligarDialogo() {
    const d = $('#dlg');
    $('#novo').onclick = () => {
        $('#form').reset();
        d.showModal();
    };
    $('#cancelar').onclick = () => d.close();
    return d;
}
const linhaEvento = (e, meio, fim) => {
    const [d, r] = e.data.split(', ');
    return `<div class="linha"><div class="dia">${d.toUpperCase()}<b>${parseInt(r)}</b></div><span class="icone" style="background:var(${ICONES[e.tipo][1]})">${ICONES[e.tipo][0]}</span><div class="info-ev">${esc(e.titulo)}<small>${e.hora}${e.local ? ' ' + esc(e.local) : ''}</small></div>${meio}${fim}</div>`;
};
