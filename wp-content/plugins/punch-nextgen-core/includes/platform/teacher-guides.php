<?php
/**
 * Teacher guide portal and downloads.
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

function png_core_render_teacher_portal() {
    if ( ! is_user_logged_in() ) {
        return '<div class="png-core-box"><p>Please log in to access the teacher guide portal.</p>' . wp_login_form( array( 'echo' => false ) ) . '</div>';
    }

    if ( ! png_core_user_can_access_teacher_portal() ) {
        return '<div class="png-core-box"><h2>Access not active</h2><p>Your teacher access is not active. Please confirm your school subscription status with the Punch NextGen administrator.</p></div>';
    }

    $query = new WP_Query(
        array(
            'post_type'      => 'png_teacher_guide',
            'post_status'    => 'publish',
            'posts_per_page' => 20,
        )
    );

    ob_start();
    echo '<div class="png-core-grid png-core-teacher-guides">';
    if ( $query->have_posts() ) {
        while ( $query->have_posts() ) {
            $query->the_post();
            $guide_id = get_the_ID();
            echo '<article class="png-core-card">';
            if ( has_post_thumbnail() ) {
                echo get_the_post_thumbnail( $guide_id, 'medium' );
            }
            echo '<h3>' . esc_html( get_the_title() ) . '</h3>';
            $summary = png_core_get_post_meta_value( $guide_id, 'png_guide_summary', get_the_excerpt() );
            if ( $summary ) {
                echo '<p>' . esc_html( wp_trim_words( $summary, 24 ) ) . '</p>';
            }
            echo '<a class="png-core-button" href="' . esc_url( get_permalink() ) . '">View Guide</a> ';
            $file_id = absint( png_core_get_post_meta_value( $guide_id, 'png_guide_pdf', 0 ) );
            if ( $file_id ) {
                $url = wp_nonce_url( admin_url( 'admin-post.php?action=png_core_download_guide&guide_id=' . $guide_id ), 'png_core_download_guide_' . $guide_id, 'png_core_nonce' );
                echo '<a class="png-core-button png-core-button-secondary" href="' . esc_url( $url ) . '">Download Pack</a>';
            }
            echo '</article>';
        }
        wp_reset_postdata();
    } else {
        echo '<p>No teacher guides are available yet.</p>';
    }
    echo '</div>';
    return ob_get_clean();
}

function png_core_render_teacher_guide_single( $guide_id ) {
    $guide_id = absint( $guide_id );

    if ( ! png_core_user_can_access_teacher_portal() ) {
        return '<div class="png-core-box"><p>This teacher guide requires an active school subscription.</p></div>';
    }

    ob_start();
    echo '<article class="png-core-box png-core-teacher-guide-single">';
    echo '<h1>' . esc_html( get_the_title( $guide_id ) ) . '</h1>';
    echo apply_filters( 'the_content', get_post_field( 'post_content', $guide_id ) );

    $fields = array(
        'png_guide_summary'    => 'Summary',
        'png_guide_key_points' => 'Key Points',
        'png_guide_questions'  => 'Discussion Questions',
        'png_guide_activity'   => 'Classroom Activity',
        'png_guide_answer_key' => 'Answer Key',
    );

    foreach ( $fields as $key => $label ) {
        $value = png_core_get_post_meta_value( $guide_id, $key, '' );
        if ( $value ) {
            echo '<h2>' . esc_html( $label ) . '</h2>';
            echo wpautop( wp_kses_post( $value ) );
        }
    }

    $file_id = absint( png_core_get_post_meta_value( $guide_id, 'png_guide_pdf', 0 ) );
    if ( $file_id ) {
        $url = wp_nonce_url( admin_url( 'admin-post.php?action=png_core_download_guide&guide_id=' . $guide_id ), 'png_core_download_guide_' . $guide_id, 'png_core_nonce' );
        echo '<p><a class="png-core-button" href="' . esc_url( $url ) . '">Download Teacher Pack</a></p>';
    }

    echo '</article>';
    return ob_get_clean();
}

function png_core_handle_download_guide() {
    $guide_id = isset( $_GET['guide_id'] ) ? absint( $_GET['guide_id'] ) : 0;
    if ( ! $guide_id || ! isset( $_GET['png_core_nonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_GET['png_core_nonce'] ) ), 'png_core_download_guide_' . $guide_id ) ) {
        wp_die( 'Invalid download request.' );
    }

    if ( ! png_core_user_can_access_teacher_portal() ) {
        wp_die( 'You do not have access to this download.' );
    }

    $file_id = absint( png_core_get_post_meta_value( $guide_id, 'png_guide_pdf', 0 ) );
    $url     = $file_id ? wp_get_attachment_url( $file_id ) : '';

    if ( ! $url ) {
        wp_die( 'Download file not found.' );
    }

    global $wpdb;
    $wpdb->insert(
        png_core_get_table_name( 'teacher_downloads' ),
        array(
            'guide_id'      => $guide_id,
            'user_id'       => get_current_user_id(),
            'school_id'     => png_core_get_user_school_id(),
            'file_id'       => $file_id,
            'downloaded_at' => current_time( 'mysql' ),
        ),
        array( '%d', '%d', '%d', '%d', '%s' )
    );

    wp_safe_redirect( $url );
    exit;
}
