<?php
/**
 * Digital comic selection and rendering.
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

function png_core_get_story_comic_id( $post_id ) {
    $manual = absint( png_core_get_post_meta_value( $post_id, 'png_manual_comic', 0 ) );
    if ( $manual ) {
        return $manual;
    }

    $settings = png_core_get_settings();
    if ( ! empty( $settings['default_comic_id'] ) ) {
        return absint( $settings['default_comic_id'] );
    }

    $today = current_time( 'Y-m-d' );
    $comics = get_posts(
        array(
            'post_type'      => 'png_comic',
            'post_status'    => 'publish',
            'posts_per_page' => 1,
            'orderby'        => 'date',
            'order'          => 'DESC',
            'meta_query'     => array(
                'relation' => 'OR',
                array(
                    'key'     => 'png_is_default_comic',
                    'value'   => '1',
                    'compare' => '=',
                ),
                array(
                    'relation' => 'AND',
                    array(
                        'key'     => 'png_active_from',
                        'value'   => $today,
                        'compare' => '<=',
                        'type'    => 'DATE',
                    ),
                    array(
                        'key'     => 'png_active_to',
                        'value'   => $today,
                        'compare' => '>=',
                        'type'    => 'DATE',
                    ),
                ),
            ),
        )
    );

    return $comics ? absint( $comics[0]->ID ) : 0;
}

function png_core_render_story_comic( $post_id = 0 ) {
    $post_id  = $post_id ? absint( $post_id ) : get_the_ID();
    $comic_id = png_core_get_story_comic_id( $post_id );

    if ( ! $comic_id ) {
        return '';
    }

    ob_start();
    echo '<section class="png-core-box png-core-comic"><h2>NextGen Comic</h2>';
    echo '<h3>' . esc_html( get_the_title( $comic_id ) ) . '</h3>';
    if ( has_post_thumbnail( $comic_id ) ) {
        echo '<div class="png-core-comic-image">' . get_the_post_thumbnail( $comic_id, 'large' ) . '</div>';
    }
    $content = get_post_field( 'post_content', $comic_id );
    if ( $content ) {
        echo '<div class="png-core-comic-content">' . wpautop( wp_kses_post( $content ) ) . '</div>';
    }
    echo '</section>';
    return ob_get_clean();
}
