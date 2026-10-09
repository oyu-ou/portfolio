<?php
/**
 * VOID Theme — functions.php
 * Theme setup, enqueues, CPT, customizer, helpers
 */

if ( ! defined( 'ABSPATH' ) ) exit;

/* ============================================================
   THEME SETUP
   ============================================================ */

function void_setup() {
    load_theme_textdomain( 'void', get_template_directory() . '/languages' );

    add_theme_support( 'title-tag' );
    add_theme_support( 'post-thumbnails' );
    add_theme_support( 'html5', array(
        'search-form', 'comment-form', 'comment-list', 'gallery', 'caption'
    ) );
    add_theme_support( 'custom-logo', array(
        'flex-width'  => true,
        'flex-height' => true,
    ) );
    add_theme_support( 'responsive-embeds' );

    // Image sizes
    add_image_size( 'project-thumb',  800, 600, true );
    add_image_size( 'project-large', 1600, 900, true );
    add_image_size( 'project-square', 800, 800, true );

    register_nav_menus( array(
        'primary' => __( 'Primary Navigation', 'void' ),
        'footer'  => __( 'Footer Navigation',  'void' ),
    ) );
}
add_action( 'after_setup_theme', 'void_setup' );

/* ============================================================
   ENQUEUE SCRIPTS & STYLES
   ============================================================ */

function void_enqueue() {
    $v   = '1.0.0';
    $uri = get_template_directory_uri();

    // ── CSS — modular, in order ──────────────────────────

    // REPLACE: fonts — swap this for custom @font-face or different Google Fonts
    wp_enqueue_style(
        'void-fonts',
        'https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500&display=swap',
        array(), null
    );

    $css_modules = array(
        'variables', 'base', 'loader', 'cursor',
        'nav', 'hero', 'portfolio', 'animations', 'responsive',
    );

    $prev = 'void-fonts';
    foreach ( $css_modules as $module ) {
        $handle = 'void-' . $module;
        wp_enqueue_style( $handle, $uri . '/assets/css/' . $module . '.css', array( $prev ), $v );
        $prev = $handle;
    }

    // Main style.css (WP requirement — usually empty of actual styles)
    wp_enqueue_style( 'void-main', get_stylesheet_uri(), array( 'void-responsive' ), $v );

    // ── JS — module order matters ────────────────────────

    $js_modules = array( 'grain', 'cursor', 'theme', 'view', 'scroll', 'main' );

    foreach ( $js_modules as $module ) {
        wp_enqueue_script(
            'void-' . $module,
            $uri . '/assets/js/' . $module . '.js',
            array(),
            $v,
            true // footer
        );
    }
}
add_action( 'wp_enqueue_scripts', 'void_enqueue' );

/* ============================================================
   CUSTOM POST TYPE: Portfolio
   ============================================================ */

function void_register_cpt() {
    $labels = array(
        'name'               => 'Portfolio',
        'singular_name'      => 'Project',
        'add_new'            => 'Add New Project',
        'add_new_item'       => 'Add New Project',
        'edit_item'          => 'Edit Project',
        'new_item'           => 'New Project',
        'view_item'          => 'View Project',
        'search_items'       => 'Search Projects',
        'not_found'          => 'No projects found',
        'not_found_in_trash' => 'No projects found in trash',
        'menu_name'          => 'Portfolio',
    );

    register_post_type( 'portfolio', array(
        'labels'             => $labels,
        'public'             => true,
        'has_archive'        => true,
        'supports'           => array( 'title', 'editor', 'thumbnail', 'excerpt', 'custom-fields', 'page-attributes' ),
        'menu_icon'          => 'dashicons-portfolio',
        'rewrite'            => array( 'slug' => 'work' ),
        'show_in_rest'       => true,
        'menu_position'      => 5,
    ) );

    // Taxonomy: Project Type
    register_taxonomy( 'project_type', 'portfolio', array(
        'label'             => 'Project Type',
        'rewrite'           => array( 'slug' => 'type' ),
        'hierarchical'      => false,
        'show_in_rest'      => true,
        'show_admin_column' => true,
    ) );
}
add_action( 'init', 'void_register_cpt' );

/* ============================================================
   CUSTOMIZER
   ============================================================ */

function void_customizer( $wp_customize ) {

    /* ── Identity ─────────────────────────────────────── */
    $wp_customize->add_section( 'void_identity', array(
        'title'    => 'Site Identity',
        'priority' => 20,
    ) );

    void_add_text( $wp_customize, 'void_site_name',    'void_identity', 'Site / Studio Name',  get_bloginfo('name') );
    void_add_text( $wp_customize, 'void_site_tagline', 'void_identity', 'Tagline',              '' );

    /* ── Hero ─────────────────────────────────────────── */
    $wp_customize->add_section( 'void_hero', array(
        'title'    => 'Hero Section',
        'priority' => 30,
    ) );

    void_add_text( $wp_customize, 'void_hero_label', 'void_hero', 'Hero Sub-label (small text beneath logo)', 'Portfolio' );

    // Video URL
    $wp_customize->add_setting( 'void_hero_video', array(
        'default'           => '',
        'sanitize_callback' => 'esc_url_raw',
    ) );
    $wp_customize->add_control( 'void_hero_video', array(
        'label'       => 'Hero Video URL (mp4)',
        'description' => 'Upload an mp4 to Media Library, paste URL here. REPLACE: hero video',
        'section'     => 'void_hero',
        'type'        => 'url',
    ) );

    /* ── About ────────────────────────────────────────── */
    $wp_customize->add_section( 'void_about', array(
        'title'    => 'About Section',
        'priority' => 40,
    ) );

    void_add_text( $wp_customize, 'void_about_heading', 'void_about', 'Heading',    'A studio that crafts' );
    void_add_text( $wp_customize, 'void_about_heading2','void_about', 'Heading Line 2', 'with intention.' );
    void_add_textarea( $wp_customize, 'void_about_text', 'void_about', 'Body Text',
        'We design and build digital experiences for brands that believe in the power of restraint. Based globally, working globally.' );

    $disciplines = array( 1 => 'Web Design', 2 => 'Video', 3 => 'Photography', 4 => 'Identity', 5 => 'Direction' );
    foreach ( $disciplines as $i => $label ) {
        void_add_text( $wp_customize, 'void_discipline_' . $i, 'void_about', 'Discipline ' . $i, $label );
    }

    /* ── Stats ────────────────────────────────────────── */
    $wp_customize->add_section( 'void_stats', array(
        'title'    => 'Stats Strip',
        'priority' => 45,
    ) );

    $stats = array(
        array( 'id' => 'void_stat1', 'label_def' => 'Projects', 'value_def' => '120+' ),
        array( 'id' => 'void_stat2', 'label_def' => 'Years',    'value_def' => '8' ),
        array( 'id' => 'void_stat3', 'label_def' => 'Awards',   'value_def' => '14' ),
        array( 'id' => 'void_stat4', 'label_def' => 'Clients',  'value_def' => '60+' ),
    );

    foreach ( $stats as $stat ) {
        void_add_text( $wp_customize, $stat['id'] . '_value', 'void_stats', $stat['label_def'] . ' Value', $stat['value_def'] );
        void_add_text( $wp_customize, $stat['id'] . '_label', 'void_stats', $stat['label_def'] . ' Label', $stat['label_def'] );
    }

    /* ── Contact ──────────────────────────────────────── */
    $wp_customize->add_section( 'void_contact', array(
        'title'    => 'Contact Section',
        'priority' => 60,
    ) );

    void_add_text( $wp_customize, 'void_contact_heading',  'void_contact', 'Heading',  'Let\'s work.' );
    void_add_textarea( $wp_customize, 'void_contact_text', 'void_contact', 'Subtext',  'Available for select projects. Get in touch to start a conversation.' );
    void_add_text( $wp_customize, 'void_contact_email',    'void_contact', 'Email',    'hello@yoursite.com' );
    void_add_text( $wp_customize, 'void_contact_twitter',  'void_contact', 'Twitter / X URL',  '' );
    void_add_text( $wp_customize, 'void_contact_instagram','void_contact', 'Instagram URL',    '' );
    void_add_text( $wp_customize, 'void_contact_linkedin', 'void_contact', 'LinkedIn URL',     '' );
    void_add_text( $wp_customize, 'void_contact_behance',  'void_contact', 'Behance URL',      '' );

    /* ── Footer ───────────────────────────────────────── */
    $wp_customize->add_section( 'void_footer', array(
        'title'    => 'Footer',
        'priority' => 80,
    ) );

    void_add_text( $wp_customize, 'void_footer_location', 'void_footer', 'Location',      'Berlin / Remote' );
    void_add_text( $wp_customize, 'void_footer_copy',     'void_footer', 'Copyright text', '' );
}
add_action( 'customize_register', 'void_customizer' );

/* ── Customizer helpers ─────────────────────────────── */

function void_add_text( $c, $id, $section, $label, $default = '' ) {
    $c->add_setting( $id, array( 'default' => $default, 'sanitize_callback' => 'sanitize_text_field' ) );
    $c->add_control( $id, array( 'label' => $label, 'section' => $section, 'type' => 'text' ) );
}

function void_add_textarea( $c, $id, $section, $label, $default = '' ) {
    $c->add_setting( $id, array( 'default' => $default, 'sanitize_callback' => 'sanitize_textarea_field' ) );
    $c->add_control( $id, array( 'label' => $label, 'section' => $section, 'type' => 'textarea' ) );
}

/* ── Helper: get customizer value ─────────────────── */
function void_opt( $key, $fallback = '' ) {
    return get_theme_mod( $key, $fallback ) ?: $fallback;
}

/* ============================================================
   PORTFOLIO SHORTCODE
   Usage: [void_portfolio count="6" type="video"]
   ============================================================ */

function void_portfolio_shortcode( $atts ) {
    $atts = shortcode_atts( array(
        'count' => -1,
        'type'  => '',
    ), $atts );

    $args = array(
        'post_type'      => 'portfolio',
        'posts_per_page' => intval( $atts['count'] ),
        'orderby'        => 'menu_order',
        'order'          => 'ASC',
    );

    if ( ! empty( $atts['type'] ) ) {
        $args['tax_query'] = array( array(
            'taxonomy' => 'project_type',
            'field'    => 'slug',
            'terms'    => $atts['type'],
        ) );
    }

    $q = new WP_Query( $args );
    if ( ! $q->have_posts() ) return '';

    ob_start();
    $i = 0;
    while ( $q->have_posts() ) {
        $q->the_post();
        $terms = get_the_terms( get_the_ID(), 'project_type' );
        $cat   = $terms ? $terms[0]->slug  : '';
        $label = $terms ? $terms[0]->name  : '';
        $num   = str_pad( $i + 1, 2, '0', STR_PAD_LEFT );
        ?>
        <a class="project-card"
           href="<?php the_permalink(); ?>"
           data-reveal="up"
           data-delay="<?php echo min( $i + 1, 8 ); ?>"
           data-category="<?php echo esc_attr( $cat ); ?>">

            <div class="project-card__media">
                <?php if ( has_post_thumbnail() ) : ?>
                    <?php the_post_thumbnail( 'project-large', array( 'class' => 'project-card__img' ) ); ?>
                <?php else : ?>
                    <div class="project-card__placeholder placeholder--<?php echo $i % 6; ?>">
                        <span><?php the_title(); ?></span>
                    </div>
                <?php endif; ?>

                <div class="project-card__curtain"></div>
                <div class="project-card__gradient"></div>

                <div class="project-card__info-overlay">
                    <span class="project-card__num"><?php echo esc_html( $num ); ?></span>
                    <h3 class="project-card__title"><?php the_title(); ?></h3>
                    <?php if ( $label ) : ?>
                    <div class="project-card__tags">
                        <span class="project-card__tag"><?php echo esc_html( $label ); ?></span>
                    </div>
                    <?php endif; ?>
                </div>

                <div class="project-card__arrow">↗</div>
            </div>

            <!-- List-view info (shown when view--list is active) -->
            <div class="project-card__info">
                <span class="project-card__num"><?php echo esc_html( $num ); ?></span>
                <span class="project-card__title"><?php the_title(); ?></span>
                <?php if ( $label ) : ?>
                <div class="project-card__tags">
                    <span class="project-card__tag"><?php echo esc_html( $label ); ?></span>
                </div>
                <?php endif; ?>
                <span class="project-card__arrow">↗</span>
            </div>
        </a>
        <?php
        $i++;
    }
    wp_reset_postdata();

    return ob_get_clean();
}
add_shortcode( 'void_portfolio', 'void_portfolio_shortcode' );

/* ============================================================
   BODY CLASS
   ============================================================ */

function void_body_classes( $classes ) {
    $classes[] = 'void-theme';
    return $classes;
}
add_filter( 'body_class', 'void_body_classes' );

/* ============================================================
   SINGLE PORTFOLIO — register meta fields for Gutenberg
   ============================================================ */

function void_register_meta() {
    $fields = array(
        'void_project_url'     => 'Live project URL',
        'void_project_year'    => 'Year',
        'void_project_client'  => 'Client name',
        'void_project_role'    => 'Your role (comma-separated)',
        'void_project_tools'   => 'Tools used (comma-separated)',
    );

    foreach ( $fields as $key => $desc ) {
        register_post_meta( 'portfolio', $key, array(
            'show_in_rest'  => true,
            'single'        => true,
            'type'          => 'string',
            'auth_callback' => function () { return current_user_can( 'edit_posts' ); },
            'description'   => $desc,
        ) );
    }
}
add_action( 'init', 'void_register_meta' );

/* ============================================================
   EXCERPT
   ============================================================ */

function void_excerpt_length() { return 20; }
add_filter( 'excerpt_length', 'void_excerpt_length', 999 );

function void_excerpt_more() { return ''; }
add_filter( 'excerpt_more', 'void_excerpt_more' );

/* ============================================================
   REMOVE EMOJI / BLOCK LIBRARY (leaner output)
   ============================================================ */

remove_action( 'wp_head',             'print_emoji_detection_script', 7 );
remove_action( 'wp_print_styles',     'print_emoji_styles' );
remove_action( 'admin_print_scripts', 'print_emoji_detection_script' );
remove_action( 'admin_print_styles',  'print_emoji_styles' );
add_filter( 'wp_lazy_loading_enabled', '__return_true' );
