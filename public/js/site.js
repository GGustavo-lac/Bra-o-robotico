(() => {
  'use strict';

  // Navegação móvel: mantém o estado acessível e fecha ao selecionar um link.
  const toggle = document.querySelector('.menu-toggle');
  const mobileNav = document.querySelector('.mobile-nav');
  if (toggle && mobileNav) {
    const closeMenu = () => {
      toggle.setAttribute('aria-expanded', 'false');
      mobileNav.hidden = true;
    };
    toggle.addEventListener('click', () => {
      const willOpen = toggle.getAttribute('aria-expanded') !== 'true';
      toggle.setAttribute('aria-expanded', String(willOpen));
      mobileNav.hidden = !willOpen;
    });
    mobileNav.querySelectorAll('a').forEach((link) => link.addEventListener('click', closeMenu));
    document.addEventListener('keydown', (event) => {
      if (event.key === 'Escape') closeMenu();
    });
  }

  // Confirma ações destrutivas do painel administrativo.
  document.querySelectorAll('[data-confirm]').forEach((form) => {
    form.addEventListener('submit', (event) => {
      const message = form.getAttribute('data-confirm') || 'Confirmar esta ação?';
      if (!window.confirm(message)) event.preventDefault();
    });
  });

  // Busca no inventário: filtra o texto visível, inclusive acentos, sem nova consulta SQL.
  const search = document.querySelector('#component-search');
  if (search) {
    const rows = [...document.querySelectorAll('[data-component-row]')];
    const groups = [...document.querySelectorAll('[data-component-group]')];
    const empty = document.querySelector('#component-empty');
    const normalize = (value) => value.normalize('NFD').replace(/[\u0300-\u036f]/g, '').toLocaleLowerCase('pt-BR');
    search.addEventListener('input', () => {
      const query = normalize(search.value.trim());
      let visible = 0;
      rows.forEach((row) => {
        const match = normalize(row.textContent || '').includes(query);
        row.hidden = !match;
        if (match) visible += 1;
      });
      groups.forEach((group) => {
        group.hidden = !group.querySelector('[data-component-row]:not([hidden])');
      });
      if (empty) empty.hidden = visible !== 0;
    });
  }
})();
