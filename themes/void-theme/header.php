<!DOCTYPE html>
<html <?php language_attributes(); ?> data-theme="dark">
<head>
<meta charset="<?php bloginfo('charset'); ?>">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="theme-color" content="#000000">
<?php wp_head(); ?>

<!-- Apply saved theme immediately to avoid FOUC -->
<script>
(function(){
  var t = localStorage.getItem('void-theme');
  if (t) document.documentElement.setAttribute('data-theme', t);
})();
</script>
</head>

<body <?php body_class(); ?>>
<?php wp_body_open(); ?>

<!-- ============================================================
     LOADER
     ============================================================ -->
<div id="void-loader" role="status" aria-label="Loading">
    <div class="loader__counter" aria-hidden="true">
        <span class="loader__counter-inner">00</span>
    </div>
    <div class="loader__bottom">
        <span class="loader__label t-label"><?php echo esc_html( void_opt( 'void_site_name', get_bloginfo('name') ) ); ?></span>
        <div class="loader__progress-wrap">
            <div class="loader__progress-bar"></div>
        </div>
    </div>
</div>

<!-- ============================================================
     TOP BAR — Logo left, Toggles right
     ============================================================ -->
<header id="void-topbar" role="banner">

    <!-- REPLACE: logo — swap the SVG below with your mark -->
    <a href="<?php echo esc_url( home_url('/') ); ?>" class="topbar__logo" aria-label="<?php bloginfo('name'); ?> home">
        <?php if ( has_custom_logo() ) : ?>
            <?php the_custom_logo(); ?>
        <?php else : ?>
        <svg class="topbar__logo-mark" viewBox="0 0 32 32" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
            <!-- REPLACE: logo SVG — placeholder circle mark -->
            <circle cx="16" cy="16" r="14" stroke="currentColor" stroke-width="1"/>
            <circle cx="16" cy="16" r="7"  fill="currentColor"/>
        </svg>
        <?php endif; ?>
    </a>

    <div class="topbar__controls">

        <!-- Theme toggle: Dark / Light -->
        <button
            id="toggle-theme"
            class="void-toggle"
            aria-label="Toggle colour scheme"
            aria-pressed="false"
        >
            <span class="toggle-label">Light</span>
        </button>

        <!-- View toggles: Grid / List -->
        <button
            id="toggle-grid"
            class="void-toggle is-active"
            aria-label="Grid view"
            aria-pressed="true"
        >
            <span class="void-toggle__icon toggle-icon--grid" aria-hidden="true">
                <span></span><span></span><span></span><span></span>
            </span>
            <span class="toggle-label sr-only">Grid</span>
        </button>

        <button
            id="toggle-list"
            class="void-toggle"
            aria-label="List view"
            aria-pressed="false"
        >
            <span class="void-toggle__icon toggle-icon--list" aria-hidden="true">
                <span></span><span></span><span></span>
            </span>
            <span class="toggle-label sr-only">List</span>
        </button>

    </div>
</header>

<!-- ============================================================
     MAIN PAGE WRAPPER
     ============================================================ -->
<div id="page-wrapper">
