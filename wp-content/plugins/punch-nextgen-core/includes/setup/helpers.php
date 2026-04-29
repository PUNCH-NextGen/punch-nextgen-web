<?php
/**
 * Shared helpers for Punch NextGen Core.
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

function png_core_get_settings() {
    $defaults = array(
        'current_term_id'                  => 0,
        'holiday_mode_enabled'             => 0,
        'school_change_policy'             => 'once_per_term',
        'reading_mission_daily_threshold'  => 3,
        'reading_mission_weekly_threshold' => 6,
        'points_read_3_today'              => 5,
        'points_read_3_week'               => 5,
        'points_read_6_week'               => 10,
        'points_poll_vote'                 => 2,
        'points_crack_submit'              => 3,
        'points_crack_correct'             => 10,
        'points_comment'                   => 1,
        'comment_points_daily_limit'       => 3,
        'school_top_contributors_limit'    => 30,
        'default_comic_id'                 => 0,
        'ads_enabled'                      => 1,
        'contact_recipient_email'          => get_option( 'admin_email' ),
        'blocked_words'                    => '',
        'public_registration_enabled'      => 1,
    );

    $saved = get_option( 'png_core_settings', array() );
    return wp_parse_args( is_array( $saved ) ? $saved : array(), $defaults );
}

function png_core_update_settings( $settings ) {
    $current = png_core_get_settings();
    update_option( 'png_core_settings', wp_parse_args( $settings, $current ) );
}

function png_core_get_table_name( $suffix ) {
    global $wpdb;
    return $wpdb->prefix . 'png_' . sanitize_key( $suffix );
}

function png_core_current_period_key( $type = 'week' ) {
    $timestamp = current_time( 'timestamp' );

    if ( 'day' === $type ) {
        return wp_date( 'Y-m-d', $timestamp );
    }

    if ( 'month' === $type ) {
        return wp_date( 'Y-m', $timestamp );
    }

    return wp_date( 'o-\WW', $timestamp );
}

function png_core_get_user_school_id( $user_id = 0 ) {
    $user_id = $user_id ? absint( $user_id ) : get_current_user_id();
    return absint( get_user_meta( $user_id, 'png_school_id', true ) );
}

function png_core_user_has_role( $user_id, $role ) {
    $user = get_userdata( $user_id );
    if ( ! $user ) {
        return false;
    }
    return in_array( $role, (array) $user->roles, true );
}

function png_core_get_post_meta_value( $post_id, $key, $default = '' ) {
    if ( function_exists( 'get_field' ) ) {
        $value = get_field( $key, $post_id );
        if ( null !== $value && '' !== $value ) {
            return $value;
        }
    }

    $value = get_post_meta( $post_id, $key, true );
    return ( '' === $value || null === $value ) ? $default : $value;
}

function png_core_sanitize_multiline_options( $value ) {
    $lines   = preg_split( '/\r\n|\r|\n/', (string) $value );
    $options = array();

    foreach ( $lines as $line ) {
        $line = trim( wp_strip_all_tags( $line ) );
        if ( '' !== $line ) {
            $options[] = $line;
        }
    }

    return $options;
}

function png_core_admin_url( $slug ) {
    return admin_url( 'admin.php?page=' . sanitize_key( $slug ) );
}

function png_core_notice( $message, $type = 'success' ) {
    $type = in_array( $type, array( 'success', 'error', 'warning', 'info' ), true ) ? $type : 'success';
    printf(
        '<div class="notice notice-%1$s is-dismissible"><p>%2$s</p></div>',
        esc_attr( $type ),
        wp_kses_post( $message )
    );
}

function png_core_count_posts( $post_type ) {
    $counts = wp_count_posts( $post_type );
    return $counts && isset( $counts->publish ) ? absint( $counts->publish ) : 0;
}

function png_core_is_truthy( $value ) {
    return in_array( $value, array( true, 1, '1', 'yes', 'true', 'on' ), true );
}
