/**
 * theme-toggle.js
 * ─────────────────────────────────────────────────────────────────────────────
 * Sistema de alternância de tema Dark / Light para o portfólio.
 *
 * Lógica de prioridade (ordem decrescente):
 *   1. Preferência salva no localStorage  → usa direto
 *   2. Preferência do SO (prefers-color-scheme) → aplica na primeira visita
 *   3. Fallback → dark (padrão do portfólio)
 *
 * Aplica   : atributo data-theme="light" | "dark" no elemento <html>
 * Persiste : localStorage.getItem('portfolio-theme')
 * ─────────────────────────────────────────────────────────────────────────────
 */

(function () {
  'use strict';

  const STORAGE_KEY   = 'portfolio-theme';
  const DARK          = 'dark';
  const LIGHT         = 'light';
  const BTN_ID        = 'theme-toggle-btn';
  const html          = document.documentElement;

  /* ── 1. Detectar tema inicial (evita FOUC — Flash of Unstyled Content) ── */
  function getInitialTheme() {
    const stored = localStorage.getItem(STORAGE_KEY);
    if (stored === DARK || stored === LIGHT) return stored;

    // Primeira visita: respeita preferência do sistema
    if (window.matchMedia && window.matchMedia('(prefers-color-scheme: light)').matches) {
      return LIGHT;
    }
    return DARK; // Fallback: dark (padrão do portfólio)
  }

  /* ── 2. Aplicar tema ao <html> ─────────────────────────────────────────── */
  function applyTheme(theme) {
    if (theme === LIGHT) {
      html.setAttribute('data-theme', LIGHT);
    } else {
      html.removeAttribute('data-theme'); // dark é o padrão (sem atributo)
    }
    localStorage.setItem(STORAGE_KEY, theme);
    updateButtonAria(theme);
  }

  /* ── 3. Atualizar ARIA do botão ────────────────────────────────────────── */
  function updateButtonAria(theme) {
    const btn = document.getElementById(BTN_ID);
    if (!btn) return;
    const next = theme === DARK ? LIGHT : DARK;
    btn.setAttribute('aria-label', 'Mudar para tema ' + (next === LIGHT ? 'claro' : 'escuro'));
    btn.setAttribute('aria-pressed', theme === LIGHT ? 'true' : 'false');
    btn.setAttribute('title', next === LIGHT ? 'Ativar tema claro' : 'Ativar tema escuro');
  }

  /* ── 4. Toggle ─────────────────────────────────────────────────────────── */
  function toggleTheme() {
    const current = html.getAttribute('data-theme') === LIGHT ? LIGHT : DARK;
    const next    = current === DARK ? LIGHT : DARK;
    applyTheme(next);
  }

  /* ── 5. Inicialização ──────────────────────────────────────────────────── */
  // Aplica o tema ANTES do primeiro paint (script no <head>)
  applyTheme(getInitialTheme());

  /* ── 6. Bind do evento de click no botão ─────────────────────────────── */
  // Usa DOMContentLoaded pois o botão pode ainda não existir no DOM
  document.addEventListener('DOMContentLoaded', function () {
    const btn = document.getElementById(BTN_ID);
    if (!btn) {
      console.warn('[theme-toggle] Botão #theme-toggle-btn não encontrado no DOM.');
      return;
    }

    btn.addEventListener('click', function (e) {
      e.preventDefault();
      toggleTheme();
    });

    // Sincronizar ARIA ao iniciar (botão já no DOM)
    const current = html.getAttribute('data-theme') === LIGHT ? LIGHT : DARK;
    updateButtonAria(current);
  });

  /* ── 7. Sincronizar com mudança de preferência do sistema em tempo real ─ */
  if (window.matchMedia) {
    window.matchMedia('(prefers-color-scheme: light)').addEventListener('change', function (e) {
      // Só reage se o usuário NÃO tiver salvo preferência manual
      if (!localStorage.getItem(STORAGE_KEY)) {
        applyTheme(e.matches ? LIGHT : DARK);
      }
    });
  }

})();
