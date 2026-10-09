/**
 * VOID THEME — theme.js
 * Dark / Light mode toggle.
 * Persists to localStorage. Respects prefers-color-scheme on first load.
 */

(function () {
  'use strict';

  var STORAGE_KEY = 'void-theme';
  var html = document.documentElement;
  var btn;

  // ── Read preference ─────────────────────────────────────

  function getPreference() {
    var saved = localStorage.getItem(STORAGE_KEY);
    if (saved) return saved;

    // First visit: respect system preference
    if (window.matchMedia('(prefers-color-scheme: light)').matches) {
      return 'light';
    }
    return 'dark';
  }

  // ── Apply theme ─────────────────────────────────────────

  function applyTheme(theme) {
    html.setAttribute('data-theme', theme);
    localStorage.setItem(STORAGE_KEY, theme);
    updateButton(theme);
  }

  // ── Update button label ──────────────────────────────────

  function updateButton(theme) {
    if (!btn) return;
    var label = btn.querySelector('.toggle-label');
    if (label) {
      label.textContent = theme === 'dark' ? 'Light' : 'Dark';
    }
    btn.setAttribute('aria-pressed', theme === 'light' ? 'true' : 'false');
  }

  // ── Toggle ──────────────────────────────────────────────

  function toggle() {
    var current = html.getAttribute('data-theme') || 'dark';
    applyTheme(current === 'dark' ? 'light' : 'dark');
  }

  // ── Init ────────────────────────────────────────────────

  function init() {
    btn = document.getElementById('toggle-theme');
    if (!btn) return;

    btn.addEventListener('click', toggle);

    // Apply saved / default theme on load
    applyTheme(getPreference());
  }

  // Apply theme immediately (before render) to avoid flash
  (function () {
    var pref = localStorage.getItem(STORAGE_KEY);
    if (!pref) return;
    html.setAttribute('data-theme', pref);
  })();

  window.VoidTheme = { init: init };

})();
