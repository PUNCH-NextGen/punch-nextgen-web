<?php
/**
 * Fallback meta boxes for sites where ACF is unavailable.
 *
 * ACF is the preferred editor interface, but these boxes keep the plugin usable
 * if ACF is temporarily disabled.
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

function png_core_register_fallback_meta_boxes() {
    if ( function_exists( 'acf_add_local_field_group' ) ) {
        return;
    }

    $screens = array( 'post', 'png_poll', 'png_school', 'png_teacher_guide', 'png_crack_this', 'png_comic', 'png_term', 'png_subscription', 'png_ad_slot', 'png_reward' );
    foreach ( $screens as $screen ) {
        add_meta_box( 'png_core_details', 'Punch NextGen Details', 'png_core_render_fallback_meta_box', $screen, 'normal', 'high' );
    }
}

function png_core_fallback_fields_for_type( $post_type ) {
    $common_date_fields = array(
        'png_active_from' => 'Active From / Start Date',
        'png_active_to'   => 'Active To / End Date',
    );

    $fields = array(
        'post' => array(
            'png_story_format'  => 'Story Format',
            'png_eli10_summary' => 'ELI10 Summary',
            'png_myth_claim'    => 'Myth / Claim',
            'png_fact_check_verdict' => 'Fact Check Verdict',
            'png_linked_poll'   => 'Linked Poll ID',
            'png_manual_comic'  => 'Manual Comic ID',
        ),
        'png_poll' => array(
            'png_poll_question'     => 'Poll Question',
            'png_poll_type'         => 'Poll Type',
            'png_poll_options'      => 'Poll Options, one per line',
            'png_poll_results_rule' => 'Results Rule',
            'png_poll_close_date'   => 'Close Date',
        ),
        'png_school' => array(
            'png_school_official_name' => 'Official School Name',
            'png_school_contact_name'  => 'Contact Person',
            'png_school_contact_email' => 'Contact Email',
            'png_school_phone'         => 'Phone Number',
            'png_school_code'          => 'School Code',
            'png_school_address'       => 'Address',
        ),
        'png_teacher_guide' => array(
            'png_guide_summary'    => 'Summary',
            'png_guide_key_points' => 'Key Points',
            'png_guide_questions'  => 'Discussion Questions',
            'png_guide_activity'   => 'Classroom Activity',
            'png_guide_answer_key' => 'Answer Key',
            'png_guide_pdf'        => 'Downloadable PDF Attachment ID',
        ),
        'png_crack_this' => array(
            'png_correct_answer'      => 'Correct Answer',
            'png_answer_explanation'  => 'Answer Explanation',
            'png_submission_deadline' => 'Submission Deadline',
            'png_reveal_date'         => 'Reveal Date',
            'png_points_value'        => 'Points Value',
        ),
        'png_comic' => array_merge(
            $common_date_fields,
            array(
                'png_feature_image'     => 'Comic Image Attachment ID',
                'png_is_default_comic'  => 'Default Comic? 1 or 0',
            )
        ),
        'png_term' => array(
            'png_start_date' => 'Start Date',
            'png_end_date'   => 'End Date',
            'png_is_holiday' => 'Holiday Period? 1 or 0',
        ),
        'png_subscription' => array(
            'png_subscription_school' => 'School ID',
            'png_subscription_term'   => 'Term ID',
            'png_subscription_type'   => 'Type',
            'png_subscription_status' => 'Status',
            'png_start_date'          => 'Start Date',
            'png_end_date'            => 'End Date',
        ),
        'png_ad_slot' => array(
            'png_ad_location' => 'Ad Location',
            'png_ad_status'   => 'Ad Status',
            'png_ad_code'     => 'Ad Code',
        ),
        'png_reward' => array(
            'png_reward_amount' => 'Reward Amount',
            'png_reward_status' => 'Reward Status',
        ),
    );

    return isset( $fields[ $post_type ] ) ? $fields[ $post_type ] : array();
}

function png_core_render_fallback_meta_box( $post ) {
    wp_nonce_field( 'png_core_save_fallback_meta', 'png_core_fallback_meta_nonce' );
    $fields = png_core_fallback_fields_for_type( $post->post_type );
    echo '<div class="png-admin-form-grid">';
    foreach ( $fields as $key => $label ) {
        $value = get_post_meta( $post->ID, $key, true );
        echo '<p><label><strong>' . esc_html( $label ) . '</strong></label><br />';
        if ( false !== strpos( $key, 'code' ) || false !== strpos( $key, 'summary' ) || false !== strpos( $key, 'questions' ) || false !== strpos( $key, 'answer' ) || false !== strpos( $key, 'options' ) || false !== strpos( $key, 'address' ) ) {
            echo '<textarea style="width:100%;min-height:90px;" name="png_core_meta[' . esc_attr( $key ) . ']">' . esc_textarea( $value ) . '</textarea>';
        } else {
            echo '<input style="width:100%;" type="text" name="png_core_meta[' . esc_attr( $key ) . ']" value="' . esc_attr( $value ) . '" />';
        }
        echo '</p>';
    }
    echo '</div>';
}

function png_core_save_fallback_meta_boxes( $post_id, $post ) {
    if ( ! isset( $_POST['png_core_fallback_meta_nonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['png_core_fallback_meta_nonce'] ) ), 'png_core_save_fallback_meta' ) ) {
        return;
    }

    if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
        return;
    }

    if ( ! current_user_can( 'edit_post', $post_id ) ) {
        return;
    }

    if ( empty( $_POST['png_core_meta'] ) || ! is_array( $_POST['png_core_meta'] ) ) {
        return;
    }

    foreach ( wp_unslash( $_POST['png_core_meta'] ) as $key => $value ) {
        $key = sanitize_key( $key );
        update_post_meta( $post_id, $key, is_array( $value ) ? array_map( 'sanitize_text_field', $value ) : wp_kses_post( $value ) );
    }
}
