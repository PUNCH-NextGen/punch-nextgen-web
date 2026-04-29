<?php
/**
 * Punch NextGen header.
 */

?><!doctype html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo( 'charset' ); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
<?php wp_body_open(); ?>

<a class="png-skip-link" href="#primary"><?php esc_html_e( 'Skip to content', 'punch-nextgen' ); ?></a>

<header class="png-header">
    <div class="png-topbar">
        <div class="png-container png-topbar__inner">
            <span class="png-topbar__tag"><?php esc_html_e( 'Punch NextGen', 'punch-nextgen' ); ?></span>
            <span class="png-topbar__text"><?php esc_html_e( 'News, learning and life skills for the next generation.', 'punch-nextgen' ); ?></span>
        </div>
    </div>

    <div class="png-container png-header__main">
        <a class="png-brand png-brand--image" href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home">
            <img src="<?php echo esc_url( png_theme_logo_url() ); ?>" alt="<?php echo esc_attr( get_bloginfo( 'name' ) ); ?>">
        </a>

        <button class="png-nav-toggle" type="button" aria-controls="png-primary-nav" aria-expanded="false">
            <span></span>
            <span></span>
            <span></span>
            <span class="screen-reader-text"><?php esc_html_e( 'Open menu', 'punch-nextgen' ); ?></span>
        </button>

        <nav id="png-primary-nav" class="png-primary-nav" aria-label="<?php esc_attr_e( 'Primary navigation', 'punch-nextgen' ); ?>">
            <?php
            wp_nav_menu(
                array(
                    'theme_location' => 'primary',
                    'container'      => false,
                    'menu_class'     => 'png-primary-nav__list',
                    'fallback_cb'    => function () {
                        ?>
                        <ul class="png-primary-nav__list">
                            <li><a href="<?php echo esc_url( home_url( '/' ) ); ?>">Home</a></li>
                            <li><a href="<?php echo esc_url( png_theme_get_page_url( 'school-showcase', '/school-showcase/' ) ); ?>">School Showcase</a></li>
                            <li><a href="<?php echo esc_url( png_theme_get_page_url( 'crack-this-lite', '/crack-this-lite/' ) ); ?>">Crack This Lite</a></li>
                            <li><a href="<?php echo esc_url( png_theme_get_page_url( 'leaderboards', '/leaderboards/' ) ); ?>">Leaderboards</a></li>
                            <li><a href="<?php echo esc_url( png_theme_get_page_url( 'teacher-guide-portal', '/teacher-guide-portal/' ) ); ?>">Teacher Guide Portal</a></li>
                            <li><a href="<?php echo esc_url( png_theme_get_page_url( 'my-profile', '/my-profile/' ) ); ?>">My Profile</a></li>
                            <li><a href="<?php echo esc_url( png_theme_get_page_url( 'contact-feedback', '/contact-feedback/' ) ); ?>">Contact / Feedback</a></li>
                        </ul>
                        <?php
                    },
                )
            );
            ?>
        </nav>

        <div class="png-header__actions">
            <button class="png-search-toggle" type="button" aria-expanded="false" aria-controls="png-search-panel">
                <?php esc_html_e( 'Search', 'punch-nextgen' ); ?>
            </button>

            <?php if ( is_user_logged_in() ) : ?>
                <a class="png-btn png-btn--dark" href="<?php echo esc_url( png_theme_get_page_url( 'my-profile', '/my-profile/' ) ); ?>">
                    <?php esc_html_e( 'My Profile', 'punch-nextgen' ); ?>
                </a>
            <?php else : ?>
                <a class="png-btn png-btn--dark" href="<?php echo esc_url( wp_login_url() ); ?>">
                    <?php esc_html_e( 'Login', 'punch-nextgen' ); ?>
                </a>
            <?php endif; ?>
        </div>
    </div>

    <div id="png-search-panel" class="png-search-panel" hidden>
        <div class="png-container">
            <?php get_search_form(); ?>
        </div>
    </div>

    <div class="png-category-strip">
        <div class="png-container png-category-strip__inner">
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

            foreach ( $category_names as $category_name ) {
                $term = get_term_by( 'name', $category_name, 'category' );

                if ( $term && ! is_wp_error( $term ) ) {
                    echo '<a href="' . esc_url( get_term_link( $term ) ) . '">' . esc_html( $category_name ) . '</a>';
                }
            }
            ?>
        </div>
    </div>
</header>
