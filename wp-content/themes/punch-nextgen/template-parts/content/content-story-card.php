<?php
/**
 * Story card.
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}
?>

<article <?php post_class( 'png-card' ); ?>>
    <a class="png-card__image" href="<?php the_permalink(); ?>">
        <?php if ( has_post_thumbnail() ) : ?>
            <?php the_post_thumbnail( 'png_card' ); ?>
        <?php else : ?>
            <span class="png-card__image-placeholder"><?php esc_html_e( 'Punch NextGen', 'punch-nextgen' ); ?></span>
        <?php endif; ?>
    </a>

    <div class="png-card__body">
        <?php
        $category = get_the_category();
        if ( ! empty( $category ) ) :
            ?>
            <a class="png-chip" href="<?php echo esc_url( get_category_link( $category[0]->term_id ) ); ?>">
                <?php echo esc_html( $category[0]->name ); ?>
            </a>
        <?php endif; ?>

        <h3><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>

        <p><?php echo esc_html( wp_trim_words( get_the_excerpt(), 18 ) ); ?></p>

        <div class="png-card__meta">
            <span><?php echo esc_html( get_the_date() ); ?></span>
            <span><?php echo esc_html( png_theme_reading_time() ); ?></span>
        </div>
    </div>
</article>
