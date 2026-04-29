<?php
/**
 * Settings registration and seeding.
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

function png_core_seed_default_options() {
    $settings = png_core_get_settings();
    update_option( 'png_core_settings', $settings );
}

function png_core_get_current_term_id() {
    $settings = png_core_get_settings();
    return absint( $settings['current_term_id'] );
}
