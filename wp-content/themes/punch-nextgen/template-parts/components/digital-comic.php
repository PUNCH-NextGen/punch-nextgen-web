<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

if ( function_exists( 'png_core_render_story_comic' ) ) {
    png_core_render_story_comic( get_the_ID() );
    return;
}

echo '<!-- Digital comic will appear here after plugin comic module is built. -->';
