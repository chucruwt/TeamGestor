const MENUS = {
    admin: [
        ['painel', 'Painel'],
        ['atletas', 'Atletas'],
        ['agenda', 'Agenda'],
        ['partidas', 'Partidas'],
        ['comunicados', 'Comunicados'],
        ['permissoes', 'Permissões'],
    ],
    atleta: [
        ['painel', 'Painel'],
        ['perfil', 'Meu perfil'],
        ['agenda', 'Agenda'],
        ['plano-treino', 'Plano de treino'],
        ['comunicados', 'Comunicados'],
    ],
};
function montarSidebar(s, pagina) {
    $('#sidebar').innerHTML =
        `<img src="../../assets/img/logo-teamgestor.png" alt="TeamGestor">` +
        MENUS[s.tipo]
            .map(([p, n]) => `<a href="${p}.html" class="${p === pagina ? 'ativo' : ''}">${n}</a>`)
            .join('') +
        `<div class="usuario"><b>${s.nome}</b><small>${s.tipo === 'admin' ? 'Admin' : 'Atleta'}</small><a href="#" onclick="Auth.sair()">Sair</a></div>`;
}
