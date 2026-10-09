/**
 * VOID THEME — cursor.js
 * Minimal two-element custom cursor.
 * Dot follows exactly; ring lags behind with lerp.
 * Morphs on hover, text, and drag states.
 */

(function () {
  'use strict';

  // Skip on touch devices
  if (window.matchMedia('(hover: none)').matches) return;

  var el, dot, ring;
  var mouseX = 0, mouseY = 0;
  var ringX  = 0, ringY  = 0;
  var lerpFactor = 0.11;
  var raf;

  // ── Build DOM ──────────────────────────────────────────

  function build() {
    el   = document.createElement('div');
    dot  = document.createElement('div');
    ring = document.createElement('div');

    el.className   = 'void-cursor';
    dot.className  = 'void-cursor__dot';
    ring.className = 'void-cursor__ring';

    el.appendChild(dot);
    el.appendChild(ring);
    document.body.appendChild(el);
  }

  // ── Track mouse ─────────────────────────────────────────

  function onMouseMove(e) {
    mouseX = e.clientX;
    mouseY = e.clientY;

    // Dot follows exactly (via CSS transform, no delay)
    dot.style.left = mouseX + 'px';
    dot.style.top  = mouseY + 'px';
  }

  // ── Ring animation loop (lerp) ──────────────────────────

  function loop() {
    raf = requestAnimationFrame(loop);

    ringX += (mouseX - ringX) * lerpFactor;
    ringY += (mouseY - ringY) * lerpFactor;

    ring.style.left = ringX + 'px';
    ring.style.top  = ringY + 'px';
  }

  // ── Click feedback ──────────────────────────────────────

  function onMouseDown() { el.classList.add('is-clicking'); }
  function onMouseUp()   { el.classList.remove('is-clicking'); }

  // ── State detection ─────────────────────────────────────

  var STATES = {
    link: [
      'a',
      'button',
      '[data-cursor="link"]',
      '.nav__link',
      '.void-toggle',
      '.project-card',
      '.nav__hamburger',
      '.about__cta',
      '.contact__link',
      '.portfolio__view-all',
    ].join(','),

    text: [
      'input[type="text"]',
      'input[type="email"]',
      'textarea',
      '[contenteditable]',
    ].join(','),

    drag: '[data-cursor="drag"]',
  };

  function setStates() {
    var els = document.querySelectorAll(
      STATES.link + ',' + STATES.text + ',' + STATES.drag
    );

    els.forEach(function (target) {
      target.addEventListener('mouseenter', function () {
        if (target.matches(STATES.link)) {
          document.body.classList.add('cursor--link');
        } else if (target.matches(STATES.text)) {
          document.body.classList.add('cursor--text');
        } else if (target.matches(STATES.drag)) {
          document.body.classList.add('cursor--drag');
        }
      });

      target.addEventListener('mouseleave', function () {
        document.body.classList.remove('cursor--link', 'cursor--text', 'cursor--drag');
      });
    });
  }

  // ── Init ────────────────────────────────────────────────

  function init() {
    build();
    document.addEventListener('mousemove', onMouseMove);
    document.addEventListener('mousedown', onMouseDown);
    document.addEventListener('mouseup',   onMouseUp);
    loop();
    setStates();

    // Re-attach state listeners when DOM changes (e.g., AJAX load)
    document.addEventListener('void:dom-updated', setStates);
  }

  window.VoidCursor = { init: init };

})();
