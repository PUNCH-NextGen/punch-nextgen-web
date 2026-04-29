<?php
/**
 * Punch NextGen homepage.
 */

get_header();

$hero_query = new WP_Query(
    array(
        'post_type'           => 'post',
        'posts_per_page'      => 1,
        'ignore_sticky_posts' => false,
    )
);

$hero_id = 0;
?>

<main id="primary" class="site-main png-home">
    <section class="png-home-hero">
        <div class="png-container png-home-hero__grid">
            <div class="png-home-hero__main">
                <?php
                if ( $hero_query->have_posts() ) :
                    while ( $hero_query->have_posts() ) :
                        $hero_query->the_post();
                        $hero_id = get_the_ID();
                        ?>
                        <article class="png-hero-card">
                            <a class="png-hero-card__image" href="<?php the_permalink(); ?>">
                                <?php if ( has_post_thumbnail() ) : ?>
                                    <?php the_post_thumbnail( 'png_hero' ); ?>
                                <?php endif; ?>
                            </a>

                            <div class="png-hero-card__content">
                                <span class="png-kicker"><?php esc_html_e( 'Top Story', 'punch-nextgen' ); ?></span>
                                <h1><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h1>
                                <p><?php echo esc_html( wp_trim_words( get_the_excerpt(), 28 ) ); ?></p>

                                <div class="png-hero-card__meta">
                                    <span><?php echo esc_html( get_the_date() ); ?></span>
                                    <span><?php echo esc_html( png_theme_reading_time() ); ?></span>
                                </div>
                            </div>
                        </article>
                        <?php
                    endwhile;
                    wp_reset_postdata();
                else :
                    ?>
                    <div class="png-empty-hero">
                        <span class="png-kicker"><?php esc_html_e( 'Punch NextGen', 'punch-nextgen' ); ?></span>
                        <h1><?php esc_html_e( 'News that helps young people understand the world.', 'punch-nextgen' ); ?></h1>
                        <p><?php esc_html_e( 'Publish your first story to activate this hero section.', 'punch-nextgen' ); ?></p>
                    </div>
                    <?php
                endif;
                ?>
            </div>

            <aside class="png-home-hero__side">
                <div class="png-feature-box png-feature-box--yellow">
                    <span><?php esc_html_e( 'Reading Mission', 'punch-nextgen' ); ?></span>
                    <h2><?php esc_html_e( 'Read 3 stories. Learn something new.', 'punch-nextgen' ); ?></h2>
                    <p><?php esc_html_e( 'Light web missions and points will be powered by the Punch NextGen Core system.', 'punch-nextgen' ); ?></p>
                </div>

                <div class="png-feature-box png-feature-box--dark">
                    <span><?php esc_html_e( 'For Teachers', 'punch-nextgen' ); ?></span>
                    <h2><?php esc_html_e( 'Classroom-ready guides', 'punch-nextgen' ); ?></h2>
                    <a href="<?php echo esc_url( png_theme_get_page_url( 'teacher-guide-portal', '/teacher-guide-portal/' ) ); ?>">
                        <?php esc_html_e( 'Open Teacher Portal', 'punch-nextgen' ); ?>
                    </a>
                </div>
            </aside>
        </div>
    </section>

    <?php png_theme_render_ad_slot_safe( 'home_top' ); ?>

    <section class="png-section">
        <div class="png-container">
            <div class="png-section-heading">
                <span><?php esc_html_e( 'Fresh Updates', 'punch-nextgen' ); ?></span>
                <h2><?php esc_html_e( 'Latest Stories', 'punch-nextgen' ); ?></h2>
            </div>

            <div class="png-card-grid">
                <?php
                $latest_query = new WP_Query(
                    array(
                        'post_type'           => 'post',
                        'posts_per_page'      => 6,
                        'post__not_in'        => $hero_id ? array( $hero_id ) : array(),
                        'ignore_sticky_posts' => true,
                    )
                );

                if ( $latest_query->have_posts() ) :
                    while ( $latest_query->have_posts() ) :
                        $latest_query->the_post();
                        get_template_part( 'template-parts/content/content', 'story-card' );
                    endwhile;
                    wp_reset_postdata();
                else :
                    echo '<p class="png-muted">' . esc_html__( 'No stories published yet.', 'punch-nextgen' ) . '</p>';
                endif;
                ?>
            </div>
        </div>
    </section>

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
                        <small><?php echo esc_html( sprintf( _n( '%s story', '%s stories', $term->count, 'punch-nextgen' ), number_format_i18n( $term->count ) ) ); ?></small>
                    </a>
                <?php endforeach; ?>
            </div>
        </div>
    </section>

    <section class="png-section">
        <div class="png-container png-two-col">
            <div class="png-panel">
                <div class="png-section-heading">
                    <span><?php esc_html_e( 'Schools', 'punch-nextgen' ); ?></span>
                    <h2><?php esc_html_e( 'Featured School Showcase', 'punch-nextgen' ); ?></h2>
                </div>

                <?php
                if ( function_exists( 'png_core_render_school_showcase_preview' ) ) {
                    png_core_render_school_showcase_preview();
                } elseif ( post_type_exists( 'png_school' ) ) {
                    $school_query = new WP_Query(
                        array(
                            'post_type'      => 'png_school',
                            'posts_per_page' => 1,
                            'post_status'    => 'publish',
                        )
                    );

                    if ( $school_query->have_posts() ) :
                        while ( $school_query->have_posts() ) :
                            $school_query->the_post();
                            get_template_part( 'template-parts/content/content', 'story-card' );
                        endwhile;
                        wp_reset_postdata();
                    else :
                        echo '<p class="png-muted">' . esc_html__( 'School showcase content will appear here.', 'punch-nextgen' ) . '</p>';
                    endif;
                }
                ?>
            </div>

            <div class="png-panel png-panel--dark">
                <div class="png-section-heading">
                    <span><?php esc_html_e( 'Challenge', 'punch-nextgen' ); ?></span>
                    <h2><?php esc_html_e( 'Crack This Lite', 'punch-nextgen' ); ?></h2>
                </div>

                <?php
                if ( function_exists( 'png_core_render_crack_this_preview' ) ) {
                    png_core_render_crack_this_preview();
                } elseif ( post_type_exists( 'png_crack_this' ) ) {
                    $crack_query = new WP_Query(
                        array(
                            'post_type'      => 'png_crack_this',
                            'posts_per_page' => 1,
                            'post_status'    => 'publish',
                        )
                    );

                    if ( $crack_query->have_posts() ) :
                        while ( $crack_query->have_posts() ) :
                            $crack_query->the_post();
                            ?>
                            <h3><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
                            <p><?php echo esc_html( wp_trim_words( get_the_excerpt(), 20 ) ); ?></p>
                            <?php
                        endwhile;
                        wp_reset_postdata();
                    else :
                        echo '<p>' . esc_html__( 'Weekly puzzle preview will appear here.', 'punch-nextgen' ) . '</p>';
                    endif;
                }
                ?>
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
