</div><!-- /#page-wrapper -->

<!-- ============================================================
     BOTTOM NAVIGATION
     ============================================================ -->
<nav id="void-nav" role="navigation" aria-label="Primary">
    <div class="nav__inner">

        <ul class="nav__items" role="list">
            <li>
                <a href="#void-hero" class="nav__link is-active">
                    <span class="nav__link-num">00</span>
                    Home
                </a>
            </li>
            <li>
                <a href="#void-portfolio" class="nav__link">
                    <span class="nav__link-num">01</span>
                    Work
                </a>
            </li>
            <li>
                <a href="#void-about" class="nav__link">
                    <span class="nav__link-num">02</span>
                    About
                </a>
            </li>
            <li>
                <a href="#void-contact" class="nav__link">
                    <span class="nav__link-num">03</span>
                    Contact
                </a>
            </li>
        </ul>

        <div class="nav__meta t-mono">
            <?php echo esc_html( void_opt( 'void_footer_location', 'Remote / Global' ) ); ?>
        </div>

        <!-- Mobile hamburger -->
        <button class="nav__hamburger" aria-label="Open menu" aria-expanded="false">
            <span></span><span></span><span></span>
        </button>

    </div>
</nav>

<!-- Mobile drawer -->
<div class="nav__mobile-drawer" role="dialog" aria-modal="true" aria-label="Mobile menu">
    <a href="#void-hero"      class="nav__mobile-link">Home</a>
    <a href="#void-portfolio" class="nav__mobile-link">Work</a>
    <a href="#void-about"     class="nav__mobile-link">About</a>
    <a href="#void-contact"   class="nav__mobile-link">Contact</a>
</div>

<?php wp_footer(); ?>
</body>
</html>
