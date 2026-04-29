<?php
/**
 * Punch NextGen single article template.
 *
 * Rules:
 * - Story content appears first.
 * - Poll appears only after story body.
 * - Digital comic appears after every story.
 * - Related stories follow the article experience.
 */

get_header();
?>

<main id="primary" class="site-main png-single-page">
    <?php
    while ( have_posts() ) :
        the_post();

        $categories = get_the_category();
        ?>
        <article <?php post_class( 'png-article' ); ?>>
            <header class="png-article__header">
                <div class="png-container png-article__header-inner">
                    <?php if ( ! empty( $categories ) ) : ?>
                        <a class="png-chip" href="<?php echo esc_url( get_category_link( $categories[0]->term_id ) ); ?>">
                            <?php echo esc_html( $categories[0]->name ); ?>
                        </a>
                    <?php endif; ?>

                    <?php the_title( '<h1 class="png-article__title">', '</h1>' ); ?>

                    <?php if ( has_excerpt() ) : ?>
                        <p class="png-article__excerpt"><?php echo esc_html( get_the_excerpt() ); ?></p>
                    <?php endif; ?>

                    <div class="png-article__meta">
                        <span><?php echo esc_html( get_the_date() ); ?></span>
                        <span><?php echo esc_html( png_theme_reading_time() ); ?></span>
                        <span><?php esc_html_e( 'By', 'punch-nextgen' ); ?> <?php the_author_posts_link(); ?></span>
                    </div>
                </div>
            </header>

            <?php if ( has_post_thumbnail() ) : ?>
                <div class="png-container">
                    <figure class="png-article__featured">
                        <?php the_post_thumbnail( 'large' ); ?>
                    </figure>
                </div>
            <?php endif; ?>

            <div class="png-container png-article__layout">
                <div class="png-article__main">
                    <div class="png-article__content">
                        <?php the_content(); ?>

                        <?php
                        wp_link_pages(
                            array(
                                'before' => '<div class="page-links">' . esc_html__( 'Pages:', 'punch-nextgen' ),
                                'after'  => '</div>',
                            )
                        );
                        ?>
                    </div>

                    <div class="png-after-story">
                        <?php
                        get_template_part( 'template-parts/components/story', 'poll' );
                        get_template_part( 'template-parts/components/digital', 'comic' );
                        png_theme_render_ad_slot_safe( 'article_after_story' );
                        ?>
                    </div>

                    <section class="png-related">
                        <div class="png-section-heading">
                            <span><?php esc_html_e( 'Keep Reading', 'punch-nextgen' ); ?></span>
                            <h2><?php esc_html_e( 'Related Stories', 'punch-nextgen' ); ?></h2>
                        </div>

                        <div class="png-card-grid">
                            <?php
                            $category_ids = wp_list_pluck( $categories, 'term_id' );

                            $related_query = new WP_Query(
                                array(
                                    'post_type'           => 'post',
                                    'posts_per_page'      => 3,
                                    'post__not_in'        => array( get_the_ID() ),
                                    'category__in'        => $category_ids,
                                    'ignore_sticky_posts' => true,
                                )
                            );

                            if ( $related_query->have_posts() ) :
                                while ( $related_query->have_posts() ) :
                                    $related_query->the_post();
                                    get_template_part( 'template-parts/content/content', 'story-card' );
                                endwhile;
                                wp_reset_postdata();
                            else :
                                echo '<p class="png-muted">' . esc_html__( 'No related stories yet.', 'punch-nextgen' ) . '</p>';
                            endif;
                            ?>
                        </div>
                    </section>

                    <?php
                    if ( comments_open() || get_comments_number() ) :
                        comments_template();
                    endif;
                    ?>
                </div>

                <aside class="png-article__side">
                    <div class="png-side-card">
                        <h2><?php esc_html_e( 'NextGen Tip', 'punch-nextgen' ); ?></h2>
                        <p><?php esc_html_e( 'Read actively: note one new thing you learned and one question you still have.', 'punch-nextgen' ); ?></p>
                    </div>

                    <?php png_theme_render_ad_slot_safe( 'article_sidebar' ); ?>
                </aside>
            </div>
        </article>
        <?php
    endwhile;
    ?>
</main>

<?php
get_footer();
