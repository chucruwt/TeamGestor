V.mascaraCpf($('#cpf'));
$('#form').addEventListener('submit', (e) => {
    e.preventDefault();
    const erro = (m) => ($('#erro').textContent = m),
        email = $('#email').value.trim();
    if (!V.cpf($('#cpf').value)) return erro('CPF inválido.');
    if (!V.email(email)) return erro('E-mail inválido.');
    if ($('#senha').value.length < 6) return erro('A senha precisa ter ao menos 6 caracteres.');
    if ($('#senha').value !== $('#senha2').value) return erro('As senhas não conferem.');
    if (!$('#termos').checked) return erro('Confirme que você é o responsável pelo time.');
    if (DB.ler('usuarios').some((u) => u.email === email))
        return erro('Este e-mail já está cadastrado.');
    const t = DB.add('times', {
        nome: $('#time').value.trim(),
        modalidade: $('#modalidade').value.trim(),
        categoria: $('#categoria').value.trim(),
    });
    // status 'ativo' só para testar no protótipo; depois será 'pendente' até a revisão da equipe
    DB.add('usuarios', {
        nome: $('#nome').value.trim(),
        cpf: $('#cpf').value,
        telefone: $('#tel').value,
        email,
        senha: $('#senha').value,
        tipo: 'admin',
        timeId: t.id,
        status: 'ativo',
    });
    alert('Time criado! (no protótipo a aprovação é automática)');
    location.href = 'login.html';
});
