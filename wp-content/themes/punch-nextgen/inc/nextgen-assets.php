<?php
/**
 * Punch NextGen theme assets.
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

function png_theme_asset_version( $relative_path ) {
    $file = get_template_directory() . '/' . ltrim( $relative_path, '/' );

    if ( file_exists( $file ) ) {
        return filemtime( $file );
    }

    return wp_get_theme()->get( 'Version' );
}

function png_theme_enqueue_nextgen_assets() {
    wp_enqueue_style(
        'png-nextgen-base',
        get_template_directory_uri() . '/assets/css/nextgen-base.css',
        array(),
        png_theme_asset_version( 'assets/css/nextgen-base.css' )
    );

    wp_enqueue_style(
        'png-nextgen-layout',
        get_template_directory_uri() . '/assets/css/nextgen-layout.css',
        array( 'png-nextgen-base' ),
        png_theme_asset_version( 'assets/css/nextgen-layout.css' )
    );

    wp_enqueue_style(
        'png-nextgen-components',
        get_template_directory_uri() . '/assets/css/nextgen-components.css',
        array( 'png-nextgen-layout' ),
        png_theme_asset_version( 'assets/css/nextgen-components.css' )
    );

    wp_enqueue_style(
        'png-nextgen-pages',
        get_template_directory_uri() . '/assets/css/nextgen-pages.css',
        array( 'png-nextgen-components' ),
        png_theme_asset_version( 'assets/css/nextgen-pages.css' )
    );

    wp_enqueue_script(
        'png-nextgen-main',
        get_template_directory_uri() . '/assets/js/nextgen-main.js',
        array(),
        png_theme_asset_version( 'assets/js/nextgen-main.js' ),
        true
    );

    wp_localize_script(
        'png-nextgen-main',
        'PNGTheme',
        array(
            'ajaxUrl' => admin_url( 'admin-ajax.php' ),
            'restUrl' => esc_url_raw( rest_url() ),
            'nonce'   => wp_create_nonce( 'wp_rest' ),
        )
    );
}
add_action( 'wp_enqueue_scripts', 'png_theme_enqueue_nextgen_assets', 20 );
