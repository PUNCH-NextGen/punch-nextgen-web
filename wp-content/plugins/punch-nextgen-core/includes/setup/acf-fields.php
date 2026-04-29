<?php
/**
 * ACF local field groups. These fields drive the editorial/admin experience when ACF is active.
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

function png_core_register_acf_fields() {
    if ( ! function_exists( 'acf_add_local_field_group' ) ) {
        return;
    }

    acf_add_local_field_group(
        array(
            'key'    => 'group_png_story_options',
            'title'  => 'Punch NextGen Story Options',
            'fields' => array(
                array(
                    'key'           => 'field_png_story_format',
                    'label'         => 'Story Format',
                    'name'          => 'png_story_format',
                    'type'          => 'select',
                    'choices'       => array(
                        'standard'           => 'Standard Story',
                        'explain_like_im_10' => 'Explain Like I’m 10',
                        'myth_vs_fact'       => 'Myth vs Fact',
                        'fact_check'         => 'Fact Check',
                        'school_showcase'    => 'School Showcase Story',
                        'opinion'            => 'Opinion / Youth Voice',
                    ),
                    'default_value' => 'standard',
                    'return_format' => 'value',
                ),
                array(
                    'key'   => 'field_png_eli10_summary',
                    'label' => 'Explain Like I’m 10 Summary',
                    'name'  => 'png_eli10_summary',
                    'type'  => 'textarea',
                    'rows'  => 4,
                ),
                array(
                    'key'   => 'field_png_myth_claim',
                    'label' => 'Myth / Claim',
                    'name'  => 'png_myth_claim',
                    'type'  => 'textarea',
                    'rows'  => 3,
                ),
                array(
                    'key'           => 'field_png_fact_check_verdict',
                    'label'         => 'Fact Check Verdict',
                    'name'          => 'png_fact_check_verdict',
                    'type'          => 'select',
                    'choices'       => array(
                        'true'        => 'True',
                        'mostly_true' => 'Mostly True',
                        'misleading'  => 'Misleading',
                        'false'       => 'False',
                        'unverified'  => 'Unverified',
                    ),
                    'allow_null'    => 1,
                    'return_format' => 'value',
                ),
                array(
                    'key'           => 'field_png_linked_poll',
                    'label'         => 'Linked End-of-Story Poll',
                    'name'          => 'png_linked_poll',
                    'type'          => 'post_object',
                    'post_type'     => array( 'png_poll' ),
                    'return_format' => 'id',
                    'allow_null'    => 1,
                ),
                array(
                    'key'           => 'field_png_manual_comic',
                    'label'         => 'Manual Comic Override',
                    'name'          => 'png_manual_comic',
                    'type'          => 'post_object',
                    'post_type'     => array( 'png_comic' ),
                    'return_format' => 'id',
                    'allow_null'    => 1,
                ),
                array(
                    'key'   => 'field_png_disable_comments',
                    'label' => 'Disable Comments for this Story',
                    'name'  => 'png_disable_comments',
                    'type'  => 'true_false',
                    'ui'    => 1,
                ),
            ),
            'location' => array(
                array(
                    array(
                        'param'    => 'post_type',
                        'operator' => '==',
                        'value'    => 'post',
                    ),
                ),
            ),
        )
    );

    acf_add_local_field_group(
        array(
            'key'    => 'group_png_poll_fields',
            'title'  => 'Poll Settings',
            'fields' => array(
                array( 'key' => 'field_png_poll_question', 'label' => 'Poll Question', 'name' => 'png_poll_question', 'type' => 'text', 'required' => 1 ),
                array(
                    'key'           => 'field_png_poll_type',
                    'label'         => 'Poll Type',
                    'name'          => 'png_poll_type',
                    'type'          => 'select',
                    'choices'       => array( 'single_choice' => 'Single Choice', 'agree_disagree' => 'Agree / Disagree', 'multi_option' => 'Multi-option' ),
                    'default_value' => 'single_choice',
                ),
                array( 'key' => 'field_png_poll_options', 'label' => 'Poll Options', 'name' => 'png_poll_options', 'type' => 'textarea', 'instructions' => 'Add one option per line.', 'rows' => 6 ),
                array(
                    'key'           => 'field_png_poll_results_rule',
                    'label'         => 'Results Display Rule',
                    'name'          => 'png_poll_results_rule',
                    'type'          => 'select',
                    'choices'       => array( 'after_vote' => 'Show after vote', 'after_close' => 'Show after close', 'admin_only' => 'Admin only' ),
                    'default_value' => 'after_vote',
                ),
                array( 'key' => 'field_png_poll_close_date', 'label' => 'Close Date', 'name' => 'png_poll_close_date', 'type' => 'date_picker', 'return_format' => 'Y-m-d' ),
                array( 'key' => 'field_png_poll_points_enabled', 'label' => 'Award Participation Points', 'name' => 'png_poll_points_enabled', 'type' => 'true_false', 'ui' => 1, 'default_value' => 1 ),
            ),
            'location' => array( array( array( 'param' => 'post_type', 'operator' => '==', 'value' => 'png_poll' ) ) ),
        )
    );

    acf_add_local_field_group(
        array(
            'key'    => 'group_png_school_fields',
            'title'  => 'School Details',
            'fields' => array(
                array( 'key' => 'field_png_school_official_name', 'label' => 'Official School Name', 'name' => 'png_school_official_name', 'type' => 'text' ),
                array( 'key' => 'field_png_school_contact_name', 'label' => 'Contact Person', 'name' => 'png_school_contact_name', 'type' => 'text' ),
                array( 'key' => 'field_png_school_contact_email', 'label' => 'Contact Email', 'name' => 'png_school_contact_email', 'type' => 'email' ),
                array( 'key' => 'field_png_school_phone', 'label' => 'Phone Number', 'name' => 'png_school_phone', 'type' => 'text' ),
                array( 'key' => 'field_png_school_address', 'label' => 'Address', 'name' => 'png_school_address', 'type' => 'textarea', 'rows' => 3 ),
                array( 'key' => 'field_png_school_code', 'label' => 'School Code', 'name' => 'png_school_code', 'type' => 'text' ),
            ),
            'location' => array( array( array( 'param' => 'post_type', 'operator' => '==', 'value' => 'png_school' ) ) ),
        )
    );

    acf_add_local_field_group(
        array(
            'key'    => 'group_png_teacher_guide_fields',
            'title'  => 'Teacher Guide Details',
            'fields' => array(
                array( 'key' => 'field_png_guide_summary', 'label' => 'Summary', 'name' => 'png_guide_summary', 'type' => 'textarea', 'rows' => 4 ),
                array( 'key' => 'field_png_guide_key_points', 'label' => 'Key Points', 'name' => 'png_guide_key_points', 'type' => 'textarea', 'rows' => 6 ),
                array( 'key' => 'field_png_guide_questions', 'label' => 'Discussion Questions', 'name' => 'png_guide_questions', 'type' => 'textarea', 'rows' => 6 ),
                array( 'key' => 'field_png_guide_activity', 'label' => 'Classroom Activity', 'name' => 'png_guide_activity', 'type' => 'textarea', 'rows' => 6 ),
                array( 'key' => 'field_png_guide_answer_key', 'label' => 'Answer Key', 'name' => 'png_guide_answer_key', 'type' => 'textarea', 'rows' => 6 ),
                array( 'key' => 'field_png_guide_pdf', 'label' => 'Downloadable Pack / PDF', 'name' => 'png_guide_pdf', 'type' => 'file', 'return_format' => 'id' ),
                array( 'key' => 'field_png_guide_class_time', 'label' => 'Estimated Class Time', 'name' => 'png_guide_class_time', 'type' => 'text' ),
                array( 'key' => 'field_png_guide_age_group', 'label' => 'Target Age Group', 'name' => 'png_guide_age_group', 'type' => 'text' ),
                array( 'key' => 'field_png_guide_related_story', 'label' => 'Related Story', 'name' => 'png_guide_related_story', 'type' => 'post_object', 'post_type' => array( 'post' ), 'return_format' => 'id', 'allow_null' => 1 ),
            ),
            'location' => array( array( array( 'param' => 'post_type', 'operator' => '==', 'value' => 'png_teacher_guide' ) ) ),
        )
    );

    acf_add_local_field_group(
        array(
            'key'    => 'group_png_dates_and_links',
            'title'  => 'Punch NextGen Details',
            'fields' => array(
                array( 'key' => 'field_png_start_date', 'label' => 'Start Date', 'name' => 'png_start_date', 'type' => 'date_picker', 'return_format' => 'Y-m-d' ),
                array( 'key' => 'field_png_end_date', 'label' => 'End Date', 'name' => 'png_end_date', 'type' => 'date_picker', 'return_format' => 'Y-m-d' ),
                array( 'key' => 'field_png_is_holiday', 'label' => 'Holiday Period', 'name' => 'png_is_holiday', 'type' => 'true_false', 'ui' => 1 ),
                array( 'key' => 'field_png_subscription_school', 'label' => 'School', 'name' => 'png_subscription_school', 'type' => 'post_object', 'post_type' => array( 'png_school' ), 'return_format' => 'id', 'allow_null' => 1 ),
                array( 'key' => 'field_png_subscription_term', 'label' => 'Academic Term', 'name' => 'png_subscription_term', 'type' => 'post_object', 'post_type' => array( 'png_term' ), 'return_format' => 'id', 'allow_null' => 1 ),
                array( 'key' => 'field_png_subscription_type', 'label' => 'Subscription Type', 'name' => 'png_subscription_type', 'type' => 'select', 'choices' => array( 'term' => 'Term', 'holiday' => 'Holiday', 'pilot' => 'Pilot', 'manual' => 'Manual' ) ),
                array( 'key' => 'field_png_subscription_status', 'label' => 'Status', 'name' => 'png_subscription_status', 'type' => 'select', 'choices' => array( 'active' => 'Active', 'expired' => 'Expired', 'pending' => 'Pending', 'cancelled' => 'Cancelled', 'manual_override' => 'Manual Override' ) ),
            ),
            'location' => array(
                array( array( 'param' => 'post_type', 'operator' => '==', 'value' => 'png_term' ) ),
                array( array( 'param' => 'post_type', 'operator' => '==', 'value' => 'png_subscription' ) ),
            ),
        )
    );

    acf_add_local_field_group(
        array(
            'key'    => 'group_png_interactive_items',
            'title'  => 'Interactive / Reward / Ad Details',
            'fields' => array(
                array( 'key' => 'field_png_feature_image', 'label' => 'Feature Image / Asset', 'name' => 'png_feature_image', 'type' => 'image', 'return_format' => 'id' ),
                array( 'key' => 'field_png_active_from', 'label' => 'Active From', 'name' => 'png_active_from', 'type' => 'date_picker', 'return_format' => 'Y-m-d' ),
                array( 'key' => 'field_png_active_to', 'label' => 'Active To', 'name' => 'png_active_to', 'type' => 'date_picker', 'return_format' => 'Y-m-d' ),
                array( 'key' => 'field_png_is_default_comic', 'label' => 'Default Comic', 'name' => 'png_is_default_comic', 'type' => 'true_false', 'ui' => 1 ),
                array( 'key' => 'field_png_correct_answer', 'label' => 'Correct Answer', 'name' => 'png_correct_answer', 'type' => 'text' ),
                array( 'key' => 'field_png_answer_explanation', 'label' => 'Answer Explanation', 'name' => 'png_answer_explanation', 'type' => 'textarea', 'rows' => 5 ),
                array( 'key' => 'field_png_submission_deadline', 'label' => 'Submission Deadline', 'name' => 'png_submission_deadline', 'type' => 'date_time_picker', 'return_format' => 'Y-m-d H:i:s' ),
                array( 'key' => 'field_png_reveal_date', 'label' => 'Reveal Date', 'name' => 'png_reveal_date', 'type' => 'date_time_picker', 'return_format' => 'Y-m-d H:i:s' ),
                array( 'key' => 'field_png_points_value', 'label' => 'Points Value', 'name' => 'png_points_value', 'type' => 'number' ),
                array( 'key' => 'field_png_ad_location', 'label' => 'Ad Location', 'name' => 'png_ad_location', 'type' => 'select', 'choices' => array( 'home_top' => 'Home Top', 'home_mid' => 'Home Middle', 'article_after_intro' => 'Article After Intro', 'article_mid' => 'Article Middle', 'article_after_story' => 'Article After Story', 'category_top' => 'Category Top', 'leaderboard_area' => 'Leaderboard Area', 'teacher_portal_area' => 'Teacher Portal Area' ) ),
                array( 'key' => 'field_png_ad_code', 'label' => 'Ad Code', 'name' => 'png_ad_code', 'type' => 'textarea', 'rows' => 8 ),
                array( 'key' => 'field_png_ad_status', 'label' => 'Ad Status', 'name' => 'png_ad_status', 'type' => 'select', 'choices' => array( 'active' => 'Active', 'inactive' => 'Inactive' ), 'default_value' => 'active' ),
                array( 'key' => 'field_png_reward_amount', 'label' => 'Reward Amount', 'name' => 'png_reward_amount', 'type' => 'number' ),
                array( 'key' => 'field_png_reward_status', 'label' => 'Reward Status', 'name' => 'png_reward_status', 'type' => 'select', 'choices' => array( 'pending_review' => 'Pending Review', 'approved' => 'Approved', 'rejected' => 'Rejected', 'paid' => 'Paid', 'cancelled' => 'Cancelled' ) ),
            ),
            'location' => array(
                array( array( 'param' => 'post_type', 'operator' => '==', 'value' => 'png_comic' ) ),
                array( array( 'param' => 'post_type', 'operator' => '==', 'value' => 'png_crack_this' ) ),
                array( array( 'param' => 'post_type', 'operator' => '==', 'value' => 'png_ad_slot' ) ),
                array( array( 'param' => 'post_type', 'operator' => '==', 'value' => 'png_reward' ) ),
            ),
        )
    );
}
