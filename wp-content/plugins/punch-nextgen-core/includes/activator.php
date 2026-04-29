<?php
/**
 * Plugin activation tasks.
 *
 * @package PunchNextGenCore
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

function png_core_run_activation() {
    update_option( 'png_core_version', PNG_CORE_VERSION );

    // Future activation tasks:
    // - create custom database tables
    // - create roles/capabilities
    // - flush rewrite rules after CPTs are registered

    flush_rewrite_rules();
}
