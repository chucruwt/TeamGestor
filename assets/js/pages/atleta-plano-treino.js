const s = Auth.proteger('atleta');
montarSidebar(s, 'plano-treino');
cabecalho('Plano de treino', s);
$('#foco').textContent = 'Foco em finalização e resistência. 4 sessões previstas até sábado.';
function desenhar() {
    const feitos = DB.ler('concluidos');
    $('#lista').innerHTML =
        DB.ler('eventos')
            .filter((e) => e.tipo !== 'jogo')
            .map((e) => {
                const ok = feitos.some((c) => c.userId === s.id && c.eventoId === e.id);
                return linhaEvento(
                    e,
                    '',
                    `<label style="margin:0;font-weight:400"><input type="checkbox" data-e="${e.id}" ${ok ? 'checked' : ''}>Concluído</label>`
                );
            })
            .join('') || '<p class="vazio">Nenhum treino previsto.</p>';
}
document.addEventListener('change', (e) => {
    const id = +e.target.dataset.e;
    if (!id) return;
    let l = DB.ler('concluidos').filter((c) => !(c.userId === s.id && c.eventoId === id));
    if (e.target.checked) l.push({ userId: s.id, eventoId: id });
    DB.salvar('concluidos', l);
});
desenhar();
