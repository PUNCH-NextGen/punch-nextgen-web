<?php
/**
 * Crack This Lite submissions and rendering.
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

function png_core_render_crack_this_single( $crack_id ) {
    $crack_id = absint( $crack_id );
    $deadline = png_core_get_post_meta_value( $crack_id, 'png_submission_deadline', '' );
    $reveal   = png_core_get_post_meta_value( $crack_id, 'png_reveal_date', '' );
    $now      = current_time( 'timestamp' );
    $revealed = $reveal ? strtotime( $reveal ) <= $now : false;

    ob_start();
    echo '<section class="png-core-box png-core-crack-this">';
    echo '<h2>' . esc_html( get_the_title( $crack_id ) ) . '</h2>';
    echo apply_filters( 'the_content', get_post_field( 'post_content', $crack_id ) );

    if ( has_post_thumbnail( $crack_id ) ) {
        echo get_the_post_thumbnail( $crack_id, 'large' );
    }

    if ( is_user_logged_in() ) {
        if ( png_core_user_submitted_crack( $crack_id, get_current_user_id() ) ) {
            echo '<p class="png-core-success">Your answer has been submitted.</p>';
        } elseif ( $deadline && strtotime( $deadline ) < $now ) {
            echo '<p class="png-core-muted">Submissions are closed.</p>';
        } else {
            echo '<form method="post" action="' . esc_url( admin_url( 'admin-post.php' ) ) . '" class="png-core-form">';
            wp_nonce_field( 'png_core_submit_crack_' . $crack_id, 'png_core_nonce' );
            echo '<input type="hidden" name="action" value="png_core_submit_crack" />';
            echo '<input type="hidden" name="crack_id" value="' . esc_attr( $crack_id ) . '" />';
            echo '<label>Your Answer <input type="text" name="answer" required /></label>';
            echo '<button class="png-core-button" type="submit">Submit Answer</button>';
            echo '</form>';
        }
    } else {
        echo '<p><a href="' . esc_url( wp_login_url( get_permalink( $crack_id ) ) ) . '">Log in</a> to submit an answer.</p>';
    }

    if ( $revealed ) {
        $answer = png_core_get_post_meta_value( $crack_id, 'png_correct_answer', '' );
        $explain = png_core_get_post_meta_value( $crack_id, 'png_answer_explanation', '' );
        echo '<div class="png-core-answer-reveal"><h3>Answer Reveal</h3>';
        if ( $answer ) {
            echo '<p><strong>Answer:</strong> ' . esc_html( $answer ) . '</p>';
        }
        if ( $explain ) {
            echo '<div>' . wpautop( wp_kses_post( $explain ) ) . '</div>';
        }
        echo '</div>';
    }

    echo '</section>';
    return ob_get_clean();
}

function png_core_user_submitted_crack( $crack_id, $user_id ) {
    global $wpdb;
    return (bool) $wpdb->get_var(
        $wpdb->prepare(
            'SELECT id FROM ' . png_core_get_table_name( 'crack_submissions' ) . ' WHERE crack_id = %d AND user_id = %d LIMIT 1',
            absint( $crack_id ),
            absint( $user_id )
        )
    );
}

function png_core_handle_submit_crack() {
    if ( ! is_user_logged_in() ) {
        wp_safe_redirect( wp_login_url() );
        exit;
    }

    $crack_id = isset( $_POST['crack_id'] ) ? absint( $_POST['crack_id'] ) : 0;
    if ( ! $crack_id || ! isset( $_POST['png_core_nonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['png_core_nonce'] ) ), 'png_core_submit_crack_' . $crack_id ) ) {
        wp_die( 'Invalid submission.' );
    }

    if ( png_core_user_submitted_crack( $crack_id, get_current_user_id() ) ) {
        wp_safe_redirect( get_permalink( $crack_id ) );
        exit;
    }

    $submitted = isset( $_POST['answer'] ) ? sanitize_text_field( wp_unslash( $_POST['answer'] ) ) : '';
    $correct   = png_core_get_post_meta_value( $crack_id, 'png_correct_answer', '' );
    $is_correct = $correct && strtolower( trim( $submitted ) ) === strtolower( trim( $correct ) );
    $settings = png_core_get_settings();
    $points = absint( $settings['points_crack_submit'] );
    if ( $is_correct ) {
        $points += absint( $settings['points_crack_correct'] );
    }

    global $wpdb;
    $wpdb->insert(
        png_core_get_table_name( 'crack_submissions' ),
        array(
            'crack_id'         => $crack_id,
            'user_id'          => get_current_user_id(),
            'school_id'        => png_core_get_user_school_id(),
            'submitted_answer' => $submitted,
            'is_correct'       => $is_correct ? 1 : 0,
            'points_awarded'   => $points,
            'submitted_at'     => current_time( 'mysql' ),
        ),
        array( '%d', '%d', '%d', '%s', '%d', '%d', '%s' )
    );

    png_core_add_points( get_current_user_id(), $points, $is_correct ? 'crack_this_correct' : 'crack_this_submit', $crack_id, 'Crack This Lite participation.' );

    wp_safe_redirect( get_permalink( $crack_id ) );
    exit;
}

function png_core_render_crack_this_archive() {
    $query = new WP_Query(
        array(
            'post_type'      => 'png_crack_this',
            'post_status'    => 'publish',
            'posts_per_page' => 12,
        )
    );

    ob_start();
    echo '<div class="png-core-grid">';
    if ( $query->have_posts() ) {
        while ( $query->have_posts() ) {
            $query->the_post();
            echo '<article class="png-core-card"><h3><a href="' . esc_url( get_permalink() ) . '">' . esc_html( get_the_title() ) . '</a></h3>';
            echo '<p>' . esc_html( get_the_excerpt() ) . '</p></article>';
        }
        wp_reset_postdata();
    } else {
        echo '<p>No Crack This Lite puzzle is available yet.</p>';
    }
    echo '</div>';
    return ob_get_clean();
}
