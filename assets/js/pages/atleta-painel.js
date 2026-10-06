const s = Auth.proteger('atleta');
montarSidebar(s, 'painel');
const time = DB.ler('times').find((t) => t.id === s.timeId),
    ev = DB.ler('eventos'),
    j = ev.find((e) => e.tipo === 'jogo');
$('#ola').textContent = 'Olá, ' + s.nome.split(' ').slice(0, 2).join(' ');
$('#time').textContent = time.nome + ' - ' + time.categoria;
if (j)
    $('#prox').innerHTML =
        `<span class="icone" style="background:var(--ic-azul);width:48px;height:48px">⚽</span><div style="flex:1"><b>Próximo compromisso: ${j.titulo}</b><p class="sub">${j.data} - ${j.hora}<br>${j.local}</p></div>
<button class="btn btn-verde">Confirmar Presença</button><button class="btn">Não poderei ir</button>`;
$('#agenda').innerHTML = ev.map(itemEvento).join('');
$('#coms').innerHTML = DB.ler('comunicados')
    .slice(0, 2)
    .map((c) => `<p class="item" style="color:var(--cinza)">${c}</p>`)
    .join('');
