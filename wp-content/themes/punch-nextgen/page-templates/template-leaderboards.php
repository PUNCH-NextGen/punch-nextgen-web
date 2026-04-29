<?php
/**
 * Template Name: Punch NextGen Leaderboards
 */

get_header();
?>

<main id="primary" class="site-main png-page png-page-leaderboards">
    <section class="png-site-section">
        <h1><?php esc_html_e( 'Leaderboards', 'punch-nextgen' ); ?></h1>
        <?php
        if ( function_exists( 'png_core_render_leaderboards_page' ) ) {
            png_core_render_leaderboards_page();
        } else {
            png_theme_render_component_placeholder( 'Leaderboards', 'User and school leaderboard module will appear here.' );
        }
        ?>
    </section>
</main>

<?php
get_footer();
