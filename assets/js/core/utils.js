const $ = (s) => document.querySelector(s);
const ICONES = {
    jogo: ['⚽', '--ic-azul'],
    tatico: ['📓', '--ic-verde'],
    fisico: ['🏃', '--ic-roxo'],
}; // trocar por /assets/icons/*.svg
const itemEvento = (e) =>
    `<div class="item"><span class="icone" style="background:var(${ICONES[e.tipo][1]})">${ICONES[e.tipo][0]}</span><div>${e.titulo}<small>${e.data} - ${e.hora}</small></div></div>`;
