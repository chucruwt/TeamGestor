// Camada de dados. Hoje usa localStorage; com o PHP, troque só este arquivo por fetch('../../api/...').
const DB = {
    ler(c) {
        return JSON.parse(localStorage.getItem('tg_' + c) || '[]');
    },
    salvar(c, v) {
        localStorage.setItem('tg_' + c, JSON.stringify(v));
    },
    add(c, o) {
        const l = this.ler(c);
        o.id = Date.now() + l.length;
        l.push(o);
        this.salvar(c, l);
        return o;
    },
    sessao() {
        return JSON.parse(localStorage.getItem('tg_sessao') || 'null');
    },
    setSessao(u) {
        u
            ? localStorage.setItem('tg_sessao', JSON.stringify(u))
            : localStorage.removeItem('tg_sessao');
    },
    seed() {
        if (this.ler('usuarios').length) return;
        this.salvar('times', [
            { id: 1, nome: 'Guarani Futsal Clube', categoria: 'Sub 17', modalidade: 'Futsal' },
        ]);
        this.salvar('usuarios', [
            {
                id: 1,
                nome: 'Rafael Costa',
                email: 'admin@time.com.br',
                senha: '123456',
                tipo: 'admin',
                timeId: 1,
                status: 'ativo',
            },
            {
                id: 2,
                nome: 'João Pedro Alves',
                email: 'joao@time.com.br',
                senha: '123456',
                tipo: 'atleta',
                timeId: 1,
                status: 'ativo',
                cpf: '123.456.789-00',
                posicao: 'Atacante',
                numero: 10,
            },
            {
                id: 3,
                nome: 'Marcos Vinícius',
                email: 'marcos@time.com.br',
                senha: '123456',
                tipo: 'atleta',
                timeId: 1,
                status: 'pendente',
                cpf: '234.567.890-11',
            },
            {
                id: 4,
                nome: 'Lucas Fernandes',
                email: 'lucas@time.com.br',
                senha: '123456',
                tipo: 'atleta',
                timeId: 1,
                status: 'pendente',
                cpf: '345.678.901-22',
            },
        ]);
        this.salvar('eventos', [
            {
                id: 1,
                tipo: 'jogo',
                titulo: 'Jogo vs. Palmeirinha FC',
                data: 'Sáb, 8 ago',
                hora: '15:00',
                local: 'Ginásio Municipal',
            },
            { id: 2, tipo: 'tatico', titulo: 'Treino tático', data: 'Qua, 12 ago', hora: '19:00' },
            { id: 3, tipo: 'fisico', titulo: 'Treino físico', data: 'Sex, 14 ago', hora: '18:00' },
        ]);
        this.salvar('comunicados', [
            'Levar uniforme reserva no sábado.',
            'Treino de quinta remarcado para sábado.',
            'Confirmar presença até sexta às 12h',
        ]);
    },
};
DB.seed();
