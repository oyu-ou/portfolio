/**
 * VOID THEME — main.js
 * Entry point. Initialises all modules after DOM ready.
 *
 * Load order in functions.php:
 *   grain.js → cursor.js → theme.js → view.js → scroll.js → main.js
 */

(function () {
  'use strict';

  function init() {
    // Film grain
    if (window.VoidGrain) VoidGrain.init();

    // Custom cursor (skips on touch)
    if (window.VoidCursor) VoidCursor.init();

    // Dark / light toggle
    if (window.VoidTheme) VoidTheme.init();

    // Portfolio view toggle
    if (window.VoidView) VoidView.init();

    // Scroll animations, loader, nav
    if (window.VoidScroll) VoidScroll.init();
  }

  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', init);
  } else {
    init();
  }

})();
