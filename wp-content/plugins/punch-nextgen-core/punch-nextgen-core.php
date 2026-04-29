<?php
/**
 * Plugin Name: Punch NextGen Core
 * Plugin URI: https://punchnextgen.com
 * Description: Core platform engine for Punch NextGen: schools, teacher portal, polls, comics, Crack This Lite, profiles, points, leaderboards, rewards, ads, reports, and admin tools.
 * Version: 1.0.0
 * Author: PUNCH NextGen
 * Text Domain: punch-nextgen-core
 * Requires at least: 6.2
 * Requires PHP: 8.0
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

define( 'PNG_CORE_VERSION', '1.0.0' );
define( 'PNG_CORE_FILE', __FILE__ );
define( 'PNG_CORE_PATH', plugin_dir_path( __FILE__ ) );
define( 'PNG_CORE_URL', plugin_dir_url( __FILE__ ) );

$png_core_files = array(
    'includes/setup/helpers.php',
    'includes/setup/database.php',
    'includes/setup/roles.php',
    'includes/setup/post-types.php',
    'includes/setup/taxonomies.php',
    'includes/setup/pages.php',
    'includes/setup/acf-fields.php',
    'includes/platform/settings.php',
    'includes/platform/meta.php',
    'includes/platform/points.php',
    'includes/platform/schools.php',
    'includes/platform/polls.php',
    'includes/platform/crack-this.php',
    'includes/platform/comics.php',
    'includes/platform/ads.php',
    'includes/platform/comments.php',
    'includes/platform/contact.php',
    'includes/platform/teacher-guides.php',
    'includes/frontend/shortcodes.php',
    'includes/frontend/content-hooks.php',
    'includes/admin/admin-ui.php',
    'includes/admin/admin-menu.php',
    'includes/admin/admin-actions.php',
);

foreach ( $png_core_files as $png_core_file ) {
    $png_core_path = PNG_CORE_PATH . $png_core_file;
    if ( file_exists( $png_core_path ) ) {
        require_once $png_core_path;
    }
}

register_activation_hook( PNG_CORE_FILE, 'png_core_activate' );
register_deactivation_hook( PNG_CORE_FILE, 'png_core_deactivate' );

/**
 * Runs plugin activation tasks.
 *
 * Activation is intentionally idempotent: it can run more than once safely.
 */
function png_core_activate() {
    png_core_register_roles_capabilities();
    png_core_register_post_types();
    png_core_register_taxonomies();
    png_core_create_tables();
    png_core_seed_default_terms();
    png_core_seed_required_pages();
    png_core_seed_default_options();
    update_option( 'png_core_version', PNG_CORE_VERSION );
    flush_rewrite_rules();
}

/**
 * Runs plugin deactivation tasks.
 *
 * Data is intentionally preserved. This plugin controls platform data, so it must
 * not delete schools, points, subscriptions, polls, or reports on deactivation.
 */
function png_core_deactivate() {
    flush_rewrite_rules();
}

/**
 * Registers runtime hooks after all plugins have loaded.
 */
function png_core_boot() {
    add_action( 'init', 'png_core_register_post_types' );
    add_action( 'init', 'png_core_register_taxonomies' );
    add_action( 'init', 'png_core_register_shortcodes' );
    add_action( 'init', 'png_core_handle_public_post_actions' );

    add_action( 'acf/init', 'png_core_register_acf_fields' );
    add_action( 'admin_init', 'png_core_maybe_upgrade' );
    add_action( 'admin_menu', 'png_core_register_admin_menu' );
    add_action( 'admin_enqueue_scripts', 'png_core_enqueue_admin_assets' );
    add_action( 'wp_enqueue_scripts', 'png_core_enqueue_public_assets' );

    add_action( 'add_meta_boxes', 'png_core_register_fallback_meta_boxes' );
    add_action( 'save_post', 'png_core_save_fallback_meta_boxes', 10, 2 );

    add_filter( 'the_content', 'png_core_append_story_modules', 20 );
    add_action( 'template_redirect', 'png_core_track_story_read' );

    add_action( 'comment_post', 'png_core_comment_posted', 10, 3 );
    add_filter( 'preprocess_comment', 'png_core_filter_blocked_words' );

    png_core_register_admin_post_actions();
}
add_action( 'plugins_loaded', 'png_core_boot' );

/**
 * Ensures updates are applied when plugin files are replaced by upload/upgrade.
 */
function png_core_maybe_upgrade() {
    $stored_version = get_option( 'png_core_version' );
    if ( $stored_version === PNG_CORE_VERSION ) {
        return;
    }

    png_core_activate();
}
