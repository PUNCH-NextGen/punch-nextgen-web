<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

if ( function_exists( 'png_core_render_story_poll' ) ) {
    png_core_render_story_poll( get_the_ID() );
    return;
}

echo '<!-- Story poll will appear here after plugin poll module is built. -->';
