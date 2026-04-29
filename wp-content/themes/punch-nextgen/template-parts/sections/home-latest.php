<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}
?>

<section class="png-site-section png-home-latest">
    <h2><?php esc_html_e( 'Latest Stories', 'punch-nextgen' ); ?></h2>

    <div class="png-card-grid">
        <?php
        $latest_query = new WP_Query(
            array(
                'post_type'           => 'post',
                'posts_per_page'      => 6,
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
            get_template_part( 'template-parts/content/content', 'none' );
        endif;
        ?>
    </div>
</section>
