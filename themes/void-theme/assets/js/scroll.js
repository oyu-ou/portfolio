/**
 * VOID THEME — scroll.js
 * Scroll-based animations:
 *  1. IntersectionObserver for [data-reveal] fade/slide-in
 *  2. Lightweight parallax for [data-parallax] elements
 *  3. Portfolio card curtain reveals
 *  4. Active nav link tracking
 */

(function () {
  'use strict';

  var raf;
  var parallaxEls = [];
  var sections    = [];
  var navLinks    = [];
  var lastScrollY = window.scrollY;
  var ticking     = false;

  // ── 1. Scroll Reveal (IntersectionObserver) ─────────────

  function initReveal() {
    var els = document.querySelectorAll('[data-reveal]');
    if (!els.length) return;

    var observer = new IntersectionObserver(
      function (entries) {
        entries.forEach(function (entry) {
          if (entry.isIntersecting) {
            entry.target.classList.add('is-revealed');
            // Un-observe after reveal (no re-trigger)
            observer.unobserve(entry.target);
          }
        });
      },
      {
        threshold:   0.12,
        rootMargin:  '0px 0px -60px 0px',
      }
    );

    els.forEach(function (el) { observer.observe(el); });
  }

  // Re-run after view toggle
  document.addEventListener('void:view-changed', function () {
    setTimeout(initReveal, 100);
  });

  // ── 2. Portfolio card curtain reveals ───────────────────

  function initCardReveals() {
    var cards = document.querySelectorAll('.project-card');
    if (!cards.length) return;

    var observer = new IntersectionObserver(
      function (entries) {
        entries.forEach(function (entry) {
          if (entry.isIntersecting) {
            entry.target.classList.add('is-visible');
            observer.unobserve(entry.target);
          }
        });
      },
      { threshold: 0.05, rootMargin: '0px 0px -40px 0px' }
    );

    cards.forEach(function (card) { observer.observe(card); });
  }

  // ── 3. Parallax ─────────────────────────────────────────

  function initParallax() {
    // Respect reduced motion
    if (window.matchMedia('(prefers-reduced-motion: reduce)').matches) return;

    var els = document.querySelectorAll('[data-parallax]');
    parallaxEls = [];

    els.forEach(function (el) {
      var speed  = parseFloat(el.getAttribute('data-parallax')) || 0.1;
      var bounds = el.getBoundingClientRect();

      parallaxEls.push({
        el:    el,
        speed: speed,
      });
    });
  }

  function updateParallax() {
    var scrollY = window.scrollY;

    parallaxEls.forEach(function (item) {
      var rect   = item.el.parentElement.getBoundingClientRect();
      var center = rect.top + rect.height / 2 - window.innerHeight / 2;
      var offset = center * item.speed;

      item.el.style.transform = 'translateY(' + offset + 'px)';
    });
  }

  // ── 4. Active nav link tracking ──────────────────────────

  function initNavTracking() {
    navLinks  = Array.from(document.querySelectorAll('.nav__link[href^="#"]'));
    sections  = navLinks.map(function (link) {
      var id = link.getAttribute('href').slice(1);
      return document.getElementById(id);
    }).filter(Boolean);
  }

  function updateActiveNav() {
    var scrollMid = window.scrollY + window.innerHeight * 0.4;

    var current = null;
    sections.forEach(function (section, i) {
      if (section && section.offsetTop <= scrollMid) {
        current = i;
      }
    });

    navLinks.forEach(function (link, i) {
      link.classList.toggle('is-active', i === current);
    });
  }

  // ── 5. Loader trigger (first load) ───────────────────────

  function initLoader() {
    var loader      = document.getElementById('void-loader');
    var bar         = document.querySelector('.loader__progress-bar');
    var counterEl   = document.querySelector('.loader__counter-inner');

    if (!loader) return;

    // Prevent scroll during load
    document.body.style.overflow = 'hidden';

    var progress  = 0;
    var countVal  = 0;
    var duration  = 1600; // ms
    var startTime = performance.now();

    function step(now) {
      var elapsed  = now - startTime;
      var fraction = Math.min(elapsed / duration, 1);

      // Ease out
      var eased = 1 - Math.pow(1 - fraction, 3);

      progress = Math.floor(eased * 100);
      countVal = progress;

      if (bar)       bar.style.width = progress + '%';
      if (counterEl) counterEl.textContent = (progress < 10 ? '0' : '') + progress;

      if (fraction < 1) {
        requestAnimationFrame(step);
      } else {
        // Done — hide loader
        setTimeout(function () {
          loader.classList.add('is-hidden');
          document.body.style.overflow = '';

          // Reveal hero
          var heroContent  = document.querySelector('.hero__content');
          var heroHint     = document.querySelector('.hero__scroll-hint');

          if (heroContent) heroContent.classList.add('is-revealed');
          if (heroHint)    heroHint.classList.add('is-revealed');

        }, 300);
      }
    }

    requestAnimationFrame(step);
  }

  // ── Scroll handler ───────────────────────────────────────

  function onScroll() {
    lastScrollY = window.scrollY;

    if (!ticking) {
      requestAnimationFrame(function () {
        updateParallax();
        updateActiveNav();
        ticking = false;
      });
      ticking = true;
    }
  }

  // ── Smooth scroll ────────────────────────────────────────

  function initSmoothScroll() {
    document.querySelectorAll('a[href^="#"]').forEach(function (anchor) {
      anchor.addEventListener('click', function (e) {
        var id = anchor.getAttribute('href');
        if (id === '#') return;

        var target = document.querySelector(id);
        if (!target) return;

        e.preventDefault();

        var navH   = parseInt(
          getComputedStyle(document.documentElement).getPropertyValue('--nav-height')
        ) || 56;

        var top = target.getBoundingClientRect().top + window.scrollY - navH;

        window.scrollTo({ top: top, behavior: 'smooth' });

        // Close mobile drawer if open
        var drawer = document.querySelector('.nav__mobile-drawer');
        var burger = document.querySelector('.nav__hamburger');
        if (drawer && drawer.classList.contains('is-open')) {
          drawer.classList.remove('is-open');
          if (burger) burger.classList.remove('is-open');
        }
      });
    });
  }

  // ── Video load ───────────────────────────────────────────

  function initHeroVideo() {
    var video  = document.getElementById('hero-video');
    var poster = document.querySelector('.hero__video-poster');

    if (!video) return;

    function onCanPlay() {
      video.classList.add('is-loaded');
      if (poster) poster.classList.add('is-hidden');
    }

    video.addEventListener('canplaythrough', onCanPlay);
    video.addEventListener('loadeddata',     onCanPlay);

    if (video.readyState >= 3) onCanPlay();
  }

  // ── Mobile nav ───────────────────────────────────────────

  function initMobileNav() {
    var burger = document.querySelector('.nav__hamburger');
    var drawer = document.querySelector('.nav__mobile-drawer');

    if (!burger || !drawer) return;

    burger.addEventListener('click', function () {
      burger.classList.toggle('is-open');
      drawer.classList.toggle('is-open');
    });
  }

  // ── Init ────────────────────────────────────────────────

  function init() {
    initLoader();
    initReveal();
    initCardReveals();
    initParallax();
    initNavTracking();
    initSmoothScroll();
    initHeroVideo();
    initMobileNav();

    window.addEventListener('scroll', onScroll, { passive: true });
    window.addEventListener('resize', initParallax);
  }

  window.VoidScroll = { init: init };

})();
