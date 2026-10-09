/**
 * AXIOM — scroll.js
 * Loader · scroll reveals · list reveals · parallax ·
 * nav tracking · smooth scroll · hero video · mobile nav · preview image
 */
(function () {
  'use strict';

  //* ── 1. LOADER — braille dot-pop typing ──────────── */
function initLoader() {
  var loader = document.getElementById('axiom-loader');
  if (!loader) return;

  /* ════════ ADJUST HERE ════════ */
  var CFG = {
    text:         loader.getAttribute('data-text') || '⠠⠕⠽⠥⠝⠙⠁⠗⠊ ⠠⠛⠁⠝⠞⠥⠇⠛⠁',
    typeMs:       2000,   // time to "type" the whole text
    holdMs:       900,    // extra popping time after typing, fades out
    fps:          24,     // pop update speed (lower = slower popping)
    trail:        3,      // cells ahead of the caret that pop random dots
    extraDots:    0.45,   // chance of a random EXTRA dot on the cell being typed (0–1)
    trailDots:    0.5,    // density of random dots in the trail cells (0–1)
    invertChance: 0.12,   // chance a cell flashes black↔white
    flashChance:  0.04,   // chance the whole line inverts on a frame
    holdPower:    0.30,   // glitch strength at start of hold phase (0–1), fades to 0
    caret:        true,
    finishDelay:  280
  };
  /* ═════════════════════════════ */

  var textEl = loader.querySelector('.loader__text');
  var bar    = loader.querySelector('.loader__bar');
  var chars  = Array.from(CFG.text);
  var n      = chars.length;
  var cells  = [];
  var dots   = [];
  var target = [];

  document.body.style.overflow = 'hidden';

  /* braille char → 8-bit mask (non-braille / space = empty cell) */
  function maskOf(ch) {
    var c = ch.codePointAt(0);
    return (c >= 0x2800 && c <= 0x28FF) ? (c - 0x2800) : 0;
  }
  chars.forEach(function (c) { target.push(maskOf(c)); });

  /* only use the bottom row (dots 7/8) if the text really needs it */
  var needs4 = target.some(function (m) { return (m & 0xC0) !== 0; });
  var ALLOWED = needs4 ? 0xFF : 0x3F;

  /* build cells + 8 dots each */
  textEl.innerHTML = '';
  chars.forEach(function () {
    var cell = document.createElement('span');
    cell.className = 'cell';
    var list = [];
    for (var b = 0; b < (needs4 ? 8 : 6); b++) {
      var d = document.createElement('span');
      d.className = 'dot';
      cell.appendChild(d);
      list.push(d);
    }
    textEl.appendChild(cell);
    cells.push(cell);
    dots.push(list);
  });

  function setMask(i, mask, inv) {
    var list = dots[i];
    for (var b = 0; b < list.length; b++) {
      list[b].classList.toggle('on', !!(mask & (1 << b)));
    }
    cells[i].classList.toggle('inv', !!inv);
  }

  function randMask(density) {
    var m = 0;
    for (var b = 0; b < 8; b++) if (Math.random() < density) m |= (1 << b);
    return m & ALLOWED;
  }

  var reduce = window.matchMedia && window.matchMedia('(prefers-reduced-motion: reduce)').matches;
  var total    = CFG.typeMs + CFG.holdMs;
  var start    = performance.now();
  var last     = 0;
  var frameGap = 1000 / CFG.fps;

  function finish() {
    for (var i = 0; i < n; i++) setMask(i, target[i], false);
    cells.forEach(function (c) { c.classList.remove('caret'); });
    textEl.classList.remove('flash');
    if (bar) bar.style.width = '100%';

    setTimeout(function () {
      loader.classList.add('done');
      document.body.style.overflow = '';

      /* Hero home page */
      var name = document.querySelector('.hero__name');
      var disc = document.querySelector('.hero__disciplines');
      var scrl = document.querySelector('.hero__scroll');
      if (name) name.classList.add('in');
      if (disc) setTimeout(function () { disc.classList.add('in'); }, 150);
      if (scrl) scrl.classList.add('in');

      /* Single project hero */
      var spHero = document.getElementById('sp-hero-section');
      if (spHero) spHero.classList.add('in');
    }, CFG.finishDelay);
  }

  if (reduce) {
    for (var k = 0; k < n; k++) setMask(k, target[k], false);
    setTimeout(finish, 600);
    return;
  }

  function step(now) {
    var elapsed = now - start;

    if (elapsed >= total) { finish(); return; }
    if (now - last < frameGap) { requestAnimationFrame(step); return; }
    last = now;

    if (bar) bar.style.width = Math.min(100, (elapsed / total) * 100) + '%';

    cells.forEach(function (c) { c.classList.remove('caret'); });

    var typing = elapsed < CFG.typeMs;
    var pos    = typing ? (elapsed / CFG.typeMs) * n : n;
    var cur    = Math.floor(pos);          /* cell being typed right now */
    var inCell = pos - cur;                /* 0 → 1 progress inside that cell */

    if (typing) {
      for (var i = 0; i < n; i++) {
        if (i < cur) {
          /* done: correct dots stay (fresh cells may blink black↔white) */
          setMask(i, target[i], (cur - i) <= 2 && Math.random() < CFG.invertChance);

        } else if (i === cur) {
          /* being typed: correct dots STAY, random extra dots pop in/out,
             extras become rarer the closer the cell is to being finished */
          var extra = randMask(CFG.extraDots * (1 - inCell));
          setMask(i, target[i] | extra, Math.random() < CFG.invertChance);

        } else if (i < cur + CFG.trail) {
          /* ahead of caret: random dots popping */
          setMask(i, target[i] === 0 ? 0 : randMask(CFG.trailDots), Math.random() < CFG.invertChance * 0.5);

        } else {
          setMask(i, 0, false);
        }
      }
      if (CFG.caret) cells[Math.min(cur, n - 1)].classList.add('caret');

    } else {
      /* hold: text complete, dots keep popping, glitch fades to nothing */
      var k2    = (elapsed - CFG.typeMs) / CFG.holdMs;     /* 0 → 1 */
      var power = CFG.holdPower * (1 - k2);
      for (var j = 0; j < n; j++) {
        var m = target[j];
        if (m !== 0) {
          m |= randMask(power);                               /* extra dots pop in */
          for (var b = 0; b < 8; b++) {                       /* real dots blink out */
            if ((target[j] & (1 << b)) && Math.random() < power * 0.4) m &= ~(1 << b);
          }
        }
        setMask(j, m, Math.random() < power * 0.3);
      }
      if (CFG.caret) cells[n - 1].classList.add('caret');
    }

    var fl = typing ? 1 : (1 - (elapsed - CFG.typeMs) / CFG.holdMs);
    textEl.classList.toggle('flash', Math.random() < CFG.flashChance * fl);

    requestAnimationFrame(step);
  }
  requestAnimationFrame(step);
}


  /* ── 2. SCROLL REVEAL ────────────────────────────── */
  function initReveal() {
    var els = document.querySelectorAll('[data-reveal]');
    if (!els.length) return;

    var io = new IntersectionObserver(function (entries) {
      entries.forEach(function (e) {
        if (e.isIntersecting) { e.target.classList.add('is-on'); io.unobserve(e.target); }
      });
    }, { threshold: 0.08, rootMargin: '0px 0px -40px 0px' });

    els.forEach(function (el) { io.observe(el); });
  }

  /* ── 3. LIST ROW REVEALS ─────────────────────────── */
  function initListReveals() {
    var items = document.querySelectorAll('.clean__item:not(.is-on)');
    if (!items.length) return;

    var io = new IntersectionObserver(function (entries) {
      entries.forEach(function (e) {
        if (e.isIntersecting) { e.target.classList.add('is-on'); io.unobserve(e.target); }
      });
    }, { threshold: 0.04, rootMargin: '0px 0px -20px 0px' });

    items.forEach(function (el) { io.observe(el); });
  }

  /* ── 4. CLIP REVEAL (for headings) ──────────────── */
  function initClipReveal() {
    var wraps = document.querySelectorAll('.clip-wrap');
    if (!wraps.length) return;

    var io = new IntersectionObserver(function (entries) {
      entries.forEach(function (e) {
        if (e.isIntersecting) { e.target.classList.add('is-on'); io.unobserve(e.target); }
      });
    }, { threshold: 0.2 });

    wraps.forEach(function (el) { io.observe(el); });
  }

  /* ── 5. ACTIVE NAV TRACKING ──────────────────────── */
  function initNavTracking() {
    var links    = Array.from(document.querySelectorAll('.nav__link[href^="#"]'));
    var sections = links.map(function (l) {
      return document.querySelector(l.getAttribute('href'));
    }).filter(Boolean);
    if (!sections.length) return;

    function update() {
      var mid = window.scrollY + window.innerHeight * 0.35;
      var cur = null;
      sections.forEach(function (s, i) { if (s && s.offsetTop <= mid) cur = i; });
      links.forEach(function (l, i) { l.classList.toggle('active', i === cur); });
    }

    window.addEventListener('scroll', update, { passive: true });
    update();
  }

  /* ── 6. SMOOTH SCROLL ────────────────────────────── */
  function initSmoothScroll() {
    document.querySelectorAll('a[href^="#"]').forEach(function (a) {
      a.addEventListener('click', function (e) {
        var id = a.getAttribute('href');
        if (id === '#') return;
        var target = document.querySelector(id);
        if (!target) return;
        e.preventDefault();
        var navH = parseInt(getComputedStyle(document.documentElement)
          .getPropertyValue('--nav-h')) || 52;
        window.scrollTo({
          top: target.getBoundingClientRect().top + window.scrollY - navH,
          behavior: 'smooth'
        });
        /* Close drawer */
        var drawer = document.querySelector('.nav__drawer');
        var burger = document.querySelector('.nav__burger');
        if (drawer) drawer.classList.remove('open');
        if (burger) { burger.classList.remove('open'); burger.setAttribute('aria-expanded','false'); }
      });
    });
  }

  /* ── 7. HERO VIDEO ───────────────────────────────── */
  function initHeroVideo() {
    var v = document.getElementById('hero-video');
    if (!v) return;
    function onLoad() { v.classList.add('loaded'); }
    v.addEventListener('canplaythrough', onLoad);
    v.addEventListener('loadeddata', onLoad);
    if (v.readyState >= 3) onLoad();
  }

  /* ── 8. MOBILE NAV ───────────────────────────────── */
  function initMobileNav() {
    var burger = document.querySelector('.nav__burger');
    var drawer = document.querySelector('.nav__drawer');
    if (!burger || !drawer) return;
    burger.addEventListener('click', function () {
      var open = drawer.classList.toggle('open');
      burger.classList.toggle('open', open);
      burger.setAttribute('aria-expanded', open);
    });
  }

  /* ── 9. HOVER PREVIEW (clean list) ──────────────── */
  function initPreview() {
    var preview = document.getElementById('preview-img');
    if (!preview || window.matchMedia('(hover:none)').matches) return;

    var imgEl = preview.querySelector('img');
    var phEl  = preview.querySelector('.preview__placeholder');
    /* Start far off-screen so no flash on init */
    var px = -9999, py = -9999, tx = -9999, ty = -9999;

    /* Lerp loop — smooth follow */
    (function loop() {
      requestAnimationFrame(loop);
      px += (tx - px) * 0.1;
      py += (ty - py) * 0.1;
      preview.style.left = Math.round(px) + 'px';
      preview.style.top  = Math.round(py) + 'px';
    })();

    document.addEventListener('mousemove', function (e) {
      tx = e.clientX + 32;
      ty = e.clientY - (preview.offsetHeight / 2);
    });

    function showPreview(src) {
      if (src && imgEl) {
        imgEl.src = src;
        imgEl.style.display = 'block';
        if (phEl) phEl.style.display = 'none';
      } else {
        if (imgEl) imgEl.style.display = 'none';
        if (phEl) phEl.style.display = 'flex';
      }
      preview.classList.add('visible');
    }

    function hidePreview() {
      preview.classList.remove('visible');
    }

    /* Attach to list items — use event delegation so it works after filter */
    document.addEventListener('mouseover', function (e) {
      var item = e.target.closest('.clean__item');
      if (!item) return;
      showPreview(item.getAttribute('data-img') || '');
    });
    document.addEventListener('mouseout', function (e) {
      var item = e.target.closest('.clean__item');
      if (!item) return;
      var relTarget = e.relatedTarget;
      if (!relTarget || !item.contains(relTarget)) hidePreview();
    });
  }

  /* ── 10. BACK-TO-TOP ─────────────────────────────── */
  function initBackTop() {
    var btn = document.querySelector('.ax-back-top');
    if (!btn) return;
    window.addEventListener('scroll', function () {
      btn.classList.toggle('visible', window.scrollY > 400);
    }, { passive: true });
    btn.addEventListener('click', function () {
      window.scrollTo({ top: 0, behavior: 'smooth' });
    });
  }

  /* ── RE-INIT REVEALS after mode/filter change ────── */
  document.addEventListener('axiom:mode',   initReveal);
  document.addEventListener('axiom:filter', function () {
    setTimeout(initListReveals, 100);
  });

  /* ── INIT ─────────────────────────────────────────── */
  function init() {
    initLoader();
    initReveal();
    initListReveals();
    initClipReveal();
    initNavTracking();
    initSmoothScroll();
    initHeroVideo();
    initMobileNav();
    initPreview();
    initBackTop();
  }

  window.AxScroll = { init: init };
})();
