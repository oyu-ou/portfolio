<!DOCTYPE html>
<html <?php language_attributes(); ?> data-theme="dark" data-mode="clean">
<head>
<meta charset="<?php bloginfo('charset'); ?>">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="theme-color" content="#000000">
<?php wp_head(); ?>
<!-- Apply saved theme + mode immediately to avoid FOUC -->
<script>
(function(){
  var t=localStorage.getItem('axiom-theme');
  var m=localStorage.getItem('axiom-mode');
  if(t) document.documentElement.setAttribute('data-theme',t);
  if(m) document.documentElement.setAttribute('data-mode',m);
})();
</script>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>

<!-- ═══════════════════════════════════════════════════
     LOADER
     ═══════════════════════════════════════════════════ -->

<div id="axiom-loader" role="status" aria-label="Loading"
     data-text="⠠⠕⠽⠥⠝⠙⠁⠗⠊ ⠠⠛⠁⠝⠞⠥⠇⠛⠁">
  <div class="loader__text" aria-hidden="true"></div>
  <span class="loader__site-name"><?php echo esc_html( ax('axiom_name', get_bloginfo('name')) ); ?></span>
  <div class="loader__bar-wrap"><div class="loader__bar"></div></div>
</div>

<!-- ═══════════════════════════════════════════════════
     TOP BAR — logo left · controls right
     ═══════════════════════════════════════════════════ -->
<header id="ax-topbar">

  <!-- REPLACE: logo — swap SVG below or set custom logo in Customizer → Site Identity -->
  <a href="<?php echo esc_url( home_url('/') ); ?>" class="topbar__logo" aria-label="<?php echo esc_attr( ax('axiom_name', get_bloginfo('name')) ); ?>">
    <?php if ( has_custom_logo() ) :
      the_custom_logo();
    else : ?>
    <button class="topbar__logo-word ax-toggle">
      Oyu
    </button>

    <svg class="topbar__logo-mark" width="235" height="126" viewBox="0 0 235 126" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
      <path d="M171.991 123C140.396 123 122 104.943 122 76.5021V13H155.332V23.2316C155.332 36.3233 159.991 46.33 171.997 46.33C183.845 46.33 188.668 36.1728 188.668 23.2316V13H222V76.5021C222 104.943 203.585 123 171.991 123Z" fill="black"/>
      <path fill-rule="evenodd" clip-rule="evenodd" d="M0 67.9221C0 103.423 24.2092 126 60.0747 126C95.7908 126 120 103.423 120 67.9221C120 32.4215 95.7908 10 60.0747 10C24.2092 10 0 32.4215 0 67.9221ZM48.1099 86.1522C45.1465 83.3164 43.5045 79.2828 43.5 74.5001H46.5C46.5044 78.5747 47.8874 81.787 50.184 83.9847C52.4916 86.193 55.8514 87.5 60.0187 87.5C64.1657 87.5 67.5161 86.194 69.8197 83.9857C72.1125 81.7877 73.4956 78.5748 73.5 74.5001H76.5C76.4955 79.2827 74.8536 83.3157 71.8958 86.1513C68.9409 88.9839 64.8007 90.5 60.0187 90.5C55.2196 90.5 51.07 88.9849 48.1099 86.1522Z" fill="black"/>
      <path fill-rule="evenodd" clip-rule="evenodd" d="M0 67.9221C0 103.423 24.2092 126 60.0747 126C95.7908 126 120 103.423 120 67.9221C120 32.4215 95.7908 10 60.0747 10C24.2092 10 0 32.4215 0 67.9221Z" fill="black"/>
      <path d="M59.9922 78C57.9968 77.9999 56.2352 77.3704 54.9658 76.1582C53.6914 74.941 53.0001 73.2188 53 71.208C53 71.2055 53 71.2027 53 71.2002H54C54 71.2027 54 71.2055 54 71.208C54.0001 74.7578 56.4208 76.9999 59.9922 77C63.5787 77 65.9999 74.7579 66 71.208C66 71.2055 66 71.2027 66 71.2002H67C67 71.2027 67 71.2055 67 71.208C66.9999 73.219 66.3083 74.9418 65.0312 76.1592C63.7595 77.3714 61.9941 78 59.9922 78Z" fill="white"/>
      <path fill-rule="evenodd" clip-rule="evenodd" d="M221 6.79221C221 8.80305 221.691 10.5289 222.967 11.7499C224.238 12.9662 226.004 13.6 228.007 13.6C230.005 13.6 231.766 12.9656 233.035 11.7493C234.309 10.5284 235 8.80299 235 6.79221C235 4.78118 234.308 3.05885 233.034 1.84162C231.764 0.629378 230.003 0 228.007 0C226.005 0 224.241 0.628739 222.969 1.84101C221.692 3.0584 221 4.78112 221 6.79221ZM228.007 12.6C224.421 12.6 222 10.3423 222 6.79221C222 3.24215 224.421 1 228.007 1C231.579 1 234 3.24215 234 6.79221C234 10.3423 231.579 12.6 228.007 12.6Z" fill="black"/>
      <path d="M231.623 7.8581V6.7661H228.227V7.7501H230.639V7.8581C230.639 9.19021 229.559 10.2701 228.227 10.2701C227.611 10.2701 227.091 10.1141 226.667 9.8021C226.243 9.4901 225.931 9.0781 225.731 8.5661C225.531 8.0461 225.431 7.4621 225.431 6.8141C225.431 6.1661 225.531 5.5821 225.731 5.0621C225.931 4.5341 226.239 4.1141 226.655 3.8021C227.079 3.4901 227.603 3.3341 228.227 3.3341C228.819 3.3341 229.311 3.4941 229.703 3.8141C230.103 4.1341 230.379 4.5661 230.531 5.1101L231.587 5.0381C231.395 4.2301 231.003 3.5821 230.411 3.0941C229.827 2.5981 229.099 2.3501 228.227 2.3501C227.427 2.3501 226.735 2.5461 226.151 2.9381C225.575 3.3221 225.135 3.8541 224.831 4.5341C224.527 5.2141 224.375 5.9741 224.375 6.8141C224.375 7.6541 224.527 8.4101 224.831 9.0821C225.143 9.7541 225.587 10.2861 226.163 10.6781C226.739 11.0621 227.427 11.2541 228.227 11.2541C230.103 11.2541 231.623 9.73366 231.623 7.8581Z" fill="black"/>
    </svg>

    <?php endif; ?>
  </a>

  <div class="topbar__right">
    <!-- Dark / Light toggle -->
    <button id="toggle-theme" class="ax-toggle" aria-label="Toggle colour scheme" aria-pressed="false">
      Light
    </button>

    <!-- CLEAN / WILD mode toggle -->
    <button id="toggle-mode" class="ax-toggle" aria-label="Toggle portfolio mode" aria-pressed="false">
      Wild ↗
    </button>
  </div>

</header>

<!-- ═══════════════════════════════════════════════════
     WILD MODE — fullscreen slider (always in DOM,
     hidden via CSS when data-mode="clean")
     ═══════════════════════════════════════════════════ -->
<div id="ax-wild" aria-label="Portfolio slider" role="region">

  <!-- Counter -->
  <div class="wild__counter">
    <span class="wild__counter-current">01</span>
    <span style="color:var(--fg-low);">/</span>
    <span class="wild__counter-total">00</span>
  </div>

  <!-- Progress dots -->
  <div class="wild__dots" role="tablist" aria-label="Slide navigation"></div>

  <!-- Slide track — populated by slider.js from axiomData.projects -->
  <div class="wild__track"></div>

  <!-- Nav arrows -->
  <div class="wild__arrows">
    <button class="wild__arrow wild__arrow--prev" aria-label="Previous project">←</button>
    <button class="wild__arrow wild__arrow--next" aria-label="Next project">→</button>
  </div>

</div>

<!-- ═══════════════════════════════════════════════════
     HOVER PREVIEW (clean list mode)
     ═══════════════════════════════════════════════════ -->
<!-- Floating hover preview (clean list mode) -->
<div id="preview-img" aria-hidden="true">
  <img src="" alt="" loading="lazy">
  <div class="preview__placeholder"></div>
</div>

<!-- Back to top -->
<button class="ax-back-top" aria-label="Back to top">↑</button>

<!-- PAGE WRAPPER -->
<div id="page-wrap">
