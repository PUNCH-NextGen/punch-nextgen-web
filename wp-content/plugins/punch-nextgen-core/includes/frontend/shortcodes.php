<?php
/**
 * Public shortcodes.
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

function png_core_register_shortcodes() {
    add_shortcode( 'png_profile', 'png_core_shortcode_profile' );
    add_shortcode( 'png_leaderboards', 'png_core_shortcode_leaderboards' );
    add_shortcode( 'png_teacher_portal', 'png_core_shortcode_teacher_portal' );
    add_shortcode( 'png_contact_form', 'png_core_shortcode_contact_form' );
    add_shortcode( 'png_school_showcase', 'png_core_shortcode_school_showcase' );
    add_shortcode( 'png_crack_this_archive', 'png_core_shortcode_crack_this_archive' );
}

function png_core_shortcode_teacher_portal() {
    return png_core_render_teacher_portal();
}

function png_core_shortcode_contact_form() {
    return png_core_render_contact_form();
}

function png_core_shortcode_crack_this_archive() {
    return png_core_render_crack_this_archive();
}

function png_core_shortcode_leaderboards() {
    ob_start();
    echo '<div class="png-core-leaderboards">';
    echo '<section class="png-core-box"><h2>Top Users This Week</h2>';
    png_core_render_leaderboard_table( 'user', 'week', 10 );
    echo '</section>';
    echo '<section class="png-core-box"><h2>Top Schools This Week</h2>';
    png_core_render_leaderboard_table( 'school', 'week', 10 );
    echo '</section>';
    echo '<section class="png-core-box"><h2>All-Time Top Users</h2>';
    png_core_render_leaderboard_table( 'user', 'all', 10 );
    echo '</section>';
    echo '</div>';
    return ob_get_clean();
}

function png_core_shortcode_school_showcase() {
    $query = new WP_Query(
        array(
            'post_type'      => 'png_school',
            'post_status'    => 'publish',
            'posts_per_page' => 6,
        )
    );

    ob_start();
    echo '<div class="png-core-grid">';
    if ( $query->have_posts() ) {
        while ( $query->have_posts() ) {
            $query->the_post();
            echo '<article class="png-core-card">';
            if ( has_post_thumbnail() ) {
                echo get_the_post_thumbnail( get_the_ID(), 'medium' );
            }
            echo '<h3>' . esc_html( get_the_title() ) . '</h3>';
            echo '<p>' . esc_html( wp_trim_words( get_the_excerpt(), 24 ) ) . '</p>';
            echo '</article>';
        }
        wp_reset_postdata();
    } else {
        echo '<p>No school showcase item has been published yet.</p>';
    }
    echo '</div>';
    return ob_get_clean();
}

function png_core_shortcode_profile() {
    if ( ! is_user_logged_in() ) {
        return png_core_render_auth_panel();
    }

    $user      = wp_get_current_user();
    $school_id = png_core_get_user_school_id( $user->ID );
    $schools   = png_core_get_schools_for_select();

    ob_start();
    echo '<div class="png-core-profile">';
    echo '<section class="png-core-box"><h2>My Profile</h2>';
    echo '<p><strong>Name:</strong> ' . esc_html( $user->display_name ) . '</p>';
    echo '<p><strong>Email:</strong> ' . esc_html( $user->user_email ) . '</p>';
    echo '<p><strong>Points:</strong> ' . esc_html( png_core_get_user_points_total( $user->ID ) ) . '</p>';
    echo '<form class="png-core-form" method="post" action="' . esc_url( admin_url( 'admin-post.php' ) ) . '">';
    wp_nonce_field( 'png_core_update_profile', 'png_core_nonce' );
    echo '<input type="hidden" name="action" value="png_core_update_profile" />';
    echo '<label>Display Name <input type="text" name="display_name" value="' . esc_attr( $user->display_name ) . '" required /></label>';
    echo '<label>School <select name="school_id"><option value="0">No school / skip for now</option>';
    foreach ( $schools as $id => $name ) {
        echo '<option value="' . esc_attr( $id ) . '" ' . selected( $school_id, $id, false ) . '>' . esc_html( $name ) . '</option>';
    }
    echo '</select></label>';
    echo '<button type="submit" class="png-core-button">Save Profile</button>';
    echo '</form>';
    echo '</section>';

    if ( png_core_user_has_role( $user->ID, 'png_teacher' ) ) {
        echo '<section class="png-core-box"><h2>Teacher Access</h2>';
        echo png_core_user_can_access_teacher_portal( $user->ID ) ? '<p class="png-core-success">Teacher portal access is active.</p>' : '<p class="png-core-warning">Teacher portal access is not currently active.</p>';
        echo '</section>';
    }

    echo '</div>';
    return ob_get_clean();
}

function png_core_render_auth_panel() {
    ob_start();
    echo '<div class="png-core-auth-grid">';
    echo '<section class="png-core-box"><h2>Log In</h2>';
    wp_login_form();
    echo '<p><a href="' . esc_url( wp_lostpassword_url() ) . '">Forgot password?</a></p>';
    echo '</section>';

    $settings = png_core_get_settings();
    if ( ! empty( $settings['public_registration_enabled'] ) ) {
        echo '<section class="png-core-box"><h2>Create Student Account</h2>';
        echo '<form class="png-core-form" method="post" action="' . esc_url( admin_url( 'admin-post.php' ) ) . '">';
        wp_nonce_field( 'png_core_register_student', 'png_core_nonce' );
        echo '<input type="hidden" name="action" value="png_core_register_student" />';
        echo '<label>Username <input type="text" name="user_login" required /></label>';
        echo '<label>Email <input type="email" name="user_email" required /></label>';
        echo '<label>Password <input type="password" name="user_pass" required /></label>';
        echo '<button type="submit" class="png-core-button">Create Account</button>';
        echo '</form>';
        echo '</section>';
    }
    echo '</div>';
    return ob_get_clean();
}

/**
 * Theme-friendly wrappers. These let the active theme call plugin renderers
 * without caring whether output is shortcode-based or direct.
 */
function png_core_render_profile_page() {
    echo png_core_shortcode_profile(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
}

function png_core_render_leaderboards_page() {
    echo png_core_shortcode_leaderboards(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
}

function png_core_render_contact_form_wrapper() {
    echo png_core_render_contact_form(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
}

function png_core_render_leaderboard_preview() {
    echo '<div class="png-core-grid">';
    echo '<div class="png-core-card"><h3>Top Users</h3>';
    png_core_render_leaderboard_table( 'user', 'week', 5 );
    echo '</div><div class="png-core-card"><h3>Top Schools</h3>';
    png_core_render_leaderboard_table( 'school', 'week', 5 );
    echo '</div></div>';
}

function png_core_render_crack_this_preview() {
    $items = get_posts(
        array(
            'post_type'      => 'png_crack_this',
            'post_status'    => 'publish',
            'posts_per_page' => 1,
        )
    );

    if ( empty( $items ) ) {
        echo '<div class="png-core-card"><h3>Crack This Lite</h3><p>No puzzle has been published yet.</p></div>';
        return;
    }

    $item = $items[0];
    echo '<div class="png-core-card"><h3>' . esc_html( $item->post_title ) . '</h3><p>' . esc_html( wp_trim_words( $item->post_excerpt ? $item->post_excerpt : $item->post_content, 24 ) ) . '</p><a class="png-core-button" href="' . esc_url( get_permalink( $item ) ) . '">Try it</a></div>';
}
