<?php
/**
 * Points, missions, and leaderboards.
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

function png_core_add_points( $user_id, $points, $source, $source_id = '', $notes = '', $period_key = '' ) {
    global $wpdb;

    $user_id = absint( $user_id );
    $points  = intval( $points );

    if ( ! $user_id || 0 === $points ) {
        return false;
    }

    $school_id = png_core_get_user_school_id( $user_id );
    $term_id   = png_core_get_current_term_id();
    $table     = png_core_get_table_name( 'points_ledger' );

    $inserted = $wpdb->insert(
        $table,
        array(
            'user_id'    => $user_id,
            'school_id'  => $school_id,
            'source'     => sanitize_key( $source ),
            'source_id'  => sanitize_text_field( (string) $source_id ),
            'points'     => $points,
            'term_id'    => $term_id,
            'period_key' => $period_key ? sanitize_text_field( $period_key ) : png_core_current_period_key( 'week' ),
            'notes'      => sanitize_textarea_field( $notes ),
            'created_at' => current_time( 'mysql' ),
        ),
        array( '%d', '%d', '%s', '%s', '%d', '%d', '%s', '%s', '%s' )
    );

    if ( $inserted ) {
        $total = absint( get_user_meta( $user_id, 'png_points_total', true ) );
        update_user_meta( $user_id, 'png_points_total', max( 0, $total + $points ) );
    }

    return (bool) $inserted;
}

function png_core_points_already_awarded( $user_id, $source, $source_id = '', $period_key = '' ) {
    global $wpdb;

    $table = png_core_get_table_name( 'points_ledger' );
    $sql   = "SELECT id FROM {$table} WHERE user_id = %d AND source = %s AND source_id = %s";
    $args  = array( absint( $user_id ), sanitize_key( $source ), sanitize_text_field( (string) $source_id ) );

    if ( $period_key ) {
        $sql   .= ' AND period_key = %s';
        $args[] = sanitize_text_field( $period_key );
    }

    $sql .= ' LIMIT 1';

    return (bool) $wpdb->get_var( $wpdb->prepare( $sql, $args ) );
}

function png_core_get_user_points_total( $user_id = 0 ) {
    $user_id = $user_id ? absint( $user_id ) : get_current_user_id();
    return absint( get_user_meta( $user_id, 'png_points_total', true ) );
}

function png_core_track_story_read() {
    if ( is_admin() || ! is_user_logged_in() || ! is_singular( 'post' ) ) {
        return;
    }

    global $wpdb, $post;

    if ( ! $post || empty( $post->ID ) ) {
        return;
    }

    $user_id   = get_current_user_id();
    $school_id = png_core_get_user_school_id( $user_id );
    $date      = png_core_current_period_key( 'day' );
    $week      = png_core_current_period_key( 'week' );
    $table     = png_core_get_table_name( 'story_reads' );

    $wpdb->query(
        $wpdb->prepare(
            "INSERT IGNORE INTO {$table} (user_id, post_id, school_id, read_date, read_week, created_at) VALUES (%d, %d, %d, %s, %s, %s)",
            $user_id,
            absint( $post->ID ),
            $school_id,
            $date,
            $week,
            current_time( 'mysql' )
        )
    );

    png_core_check_reading_missions( $user_id, $date, $week );
}

function png_core_check_reading_missions( $user_id, $date, $week ) {
    global $wpdb;

    $settings = png_core_get_settings();
    $table    = png_core_get_table_name( 'story_reads' );

    $daily_count = absint(
        $wpdb->get_var(
            $wpdb->prepare( "SELECT COUNT(*) FROM {$table} WHERE user_id = %d AND read_date = %s", $user_id, $date )
        )
    );

    if ( $daily_count >= absint( $settings['reading_mission_daily_threshold'] ) && ! png_core_points_already_awarded( $user_id, 'read_3_today', $date, $date ) ) {
        png_core_add_points( $user_id, absint( $settings['points_read_3_today'] ), 'read_3_today', $date, 'Read 3 stories today.', $date );
    }

    $weekly_count = absint(
        $wpdb->get_var(
            $wpdb->prepare( "SELECT COUNT(*) FROM {$table} WHERE user_id = %d AND read_week = %s", $user_id, $week )
        )
    );

    if ( $weekly_count >= 3 && ! png_core_points_already_awarded( $user_id, 'read_3_week', $week, $week ) ) {
        png_core_add_points( $user_id, absint( $settings['points_read_3_week'] ), 'read_3_week', $week, 'Read 3 stories this week.', $week );
    }

    if ( $weekly_count >= absint( $settings['reading_mission_weekly_threshold'] ) && ! png_core_points_already_awarded( $user_id, 'read_6_week', $week, $week ) ) {
        png_core_add_points( $user_id, absint( $settings['points_read_6_week'] ), 'read_6_week', $week, 'Read 6 stories this week.', $week );
    }
}

function png_core_get_leaderboard( $type = 'user', $period = 'week', $limit = 10 ) {
    global $wpdb;

    $table = png_core_get_table_name( 'points_ledger' );
    $where = '1=1';
    $args  = array();

    if ( 'week' === $period ) {
        $where .= ' AND period_key = %s';
        $args[] = png_core_current_period_key( 'week' );
    } elseif ( 'term' === $period ) {
        $term_id = png_core_get_current_term_id();
        if ( $term_id ) {
            $where .= ' AND term_id = %d';
            $args[] = $term_id;
        }
    }

    if ( 'school' === $type ) {
        $where .= ' AND school_id > 0';
        $sql = "SELECT school_id as object_id, SUM(points) as total_points FROM {$table} WHERE {$where} GROUP BY school_id ORDER BY total_points DESC LIMIT %d";
    } else {
        $sql = "SELECT user_id as object_id, SUM(points) as total_points FROM {$table} WHERE {$where} GROUP BY user_id ORDER BY total_points DESC LIMIT %d";
    }

    $args[] = absint( $limit );

    return $wpdb->get_results( $wpdb->prepare( $sql, $args ) );
}

function png_core_render_leaderboard_table( $type = 'user', $period = 'week', $limit = 10 ) {
    $items = png_core_get_leaderboard( $type, $period, $limit );
    echo '<div class="png-core-table-wrap"><table class="png-core-table"><thead><tr><th>Rank</th><th>' . ( 'school' === $type ? 'School' : 'User' ) . '</th><th>Points</th></tr></thead><tbody>';
    if ( empty( $items ) ) {
        echo '<tr><td colspan="3">No points recorded yet.</td></tr>';
    } else {
        $rank = 1;
        foreach ( $items as $item ) {
            if ( 'school' === $type ) {
                $name = get_the_title( absint( $item->object_id ) );
            } else {
                $user = get_userdata( absint( $item->object_id ) );
                $name = $user ? $user->display_name : 'Unknown user';
            }
            echo '<tr><td>' . esc_html( $rank ) . '</td><td>' . esc_html( $name ) . '</td><td>' . esc_html( absint( $item->total_points ) ) . '</td></tr>';
            $rank++;
        }
    }
    echo '</tbody></table></div>';
}
