<?php
/**
 * VOID Theme — single.php
 * Handles both blog posts and portfolio single view.
 * For portfolio projects, renders a full-bleed cinematic layout.
 */

get_header();

while ( have_posts() ) : the_post();

$is_portfolio = ( get_post_type() === 'portfolio' );

// Portfolio meta
$project_url   = get_post_meta( get_the_ID(), 'void_project_url',    true );
$project_year  = get_post_meta( get_the_ID(), 'void_project_year',   true );
$project_client= get_post_meta( get_the_ID(), 'void_project_client', true );
$project_role  = get_post_meta( get_the_ID(), 'void_project_role',   true );
$project_tools = get_post_meta( get_the_ID(), 'void_project_tools',  true );

$terms = get_the_terms( get_the_ID(), 'project_type' );
$type  = $terms ? $terms[0]->name : '';

?>

<article id="project-<?php the_ID(); ?>" <?php post_class(); ?>>

<?php if ( $is_portfolio ) : ?>

    <!-- ── Full-bleed hero image ──────────────────────── -->
    <div style="
        position:relative;
        width:100%;
        height:100svh;
        overflow:hidden;
        background:var(--fg-dimmer);
    ">
        <?php if ( has_post_thumbnail() ) : ?>
            <?php the_post_thumbnail( 'project-large', array(
                'style' => 'width:100%;height:100%;object-fit:cover;',
                'alt'   => get_the_title(),
            ) ); ?>
        <?php else : ?>
            <div style="
                width:100%;height:100%;
                display:flex;align-items:center;justify-content:center;
            ">
                <span class="t-label" style="color:var(--fg-dim);">No image</span>
            </div>
        <?php endif; ?>

        <!-- Gradient scrim -->
        <div style="
            position:absolute;inset:0;
            background:linear-gradient(to bottom, transparent 50%, rgba(0,0,0,0.85) 100%);
        " aria-hidden="true"></div>

        <!-- Project title overlay -->
        <div style="
            position:absolute;
            bottom:calc(var(--nav-height) + 48px);
            left:var(--page-pad-x);
            right:var(--page-pad-x);
            display:flex;
            align-items:flex-end;
            justify-content:space-between;
            gap:40px;
            flex-wrap:wrap;
        ">
            <h1 style="
                font-size:var(--text-3xl);
                font-weight:var(--weight-light);
                letter-spacing:var(--tracking-tight);
                line-height:var(--leading-snug);
                color:#fff;
                max-width:800px;
            "><?php the_title(); ?></h1>

            <?php if ( $project_url ) : ?>
            <a href="<?php echo esc_url( $project_url ); ?>"
               target="_blank" rel="noopener noreferrer"
               style="
                 color:#fff;
                 font-size:var(--text-xs);
                 letter-spacing:var(--tracking-widest);
                 text-transform:uppercase;
                 border-bottom:1px solid rgba(255,255,255,0.4);
                 padding-bottom:2px;
                 white-space:nowrap;
                 flex-shrink:0;
               ">
                View Live ↗
            </a>
            <?php endif; ?>
        </div>
    </div>

    <!-- ── Project meta strip ─────────────────────────── -->
    <div style="
        border-bottom:1px solid var(--border);
        display:flex;
        gap:clamp(24px,6vw,80px);
        padding:40px var(--page-pad-x);
        flex-wrap:wrap;
        overflow-x:auto;
    " data-reveal="up">
        <?php
        $meta_items = array(
            'Type'   => $type,
            'Year'   => $project_year,
            'Client' => $project_client,
            'Role'   => $project_role,
            'Tools'  => $project_tools,
        );
        foreach ( $meta_items as $label => $value ) :
            if ( ! $value ) continue;
        ?>
        <div style="flex-shrink:0;">
            <div class="t-label" style="color:var(--fg-dim);margin-bottom:8px;"><?php echo esc_html( $label ); ?></div>
            <div style="font-size:var(--text-md);font-weight:var(--weight-light);"><?php echo esc_html( $value ); ?></div>
        </div>
        <?php endforeach; ?>
    </div>

    <!-- ── Project body content ───────────────────────── -->
    <div style="
        padding:var(--section-gap) var(--page-pad-x);
        display:grid;
        grid-template-columns:1fr 1fr;
        gap:clamp(40px,8vw,120px);
        align-items:start;
    " class="project-content-grid">
        <div data-reveal="up">
            <div class="t-body" style="max-width:none;">
                <?php the_content(); ?>
            </div>
        </div>
        <div data-reveal="up" data-delay="2" style="position:sticky;top:calc(var(--nav-height) + 40px);">
            <?php if ( has_post_thumbnail() ) : ?>
                <?php the_post_thumbnail( 'project-square', array(
                    'style' => 'width:100%;aspect-ratio:1/1;object-fit:cover;',
                ) ); ?>
            <?php endif; ?>
        </div>
    </div>

    <!-- ── Next / Prev project navigation ─────────────── -->
    <div style="
        border-top:1px solid var(--border);
        display:grid;
        grid-template-columns:1fr 1fr;
    ">
        <?php
        $prev = get_adjacent_post( false, '', true,  'project_type' );
        $next = get_adjacent_post( false, '', false, 'project_type' );

        $nav_items = array(
            array( 'post' => $prev, 'label' => '← Previous', 'align' => 'flex-start' ),
            array( 'post' => $next, 'label' => 'Next →',     'align' => 'flex-end' ),
        );

        foreach ( $nav_items as $nav ) :
            if ( $nav['post'] ) :
        ?>
        <a href="<?php echo esc_url( get_permalink( $nav['post'] ) ); ?>" style="
            display:flex;
            flex-direction:column;
            align-items:<?php echo $nav['align']; ?>;
            padding:clamp(32px,5vw,60px) var(--page-pad-x);
            border-right:<?php echo $nav['align'] === 'flex-start' ? '1px solid var(--border)' : 'none'; ?>;
            transition:background var(--dur-fast);
            gap:12px;
        "
        onmouseover="this.style.background='var(--fg-dimmer)'"
        onmouseout="this.style.background=''">
            <span class="t-label" style="color:var(--fg-dim);"><?php echo $nav['label']; ?></span>
            <span style="font-size:var(--text-xl);font-weight:var(--weight-light);letter-spacing:var(--tracking-tight);">
                <?php echo esc_html( get_the_title( $nav['post'] ) ); ?>
            </span>
        </a>
        <?php
            else :
                echo '<div></div>';
            endif;
        endforeach;
        ?>
    </div>

<?php else : ?>

    <!-- ── Regular blog post ──────────────────────────── -->
    <div style="
        padding:calc(80px + var(--nav-height)) var(--page-pad-x) var(--section-gap);
        max-width:780px;
    ">
        <header style="margin-bottom:60px;" data-reveal="up">
            <div class="t-label" style="color:var(--fg-dim);margin-bottom:20px;">
                <?php echo get_the_date(); ?> &nbsp;·&nbsp; <?php the_category(', '); ?>
            </div>
            <h1 style="
                font-size:var(--text-2xl);
                font-weight:var(--weight-light);
                letter-spacing:var(--tracking-tight);
                line-height:var(--leading-snug);
            "><?php the_title(); ?></h1>
        </header>

        <?php if ( has_post_thumbnail() ) : ?>
        <div style="margin-bottom:60px;" data-reveal="up" data-delay="1">
            <?php the_post_thumbnail( 'project-large', array( 'style' => 'width:100%;' ) ); ?>
        </div>
        <?php endif; ?>

        <div class="t-body" style="max-width:none;color:var(--fg);" data-reveal="up" data-delay="2">
            <?php the_content(); ?>
        </div>
    </div>

<?php endif; ?>

</article>

<?php endwhile; ?>

<style>
@media (max-width: 760px) {
    .project-content-grid {
        grid-template-columns: 1fr !important;
    }
}
</style>

<?php get_footer(); ?>
