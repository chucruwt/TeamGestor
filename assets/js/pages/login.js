$('#form').addEventListener('submit', (e) => {
    e.preventDefault();
    $('#erro').textContent = Auth.login($('#email').value.trim(), $('#senha').value);
});
