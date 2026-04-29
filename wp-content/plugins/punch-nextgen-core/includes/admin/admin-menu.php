<?php
/**
 * Admin menu and pages.
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

function png_core_register_admin_menu() {
    add_menu_page( 'Punch NextGen', 'Punch NextGen', 'manage_options', 'png-core-dashboard', 'png_core_render_admin_dashboard_page', 'dashicons-welcome-learn-more', 30 );
    add_submenu_page( 'png-core-dashboard', 'Dashboard', 'Dashboard', 'manage_options', 'png-core-dashboard', 'png_core_render_admin_dashboard_page' );
    add_submenu_page( 'png-core-dashboard', 'Schools', 'Schools', 'png_manage_schools', 'edit.php?post_type=png_school' );
    add_submenu_page( 'png-core-dashboard', 'Terms & Holidays', 'Terms & Holidays', 'png_manage_terms', 'edit.php?post_type=png_term' );
    add_submenu_page( 'png-core-dashboard', 'School Subscriptions', 'School Subscriptions', 'png_manage_subscriptions', 'edit.php?post_type=png_subscription' );
    add_submenu_page( 'png-core-dashboard', 'Teacher Accounts', 'Teacher Accounts', 'png_manage_teachers', 'png-core-teachers', 'png_core_render_admin_teachers_page' );
    add_submenu_page( 'png-core-dashboard', 'Teacher Guides', 'Teacher Guides', 'edit_posts', 'edit.php?post_type=png_teacher_guide' );
    add_submenu_page( 'png-core-dashboard', 'Story Polls', 'Story Polls', 'edit_posts', 'edit.php?post_type=png_poll' );
    add_submenu_page( 'png-core-dashboard', 'Crack This Lite', 'Crack This Lite', 'edit_posts', 'edit.php?post_type=png_crack_this' );
    add_submenu_page( 'png-core-dashboard', 'Digital Comics', 'Digital Comics', 'edit_posts', 'edit.php?post_type=png_comic' );
    add_submenu_page( 'png-core-dashboard', 'Ad Slots', 'Ad Slots', 'png_manage_ads', 'edit.php?post_type=png_ad_slot' );
    add_submenu_page( 'png-core-dashboard', 'Points Rules', 'Points Rules', 'png_manage_points', 'png-core-points', 'png_core_render_admin_points_page' );
    add_submenu_page( 'png-core-dashboard', 'Leaderboards', 'Leaderboards', 'png_manage_leaderboards', 'png-core-leaderboards', 'png_core_render_admin_leaderboards_page' );
    add_submenu_page( 'png-core-dashboard', 'Reports', 'Reports', 'png_view_reports', 'png-core-reports', 'png_core_render_admin_reports_page' );
    add_submenu_page( 'png-core-dashboard', 'Rewards & Winners', 'Rewards & Winners', 'png_manage_rewards', 'edit.php?post_type=png_reward' );
    add_submenu_page( 'png-core-dashboard', 'Settings', 'Settings', 'manage_options', 'png-core-settings', 'png_core_render_admin_settings_page' );
}

function png_core_render_admin_dashboard_page() {
    png_core_admin_wrap_start( 'Punch NextGen Dashboard', 'Manage the platform engine: schools, teacher access, polls, comics, points, leaderboards, ads, rewards, and reports.' );

    png_core_admin_cards_start();
    png_core_admin_card( 'Schools', png_core_count_posts( 'png_school' ), 'Registered schools.', admin_url( 'edit.php?post_type=png_school' ) );
    png_core_admin_card( 'Teacher Guides', png_core_count_posts( 'png_teacher_guide' ), 'Classroom resources.', admin_url( 'edit.php?post_type=png_teacher_guide' ) );
    png_core_admin_card( 'Polls', png_core_count_posts( 'png_poll' ), 'End-of-story polls.', admin_url( 'edit.php?post_type=png_poll' ) );
    png_core_admin_card( 'Crack This Lite', png_core_count_posts( 'png_crack_this' ), 'Puzzle posts.', admin_url( 'edit.php?post_type=png_crack_this' ) );
    png_core_admin_card( 'Comics', png_core_count_posts( 'png_comic' ), 'Digital comic assets.', admin_url( 'edit.php?post_type=png_comic' ) );
    png_core_admin_card( 'Rewards', png_core_count_posts( 'png_reward' ), 'Cash reward/winner records.', admin_url( 'edit.php?post_type=png_reward' ) );
    png_core_admin_cards_end();

    png_core_admin_section_start( 'Operational Shortcuts', 'Quick links to the most common admin tasks.' );
    echo '<p><a class="button button-primary" href="' . esc_url( admin_url( 'post-new.php?post_type=png_school' ) ) . '">Add School</a> ';
    echo '<a class="button" href="' . esc_url( png_core_admin_url( 'png-core-teachers' ) ) . '">Create Teacher</a> ';
    echo '<a class="button" href="' . esc_url( admin_url( 'post-new.php?post_type=png_poll' ) ) . '">Create Poll</a> ';
    echo '<a class="button" href="' . esc_url( admin_url( 'post-new.php?post_type=png_teacher_guide' ) ) . '">Add Teacher Guide</a></p>';
    png_core_admin_section_end();

    png_core_admin_wrap_end();
}

function png_core_render_admin_teachers_page() {
    $schools = png_core_get_schools_for_select();
    png_core_admin_wrap_start( 'Teacher Accounts', 'Create teacher accounts and link them to schools. Teacher portal access still depends on active school subscription.' );

    png_core_admin_section_start( 'Create Teacher' );
    echo '<form method="post" action="' . esc_url( admin_url( 'admin-post.php' ) ) . '" class="png-core-admin-form">';
    wp_nonce_field( 'png_core_create_teacher', 'png_core_nonce' );
    echo '<input type="hidden" name="action" value="png_core_create_teacher" />';
    echo '<label>Teacher Name <input type="text" name="teacher_name" /></label>';
    echo '<label>Teacher Email <input type="email" name="teacher_email" required /></label>';
    echo '<label>School <select name="school_id"><option value="0">Select school</option>';
    foreach ( $schools as $id => $name ) {
        echo '<option value="' . esc_attr( $id ) . '">' . esc_html( $name ) . '</option>';
    }
    echo '</select></label>';
    echo '<button class="button button-primary" type="submit">Create Teacher</button>';
    echo '</form>';
    png_core_admin_section_end();

    png_core_admin_section_start( 'Existing Teachers' );
    $teachers = get_users( array( 'role' => 'png_teacher', 'number' => 100 ) );
    echo '<table class="widefat striped"><thead><tr><th>Name</th><th>Email</th><th>School</th><th>Access</th></tr></thead><tbody>';
    if ( $teachers ) {
        foreach ( $teachers as $teacher ) {
            $school_id = png_core_get_user_school_id( $teacher->ID );
            echo '<tr><td>' . esc_html( $teacher->display_name ) . '</td><td>' . esc_html( $teacher->user_email ) . '</td><td>' . esc_html( $school_id ? get_the_title( $school_id ) : 'Not linked' ) . '</td><td>';
            png_core_admin_status_badge( png_core_user_can_access_teacher_portal( $teacher->ID ) ? 'Active' : 'Inactive', png_core_user_can_access_teacher_portal( $teacher->ID ) ? 'good' : 'warn' );
            echo '</td></tr>';
        }
    } else {
        echo '<tr><td colspan="4">No teachers have been created yet.</td></tr>';
    }
    echo '</tbody></table>';
    png_core_admin_section_end();

    png_core_admin_wrap_end();
}

function png_core_render_admin_points_page() {
    $s = png_core_get_settings();
    png_core_admin_wrap_start( 'Points Rules', 'Configure light web points for reading missions, polls, Crack This Lite, and limited comments.' );
    echo '<form method="post" action="' . esc_url( admin_url( 'admin-post.php' ) ) . '" class="png-core-admin-form png-core-admin-form-grid">';
    wp_nonce_field( 'png_core_save_points', 'png_core_nonce' );
    echo '<input type="hidden" name="action" value="png_core_save_points" />';
    $fields = array(
        'reading_mission_daily_threshold' => 'Daily reading mission threshold',
        'reading_mission_weekly_threshold' => 'Weekly reading mission threshold',
        'points_read_3_today' => 'Points: Read 3 today',
        'points_read_3_week' => 'Points: Read 3 this week',
        'points_read_6_week' => 'Points: Read 6 this week',
        'points_poll_vote' => 'Points: Poll vote',
        'points_crack_submit' => 'Points: Crack This submission',
        'points_crack_correct' => 'Extra points: Correct Crack This answer',
        'points_comment' => 'Points: Comment participation',
        'comment_points_daily_limit' => 'Daily comment points limit',
        'school_top_contributors_limit' => 'School leaderboard top contributors limit',
    );
    foreach ( $fields as $key => $label ) {
        echo '<label>' . esc_html( $label ) . '<input type="number" min="0" name="' . esc_attr( $key ) . '" value="' . esc_attr( absint( $s[ $key ] ) ) . '" /></label>';
    }
    echo '<button class="button button-primary" type="submit">Save Points Rules</button></form>';
    png_core_admin_wrap_end();
}

function png_core_render_admin_leaderboards_page() {
    png_core_admin_wrap_start( 'Leaderboards', 'Review current individual and school rankings.' );
    png_core_admin_section_start( 'Top Users This Week' );
    png_core_render_leaderboard_table( 'user', 'week', 20 );
    png_core_admin_section_end();
    png_core_admin_section_start( 'Top Schools This Week' );
    png_core_render_leaderboard_table( 'school', 'week', 20 );
    png_core_admin_section_end();
    png_core_admin_wrap_end();
}

function png_core_render_admin_reports_page() {
    global $wpdb;
    png_core_admin_wrap_start( 'Reports', 'Participation, teacher downloads, contact messages, points, and activity reports.' );
    png_core_admin_cards_start();
    png_core_admin_card( 'Point Records', absint( $wpdb->get_var( 'SELECT COUNT(*) FROM ' . png_core_get_table_name( 'points_ledger' ) ) ), 'Total point ledger rows.' );
    png_core_admin_card( 'Story Reads', absint( $wpdb->get_var( 'SELECT COUNT(*) FROM ' . png_core_get_table_name( 'story_reads' ) ) ), 'Tracked logged-in reads.' );
    png_core_admin_card( 'Poll Votes', absint( $wpdb->get_var( 'SELECT COUNT(*) FROM ' . png_core_get_table_name( 'poll_votes' ) ) ), 'Total votes.' );
    png_core_admin_card( 'Crack Submissions', absint( $wpdb->get_var( 'SELECT COUNT(*) FROM ' . png_core_get_table_name( 'crack_submissions' ) ) ), 'Puzzle submissions.' );
    png_core_admin_card( 'Teacher Downloads', absint( $wpdb->get_var( 'SELECT COUNT(*) FROM ' . png_core_get_table_name( 'teacher_downloads' ) ) ), 'Guide pack downloads.' );
    png_core_admin_card( 'Contact Messages', absint( $wpdb->get_var( 'SELECT COUNT(*) FROM ' . png_core_get_table_name( 'contact_messages' ) ) ), 'Feedback messages.' );
    png_core_admin_cards_end();
    png_core_admin_wrap_end();
}

function png_core_render_admin_settings_page() {
    $s = png_core_get_settings();
    $terms = get_posts( array( 'post_type' => 'png_term', 'post_status' => 'publish', 'posts_per_page' => 200 ) );
    $comics = get_posts( array( 'post_type' => 'png_comic', 'post_status' => 'publish', 'posts_per_page' => 200 ) );

    png_core_admin_wrap_start( 'Punch NextGen Settings', 'Global settings for term windows, holiday mode, contact routing, ads, registration, comments, and default comics.' );
    echo '<form method="post" action="' . esc_url( admin_url( 'admin-post.php' ) ) . '" class="png-core-admin-form">';
    wp_nonce_field( 'png_core_save_settings', 'png_core_nonce' );
    echo '<input type="hidden" name="action" value="png_core_save_settings" />';
    echo '<label>Current Term <select name="current_term_id"><option value="0">Auto-detect by date</option>';
    foreach ( $terms as $term ) {
        echo '<option value="' . esc_attr( $term->ID ) . '" ' . selected( absint( $s['current_term_id'] ), $term->ID, false ) . '>' . esc_html( $term->post_title ) . '</option>';
    }
    echo '</select></label>';
    echo '<label><input type="checkbox" name="holiday_mode_enabled" value="1" ' . checked( ! empty( $s['holiday_mode_enabled'] ), true, false ) . ' /> Holiday mode enabled</label>';
    echo '<label><input type="checkbox" name="ads_enabled" value="1" ' . checked( ! empty( $s['ads_enabled'] ), true, false ) . ' /> Ads enabled</label>';
    echo '<label><input type="checkbox" name="public_registration_enabled" value="1" ' . checked( ! empty( $s['public_registration_enabled'] ), true, false ) . ' /> Student public registration enabled</label>';
    echo '<label>School Change Policy <select name="school_change_policy"><option value="once_per_term" ' . selected( $s['school_change_policy'], 'once_per_term', false ) . '>Once per term</option><option value="admin_only" ' . selected( $s['school_change_policy'], 'admin_only', false ) . '>Admin only</option><option value="open" ' . selected( $s['school_change_policy'], 'open', false ) . '>Open</option></select></label>';
    echo '<label>Default Comic <select name="default_comic_id"><option value="0">Auto select</option>';
    foreach ( $comics as $comic ) {
        echo '<option value="' . esc_attr( $comic->ID ) . '" ' . selected( absint( $s['default_comic_id'] ), $comic->ID, false ) . '>' . esc_html( $comic->post_title ) . '</option>';
    }
    echo '</select></label>';
    echo '<label>Contact Recipient Email <input type="email" name="contact_recipient_email" value="' . esc_attr( $s['contact_recipient_email'] ) . '" /></label>';
    echo '<label>Blocked Words <textarea name="blocked_words" rows="6">' . esc_textarea( $s['blocked_words'] ) . '</textarea><span class="description">One word or phrase per line.</span></label>';
    echo '<button class="button button-primary" type="submit">Save Settings</button></form>';
    png_core_admin_wrap_end();
}
