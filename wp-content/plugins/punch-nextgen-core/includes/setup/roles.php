<?php
/**
 * Roles and capabilities.
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

function png_core_custom_capabilities() {
    return array(
        'png_manage_schools',
        'png_manage_terms',
        'png_manage_subscriptions',
        'png_manage_teachers',
        'png_manage_points',
        'png_manage_leaderboards',
        'png_manage_rewards',
        'png_manage_ads',
        'png_view_reports',
        'png_access_teacher_portal',
        'png_moderate_platform',
    );
}

function png_core_apply_caps_to_role( $role_name, $caps ) {
    $role = get_role( $role_name );
    if ( ! $role ) {
        return;
    }

    foreach ( $caps as $cap => $grant ) {
        if ( $grant ) {
            $role->add_cap( $cap );
        }
    }
}

function png_core_register_roles_capabilities() {
    $student_caps = array( 'read' => true );

    $teacher_caps = array(
        'read'                      => true,
        'upload_files'              => true,
        'png_access_teacher_portal' => true,
    );

    $school_admin_caps = array(
        'read'                      => true,
        'list_users'                => true,
        'upload_files'              => true,
        'png_access_teacher_portal' => true,
        'png_manage_teachers'       => true,
    );

    $moderator_caps = array(
        'read'                  => true,
        'edit_posts'            => true,
        'edit_others_posts'     => true,
        'moderate_comments'     => true,
        'png_moderate_platform' => true,
    );

    $subscription_admin_caps = array(
        'read'                     => true,
        'edit_posts'               => true,
        'upload_files'             => true,
        'list_users'               => true,
        'png_manage_schools'       => true,
        'png_manage_terms'         => true,
        'png_manage_subscriptions' => true,
        'png_manage_teachers'      => true,
        'png_view_reports'         => true,
    );

    add_role( 'png_student', 'NextGen Student', $student_caps );
    add_role( 'png_teacher', 'NextGen Teacher', $teacher_caps );
    add_role( 'png_school_admin', 'NextGen School Admin', $school_admin_caps );
    add_role( 'png_moderator', 'NextGen Moderator', $moderator_caps );
    add_role( 'png_subscription_admin', 'NextGen Subscription Admin', $subscription_admin_caps );

    // add_role() does not update an existing role, so re-apply caps on every upgrade.
    png_core_apply_caps_to_role( 'png_student', $student_caps );
    png_core_apply_caps_to_role( 'png_teacher', $teacher_caps );
    png_core_apply_caps_to_role( 'png_school_admin', $school_admin_caps );
    png_core_apply_caps_to_role( 'png_moderator', $moderator_caps );
    png_core_apply_caps_to_role( 'png_subscription_admin', $subscription_admin_caps );

    $admin_caps = array_fill_keys( png_core_custom_capabilities(), true );
    png_core_apply_caps_to_role( 'administrator', $admin_caps );
    png_core_apply_caps_to_role(
        'editor',
        array(
            'png_moderate_platform' => true,
            'png_view_reports'      => true,
        )
    );
}
