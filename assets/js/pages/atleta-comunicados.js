const s = Auth.proteger('atleta');
montarSidebar(s, 'comunicados');
cabecalho('Comunicados', s);
$('#lista').innerHTML =
    DB.ler('comunicados')
        .map(
            (c) =>
                `<div class="linha"><span class="icone" style="background:var(--ic-laranja)">🔔</span><div class="info-ev" style="font-weight:400">${esc(c)}</div></div>`
        )
        .reverse()
        .join('') || '<p class="vazio">Nenhum comunicado por enquanto.</p>';
