<?php
/**
 * Comment moderation helpers and optional comment participation points.
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

function png_core_filter_blocked_words( $commentdata ) {
    $settings = png_core_get_settings();
    $blocked  = png_core_sanitize_multiline_options( $settings['blocked_words'] );

    if ( empty( $blocked ) || empty( $commentdata['comment_content'] ) ) {
        return $commentdata;
    }

    foreach ( $blocked as $word ) {
        if ( false !== stripos( $commentdata['comment_content'], $word ) ) {
            wp_die( 'Your comment contains a blocked word and cannot be submitted.' );
        }
    }

    return $commentdata;
}

function png_core_comment_posted( $comment_id, $comment_approved, $commentdata ) {
    if ( empty( $commentdata['user_id'] ) ) {
        return;
    }

    $settings = png_core_get_settings();
    $user_id  = absint( $commentdata['user_id'] );
    $day      = png_core_current_period_key( 'day' );
    $source_id = $day . ':' . absint( $comment_id );

    if ( absint( $settings['points_comment'] ) < 1 ) {
        return;
    }

    global $wpdb;
    $table = png_core_get_table_name( 'points_ledger' );
    $count_today = absint(
        $wpdb->get_var(
            $wpdb->prepare( "SELECT COUNT(*) FROM {$table} WHERE user_id = %d AND source = 'comment_participation' AND period_key = %s", $user_id, $day )
        )
    );

    if ( $count_today >= absint( $settings['comment_points_daily_limit'] ) ) {
        return;
    }

    png_core_add_points( $user_id, absint( $settings['points_comment'] ), 'comment_participation', $source_id, 'Comment participation.', $day );
}
