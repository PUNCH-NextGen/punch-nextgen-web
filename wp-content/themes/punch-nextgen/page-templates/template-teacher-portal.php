<?php
/**
 * Template Name: Punch NextGen Teacher Portal
 */

get_header();
?>

<main id="primary" class="site-main png-page png-page-teacher-portal">
    <section class="png-site-section">
        <h1><?php esc_html_e( 'Teacher Guide Portal', 'punch-nextgen' ); ?></h1>
        <?php
        if ( function_exists( 'png_core_render_teacher_portal' ) ) {
            png_core_render_teacher_portal();
        } else {
            png_theme_render_component_placeholder( 'Teacher Guide Portal', 'Teacher guide access will be powered by school subscription rules.' );
        }
        ?>
    </section>
</main>

<?php
get_footer();
