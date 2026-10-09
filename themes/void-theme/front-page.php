<?php
/**
 * VOID Theme — front-page.php
 * One-page portfolio homepage.
 */
get_header();
?>

<!-- ============================================================
     HERO — Fullscreen video + logo
     ============================================================ -->
<section id="void-hero">

    <!-- REPLACE: hero video — upload mp4 to Media Library, set URL in Customizer > Hero Section -->
    <div class="hero__video-wrap" aria-hidden="true">
        <?php $video_url = void_opt( 'void_hero_video', '' ); ?>
        <?php if ( $video_url ) : ?>
        <video
            id="hero-video"
            autoplay
            muted
            loop
            playsinline
            preload="auto"
        >
            <source src="<?php echo esc_url( $video_url ); ?>" type="video/mp4">
        </video>
        <?php endif; ?>
        <div class="hero__video-poster <?php echo $video_url ? '' : 'is-placeholder'; ?>"></div>
    </div>

    <!-- Overlay -->
    <div class="hero__overlay" aria-hidden="true"></div>

    <!-- Content -->
    <div class="hero__content" role="main">

        <!-- REPLACE: logo — swap this div with your animated SVG logo -->
        <div class="hero__logo-wrap" aria-label="<?php echo esc_attr( void_opt( 'void_site_name', get_bloginfo('name') ) ); ?>">
            <?php if ( has_custom_logo() ) : ?>
                <?php the_custom_logo(); ?>
            <?php else : ?>
            <div class="hero__logo-placeholder t-label">
                ← Replace with your SVG logo →
            </div>
            <?php endif; ?>
        </div>

        <p class="hero__label">
            <?php echo esc_html( void_opt( 'void_hero_label', 'Portfolio' ) ); ?>
        </p>

    </div>

    <!-- Scroll hint -->
    <div class="hero__scroll-hint" aria-hidden="true">
        <span class="hero__scroll-hint-label t-label">Scroll</span>
        <div class="hero__scroll-line"></div>
    </div>

    <!-- Index -->
    <div class="hero__index t-mono" aria-hidden="true">
        <?php echo esc_html( date('Y') ); ?>
    </div>

</section>

<!-- ============================================================
     MARQUEE STRIP
     ============================================================ -->
<?php
$disciplines = array(
    void_opt( 'void_discipline_1', 'Web Design' ),
    void_opt( 'void_discipline_2', 'Video' ),
    void_opt( 'void_discipline_3', 'Photography' ),
    void_opt( 'void_discipline_4', 'Identity' ),
    void_opt( 'void_discipline_5', 'Direction' ),
);
$disciplines = array_filter( $disciplines );
?>
<div class="void-marquee" aria-hidden="true">
    <div class="void-marquee__track">
        <?php
        // Repeat 4× for seamless loop
        for ( $r = 0; $r < 4; $r++ ) {
            foreach ( $disciplines as $d ) {
                echo '<span class="void-marquee__item">';
                echo esc_html( $d );
                echo '<span class="void-marquee__sep"></span>';
                echo '</span>';
            }
        }
        ?>
    </div>
</div>

<!-- ============================================================
     PORTFOLIO GRID
     ============================================================ -->
<section id="void-portfolio" class="section">

    <div class="portfolio__header">
        <div>
            <h2 class="portfolio__title" data-reveal="up">
                Selected<br>Work
            </h2>
        </div>
        <?php
        $project_count = wp_count_posts('portfolio')->publish;
        ?>
        <span class="portfolio__count t-label" data-reveal="up" data-delay="2">
            <?php echo str_pad( $project_count, 2, '0', STR_PAD_LEFT ); ?> Projects
        </span>
    </div>

    <div class="portfolio__grid view--grid" id="portfolio-grid">
        <?php
        $portfolio_query = new WP_Query( array(
            'post_type'      => 'portfolio',
            'posts_per_page' => -1,
            'orderby'        => 'menu_order',
            'order'          => 'ASC',
        ) );

        if ( $portfolio_query->have_posts() ) :
            $i = 0;
            while ( $portfolio_query->have_posts() ) :
                $portfolio_query->the_post();
                $terms = get_the_terms( get_the_ID(), 'project_type' );
                $cat   = $terms ? $terms[0]->slug : '';
                $label = $terms ? $terms[0]->name : '';
                $num   = str_pad( $i + 1, 2, '0', STR_PAD_LEFT );
                ?>
                <a class="project-card"
                   href="<?php the_permalink(); ?>"
                   data-reveal="up"
                   data-delay="<?php echo min( ($i % 6) + 1, 8 ); ?>"
                   data-category="<?php echo esc_attr( $cat ); ?>">

                    <div class="project-card__media">
                        <?php if ( has_post_thumbnail() ) : ?>
                            <?php the_post_thumbnail( 'project-large', array( 'class' => 'project-card__img', 'loading' => 'lazy', 'alt' => get_the_title() ) ); ?>
                        <?php else : ?>
                            <div class="project-card__placeholder placeholder--<?php echo $i % 6; ?>">
                                <span class="t-label"><?php the_title(); ?></span>
                            </div>
                        <?php endif; ?>

                        <div class="project-card__curtain" aria-hidden="true"></div>
                        <div class="project-card__gradient" aria-hidden="true"></div>

                        <div class="project-card__info-overlay">
                            <span class="project-card__num"><?php echo esc_html( $num ); ?></span>
                            <h3 class="project-card__title"><?php the_title(); ?></h3>
                            <?php if ( $label ) : ?>
                            <div class="project-card__tags">
                                <span class="project-card__tag"><?php echo esc_html( $label ); ?></span>
                            </div>
                            <?php endif; ?>
                        </div>

                        <div class="project-card__arrow" aria-hidden="true">↗</div>
                    </div>

                    <!-- Used by list view -->
                    <div class="project-card__info" aria-hidden="true">
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
            endwhile;
            wp_reset_postdata();

        else : ?>
            <!-- Demo placeholders when no portfolio CPT items exist -->
            <?php
            $demo = array(
                array( 'title' => 'Brand Identity System',    'cat' => 'Identity',    'slug' => 'identity' ),
                array( 'title' => 'Editorial Film',           'cat' => 'Video',       'slug' => 'video' ),
                array( 'title' => 'Web Experience',           'cat' => 'Web Design',  'slug' => 'web' ),
                array( 'title' => 'Portrait Series',          'cat' => 'Photography', 'slug' => 'photo' ),
                array( 'title' => 'Motion Campaign',          'cat' => 'Video',       'slug' => 'video' ),
                array( 'title' => 'Type Specimen',            'cat' => 'Identity',    'slug' => 'identity' ),
            );
            foreach ( $demo as $k => $p ) :
                $num = str_pad( $k + 1, 2, '0', STR_PAD_LEFT );
            ?>
            <div class="project-card"
                 data-reveal="up"
                 data-delay="<?php echo min( $k + 1, 8 ); ?>"
                 data-category="<?php echo esc_attr( $p['slug'] ); ?>">

                <div class="project-card__media">
                    <div class="project-card__placeholder placeholder--<?php echo $k % 6; ?>">
                        <span class="t-label">Add thumbnail</span>
                    </div>
                    <div class="project-card__curtain" aria-hidden="true"></div>
                    <div class="project-card__gradient" aria-hidden="true"></div>
                    <div class="project-card__info-overlay">
                        <span class="project-card__num"><?php echo esc_html( $num ); ?></span>
                        <h3 class="project-card__title"><?php echo esc_html( $p['title'] ); ?></h3>
                        <div class="project-card__tags">
                            <span class="project-card__tag"><?php echo esc_html( $p['cat'] ); ?></span>
                        </div>
                    </div>
                    <div class="project-card__arrow" aria-hidden="true">↗</div>
                </div>

                <div class="project-card__info" aria-hidden="true">
                    <span class="project-card__num"><?php echo esc_html( $num ); ?></span>
                    <span class="project-card__title"><?php echo esc_html( $p['title'] ); ?></span>
                    <div class="project-card__tags">
                        <span class="project-card__tag"><?php echo esc_html( $p['cat'] ); ?></span>
                    </div>
                    <span class="project-card__arrow">↗</span>
                </div>
            </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>

</section>

<!-- ============================================================
     STATS STRIP
     ============================================================ -->
<?php
$stats = array(
    array( 'value' => void_opt('void_stat1_value','120+'), 'label' => void_opt('void_stat1_label','Projects') ),
    array( 'value' => void_opt('void_stat2_value','8'),    'label' => void_opt('void_stat2_label','Years') ),
    array( 'value' => void_opt('void_stat3_value','14'),   'label' => void_opt('void_stat3_label','Awards') ),
    array( 'value' => void_opt('void_stat4_value','60+'),  'label' => void_opt('void_stat4_label','Clients') ),
);
?>
<div class="info-strip" role="list">
    <?php foreach ( $stats as $i => $stat ) : ?>
    <div class="info-strip__item" role="listitem" data-reveal="up" data-delay="<?php echo $i + 1; ?>">
        <div class="info-strip__label t-label"><?php echo esc_html( $stat['label'] ); ?></div>
        <div class="info-strip__value"><?php echo esc_html( $stat['value'] ); ?></div>
    </div>
    <?php endforeach; ?>
</div>

<!-- ============================================================
     ABOUT
     ============================================================ -->
<section id="void-about" class="section">
    <div class="about__inner">

        <div class="about__heading-col">
            <h2 class="about__heading" data-reveal="up">
                <?php echo esc_html( void_opt( 'void_about_heading',  'A studio that crafts' ) ); ?><br>
                <?php echo esc_html( void_opt( 'void_about_heading2', 'with intention.' ) ); ?>
            </h2>
        </div>

        <div class="about__body">
            <p class="about__text" data-reveal="up" data-delay="1">
                <?php echo esc_html( void_opt( 'void_about_text',
                    'We design and build digital experiences for brands that believe in the power of restraint. Based globally, working globally.'
                ) ); ?>
            </p>

            <div class="about__disciplines" data-reveal="up" data-delay="2">
                <?php
                for ( $i = 1; $i <= 5; $i++ ) {
                    $d = void_opt( 'void_discipline_' . $i, '' );
                    if ( $d ) {
                        echo '<span class="about__discipline-tag t-label">' . esc_html( $d ) . '</span>';
                    }
                }
                ?>
            </div>

            <a href="#void-contact" class="about__cta t-label" data-reveal="up" data-delay="3">
                Start a project
                <span aria-hidden="true">→</span>
            </a>
        </div>

    </div>
</section>

<!-- ============================================================
     CONTACT
     ============================================================ -->
<section id="void-contact" class="section">
    <div class="contact__inner">

        <h2 class="contact__heading" data-reveal="up">
            <?php echo esc_html( void_opt( 'void_contact_heading', "Let's work." ) ); ?>
        </h2>

        <div class="contact__sub">

            <p class="contact__text" data-reveal="up" data-delay="1">
                <?php echo esc_html( void_opt( 'void_contact_text',
                    'Available for select projects. Get in touch to start a conversation.'
                ) ); ?>
            </p>

            <div class="contact__links" data-reveal="up" data-delay="2">
                <?php
                $email = void_opt( 'void_contact_email', '' );
                if ( $email ) :
                ?>
                <a href="mailto:<?php echo esc_attr( $email ); ?>" class="contact__link">
                    <?php echo esc_html( $email ); ?>
                    <span aria-hidden="true">↗</span>
                </a>
                <?php endif; ?>

                <?php
                $socials = array(
                    'void_contact_instagram' => 'Instagram',
                    'void_contact_twitter'   => 'Twitter',
                    'void_contact_behance'   => 'Behance',
                    'void_contact_linkedin'  => 'LinkedIn',
                );
                foreach ( $socials as $key => $name ) :
                    $url = void_opt( $key, '' );
                    if ( $url ) :
                ?>
                <a href="<?php echo esc_url( $url ); ?>" class="contact__link" target="_blank" rel="noopener noreferrer">
                    <?php echo esc_html( $name ); ?>
                    <span aria-hidden="true">↗</span>
                </a>
                <?php
                    endif;
                endforeach;
                ?>
            </div>

        </div>

        <!-- Footer bottom row -->
        <div class="divider" style="margin-top: var(--section-gap);"></div>
        <div style="display:flex; justify-content:space-between; align-items:center; padding-top:32px; flex-wrap:wrap; gap:16px;">
            <span class="t-caption">
                &copy; <?php echo date('Y'); ?>
                <?php echo esc_html( void_opt( 'void_footer_copy', void_opt( 'void_site_name', get_bloginfo('name') ) ) ); ?>.
                All rights reserved.
            </span>
            <span class="t-caption">
                <?php echo esc_html( void_opt( 'void_footer_location', 'Remote / Global' ) ); ?>
            </span>
        </div>

    </div>
</section>

<?php get_footer(); ?>
