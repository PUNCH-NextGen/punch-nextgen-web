<?php
/**
 * Punch NextGen homepage template.
 */

get_header();
?>

<main id="primary" class="site-main png-front-page">
    <?php
    get_template_part( 'template-parts/sections/home', 'hero' );
    get_template_part( 'template-parts/sections/home', 'latest' );
    get_template_part( 'template-parts/sections/home', 'category-highlights' );
    get_template_part( 'template-parts/sections/home', 'school-showcase' );
    get_template_part( 'template-parts/sections/home', 'leaderboard-preview' );
    get_template_part( 'template-parts/sections/home', 'crack-this-preview' );
    get_template_part( 'template-parts/sections/home', 'ads' );
    ?>
</main>

<?php
get_footer();
