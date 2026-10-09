<?php
add_action('after_setup_theme', function() {
    // Add support for block styles
    add_theme_support('wp-block-styles');
    add_theme_support('align-wide');

    // Register menus
    register_nav_menus([
        'primary' => __('Primary Menu', 'portfolio-theme')
    ]);
});
?>