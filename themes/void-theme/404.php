<?php
/**
 * VOID Theme — 404.php
 */
get_header();
?>

<main style="
    min-height: 100svh;
    display: flex;
    flex-direction: column;
    align-items: flex-start;
    justify-content: flex-end;
    padding: 80px var(--page-pad-x) calc(var(--nav-height) + 60px);
    position: relative;
    overflow: hidden;
">

    <div style="
        position:absolute;
        top:50%;left:50%;
        transform:translate(-50%,-50%);
        font-size:clamp(12rem,30vw,28rem);
        font-weight:var(--weight-light);
        letter-spacing:var(--tracking-tight);
        color:var(--fg-dimmer);
        user-select:none;
        pointer-events:none;
        line-height:1;
        white-space:nowrap;
    " aria-hidden="true">404</div>

    <div style="position:relative;z-index:2;" data-reveal="up">
        <div class="t-label" style="color:var(--fg-dim);margin-bottom:20px;">Page not found</div>
        <h1 style="
            font-size:var(--text-3xl);
            font-weight:var(--weight-light);
            letter-spacing:var(--tracking-tight);
            line-height:var(--leading-snug);
            margin-bottom:48px;
        ">Nothing here.</h1>
        <a href="<?php echo esc_url( home_url('/') ); ?>"
           style="
            font-size:var(--text-xs);
            letter-spacing:var(--tracking-widest);
            text-transform:uppercase;
            color:var(--fg);
            display:inline-flex;
            align-items:center;
            gap:12px;
            padding-bottom:2px;
            border-bottom:1px solid var(--fg);
            transition:gap var(--dur-base) var(--ease-out), color var(--dur-fast);
           "
           onmouseover="this.style.gap='20px';this.style.color='var(--accent)';this.style.borderColor='var(--accent)'"
           onmouseout="this.style.gap='12px';this.style.color='';this.style.borderColor=''">
            Go home <span aria-hidden="true">→</span>
        </a>
    </div>

</main>

<?php get_footer(); ?>
