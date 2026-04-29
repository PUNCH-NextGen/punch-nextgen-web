<?php
/**
 * Taxonomies and seed terms.
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

function png_core_register_taxonomy( $taxonomy, $object_types, $singular, $plural, $args = array() ) {
    $labels = array(
        'name'          => $plural,
        'singular_name' => $singular,
        'search_items'  => 'Search ' . $plural,
        'all_items'     => 'All ' . $plural,
        'edit_item'     => 'Edit ' . $singular,
        'update_item'   => 'Update ' . $singular,
        'add_new_item'  => 'Add New ' . $singular,
        'new_item_name' => 'New ' . $singular . ' Name',
        'menu_name'     => $plural,
    );

    $defaults = array(
        'labels'            => $labels,
        'hierarchical'      => true,
        'show_ui'           => true,
        'show_admin_column' => true,
        'show_in_rest'      => true,
        'rewrite'           => array( 'slug' => str_replace( 'png_', '', $taxonomy ) ),
    );

    register_taxonomy( $taxonomy, $object_types, array_merge( $defaults, $args ) );
}

function png_core_register_taxonomies() {
    png_core_register_taxonomy( 'png_teacher_guide_category', array( 'png_teacher_guide' ), 'Teacher Guide Category', 'Teacher Guide Categories', array( 'rewrite' => array( 'slug' => 'teacher-guide-category' ) ) );
    png_core_register_taxonomy( 'png_school_type', array( 'png_school' ), 'School Type', 'School Types', array( 'rewrite' => array( 'slug' => 'school-type' ) ) );
    png_core_register_taxonomy( 'png_school_location', array( 'png_school' ), 'School Location', 'School Locations', array( 'rewrite' => array( 'slug' => 'school-location' ) ) );
    png_core_register_taxonomy( 'png_comic_category', array( 'png_comic' ), 'Comic Category', 'Comic Categories', array( 'rewrite' => array( 'slug' => 'comic-category' ) ) );
    png_core_register_taxonomy( 'png_crack_level', array( 'png_crack_this' ), 'Crack This Level', 'Crack This Levels', array( 'rewrite' => array( 'slug' => 'crack-this-level' ) ) );
    png_core_register_taxonomy( 'png_reward_type', array( 'png_reward' ), 'Reward Type', 'Reward Types', array( 'rewrite' => array( 'slug' => 'reward-type' ) ) );
}

function png_core_seed_default_terms() {
    $categories = array(
        'News',
        'Culture & Trends',
        'Campus & School Life',
        'Money & Life Skills',
        'Career & Opportunities',
        'Sports',
        'Opinion / Youth Voices',
        'Myth vs Fact',
        'Fact Check',
    );

    foreach ( $categories as $category ) {
        if ( ! term_exists( $category, 'category' ) ) {
            wp_insert_term( $category, 'category' );
        }
    }

    $guide_categories = array(
        'Reading & Comprehension',
        'Writing & Expression',
        'Current Affairs Discussion',
        'News Literacy / Media Literacy',
        'Civic & Society',
        'Life Skills',
        'Fact Check Classroom Kit',
        'Myth vs Fact Classroom Kit',
    );

    foreach ( $guide_categories as $category ) {
        if ( ! term_exists( $category, 'png_teacher_guide_category' ) ) {
            wp_insert_term( $category, 'png_teacher_guide_category' );
        }
    }

    $crack_levels = array( 'Beginner', 'Intermediate', 'Advanced' );
    foreach ( $crack_levels as $level ) {
        if ( ! term_exists( $level, 'png_crack_level' ) ) {
            wp_insert_term( $level, 'png_crack_level' );
        }
    }
}
