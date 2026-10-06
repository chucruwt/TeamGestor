const Auth = {
    login(email, senha) {
        const u = DB.ler('usuarios').find((x) => x.email === email && x.senha === senha);
        if (!u) return 'E-mail ou senha incorretos.';
        if (u.status !== 'ativo') return 'Seu cadastro ainda aguarda aprovação.';
        DB.setSessao(u);
        location.href = '../' + u.tipo + '/painel.html';
        return '';
    },
    sair() {
        DB.setSessao(null);
        location.href = '../auth/login.html';
    },
    proteger(tipo) {
        const s = DB.sessao();
        if (!s || s.tipo !== tipo) {
            location.href = '../auth/login.html';
        }
        return s;
    },
};
