<?php
/**
 * Template Name: Punch NextGen Contact
 */

get_header();
?>

<main id="primary" class="site-main png-page png-page-contact">
    <section class="png-site-section">
        <h1><?php esc_html_e( 'Contact / Feedback', 'punch-nextgen' ); ?></h1>
        <?php
        if ( function_exists( 'png_core_render_contact_form' ) ) {
            png_core_render_contact_form();
        } else {
            png_theme_render_component_placeholder( 'Contact Form', 'The single public contact form will appear here.' );
        }
        ?>
    </section>
</main>

<?php
get_footer();
