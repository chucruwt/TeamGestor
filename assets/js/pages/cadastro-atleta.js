V.mascaraCpf($('#cpf'));
DB.ler('times').forEach((t) => $('#time').add(new Option(`${t.nome} - ${t.categoria}`, t.id)));
$('#form').addEventListener('submit', (e) => {
    e.preventDefault();
    const erro = (m) => ($('#erro').textContent = m),
        email = $('#email').value.trim();
    if (!V.cpf($('#cpf').value)) return erro('CPF inválido.');
    if (!V.email(email)) return erro('E-mail inválido.');
    if ($('#senha').value.length < 6) return erro('A senha precisa ter ao menos 6 caracteres.');
    if ($('#senha').value !== $('#senha2').value) return erro('As senhas não conferem.');
    if (!$('#termos').checked) return erro('Aceite os termos de uso para continuar.');
    if (DB.ler('usuarios').some((u) => u.email === email))
        return erro('Este e-mail já está cadastrado.');
    DB.add('usuarios', {
        nome: $('#nome').value.trim(),
        cpf: $('#cpf').value,
        nascimento: $('#nasc').value,
        email,
        senha: $('#senha').value,
        tipo: 'atleta',
        timeId: +$('#time').value,
        status: 'pendente',
    });
    alert('Solicitação enviada! Aguarde a aprovação do administrador.');
    location.href = 'login.html';
});
