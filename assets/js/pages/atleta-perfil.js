const s = Auth.proteger('atleta');
montarSidebar(s, 'perfil');
cabecalho('Meu perfil', s);
function desenhar() {
    const u = DB.ler('usuarios').find((x) => x.id === s.id),
        cpf = u.cpf ? '***.' + u.cpf.slice(4, 11) + '-**' : '—';
    $('#resumo').innerHTML =
        `<span class="av"></span><div><h3 style="font-size:18px">${esc(u.nome)}</h3><p class="sub">Atleta${u.numero ? ' - nº' + u.numero : ''}</p></div>`;
    $('#dados').innerHTML = [
        ['Time', timeDe(u).nome + ' - ' + timeDe(u).categoria],
        ['Posição', u.posicao || '—'],
        ['Número', u.numero || '—'],
        ['Frequência', frequencia(u.id)],
        ['E-mail', u.email],
        ['CPF', cpf],
    ]
        .map(([k, v]) => `<div><small>${k}</small>${esc(v)}</div>`)
        .join('');
    $('#nome').value = u.nome;
    $('#email').value = u.email;
    $('#tel').value = u.telefone || '';
}
$('#form').addEventListener('submit', (e) => {
    e.preventDefault();
    const us = DB.ler('usuarios'),
        u = us.find((x) => x.id === s.id),
        email = $('#email').value.trim();
    if (!V.email(email)) return ($('#erro').textContent = 'E-mail inválido.');
    if (us.some((x) => x.email === email && x.id !== u.id))
        return ($('#erro').textContent = 'Este e-mail já está em uso.');
    Object.assign(u, { nome: $('#nome').value.trim(), email, telefone: $('#tel').value.trim() });
    DB.salvar('usuarios', us);
    DB.setSessao(u);
    $('#erro').textContent = '';
    montarSidebar(u, 'perfil');
    desenhar();
    alert('Dados atualizados!');
});
desenhar();
