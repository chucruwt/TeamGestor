const s = Auth.proteger('admin');
montarSidebar(s, 'painel');
const time = DB.ler('times').find((t) => t.id === s.timeId),
    us = DB.ler('usuarios').filter((u) => u.timeId === s.timeId && u.tipo === 'atleta'),
    ev = DB.ler('eventos');
$('#ola').textContent = 'Olá, ' + s.nome.split(' ')[0];
$('#time').textContent = time.nome + ' - ' + time.categoria;
$('#s1').textContent = us.filter((u) => u.status === 'ativo').length;
const pend = us.filter((u) => u.status === 'pendente');
$('#s4').textContent = pend.length;
const jogo = ev.find((e) => e.tipo === 'jogo');
$('#s3').textContent = jogo ? jogo.data : '—';
$('#agenda').innerHTML = ev.map(itemEvento).join('');
$('#coms').innerHTML = DB.ler('comunicados')
    .map((c) => `<p class="item" style="color:var(--cinza)">${c}</p>`)
    .join('');
$('#pend').innerHTML =
    pend
        .map(
            (u) =>
                `<div class="item"><span class="icone" style="background:var(--pendente);border-radius:50%;width:22px;height:22px"></span>${u.nome}</div>`
        )
        .join('') || '<p class="sub mt">Nenhuma solicitação.</p>';
