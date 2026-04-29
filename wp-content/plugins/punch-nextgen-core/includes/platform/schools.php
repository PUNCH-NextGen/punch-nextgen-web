<?php
/**
 * Schools, terms, and subscription access helpers.
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

function png_core_get_active_term_id() {
    $current = png_core_get_current_term_id();
    if ( $current ) {
        return $current;
    }

    $today = current_time( 'Y-m-d' );
    $terms = get_posts(
        array(
            'post_type'      => 'png_term',
            'posts_per_page' => 1,
            'post_status'    => 'publish',
            'meta_query'     => array(
                array(
                    'key'     => 'png_start_date',
                    'value'   => $today,
                    'compare' => '<=',
                    'type'    => 'DATE',
                ),
                array(
                    'key'     => 'png_end_date',
                    'value'   => $today,
                    'compare' => '>=',
                    'type'    => 'DATE',
                ),
            ),
        )
    );

    return $terms ? absint( $terms[0]->ID ) : 0;
}

function png_core_school_has_active_subscription( $school_id, $term_id = 0 ) {
    $school_id = absint( $school_id );
    $term_id   = $term_id ? absint( $term_id ) : png_core_get_active_term_id();

    if ( ! $school_id ) {
        return false;
    }

    $today = current_time( 'Y-m-d' );

    $meta_query = array(
        'relation' => 'AND',
        array(
            'key'     => 'png_subscription_school',
            'value'   => $school_id,
            'compare' => '=',
        ),
        array(
            'key'     => 'png_subscription_status',
            'value'   => array( 'active', 'manual_override' ),
            'compare' => 'IN',
        ),
        array(
            'key'     => 'png_start_date',
            'value'   => $today,
            'compare' => '<=',
            'type'    => 'DATE',
        ),
        array(
            'key'     => 'png_end_date',
            'value'   => $today,
            'compare' => '>=',
            'type'    => 'DATE',
        ),
    );

    if ( $term_id ) {
        $meta_query[] = array(
            'key'     => 'png_subscription_term',
            'value'   => $term_id,
            'compare' => '=',
        );
    }

    $query = new WP_Query(
        array(
            'post_type'      => 'png_subscription',
            'post_status'    => 'publish',
            'posts_per_page' => 1,
            'fields'         => 'ids',
            'meta_query'     => $meta_query,
        )
    );

    return $query->have_posts();
}

function png_core_user_can_access_teacher_portal( $user_id = 0 ) {
    $user_id = $user_id ? absint( $user_id ) : get_current_user_id();

    if ( ! $user_id ) {
        return false;
    }

    if ( user_can( $user_id, 'manage_options' ) || user_can( $user_id, 'png_manage_subscriptions' ) ) {
        return true;
    }

    if ( ! png_core_user_has_role( $user_id, 'png_teacher' ) && ! user_can( $user_id, 'png_access_teacher_portal' ) ) {
        return false;
    }

    $school_id = png_core_get_user_school_id( $user_id );
    return png_core_school_has_active_subscription( $school_id );
}

function png_core_get_schools_for_select() {
    $schools = get_posts(
        array(
            'post_type'      => 'png_school',
            'post_status'    => 'publish',
            'posts_per_page' => 300,
            'orderby'        => 'title',
            'order'          => 'ASC',
        )
    );

    $options = array();
    foreach ( $schools as $school ) {
        $options[ $school->ID ] = $school->post_title;
    }

    return $options;
}
