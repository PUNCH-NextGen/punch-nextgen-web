<?php
/**
 * Custom database tables for high-volume interaction data.
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

function png_core_create_tables() {
    global $wpdb;

    require_once ABSPATH . 'wp-admin/includes/upgrade.php';

    $charset_collate = $wpdb->get_charset_collate();

    $tables = array();

    $tables[] = "CREATE TABLE " . png_core_get_table_name( 'points_ledger' ) . " (
        id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
        user_id BIGINT UNSIGNED NOT NULL DEFAULT 0,
        school_id BIGINT UNSIGNED NOT NULL DEFAULT 0,
        source VARCHAR(80) NOT NULL,
        source_id VARCHAR(120) DEFAULT '',
        points INT NOT NULL DEFAULT 0,
        term_id BIGINT UNSIGNED NOT NULL DEFAULT 0,
        period_key VARCHAR(40) DEFAULT '',
        notes TEXT NULL,
        created_at DATETIME NOT NULL,
        PRIMARY KEY (id),
        KEY user_id (user_id),
        KEY school_id (school_id),
        KEY source (source),
        KEY period_key (period_key)
    ) $charset_collate;";

    $tables[] = "CREATE TABLE " . png_core_get_table_name( 'story_reads' ) . " (
        id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
        user_id BIGINT UNSIGNED NOT NULL,
        post_id BIGINT UNSIGNED NOT NULL,
        school_id BIGINT UNSIGNED NOT NULL DEFAULT 0,
        read_date DATE NOT NULL,
        read_week VARCHAR(12) NOT NULL,
        created_at DATETIME NOT NULL,
        PRIMARY KEY (id),
        UNIQUE KEY user_post_day (user_id, post_id, read_date),
        KEY user_date (user_id, read_date),
        KEY user_week (user_id, read_week),
        KEY school_id (school_id)
    ) $charset_collate;";

    $tables[] = "CREATE TABLE " . png_core_get_table_name( 'poll_votes' ) . " (
        id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
        poll_id BIGINT UNSIGNED NOT NULL,
        post_id BIGINT UNSIGNED NOT NULL DEFAULT 0,
        user_id BIGINT UNSIGNED NOT NULL DEFAULT 0,
        anon_hash VARCHAR(80) DEFAULT '',
        option_key VARCHAR(120) NOT NULL,
        created_at DATETIME NOT NULL,
        PRIMARY KEY (id),
        KEY poll_id (poll_id),
        KEY user_id (user_id),
        KEY anon_hash (anon_hash)
    ) $charset_collate;";

    $tables[] = "CREATE TABLE " . png_core_get_table_name( 'crack_submissions' ) . " (
        id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
        crack_id BIGINT UNSIGNED NOT NULL,
        user_id BIGINT UNSIGNED NOT NULL,
        school_id BIGINT UNSIGNED NOT NULL DEFAULT 0,
        submitted_answer TEXT NULL,
        is_correct TINYINT(1) NOT NULL DEFAULT 0,
        points_awarded INT NOT NULL DEFAULT 0,
        submitted_at DATETIME NOT NULL,
        PRIMARY KEY (id),
        UNIQUE KEY crack_user (crack_id, user_id),
        KEY school_id (school_id)
    ) $charset_collate;";

    $tables[] = "CREATE TABLE " . png_core_get_table_name( 'teacher_downloads' ) . " (
        id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
        guide_id BIGINT UNSIGNED NOT NULL,
        user_id BIGINT UNSIGNED NOT NULL,
        school_id BIGINT UNSIGNED NOT NULL DEFAULT 0,
        file_id BIGINT UNSIGNED NOT NULL DEFAULT 0,
        downloaded_at DATETIME NOT NULL,
        PRIMARY KEY (id),
        KEY guide_id (guide_id),
        KEY user_id (user_id),
        KEY school_id (school_id)
    ) $charset_collate;";

    $tables[] = "CREATE TABLE " . png_core_get_table_name( 'contact_messages' ) . " (
        id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
        name VARCHAR(190) NOT NULL DEFAULT '',
        email VARCHAR(190) NOT NULL DEFAULT '',
        subject VARCHAR(255) NOT NULL DEFAULT '',
        message LONGTEXT NULL,
        context_url TEXT NULL,
        status VARCHAR(40) NOT NULL DEFAULT 'new',
        created_at DATETIME NOT NULL,
        PRIMARY KEY (id),
        KEY status (status),
        KEY email (email)
    ) $charset_collate;";

    foreach ( $tables as $sql ) {
        dbDelta( $sql );
    }
}
