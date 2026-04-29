<?php
/**
 * Single post template.
 *
 * Story rules:
 * - story content displays first
 * - poll appears only after story body
 * - digital comic appears after every story
 */

get_header();
?>

<main id="primary" class="site-main png-single">
    <section class="png-site-section">
        <?php
        while ( have_posts() ) :
            the_post();

            get_template_part( 'template-parts/content/content', 'single-story' );

            the_post_navigation(
                array(
                    'prev_text' => '<span class="nav-subtitle">' . esc_html__( 'Previous:', 'punch-nextgen' ) . '</span> <span class="nav-title">%title</span>',
                    'next_text' => '<span class="nav-subtitle">' . esc_html__( 'Next:', 'punch-nextgen' ) . '</span> <span class="nav-title">%title</span>',
                )
            );

            if ( comments_open() || get_comments_number() ) :
                comments_template();
            endif;

        endwhile;
        ?>
    </section>
</main>

<?php
get_footer();
