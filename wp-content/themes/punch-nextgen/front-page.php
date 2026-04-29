<?php
/**
 * Punch NextGen homepage.
 */

get_header();

$big_news_term = get_term_by( 'name', 'The Big News', 'category' );
$hero_args     = array(
    'post_type'           => 'post',
    'posts_per_page'      => 1,
    'ignore_sticky_posts' => false,
);

if ( $big_news_term && ! is_wp_error( $big_news_term ) ) {
    $hero_args['cat'] = $big_news_term->term_id;
}

$hero_query = new WP_Query( $hero_args );
$hero_id    = 0;
?>

<main id="primary" class="site-main png-home">
    <section class="png-news-hero">
        <div class="png-container png-news-hero__grid">
            <div class="png-news-hero__lead">
                <div class="png-section-heading png-section-heading--compact">
                    <span><?php esc_html_e( 'The Big News', 'punch-nextgen' ); ?></span>
                    <h1><?php esc_html_e( 'Understand the story shaping today.', 'punch-nextgen' ); ?></h1>
                </div>

                <?php
                if ( $hero_query->have_posts() ) :
                    while ( $hero_query->have_posts() ) :
                        $hero_query->the_post();
                        $hero_id = get_the_ID();
                        $categories = get_the_category();
                        ?>
                        <article class="png-big-news-card">
                            <a class="png-big-news-card__image" href="<?php the_permalink(); ?>">
                                <?php if ( has_post_thumbnail() ) : ?>
                                    <?php the_post_thumbnail( 'png_hero' ); ?>
                                <?php else : ?>
                                    <span><?php esc_html_e( 'Punch NextGen', 'punch-nextgen' ); ?></span>
                                <?php endif; ?>
                            </a>

                            <div class="png-big-news-card__content">
                                <?php if ( ! empty( $categories ) ) : ?>
                                    <a class="png-chip" href="<?php echo esc_url( get_category_link( $categories[0]->term_id ) ); ?>">
                                        <?php echo esc_html( $categories[0]->name ); ?>
                                    </a>
                                <?php endif; ?>

                                <h2><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
                                <p><?php echo esc_html( wp_trim_words( get_the_excerpt(), 32 ) ); ?></p>

                                <div class="png-hero-card__meta">
                                    <span><?php echo esc_html( get_the_date() ); ?></span>
                                    <span><?php echo esc_html( png_theme_reading_time() ); ?></span>
                                </div>

                                <a class="png-read-more" href="<?php the_permalink(); ?>">
                                    <?php esc_html_e( 'Read the story', 'punch-nextgen' ); ?>
                                </a>
                            </div>
                        </article>
                        <?php
                    endwhile;
                    wp_reset_postdata();
                else :
                    ?>
                    <div class="png-empty-hero png-big-news-card">
                        <div class="png-big-news-card__content">
                            <span class="png-kicker"><?php esc_html_e( 'Punch NextGen', 'punch-nextgen' ); ?></span>
                            <h2><?php esc_html_e( 'The Big News will appear here.', 'punch-nextgen' ); ?></h2>
                            <p><?php esc_html_e( 'Create a post under “The Big News” or publish any story to activate this section.', 'punch-nextgen' ); ?></p>
                        </div>
                    </div>
                    <?php
                endif;
                ?>
            </div>

            <aside class="png-news-hero__latest">
                <div class="png-latest-panel">
                    <div class="png-section-heading png-section-heading--compact">
                        <span><?php esc_html_e( 'Just In', 'punch-nextgen' ); ?></span>
                        <h2><?php esc_html_e( 'Latest Stories', 'punch-nextgen' ); ?></h2>
                    </div>

                    <?php
                    $latest_query = new WP_Query(
                        array(
                            'post_type'           => 'post',
                            'posts_per_page'      => 5,
                            'post__not_in'        => $hero_id ? array( $hero_id ) : array(),
                            'ignore_sticky_posts' => true,
                        )
                    );

                    if ( $latest_query->have_posts() ) :
                        echo '<div class="png-latest-list">';
                        while ( $latest_query->have_posts() ) :
                            $latest_query->the_post();
                            ?>
                            <article class="png-latest-item">
                                <a class="png-latest-item__thumb" href="<?php the_permalink(); ?>">
                                    <?php if ( has_post_thumbnail() ) : ?>
                                        <?php the_post_thumbnail( 'png_thumb' ); ?>
                                    <?php else : ?>
                                        <span>PN</span>
                                    <?php endif; ?>
                                </a>
                                <div>
                                    <h3><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
                                    <small><?php echo esc_html( get_the_date() ); ?> • <?php echo esc_html( png_theme_reading_time() ); ?></small>
                                </div>
                            </article>
                            <?php
                        endwhile;
                        echo '</div>';
                        wp_reset_postdata();
                    else :
                        echo '<p class="png-muted">' . esc_html__( 'Publish more stories to fill this latest stories panel.', 'punch-nextgen' ) . '</p>';
                    endif;
                    ?>
                </div>
            </aside>
        </div>
    </section>

    <?php png_theme_render_ad_slot_safe( 'home_top' ); ?>

    <section class="png-section png-section--soft">
        <div class="png-container">
            <div class="png-section-heading">
                <span><?php esc_html_e( 'Explore', 'punch-nextgen' ); ?></span>
                <h2><?php esc_html_e( 'Category Highlights', 'punch-nextgen' ); ?></h2>
            </div>

            <div class="png-category-grid">
                <?php
                $category_names = array(
                    'News',
                    'Culture & Trends',
                    'Campus & School Life',
                    'Money & Life Skills',
                    'Career & Opportunities',
                    'Sports',
                    'Opinion / Youth Voices',
                    'Myth vs Fact',
                    'Fact Check',
                );

                foreach ( $category_names as $category_name ) :
                    $term = get_term_by( 'name', $category_name, 'category' );

                    if ( ! $term || is_wp_error( $term ) ) {
                        continue;
                    }
                    ?>
                    <a class="png-category-tile" href="<?php echo esc_url( get_term_link( $term ) ); ?>">
                        <span><?php echo esc_html( $category_name ); ?></span>
                        <em><?php esc_html_e( 'Explore', 'punch-nextgen' ); ?></em>
                    </a>
                <?php endforeach; ?>
            </div>
        </div>
    </section>

    <section class="png-section">
        <div class="png-container">
            <div class="png-section-heading">
                <span><?php esc_html_e( 'Fresh Updates', 'punch-nextgen' ); ?></span>
                <h2><?php esc_html_e( 'More Stories', 'punch-nextgen' ); ?></h2>
            </div>

            <div class="png-card-grid">
                <?php
                $more_query = new WP_Query(
                    array(
                        'post_type'           => 'post',
                        'posts_per_page'      => 6,
                        'offset'              => 1,
                        'ignore_sticky_posts' => true,
                    )
                );

                if ( $more_query->have_posts() ) :
                    while ( $more_query->have_posts() ) :
                        $more_query->the_post();
                        get_template_part( 'template-parts/content/content', 'story-card' );
                    endwhile;
                    wp_reset_postdata();
                else :
                    echo '<p class="png-muted">' . esc_html__( 'More stories will appear here as the newsroom publishes.', 'punch-nextgen' ) . '</p>';
                endif;
                ?>
            </div>
        </div>
    </section>

    <section class="png-section png-feature-row-v2">
        <div class="png-container">
            <div class="png-feature-grid-v2">
                <div class="png-feature-block-v2 png-feature-block-v2--school">
                    <div class="png-section-heading">
                        <span><?php esc_html_e( 'Schools', 'punch-nextgen' ); ?></span>
                        <h2><?php esc_html_e( 'Featured School Showcase', 'punch-nextgen' ); ?></h2>
                    </div>

                    <?php
                    if ( function_exists( 'png_theme_render_home_school_showcase_v2' ) ) {
                        png_theme_render_home_school_showcase_v2();
                    }
                    ?>
                </div>

                <div class="png-feature-block-v2 png-feature-block-v2--crack">
                    <div class="png-section-heading">
                        <span><?php esc_html_e( 'Challenge', 'punch-nextgen' ); ?></span>
                        <h2><?php esc_html_e( 'Crack This Lite', 'punch-nextgen' ); ?></h2>
                    </div>

                    <?php
                    if ( function_exists( 'png_theme_render_home_crack_this_v2' ) ) {
                        png_theme_render_home_crack_this_v2();
                    }
                    ?>
                </div>
            </div>
        </div>
    </section>

    <section class="png-section png-section--leaderboard">
        <div class="png-container">
            <div class="png-section-heading">
                <span><?php esc_html_e( 'Progress', 'punch-nextgen' ); ?></span>
                <h2><?php esc_html_e( 'Top Students & Schools', 'punch-nextgen' ); ?></h2>
            </div>

            <?php
            if ( function_exists( 'png_core_render_leaderboard_preview' ) ) {
                png_core_render_leaderboard_preview();
            } else {
                echo '<div class="png-leaderboard-placeholder">';
                echo '<p>' . esc_html__( 'Leaderboards will appear here after users start earning points.', 'punch-nextgen' ) . '</p>';
                echo '<a class="png-btn png-btn--dark" href="' . esc_url( png_theme_get_page_url( 'leaderboards', '/leaderboards/' ) ) . '">' . esc_html__( 'View Leaderboards', 'punch-nextgen' ) . '</a>';
                echo '</div>';
            }
            ?>
        </div>
    </section>

    <?php png_theme_render_ad_slot_safe( 'home_mid' ); ?>
</main>

<?php
get_footer();
