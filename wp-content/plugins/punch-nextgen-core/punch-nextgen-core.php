<?php
/**
 * Plugin Name: Punch NextGen Core
 * Description: Core platform logic for the Punch NextGen web platform.
 * Version: 0.1.0
 * Author: PUNCH NextGen
 * Text Domain: punch-nextgen-core
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

define( 'PNG_CORE_VERSION', '0.1.0' );
define( 'PNG_CORE_FILE', __FILE__ );
define( 'PNG_CORE_PATH', plugin_dir_path( __FILE__ ) );
define( 'PNG_CORE_URL', plugin_dir_url( __FILE__ ) );

require_once PNG_CORE_PATH . 'includes/setup.php';

register_activation_hook( PNG_CORE_FILE, 'png_core_activate' );
register_deactivation_hook( PNG_CORE_FILE, 'png_core_deactivate' );

add_action( 'plugins_loaded', 'png_core_bootstrap' );
