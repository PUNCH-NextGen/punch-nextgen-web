<?php
/**
 * Admin-post/public form actions.
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

function png_core_register_admin_post_actions() {
    add_action( 'admin_post_png_core_vote_poll', 'png_core_handle_vote_poll' );
    add_action( 'admin_post_nopriv_png_core_vote_poll', 'png_core_handle_vote_poll' );
    add_action( 'admin_post_png_core_submit_crack', 'png_core_handle_submit_crack' );
    add_action( 'admin_post_png_core_contact_submit', 'png_core_handle_contact_submit' );
    add_action( 'admin_post_nopriv_png_core_contact_submit', 'png_core_handle_contact_submit' );
    add_action( 'admin_post_png_core_download_guide', 'png_core_handle_download_guide' );
    add_action( 'admin_post_png_core_update_profile', 'png_core_handle_update_profile' );
    add_action( 'admin_post_nopriv_png_core_register_student', 'png_core_handle_register_student' );
    add_action( 'admin_post_png_core_save_settings', 'png_core_handle_save_settings' );
    add_action( 'admin_post_png_core_save_points', 'png_core_handle_save_points' );
    add_action( 'admin_post_png_core_create_teacher', 'png_core_handle_create_teacher' );
}

function png_core_handle_public_post_actions() {
    // Placeholder for future REST-free lightweight actions.
}

function png_core_handle_update_profile() {
    if ( ! is_user_logged_in() || ! isset( $_POST['png_core_nonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['png_core_nonce'] ) ), 'png_core_update_profile' ) ) {
        wp_die( 'Invalid profile update.' );
    }

    $user_id      = get_current_user_id();
    $display_name = isset( $_POST['display_name'] ) ? sanitize_text_field( wp_unslash( $_POST['display_name'] ) ) : '';
    $school_id    = isset( $_POST['school_id'] ) ? absint( $_POST['school_id'] ) : 0;

    if ( $display_name ) {
        wp_update_user( array( 'ID' => $user_id, 'display_name' => $display_name ) );
    }

    $current_school = png_core_get_user_school_id( $user_id );
    if ( $school_id !== $current_school ) {
        update_user_meta( $user_id, 'png_school_id', $school_id );
        update_user_meta( $user_id, 'png_last_school_change_term_id', png_core_get_active_term_id() );
    }

    wp_safe_redirect( wp_get_referer() ? wp_get_referer() : home_url() );
    exit;
}

function png_core_handle_register_student() {
    $settings = png_core_get_settings();
    if ( empty( $settings['public_registration_enabled'] ) ) {
        wp_die( 'Registration is currently closed.' );
    }

    if ( ! isset( $_POST['png_core_nonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['png_core_nonce'] ) ), 'png_core_register_student' ) ) {
        wp_die( 'Invalid registration.' );
    }

    $login = isset( $_POST['user_login'] ) ? sanitize_user( wp_unslash( $_POST['user_login'] ) ) : '';
    $email = isset( $_POST['user_email'] ) ? sanitize_email( wp_unslash( $_POST['user_email'] ) ) : '';
    $pass  = isset( $_POST['user_pass'] ) ? (string) wp_unslash( $_POST['user_pass'] ) : '';

    if ( ! $login || ! $email || ! $pass ) {
        wp_die( 'All fields are required.' );
    }

    $user_id = wp_create_user( $login, $pass, $email );
    if ( is_wp_error( $user_id ) ) {
        wp_die( esc_html( $user_id->get_error_message() ) );
    }

    $user = new WP_User( $user_id );
    $user->set_role( 'png_student' );
    wp_set_current_user( $user_id );
    wp_set_auth_cookie( $user_id );

    wp_safe_redirect( home_url( '/my-profile/' ) );
    exit;
}

function png_core_handle_save_settings() {
    if ( ! current_user_can( 'manage_options' ) || ! isset( $_POST['png_core_nonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['png_core_nonce'] ) ), 'png_core_save_settings' ) ) {
        wp_die( 'You cannot save these settings.' );
    }

    $settings = png_core_get_settings();
    $keys = array( 'current_term_id', 'holiday_mode_enabled', 'school_change_policy', 'default_comic_id', 'ads_enabled', 'contact_recipient_email', 'blocked_words', 'public_registration_enabled' );
    foreach ( $keys as $key ) {
        if ( isset( $_POST[ $key ] ) ) {
            $settings[ $key ] = is_array( $_POST[ $key ] ) ? array_map( 'sanitize_text_field', wp_unslash( $_POST[ $key ] ) ) : sanitize_textarea_field( wp_unslash( $_POST[ $key ] ) );
        } else {
            if ( in_array( $key, array( 'holiday_mode_enabled', 'ads_enabled', 'public_registration_enabled' ), true ) ) {
                $settings[ $key ] = 0;
            }
        }
    }

    png_core_update_settings( $settings );
    wp_safe_redirect( add_query_arg( 'updated', '1', wp_get_referer() ) );
    exit;
}

function png_core_handle_save_points() {
    if ( ! current_user_can( 'png_manage_points' ) || ! isset( $_POST['png_core_nonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['png_core_nonce'] ) ), 'png_core_save_points' ) ) {
        wp_die( 'You cannot save point rules.' );
    }

    $settings = png_core_get_settings();
    $keys = array( 'reading_mission_daily_threshold', 'reading_mission_weekly_threshold', 'points_read_3_today', 'points_read_3_week', 'points_read_6_week', 'points_poll_vote', 'points_crack_submit', 'points_crack_correct', 'points_comment', 'comment_points_daily_limit', 'school_top_contributors_limit' );
    foreach ( $keys as $key ) {
        if ( isset( $_POST[ $key ] ) ) {
            $settings[ $key ] = absint( $_POST[ $key ] );
        }
    }
    png_core_update_settings( $settings );
    wp_safe_redirect( add_query_arg( 'updated', '1', wp_get_referer() ) );
    exit;
}

function png_core_handle_create_teacher() {
    if ( ! current_user_can( 'png_manage_teachers' ) || ! isset( $_POST['png_core_nonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['png_core_nonce'] ) ), 'png_core_create_teacher' ) ) {
        wp_die( 'You cannot create teachers.' );
    }

    $email     = isset( $_POST['teacher_email'] ) ? sanitize_email( wp_unslash( $_POST['teacher_email'] ) ) : '';
    $name      = isset( $_POST['teacher_name'] ) ? sanitize_text_field( wp_unslash( $_POST['teacher_name'] ) ) : '';
    $school_id = isset( $_POST['school_id'] ) ? absint( $_POST['school_id'] ) : 0;

    if ( ! $email || ! is_email( $email ) ) {
        wp_die( 'A valid teacher email is required.' );
    }

    $login = sanitize_user( current( explode( '@', $email ) ) );
    if ( username_exists( $login ) ) {
        $login .= '_' . wp_generate_password( 4, false, false );
    }

    $password = wp_generate_password( 16, true, true );
    $user_id  = wp_create_user( $login, $password, $email );
    if ( is_wp_error( $user_id ) ) {
        wp_die( esc_html( $user_id->get_error_message() ) );
    }

    wp_update_user( array( 'ID' => $user_id, 'display_name' => $name ? $name : $login ) );
    $user = new WP_User( $user_id );
    $user->set_role( 'png_teacher' );
    update_user_meta( $user_id, 'png_school_id', $school_id );
    update_user_meta( $user_id, 'png_teacher_status', 'active' );
    update_user_meta( $user_id, 'png_teacher_created_by', get_current_user_id() );

    wp_new_user_notification( $user_id, null, 'both' );
    wp_safe_redirect( add_query_arg( 'teacher_created', '1', wp_get_referer() ) );
    exit;
}
