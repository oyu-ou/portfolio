/**
 * VOID THEME — view.js
 * Grid / List portfolio view toggle with smooth transition.
 * Persists preference to localStorage.
 */

(function () {
  'use strict';

  var STORAGE_KEY = 'void-portfolio-view';
  var grid, btnGrid, btnList;
  var isAnimating = false;

  // ── Apply view ───────────────────────────────────────────

  function applyView(view, animate) {
    if (!grid) return;

    if (animate && !isAnimating) {
      isAnimating = true;

      // 1. Fade out
      grid.classList.add('is-transitioning');
      grid.style.opacity = '0';
      grid.style.transform = 'translateY(12px)';
      grid.style.transition = 'opacity 0.35s ease, transform 0.35s ease';

      setTimeout(function () {
        // 2. Swap class
        grid.classList.remove('view--grid', 'view--list');
        grid.classList.add('view--' + view);

        // Force reflow
        void grid.offsetHeight;

        // 3. Fade in
        grid.style.opacity = '1';
        grid.style.transform = 'translateY(0)';

        setTimeout(function () {
          grid.classList.remove('is-transitioning');
          grid.style.transition = '';
          isAnimating = false;
        }, 400);
      }, 380);

    } else {
      grid.classList.remove('view--grid', 'view--list');
      grid.classList.add('view--' + view);
    }

    // Update buttons
    updateButtons(view);
    localStorage.setItem(STORAGE_KEY, view);

    // Dispatch event for other modules (e.g., re-init scroll reveals)
    document.dispatchEvent(new CustomEvent('void:view-changed', { detail: { view: view } }));
  }

  function updateButtons(view) {
    if (!btnGrid || !btnList) return;

    if (view === 'grid') {
      btnGrid.classList.add('is-active');
      btnList.classList.remove('is-active');
    } else {
      btnList.classList.add('is-active');
      btnGrid.classList.remove('is-active');
    }
  }

  // ── Init ────────────────────────────────────────────────

  function init() {
    grid    = document.querySelector('.portfolio__grid');
    btnGrid = document.getElementById('toggle-grid');
    btnList = document.getElementById('toggle-list');

    if (!grid) return;

    // Restore saved preference
    var saved = localStorage.getItem(STORAGE_KEY) || 'grid';
    applyView(saved, false);

    if (btnGrid) {
      btnGrid.addEventListener('click', function () { applyView('grid', true); });
    }
    if (btnList) {
      btnList.addEventListener('click', function () { applyView('list', true); });
    }
  }

  window.VoidView = { init: init };

})();
