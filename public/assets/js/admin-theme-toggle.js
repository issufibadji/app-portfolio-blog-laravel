/**
 * admin-theme-toggle.js
 * ─────────────────────────────────────────────────────────────────────────────
 * Alternância de tema Dark / Light para o painel admin.
 *
 * localStorage key : 'admin-theme'
 * Botão            : #admin-theme-toggle-btn
 * Atributo HTML    : data-theme="light"  (dark = sem atributo, é o padrão)
 * ─────────────────────────────────────────────────────────────────────────────
 */

(function () {
  'use strict';

  var STORAGE_KEY = 'admin-theme';
  var LIGHT       = 'light';
  var DARK        = 'dark';
  var BTN_ID      = 'admin-theme-toggle-btn';
  var html        = document.documentElement;

  /* ── 1. Obter tema inicial ─────────────────────────────────────────────── */
  function getInitialTheme() {
    var stored = localStorage.getItem(STORAGE_KEY);
    if (stored === LIGHT || stored === DARK) return stored;
    // Respeita preferência do sistema na primeira visita
    if (window.matchMedia && window.matchMedia('(prefers-color-scheme: light)').matches) {
      return LIGHT;
    }
    return DARK; // padrão do admin
  }

  /* ── 2. Aplicar tema ───────────────────────────────────────────────────── */
  function applyTheme(theme) {
    if (theme === LIGHT) {
      html.setAttribute('data-theme', LIGHT);
    } else {
      html.removeAttribute('data-theme');
    }
    localStorage.setItem(STORAGE_KEY, theme);
    updateButton(theme);
  }

  /* ── 3. Atualizar estado visual e ARIA do botão ────────────────────────── */
  function updateButton(theme) {
    var btn = document.getElementById(BTN_ID);
    if (!btn) return;

    var isLight = theme === LIGHT;
    btn.setAttribute('aria-pressed', isLight ? 'true' : 'false');
    btn.setAttribute('aria-label',   isLight ? 'Ativar tema escuro' : 'Ativar tema claro');
    btn.setAttribute('title',        isLight ? 'Tema: Claro (clique para escuro)' : 'Tema: Escuro (clique para claro)');
  }

  /* ── 4. Toggle ─────────────────────────────────────────────────────────── */
  function toggleTheme() {
    var current = html.getAttribute('data-theme') === LIGHT ? LIGHT : DARK;
    applyTheme(current === DARK ? LIGHT : DARK);
  }

  /* ── 5. Inicialização (aplica tema imediatamente para evitar FOUC) ──────── */
  applyTheme(getInitialTheme());

  /* ── 6. Bind do clique após o DOM estar pronto ─────────────────────────── */
  function bindButton() {
    var btn = document.getElementById(BTN_ID);
    if (!btn) {
      console.warn('[admin-theme-toggle] Botão #' + BTN_ID + ' não encontrado.');
      return;
    }
    btn.addEventListener('click', function (e) {
      e.preventDefault();
      toggleTheme();
    });
    // Sincroniza ARIA com o tema já aplicado
    var current = html.getAttribute('data-theme') === LIGHT ? LIGHT : DARK;
    updateButton(current);
  }

  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', bindButton);
  } else {
    bindButton(); // DOM já pronto (script carregado no final do body)
  }

  /* ── 7. Sincronizar com mudança de preferência do sistema ──────────────── */
  if (window.matchMedia) {
    window.matchMedia('(prefers-color-scheme: light)').addEventListener('change', function (e) {
      // Só reage se não houver preferência salva manualmente
      if (!localStorage.getItem(STORAGE_KEY)) {
        applyTheme(e.matches ? LIGHT : DARK);
      }
    });
  }

})();
