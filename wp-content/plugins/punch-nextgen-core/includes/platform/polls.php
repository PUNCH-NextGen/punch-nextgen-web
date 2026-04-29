<?php
/**
 * Poll rendering and voting.
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

function png_core_get_poll_options( $poll_id ) {
    $options = png_core_get_post_meta_value( $poll_id, 'png_poll_options', '' );
    if ( is_array( $options ) ) {
        return $options;
    }
    return png_core_sanitize_multiline_options( $options );
}

function png_core_get_linked_poll_for_post( $post_id ) {
    $poll_id = png_core_get_post_meta_value( $post_id, 'png_linked_poll', 0 );
    $poll_id = absint( $poll_id );
    return ( $poll_id && 'png_poll' === get_post_type( $poll_id ) ) ? $poll_id : 0;
}

function png_core_poll_user_has_voted( $poll_id, $post_id = 0 ) {
    global $wpdb;

    $table   = png_core_get_table_name( 'poll_votes' );
    $poll_id = absint( $poll_id );

    if ( is_user_logged_in() ) {
        return (bool) $wpdb->get_var(
            $wpdb->prepare( "SELECT id FROM {$table} WHERE poll_id = %d AND user_id = %d LIMIT 1", $poll_id, get_current_user_id() )
        );
    }

    $hash = png_core_poll_anon_hash();
    return (bool) $wpdb->get_var(
        $wpdb->prepare( "SELECT id FROM {$table} WHERE poll_id = %d AND anon_hash = %s LIMIT 1", $poll_id, $hash )
    );
}

function png_core_poll_anon_hash() {
    $ip = isset( $_SERVER['REMOTE_ADDR'] ) ? sanitize_text_field( wp_unslash( $_SERVER['REMOTE_ADDR'] ) ) : '';
    $ua = isset( $_SERVER['HTTP_USER_AGENT'] ) ? sanitize_text_field( wp_unslash( $_SERVER['HTTP_USER_AGENT'] ) ) : '';
    return wp_hash( $ip . '|' . $ua );
}

function png_core_render_story_poll( $post_id = 0 ) {
    $post_id = $post_id ? absint( $post_id ) : get_the_ID();
    $poll_id = png_core_get_linked_poll_for_post( $post_id );

    if ( ! $poll_id ) {
        return '';
    }

    return png_core_render_poll( $poll_id, $post_id );
}

function png_core_render_poll( $poll_id, $post_id = 0 ) {
    $poll_id  = absint( $poll_id );
    $post_id  = absint( $post_id );
    $question = png_core_get_post_meta_value( $poll_id, 'png_poll_question', get_the_title( $poll_id ) );
    $options  = png_core_get_poll_options( $poll_id );
    $voted    = png_core_poll_user_has_voted( $poll_id, $post_id );

    ob_start();
    echo '<section class="png-core-box png-core-poll" id="png-poll-' . esc_attr( $poll_id ) . '">';
    echo '<h2>' . esc_html( $question ) . '</h2>';

    if ( empty( $options ) ) {
        echo '<p>This poll has no options yet.</p>';
    } elseif ( $voted ) {
        png_core_render_poll_results( $poll_id );
    } else {
        echo '<form method="post" action="' . esc_url( admin_url( 'admin-post.php' ) ) . '">';
        wp_nonce_field( 'png_core_vote_poll_' . $poll_id, 'png_core_nonce' );
        echo '<input type="hidden" name="action" value="png_core_vote_poll" />';
        echo '<input type="hidden" name="poll_id" value="' . esc_attr( $poll_id ) . '" />';
        echo '<input type="hidden" name="post_id" value="' . esc_attr( $post_id ) . '" />';
        foreach ( $options as $index => $option ) {
            echo '<label class="png-core-choice"><input type="radio" name="option_key" value="' . esc_attr( $index ) . '" required /> <span>' . esc_html( $option ) . '</span></label>';
        }
        echo '<button class="png-core-button" type="submit">Vote</button>';
        echo '</form>';
    }

    echo '</section>';
    return ob_get_clean();
}

function png_core_render_poll_results( $poll_id ) {
    global $wpdb;

    $options = png_core_get_poll_options( $poll_id );
    $table   = png_core_get_table_name( 'poll_votes' );
    $total   = absint( $wpdb->get_var( $wpdb->prepare( "SELECT COUNT(*) FROM {$table} WHERE poll_id = %d", $poll_id ) ) );

    echo '<div class="png-core-poll-results">';
    foreach ( $options as $index => $option ) {
        $count   = absint( $wpdb->get_var( $wpdb->prepare( "SELECT COUNT(*) FROM {$table} WHERE poll_id = %d AND option_key = %s", $poll_id, (string) $index ) ) );
        $percent = $total ? round( ( $count / $total ) * 100 ) : 0;
        echo '<div class="png-core-result"><span>' . esc_html( $option ) . '</span><strong>' . esc_html( $percent ) . '%</strong><div class="png-core-result-bar"><i style="width:' . esc_attr( $percent ) . '%"></i></div></div>';
    }
    echo '<p class="png-core-muted">' . esc_html( $total ) . ' vote(s)</p>';
    echo '</div>';
}

function png_core_handle_vote_poll() {
    $poll_id = isset( $_POST['poll_id'] ) ? absint( $_POST['poll_id'] ) : 0;
    $post_id = isset( $_POST['post_id'] ) ? absint( $_POST['post_id'] ) : 0;

    if ( ! $poll_id || ! isset( $_POST['png_core_nonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['png_core_nonce'] ) ), 'png_core_vote_poll_' . $poll_id ) ) {
        wp_die( 'Invalid poll request.' );
    }

    if ( png_core_poll_user_has_voted( $poll_id, $post_id ) ) {
        wp_safe_redirect( wp_get_referer() ? wp_get_referer() : home_url() );
        exit;
    }

    $option_key = isset( $_POST['option_key'] ) ? sanitize_text_field( wp_unslash( $_POST['option_key'] ) ) : '';
    $options    = png_core_get_poll_options( $poll_id );

    if ( '' === $option_key || ! isset( $options[ absint( $option_key ) ] ) ) {
        wp_die( 'Invalid poll option.' );
    }

    global $wpdb;
    $wpdb->insert(
        png_core_get_table_name( 'poll_votes' ),
        array(
            'poll_id'    => $poll_id,
            'post_id'    => $post_id,
            'user_id'    => get_current_user_id(),
            'anon_hash'  => is_user_logged_in() ? '' : png_core_poll_anon_hash(),
            'option_key' => $option_key,
            'created_at' => current_time( 'mysql' ),
        ),
        array( '%d', '%d', '%d', '%s', '%s', '%s' )
    );

    if ( is_user_logged_in() ) {
        $settings = png_core_get_settings();
        png_core_add_points( get_current_user_id(), absint( $settings['points_poll_vote'] ), 'poll_vote', $poll_id, 'Poll participation.' );
    }

    wp_safe_redirect( wp_get_referer() ? wp_get_referer() : get_permalink( $post_id ) );
    exit;
}
