<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}
?>

<article id="post-<?php the_ID(); ?>" <?php post_class( 'png-story-card' ); ?>>
    <a href="<?php the_permalink(); ?>">
        <?php if ( has_post_thumbnail() ) : ?>
            <?php the_post_thumbnail( 'png_card_medium' ); ?>
        <?php endif; ?>

        <h3><?php the_title(); ?></h3>
    </a>

    <div class="png-story-card__meta">
        <span><?php echo esc_html( get_the_date() ); ?></span>
    </div>
</article>
