<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

if ( function_exists( 'png_core_render_crack_this_preview' ) ) {
    png_core_render_crack_this_preview();
    return;
}

png_theme_render_component_placeholder(
    'Crack This Lite',
    'Weekly puzzle preview will appear here.'
);
