const form = document.getElementById('form-login');
form.addEventListener('submit', async (e) => {
  e.preventDefault();

  try {
    const res = await fetch('login.php', {
      method: 'POST',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify({
        usuario: document.getElementById('usuario').value,
        senha: document.getElementById('senha').value
      })
    });

    if (!res.ok) {
      alert('Erro ao conectar com o servidor.');
      return;
    }

    const data = await res.json();

    if (data.success) {
      window.location.href = '../tela_inicial/telainicial.html';
    } else {
      alert('Usuário ou senha inválidos.');
    }

  } catch (err) {
    console.error(err);
    alert('Ocorreu um erro inesperado.');
  }
});
