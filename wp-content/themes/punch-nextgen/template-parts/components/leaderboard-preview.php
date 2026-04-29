<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

if ( function_exists( 'png_core_render_leaderboard_preview' ) ) {
    png_core_render_leaderboard_preview();
    return;
}

png_theme_render_component_placeholder(
    'Leaderboard Preview',
    'Top students and top schools will appear here.'
);
