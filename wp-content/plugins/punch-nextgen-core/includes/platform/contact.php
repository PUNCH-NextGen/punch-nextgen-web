<?php
/**
 * Contact form storage and notification.
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

function png_core_render_contact_form() {
    ob_start();
    echo '<form class="png-core-form png-core-contact-form" method="post" action="' . esc_url( admin_url( 'admin-post.php' ) ) . '">';
    wp_nonce_field( 'png_core_contact_submit', 'png_core_nonce' );
    echo '<input type="hidden" name="action" value="png_core_contact_submit" />';
    echo '<input type="text" name="png_hp" value="" class="png-core-hp" tabindex="-1" autocomplete="off" />';
    echo '<label>Name <input type="text" name="name" required /></label>';
    echo '<label>Email <input type="email" name="email" required /></label>';
    echo '<label>Subject <input type="text" name="subject" required /></label>';
    echo '<label>Message <textarea name="message" rows="6" required></textarea></label>';
    echo '<input type="hidden" name="context_url" value="' . esc_url( home_url( add_query_arg( array(), $_SERVER['REQUEST_URI'] ?? '' ) ) ) . '" />';
    echo '<button class="png-core-button" type="submit">Send Message</button>';
    echo '</form>';
    return ob_get_clean();
}

function png_core_handle_contact_submit() {
    if ( ! isset( $_POST['png_core_nonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['png_core_nonce'] ) ), 'png_core_contact_submit' ) ) {
        wp_die( 'Invalid request.' );
    }

    if ( ! empty( $_POST['png_hp'] ) ) {
        wp_safe_redirect( home_url() );
        exit;
    }

    $name    = isset( $_POST['name'] ) ? sanitize_text_field( wp_unslash( $_POST['name'] ) ) : '';
    $email   = isset( $_POST['email'] ) ? sanitize_email( wp_unslash( $_POST['email'] ) ) : '';
    $subject = isset( $_POST['subject'] ) ? sanitize_text_field( wp_unslash( $_POST['subject'] ) ) : '';
    $message = isset( $_POST['message'] ) ? sanitize_textarea_field( wp_unslash( $_POST['message'] ) ) : '';
    $context = isset( $_POST['context_url'] ) ? esc_url_raw( wp_unslash( $_POST['context_url'] ) ) : '';

    global $wpdb;
    $wpdb->insert(
        png_core_get_table_name( 'contact_messages' ),
        array(
            'name'        => $name,
            'email'       => $email,
            'subject'     => $subject,
            'message'     => $message,
            'context_url' => $context,
            'status'      => 'new',
            'created_at'  => current_time( 'mysql' ),
        ),
        array( '%s', '%s', '%s', '%s', '%s', '%s', '%s' )
    );

    $settings = png_core_get_settings();
    wp_mail(
        sanitize_email( $settings['contact_recipient_email'] ),
        'Punch NextGen Contact: ' . $subject,
        "Name: {$name}\nEmail: {$email}\nContext: {$context}\n\n{$message}"
    );

    wp_safe_redirect( add_query_arg( 'png_contact', 'sent', wp_get_referer() ? wp_get_referer() : home_url() ) );
    exit;
}
