<?php
/**
 * Digital comic component.
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

if ( function_exists( 'png_core_render_story_comic' ) ) {
    png_core_render_story_comic( get_the_ID() );
    return;
}

if ( post_type_exists( 'png_comic' ) ) {
    $comic_query = new WP_Query(
        array(
            'post_type'      => 'png_comic',
            'posts_per_page' => 1,
            'post_status'    => 'publish',
        )
    );

    if ( $comic_query->have_posts() ) :
        ?>
        <section class="png-comic-block">
            <div class="png-section-heading">
                <span><?php esc_html_e( 'NextGen Comic', 'punch-nextgen' ); ?></span>
                <h2><?php esc_html_e( 'Quick comic break', 'punch-nextgen' ); ?></h2>
            </div>

            <?php
            while ( $comic_query->have_posts() ) :
                $comic_query->the_post();
                ?>
                <article class="png-comic-card">
                    <?php if ( has_post_thumbnail() ) : ?>
                        <?php the_post_thumbnail( 'large' ); ?>
                    <?php endif; ?>
                    <h3><?php the_title(); ?></h3>
                    <div><?php the_excerpt(); ?></div>
                </article>
                <?php
            endwhile;
            wp_reset_postdata();
            ?>
        </section>
        <?php
    endif;
}
