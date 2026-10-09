<?php
/**
 * VOID Theme — index.php
 * Fallback template for archive, blog, search pages.
 */
get_header();
?>

<main style="
    padding: calc(80px + var(--nav-height)) var(--page-pad-x) var(--section-gap);
    min-height: 100vh;
">

    <?php if ( have_posts() ) : ?>

        <header style="margin-bottom: 80px;">
            <h1 class="t-heading" data-reveal="up">
                <?php
                if ( is_search() ) {
                    echo 'Search: ' . esc_html( get_search_query() );
                } elseif ( is_category() ) {
                    single_cat_title();
                } elseif ( is_tag() ) {
                    single_tag_title();
                } elseif ( is_archive() ) {
                    the_archive_title();
                } else {
                    bloginfo('name');
                }
                ?>
            </h1>
        </header>

        <div style="display:grid; gap:1px;">
            <?php while ( have_posts() ) : the_post(); ?>
            <article style="
                padding: 40px 0;
                border-bottom: 1px solid var(--border);
                display:flex;
                justify-content:space-between;
                align-items:baseline;
                gap:40px;
                flex-wrap:wrap;
            " data-reveal="up">
                <h2 style="font-size:var(--text-xl); font-weight:var(--weight-light); letter-spacing:var(--tracking-tight);">
                    <a href="<?php the_permalink(); ?>" style="transition:color var(--dur-fast);"
                       onmouseover="this.style.color='var(--accent)'"
                       onmouseout="this.style.color=''">
                        <?php the_title(); ?>
                    </a>
                </h2>
                <span class="t-caption"><?php echo get_the_date(); ?></span>
            </article>
            <?php endwhile; ?>
        </div>

        <div style="margin-top:60px;">
            <?php the_posts_navigation(); ?>
        </div>

    <?php else : ?>
        <p class="t-body">Nothing found.</p>
    <?php endif; ?>

</main>

<?php get_footer(); ?>
