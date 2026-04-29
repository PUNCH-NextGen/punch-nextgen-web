<?php
/**
 * Punch NextGen theme setup.
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

function png_theme_nextgen_setup() {
    add_theme_support( 'title-tag' );
    add_theme_support( 'post-thumbnails' );
    add_theme_support( 'html5', array( 'search-form', 'comment-form', 'comment-list', 'gallery', 'caption', 'style', 'script' ) );

    add_image_size( 'png_card_large', 720, 420, true );
    add_image_size( 'png_card_medium', 480, 300, true );
    add_image_size( 'png_card_square', 420, 420, true );
    add_image_size( 'png_comic_strip', 900, 420, false );

    register_nav_menus(
        array(
            'primary'       => esc_html__( 'Primary Menu', 'punch-nextgen' ),
            'category_menu' => esc_html__( 'Category Menu', 'punch-nextgen' ),
            'footer_menu'   => esc_html__( 'Footer Menu', 'punch-nextgen' ),
            'teacher_menu'  => esc_html__( 'Teacher Portal Menu', 'punch-nextgen' ),
        )
    );
}
add_action( 'after_setup_theme', 'png_theme_nextgen_setup', 20 );
