<?php
/**
 * Template Name: Punch NextGen Profile
 */

get_header();
?>

<main id="primary" class="site-main png-page png-page-profile">
    <section class="png-site-section">
        <h1><?php esc_html_e( 'My Profile', 'punch-nextgen' ); ?></h1>
        <?php
        if ( function_exists( 'png_core_render_profile_page' ) ) {
            png_core_render_profile_page();
        } else {
            png_theme_render_component_placeholder( 'Profile', 'Student and teacher profile management will appear here.' );
        }
        ?>
    </section>
</main>

<?php
get_footer();
