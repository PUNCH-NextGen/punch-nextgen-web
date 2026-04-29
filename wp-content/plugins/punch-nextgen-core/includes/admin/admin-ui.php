<?php
/**
 * Admin UI helpers.
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

function png_core_enqueue_admin_assets( $hook ) {
    if ( false === strpos( (string) $hook, 'png-core' ) && false === strpos( (string) $hook, 'png_' ) ) {
        return;
    }

    wp_enqueue_style( 'png-core-admin', PNG_CORE_URL . 'assets/css/admin.css', array(), PNG_CORE_VERSION );
    wp_enqueue_script( 'png-core-admin', PNG_CORE_URL . 'assets/js/admin.js', array(), PNG_CORE_VERSION, true );
}

function png_core_admin_wrap_start( $title, $description = '' ) {
    echo '<div class="wrap png-core-admin-wrap">';
    echo '<div class="png-core-admin-hero"><div><h1>' . esc_html( $title ) . '</h1>';
    if ( $description ) {
        echo '<p>' . esc_html( $description ) . '</p>';
    }
    echo '</div></div>';
}

function png_core_admin_wrap_end() {
    echo '</div>';
}

function png_core_admin_card( $title, $value, $description = '', $url = '' ) {
    echo '<div class="png-core-admin-card">';
    echo '<h3>' . esc_html( $title ) . '</h3>';
    echo '<strong>' . esc_html( $value ) . '</strong>';
    if ( $description ) {
        echo '<p>' . esc_html( $description ) . '</p>';
    }
    if ( $url ) {
        echo '<a class="button button-secondary" href="' . esc_url( $url ) . '">Manage</a>';
    }
    echo '</div>';
}

function png_core_admin_cards_start() {
    echo '<div class="png-core-admin-cards">';
}

function png_core_admin_cards_end() {
    echo '</div>';
}

function png_core_admin_section_start( $title, $description = '' ) {
    echo '<section class="png-core-admin-section"><h2>' . esc_html( $title ) . '</h2>';
    if ( $description ) {
        echo '<p>' . esc_html( $description ) . '</p>';
    }
}

function png_core_admin_section_end() {
    echo '</section>';
}

function png_core_admin_status_badge( $text, $type = 'neutral' ) {
    echo '<span class="png-core-status png-core-status-' . esc_attr( sanitize_html_class( $type ) ) . '">' . esc_html( $text ) . '</span>';
}
