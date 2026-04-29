<?php
/**
 * Plugin bootstrap and loader.
 *
 * @package PunchNextGenCore
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

function png_core_require_file( $relative_path ) {
    $file = PNG_CORE_PATH . ltrim( $relative_path, '/' );

    if ( file_exists( $file ) ) {
        require_once $file;
    }
}

function png_core_activate() {
    png_core_require_file( 'includes/activator.php' );

    if ( function_exists( 'png_core_run_activation' ) ) {
        png_core_run_activation();
    }
}

function png_core_deactivate() {
    png_core_require_file( 'includes/deactivator.php' );

    if ( function_exists( 'png_core_run_deactivation' ) ) {
        png_core_run_deactivation();
    }
}

function png_core_bootstrap() {
    $files = array(
        'includes/helpers.php',
        'includes/database.php',
        'includes/settings.php',
        'includes/post-types.php',
        'includes/taxonomies.php',
        'includes/roles-capabilities.php',
        'includes/acf-fields.php',

        'includes/users/registration.php',
        'includes/users/profiles.php',
        'includes/users/school-linking.php',
        'includes/users/teacher-accounts.php',

        'includes/schools/schools.php',
        'includes/schools/terms.php',
        'includes/schools/subscriptions.php',
        'includes/schools/holidays.php',

        'includes/content/story-formats.php',
        'includes/content/teacher-guides.php',
        'includes/content/comics.php',
        'includes/content/ads.php',

        'includes/interactions/polls.php',
        'includes/interactions/crack-this-lite.php',
        'includes/interactions/story-reads.php',
        'includes/interactions/comments.php',
        'includes/interactions/contact.php',

        'includes/points/points-ledger.php',
        'includes/points/badges.php',
        'includes/points/missions.php',
        'includes/points/leaderboards.php',
        'includes/points/rewards.php',

        'includes/admin/admin-menu.php',
        'includes/admin/dashboard.php',
        'includes/admin/schools-page.php',
        'includes/admin/terms-page.php',
        'includes/admin/subscriptions-page.php',
        'includes/admin/teachers-page.php',
        'includes/admin/points-page.php',
        'includes/admin/leaderboards-page.php',
        'includes/admin/reports-page.php',
        'includes/admin/rewards-page.php',
        'includes/admin/settings-page.php',

        'includes/api/rest-routes.php',
        'includes/api/ajax-handlers.php',
    );

    foreach ( $files as $file ) {
        png_core_require_file( $file );
    }

    do_action( 'png_core_loaded' );
}
