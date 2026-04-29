<?php
/**
 * Custom post types.
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

function png_core_register_post_type( $type, $singular, $plural, $args = array() ) {
    $labels = array(
        'name'               => $plural,
        'singular_name'      => $singular,
        'add_new'            => 'Add New',
        'add_new_item'       => 'Add New ' . $singular,
        'edit_item'          => 'Edit ' . $singular,
        'new_item'           => 'New ' . $singular,
        'view_item'          => 'View ' . $singular,
        'view_items'         => 'View ' . $plural,
        'search_items'       => 'Search ' . $plural,
        'not_found'          => 'No ' . strtolower( $plural ) . ' found',
        'not_found_in_trash' => 'No ' . strtolower( $plural ) . ' found in Trash',
        'all_items'          => 'All ' . $plural,
        'menu_name'          => $plural,
    );

    $defaults = array(
        'labels'          => $labels,
        'public'          => true,
        'show_ui'         => true,
        'show_in_menu'    => false,
        'show_in_rest'    => true,
        'has_archive'     => true,
        'rewrite'         => array( 'slug' => str_replace( 'png_', '', $type ) ),
        'supports'        => array( 'title', 'editor', 'thumbnail', 'excerpt', 'author', 'revisions' ),
        'capability_type' => 'post',
        'map_meta_cap'    => true,
    );

    register_post_type( $type, array_merge( $defaults, $args ) );
}

function png_core_register_post_types() {
    png_core_register_post_type(
        'png_school',
        'School',
        'Schools',
        array(
            'menu_icon' => 'dashicons-welcome-learn-more',
            'rewrite'   => array( 'slug' => 'schools' ),
            'supports'  => array( 'title', 'editor', 'thumbnail', 'excerpt', 'revisions' ),
        )
    );

    png_core_register_post_type(
        'png_teacher_guide',
        'Teacher Guide',
        'Teacher Guides',
        array(
            'menu_icon' => 'dashicons-welcome-write-blog',
            'rewrite'   => array( 'slug' => 'teacher-guides' ),
        )
    );

    png_core_register_post_type(
        'png_crack_this',
        'Crack This Lite',
        'Crack This Lite',
        array(
            'menu_icon' => 'dashicons-lightbulb',
            'rewrite'   => array( 'slug' => 'crack-this-lite' ),
            'supports'  => array( 'title', 'editor', 'thumbnail', 'excerpt', 'author', 'revisions', 'comments' ),
        )
    );

    png_core_register_post_type(
        'png_comic',
        'Digital Comic',
        'Digital Comics',
        array(
            'menu_icon' => 'dashicons-format-image',
            'rewrite'   => array( 'slug' => 'comics' ),
            'supports'  => array( 'title', 'editor', 'thumbnail', 'excerpt', 'revisions' ),
        )
    );

    png_core_register_post_type(
        'png_poll',
        'Story Poll',
        'Story Polls',
        array(
            'public'       => false,
            'show_ui'      => true,
            'show_in_rest' => true,
            'menu_icon'    => 'dashicons-chart-bar',
            'supports'     => array( 'title', 'revisions' ),
            'has_archive'  => false,
            'rewrite'      => false,
        )
    );

    png_core_register_post_type(
        'png_term',
        'Academic Term',
        'Academic Terms',
        array(
            'public'       => false,
            'show_ui'      => true,
            'show_in_rest' => true,
            'menu_icon'    => 'dashicons-calendar-alt',
            'supports'     => array( 'title', 'revisions' ),
            'has_archive'  => false,
            'rewrite'      => false,
        )
    );

    png_core_register_post_type(
        'png_subscription',
        'School Subscription',
        'School Subscriptions',
        array(
            'public'       => false,
            'show_ui'      => true,
            'show_in_rest' => true,
            'menu_icon'    => 'dashicons-lock',
            'supports'     => array( 'title', 'revisions' ),
            'has_archive'  => false,
            'rewrite'      => false,
        )
    );

    png_core_register_post_type(
        'png_reward',
        'Reward Achievement',
        'Reward Achievements',
        array(
            'menu_icon' => 'dashicons-awards',
            'rewrite'   => array( 'slug' => 'winners' ),
            'supports'  => array( 'title', 'editor', 'thumbnail', 'excerpt', 'revisions' ),
        )
    );

    png_core_register_post_type(
        'png_ad_slot',
        'Ad Slot',
        'Ad Slots',
        array(
            'public'       => false,
            'show_ui'      => true,
            'show_in_rest' => true,
            'menu_icon'    => 'dashicons-megaphone',
            'supports'     => array( 'title', 'revisions' ),
            'has_archive'  => false,
            'rewrite'      => false,
        )
    );

    png_core_register_post_type(
        'png_contact_message',
        'Contact Message',
        'Contact Messages',
        array(
            'public'       => false,
            'show_ui'      => true,
            'show_in_rest' => false,
            'menu_icon'    => 'dashicons-email-alt',
            'supports'     => array( 'title', 'editor', 'revisions' ),
            'has_archive'  => false,
            'rewrite'      => false,
        )
    );
}
