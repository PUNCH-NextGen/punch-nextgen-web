<?php
/**
 * Punch NextGen theme helper functions.
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

function png_theme_get_story_format( $post_id = null ) {
    $post_id = $post_id ? $post_id : get_the_ID();

    if ( function_exists( 'get_field' ) ) {
        $format = get_field( 'png_story_format', $post_id );

        if ( ! empty( $format ) ) {
            return $format;
        }
    }

    return 'standard';
}

function png_theme_render_ad_slot( $slot ) {
    if ( function_exists( 'png_core_render_ad_slot' ) ) {
        png_core_render_ad_slot( $slot );
        return;
    }

    echo '<!-- Punch NextGen ad slot: ' . esc_html( $slot ) . ' -->';
}

function png_theme_render_component_placeholder( $title, $description = '' ) {
    ?>
    <section class="png-site-section png-placeholder">
        <h2><?php echo esc_html( $title ); ?></h2>
        <?php if ( $description ) : ?>
            <p><?php echo esc_html( $description ); ?></p>
        <?php endif; ?>
    </section>
    <?php
}
