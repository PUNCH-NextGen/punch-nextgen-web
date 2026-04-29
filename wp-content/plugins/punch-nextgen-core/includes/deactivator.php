<?php
/**
 * Plugin deactivation tasks.
 *
 * @package PunchNextGenCore
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

function png_core_run_deactivation() {
    flush_rewrite_rules();
}
