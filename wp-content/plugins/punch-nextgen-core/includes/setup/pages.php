<?php
/**
 * Required pages and default menu setup.
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

function png_core_required_pages() {
    return array(
        'home' => array(
            'title'   => 'Home',
            'content' => '',
            'front'   => true,
        ),
        'leaderboards' => array(
            'title'   => 'Leaderboards',
            'content' => '[png_leaderboards]',
        ),
        'teacher-guide-portal' => array(
            'title'   => 'Teacher Guide Portal',
            'content' => '[png_teacher_portal]',
        ),
        'my-profile' => array(
            'title'   => 'My Profile',
            'content' => '[png_profile]',
        ),
        'contact-feedback' => array(
            'title'   => 'Contact / Feedback',
            'content' => '[png_contact_form]',
        ),
        'school-showcase' => array(
            'title'   => 'School Showcase',
            'content' => '[png_school_showcase]',
        ),
        'crack-this-lite' => array(
            'title'   => 'Crack This Lite',
            'content' => '[png_crack_this_archive]',
        ),
    );
}

function png_core_seed_required_pages() {
    $page_ids = get_option( 'png_core_required_pages', array() );

    foreach ( png_core_required_pages() as $slug => $page ) {
        $existing = get_page_by_path( $slug );
        if ( $existing ) {
            $page_id = $existing->ID;
        } else {
            $page_id = wp_insert_post(
                array(
                    'post_type'    => 'page',
                    'post_title'   => $page['title'],
                    'post_name'    => $slug,
                    'post_content' => $page['content'],
                    'post_status'  => 'publish',
                )
            );
        }

        if ( ! is_wp_error( $page_id ) && $page_id ) {
            $page_ids[ $slug ] = absint( $page_id );
            if ( ! empty( $page['front'] ) ) {
                update_option( 'show_on_front', 'page' );
                update_option( 'page_on_front', absint( $page_id ) );
            }
        }
    }

    update_option( 'png_core_required_pages', $page_ids );
    png_core_seed_primary_menu( $page_ids );
}

function png_core_seed_primary_menu( $page_ids ) {
    $menu_name = 'Punch NextGen Primary';
    $menu      = wp_get_nav_menu_object( $menu_name );

    if ( ! $menu ) {
        $menu_id = wp_create_nav_menu( $menu_name );
    } else {
        $menu_id = $menu->term_id;
    }

    if ( is_wp_error( $menu_id ) || ! $menu_id ) {
        return;
    }

    $existing_items = wp_get_nav_menu_items( $menu_id );
    if ( ! empty( $existing_items ) ) {
        return;
    }

    $order = array( 'home', 'school-showcase', 'crack-this-lite', 'leaderboards', 'teacher-guide-portal', 'my-profile', 'contact-feedback' );
    foreach ( $order as $slug ) {
        if ( empty( $page_ids[ $slug ] ) ) {
            continue;
        }
        wp_update_nav_menu_item(
            $menu_id,
            0,
            array(
                'menu-item-title'     => get_the_title( $page_ids[ $slug ] ),
                'menu-item-object'    => 'page',
                'menu-item-object-id' => $page_ids[ $slug ],
                'menu-item-type'      => 'post_type',
                'menu-item-status'    => 'publish',
            )
        );
    }

    $locations = get_theme_mod( 'nav_menu_locations', array() );
    foreach ( array( 'primary', 'menu-1', 'category_menu' ) as $location ) {
        if ( has_nav_menu( $location ) || empty( $locations[ $location ] ) ) {
            $locations[ $location ] = $menu_id;
            break;
        }
    }
    set_theme_mod( 'nav_menu_locations', $locations );
}
