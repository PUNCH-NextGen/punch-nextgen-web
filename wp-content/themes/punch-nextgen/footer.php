<?php
/**
 * Punch NextGen footer.
 */

?>

<footer class="png-footer">
    <div class="png-container png-footer__grid">
        <div class="png-footer__brand">
            <a class="png-footer-logo" href="<?php echo esc_url( home_url( '/' ) ); ?>">
                <img src="<?php echo esc_url( png_theme_logo_url() ); ?>" alt="<?php echo esc_attr( get_bloginfo( 'name' ) ); ?>">
            </a>

            <p><?php esc_html_e( 'A youth-focused news and learning platform connecting classroom knowledge with real-world stories.', 'punch-nextgen' ); ?></p>
        </div>

        <div class="png-footer__col">
            <h2><?php esc_html_e( 'Explore', 'punch-nextgen' ); ?></h2>
            <a href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php esc_html_e( 'Home', 'punch-nextgen' ); ?></a>
            <a href="<?php echo esc_url( png_theme_get_page_url( 'leaderboards', '/leaderboards/' ) ); ?>"><?php esc_html_e( 'Leaderboards', 'punch-nextgen' ); ?></a>
            <a href="<?php echo esc_url( png_theme_get_page_url( 'crack-this-lite', '/crack-this-lite/' ) ); ?>"><?php esc_html_e( 'Crack This Lite', 'punch-nextgen' ); ?></a>
            <a href="<?php echo esc_url( png_theme_get_page_url( 'school-showcase', '/school-showcase/' ) ); ?>"><?php esc_html_e( 'School Showcase', 'punch-nextgen' ); ?></a>
        </div>

        <div class="png-footer__col">
            <h2><?php esc_html_e( 'For Schools', 'punch-nextgen' ); ?></h2>
            <a href="<?php echo esc_url( png_theme_get_page_url( 'teacher-guide-portal', '/teacher-guide-portal/' ) ); ?>"><?php esc_html_e( 'Teacher Guide Portal', 'punch-nextgen' ); ?></a>
            <a href="<?php echo esc_url( png_theme_get_page_url( 'my-profile', '/my-profile/' ) ); ?>"><?php esc_html_e( 'Student Profile', 'punch-nextgen' ); ?></a>
            <a href="<?php echo esc_url( png_theme_get_page_url( 'contact-feedback', '/contact-feedback/' ) ); ?>"><?php esc_html_e( 'Contact / Feedback', 'punch-nextgen' ); ?></a>
        </div>

        <div class="png-footer__col">
            <h2><?php esc_html_e( 'Categories', 'punch-nextgen' ); ?></h2>
            <?php
            foreach ( array( 'News', 'Culture & Trends', 'Campus & School Life', 'Money & Life Skills', 'Fact Check' ) as $category_name ) {
                $term = get_term_by( 'name', $category_name, 'category' );

                if ( $term && ! is_wp_error( $term ) ) {
                    echo '<a href="' . esc_url( get_term_link( $term ) ) . '">' . esc_html( $category_name ) . '</a>';
                }
            }
            ?>
        </div>
    </div>

    <div class="png-footer__bottom">
        <div class="png-container">
            <p>&copy; <?php echo esc_html( date_i18n( 'Y' ) ); ?> <?php esc_html_e( 'Punch NextGen. All rights reserved.', 'punch-nextgen' ); ?></p>
        </div>
    </div>
</footer>

<?php wp_footer(); ?>
</body>
</html>
