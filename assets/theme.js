(function () {
  'use strict';
  // Aplicar a preferência antes da folha de estilos evita a troca de cores ao carregar.
  try {
    var saved = window.localStorage.getItem('oficina-senac178:tema');
    if (saved === 'light' || saved === 'dark') {
      document.documentElement.dataset.theme = saved;
    }
  } catch (error) {
    // O modo escuro padrão continua funcionando se o armazenamento estiver indisponível.
  }
}());
