'use strict';
const themeButton = document.getElementById('alternar-tema');
function updateThemeButton() {
  if (!themeButton) return;
  const dark = document.documentElement.dataset.theme === 'dark';
  themeButton.textContent = dark ? 'Modo claro' : 'Modo escuro';
  themeButton.setAttribute('aria-label', dark ? 'Ativar modo claro' : 'Ativar modo escuro');
}
if (themeButton) {
  updateThemeButton();
  themeButton.addEventListener('click', function () {
    const next = document.documentElement.dataset.theme === 'dark' ? 'light' : 'dark';
    document.documentElement.dataset.theme = next;
    try {
      window.localStorage.setItem('oficina-senac178:tema', next);
    } catch (error) {
      // A alternância funciona mesmo quando não é possível guardar a preferência.
    }
    updateThemeButton();
  });
}
document.querySelectorAll('form[data-delete]').forEach(function (form) {
  form.addEventListener('submit', function (event) {
    if (!window.confirm('Excluir a solicitação ' + form.dataset.delete + '? Esta ação remove o registro da demonstração.')) {
      event.preventDefault();
    }
  });
});
const form = document.getElementById('formulario');
if (form && form.dataset.focus) {
  document.getElementById(form.dataset.focus)?.focus();
}
