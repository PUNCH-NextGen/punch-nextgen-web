<?php
/**
 * Controlled ad slots.
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

function png_core_render_ad_slot( $location ) {
    $settings = png_core_get_settings();
    if ( empty( $settings['ads_enabled'] ) ) {
        return;
    }

    $ads = get_posts(
        array(
            'post_type'      => 'png_ad_slot',
            'post_status'    => 'publish',
            'posts_per_page' => 1,
            'meta_query'     => array(
                array(
                    'key'   => 'png_ad_location',
                    'value' => sanitize_key( $location ),
                ),
                array(
                    'key'   => 'png_ad_status',
                    'value' => 'active',
                ),
            ),
        )
    );

    if ( empty( $ads ) ) {
        echo '<!-- Punch NextGen ad slot: ' . esc_html( $location ) . ' -->';
        return;
    }

    $code = get_post_meta( $ads[0]->ID, 'png_ad_code', true );
    if ( ! $code ) {
        return;
    }

    echo '<div class="png-core-ad-slot png-core-ad-slot-' . esc_attr( sanitize_html_class( $location ) ) . '">';
    echo '<span class="png-core-ad-label">Advertisement</span>';
    echo $code; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- ad code is admin-managed.
    echo '</div>';
}
