<?php
/**
 * End-of-story poll component.
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

if ( function_exists( 'png_core_render_story_poll' ) ) {
    png_core_render_story_poll( get_the_ID() );
    return;
}

if ( shortcode_exists( 'png_story_poll' ) ) {
    echo do_shortcode( '[png_story_poll post_id="' . absint( get_the_ID() ) . '"]' );
    return;
}

echo '<!-- Punch NextGen story poll appears here when linked. -->';
