<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}
?>

<article id="post-<?php the_ID(); ?>" <?php post_class( 'png-single-story' ); ?>>
    <header class="png-single-story__header">
        <?php the_title( '<h1>', '</h1>' ); ?>
        <div class="png-single-story__meta">
            <span><?php echo esc_html( get_the_date() ); ?></span>
        </div>
    </header>

    <?php if ( has_post_thumbnail() ) : ?>
        <div class="png-single-story__image">
            <?php the_post_thumbnail( 'large' ); ?>
        </div>
    <?php endif; ?>

    <div class="png-single-story__content">
        <?php the_content(); ?>
    </div>

    <?php
    get_template_part( 'template-parts/components/story', 'poll' );
    get_template_part( 'template-parts/components/digital', 'comic' );
    png_theme_render_ad_slot( 'article_after_story' );
    ?>
</article>
