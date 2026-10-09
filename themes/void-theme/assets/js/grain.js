/**
 * VOID THEME — grain.js
 * Animated 35mm film grain overlay via canvas.
 * Creates a new noise frame every ~2 frames for authentic feel.
 */

(function () {
  'use strict';

  var canvas, ctx, w, h, imageData, pixels;
  var raf;
  var lastTime = 0;
  var fps = 24; // Grain updates at 24fps for film feel
  var frameInterval = 1000 / fps;

  function init() {
    canvas = document.createElement('canvas');
    canvas.id = 'grain-canvas';
    canvas.setAttribute('aria-hidden', 'true');
    document.body.appendChild(canvas);

    ctx = canvas.getContext('2d');
    resize();
    loop(0);

    window.addEventListener('resize', resize);
  }

  function resize() {
    // Use lower resolution for performance (then CSS scales it up)
    var dpr = Math.min(window.devicePixelRatio || 1, 2);
    w = canvas.width  = Math.ceil(window.innerWidth  * 0.5);
    h = canvas.height = Math.ceil(window.innerHeight * 0.5);

    canvas.style.width  = window.innerWidth  + 'px';
    canvas.style.height = window.innerHeight + 'px';

    imageData = ctx.createImageData(w, h);
    pixels    = imageData.data;
  }

  function renderGrain() {
    var len = pixels.length;
    for (var i = 0; i < len; i += 4) {
      var val = (Math.random() * 255) | 0;
      pixels[i]     = val; // R
      pixels[i + 1] = val; // G
      pixels[i + 2] = val; // B
      pixels[i + 3] = 60;  // A — controls grain density
    }
    ctx.putImageData(imageData, 0, 0);
  }

  function loop(timestamp) {
    raf = requestAnimationFrame(loop);

    var delta = timestamp - lastTime;
    if (delta < frameInterval) return;

    lastTime = timestamp - (delta % frameInterval);
    renderGrain();
  }

  // ── Public API ──────────────────────────────────────────

  // Expose on window so main.js can call grain.init()
  window.VoidGrain = { init: init };

})();
