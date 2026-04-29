<?php
/**
 * Frontend content hooks.
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

function png_core_append_story_modules( $content ) {
    if ( is_admin() || is_feed() || ! is_singular( 'post' ) || ! in_the_loop() || ! is_main_query() ) {
        return $content;
    }

    $post_id = get_the_ID();
    $output  = $content;

    // Strict placement: poll is appended after the story body only.
    $output .= png_core_render_story_poll( $post_id );

    // Required rule: digital comic appears after every story when available.
    $output .= png_core_render_story_comic( $post_id );

    ob_start();
    png_core_render_ad_slot( 'article_after_story' );
    $output .= ob_get_clean();

    return $output;
}

function png_core_enqueue_public_assets() {
    wp_enqueue_style( 'png-core-public', PNG_CORE_URL . 'assets/css/public.css', array(), PNG_CORE_VERSION );
    wp_enqueue_script( 'png-core-public', PNG_CORE_URL . 'assets/js/public.js', array(), PNG_CORE_VERSION, true );
}
