<?php
/**
 * Punch NextGen functions and definitions
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package Punch_NextGen
 */

if ( ! defined( '_S_VERSION' ) ) {
	// Replace the version number of the theme on each release.
	define( '_S_VERSION', '1.0.0' );
}

/**
 * Sets up theme defaults and registers support for various WordPress features.
 *
 * Note that this function is hooked into the after_setup_theme hook, which
 * runs before the init hook. The init hook is too late for some features, such
 * as indicating support for post thumbnails.
 */
function punch_nextgen_setup() {
	/*
		* Make theme available for translation.
		* Translations can be filed in the /languages/ directory.
		* If you're building a theme based on Punch NextGen, use a find and replace
		* to change 'punch-nextgen' to the name of your theme in all the template files.
		*/
	load_theme_textdomain( 'punch-nextgen', get_template_directory() . '/languages' );

	// Add default posts and comments RSS feed links to head.
	add_theme_support( 'automatic-feed-links' );

	/*
		* Let WordPress manage the document title.
		* By adding theme support, we declare that this theme does not use a
		* hard-coded <title> tag in the document head, and expect WordPress to
		* provide it for us.
		*/
	add_theme_support( 'title-tag' );

	/*
		* Enable support for Post Thumbnails on posts and pages.
		*
		* @link https://developer.wordpress.org/themes/functionality/featured-images-post-thumbnails/
		*/
	add_theme_support( 'post-thumbnails' );

	// This theme uses wp_nav_menu() in one location.
	register_nav_menus(
		array(
			'menu-1' => esc_html__( 'Primary', 'punch-nextgen' ),
		)
	);

	/*
		* Switch default core markup for search form, comment form, and comments
		* to output valid HTML5.
		*/
	add_theme_support(
		'html5',
		array(
			'search-form',
			'comment-form',
			'comment-list',
			'gallery',
			'caption',
			'style',
			'script',
		)
	);

	// Set up the WordPress core custom background feature.
	add_theme_support(
		'custom-background',
		apply_filters(
			'punch_nextgen_custom_background_args',
			array(
				'default-color' => 'ffffff',
				'default-image' => '',
			)
		)
	);

	// Add theme support for selective refresh for widgets.
	add_theme_support( 'customize-selective-refresh-widgets' );

	/**
	 * Add support for core custom logo.
	 *
	 * @link https://codex.wordpress.org/Theme_Logo
	 */
	add_theme_support(
		'custom-logo',
		array(
			'height'      => 250,
			'width'       => 250,
			'flex-width'  => true,
			'flex-height' => true,
		)
	);
}
add_action( 'after_setup_theme', 'punch_nextgen_setup' );

/**
 * Set the content width in pixels, based on the theme's design and stylesheet.
 *
 * Priority 0 to make it available to lower priority callbacks.
 *
 * @global int $content_width
 */
function punch_nextgen_content_width() {
	$GLOBALS['content_width'] = apply_filters( 'punch_nextgen_content_width', 640 );
}
add_action( 'after_setup_theme', 'punch_nextgen_content_width', 0 );

/**
 * Register widget area.
 *
 * @link https://developer.wordpress.org/themes/functionality/sidebars/#registering-a-sidebar
 */
function punch_nextgen_widgets_init() {
	register_sidebar(
		array(
			'name'          => esc_html__( 'Sidebar', 'punch-nextgen' ),
			'id'            => 'sidebar-1',
			'description'   => esc_html__( 'Add widgets here.', 'punch-nextgen' ),
			'before_widget' => '<section id="%1$s" class="widget %2$s">',
			'after_widget'  => '</section>',
			'before_title'  => '<h2 class="widget-title">',
			'after_title'   => '</h2>',
		)
	);
}
add_action( 'widgets_init', 'punch_nextgen_widgets_init' );

/**
 * Enqueue scripts and styles.
 */
function punch_nextgen_scripts() {
	wp_enqueue_style( 'punch-nextgen-style', get_stylesheet_uri(), array(), _S_VERSION );
	wp_style_add_data( 'punch-nextgen-style', 'rtl', 'replace' );

	wp_enqueue_script( 'punch-nextgen-navigation', get_template_directory_uri() . '/js/navigation.js', array(), _S_VERSION, true );

	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}
}
add_action( 'wp_enqueue_scripts', 'punch_nextgen_scripts' );

/**
 * Implement the Custom Header feature.
 */
require get_template_directory() . '/inc/custom-header.php';

/**
 * Custom template tags for this theme.
 */
require get_template_directory() . '/inc/template-tags.php';

/**
 * Functions which enhance the theme by hooking into WordPress.
 */
require get_template_directory() . '/inc/template-functions.php';

/**
 * Customizer additions.
 */
require get_template_directory() . '/inc/customizer.php';

/**
 * Load Jetpack compatibility file.
 */
if ( defined( 'JETPACK__VERSION' ) ) {
	require get_template_directory() . '/inc/jetpack.php';
}


/**
 * Punch NextGen theme additions.
 */
require get_template_directory() . '/inc/nextgen-loader.php';

/**
 * Punch NextGen urgent UI layer.
 * Handles the first production-ready header, footer, homepage and article UI assets.
 */
if ( ! function_exists( 'png_theme_urgent_asset_version' ) ) {
    function png_theme_urgent_asset_version( $relative_path ) {
        $file = get_template_directory() . '/' . ltrim( $relative_path, '/' );

        if ( file_exists( $file ) ) {
            return filemtime( $file );
        }

        return wp_get_theme()->get( 'Version' );
    }
}

if ( ! function_exists( 'png_theme_urgent_setup' ) ) {
    function png_theme_urgent_setup() {
        add_theme_support( 'title-tag' );
        add_theme_support( 'post-thumbnails' );
        add_theme_support( 'custom-logo' );
        add_theme_support( 'html5', array( 'search-form', 'comment-form', 'comment-list', 'gallery', 'caption', 'style', 'script' ) );

        add_image_size( 'png_hero', 960, 560, true );
        add_image_size( 'png_card', 520, 340, true );
        add_image_size( 'png_thumb', 240, 160, true );

        register_nav_menus(
            array(
                'primary'       => esc_html__( 'Primary Menu', 'punch-nextgen' ),
                'category_menu' => esc_html__( 'Category Menu', 'punch-nextgen' ),
                'footer_menu'   => esc_html__( 'Footer Menu', 'punch-nextgen' ),
            )
        );
    }
}
add_action( 'after_setup_theme', 'png_theme_urgent_setup', 30 );

if ( ! function_exists( 'png_theme_urgent_assets' ) ) {
    function png_theme_urgent_assets() {
        wp_enqueue_style(
            'png-urgent-ui',
            get_template_directory_uri() . '/assets/css/urgent-ui.css',
            array(),
            png_theme_urgent_asset_version( 'assets/css/urgent-ui.css' )
        );

        wp_enqueue_script(
            'png-urgent-ui',
            get_template_directory_uri() . '/assets/js/urgent-ui.js',
            array(),
            png_theme_urgent_asset_version( 'assets/js/urgent-ui.js' ),
            true
        );
    }
}
add_action( 'wp_enqueue_scripts', 'png_theme_urgent_assets', 40 );

if ( ! function_exists( 'png_theme_get_page_url' ) ) {
    function png_theme_get_page_url( $slug, $fallback = '/' ) {
        $page = get_page_by_path( $slug );

        if ( $page ) {
            return get_permalink( $page );
        }

        return home_url( $fallback );
    }
}

if ( ! function_exists( 'png_theme_render_ad_slot_safe' ) ) {
    function png_theme_render_ad_slot_safe( $slot ) {
        if ( function_exists( 'png_core_render_ad_slot' ) ) {
            png_core_render_ad_slot( $slot );
            return;
        }

        echo '<div class="png-ad-slot png-ad-slot--empty" aria-hidden="true"></div>';
    }
}

if ( ! function_exists( 'png_theme_reading_time' ) ) {
    function png_theme_reading_time( $post_id = null ) {
        $post_id = $post_id ? $post_id : get_the_ID();
        $content = get_post_field( 'post_content', $post_id );
        $words   = str_word_count( wp_strip_all_tags( $content ) );
        $minutes = max( 1, (int) ceil( $words / 220 ) );

        return sprintf( _n( '%s min read', '%s mins read', $minutes, 'punch-nextgen' ), number_format_i18n( $minutes ) );
    }
}

/**
 * Punch NextGen logo image URL.
 * Replace this later with the final logo URL if needed.
 */
if ( ! function_exists( 'png_theme_logo_url' ) ) {
    function png_theme_logo_url() {
        $logo_file = get_template_directory() . '/assets/images/punch-nextgen-logo.svg';
        $version   = file_exists( $logo_file ) ? filemtime( $logo_file ) : time();

        return get_template_directory_uri() . '/assets/images/punch-nextgen-logo.svg?v=' . $version;
    }
}

/**
 * Render homepage School Showcase preview.
 * Uses the core plugin helper if available, otherwise falls back to the latest published School CPT.
 */
if ( ! function_exists( 'png_theme_render_home_school_showcase' ) ) {
    function png_theme_render_home_school_showcase() {
        if ( function_exists( 'png_core_render_school_showcase_preview' ) ) {
            png_core_render_school_showcase_preview();
            return;
        }

        if ( ! post_type_exists( 'png_school' ) ) {
            echo '<p class="png-muted">' . esc_html__( 'School showcase content will appear here.', 'punch-nextgen' ) . '</p>';
            return;
        }

        $school_query = new WP_Query(
            array(
                'post_type'      => 'png_school',
                'posts_per_page' => 1,
                'post_status'    => 'publish',
            )
        );

        if ( ! $school_query->have_posts() ) {
            echo '<p class="png-muted">' . esc_html__( 'School showcase content will appear here.', 'punch-nextgen' ) . '</p>';
            return;
        }

        echo '<div class="png-school-showcase-card">';

        while ( $school_query->have_posts() ) {
            $school_query->the_post();

            $school_location = '';
            $location_terms  = get_the_terms( get_the_ID(), 'png_school_location' );

            if ( ! empty( $location_terms ) && ! is_wp_error( $location_terms ) ) {
                $school_location = $location_terms[0]->name;
            }

            $official_name = '';

            if ( function_exists( 'get_field' ) ) {
                $official_name = get_field( 'png_school_official_name', get_the_ID() );
            }

            if ( empty( $official_name ) ) {
                $official_name = get_the_title();
            }
            ?>

            <article class="png-school-showcase-card__inner">
                <a class="png-school-showcase-card__image" href="<?php the_permalink(); ?>">
                    <?php if ( has_post_thumbnail() ) : ?>
                        <?php the_post_thumbnail( 'png_card' ); ?>
                    <?php else : ?>
                        <span><?php esc_html_e( 'School Showcase', 'punch-nextgen' ); ?></span>
                    <?php endif; ?>
                </a>

                <div class="png-school-showcase-card__content">
                    <?php if ( $school_location ) : ?>
                        <span class="png-chip"><?php echo esc_html( $school_location ); ?></span>
                    <?php else : ?>
                        <span class="png-chip"><?php esc_html_e( 'Featured School', 'punch-nextgen' ); ?></span>
                    <?php endif; ?>

                    <h3><a href="<?php the_permalink(); ?>"><?php echo esc_html( $official_name ); ?></a></h3>

                    <p>
                        <?php
                        $summary = has_excerpt() ? get_the_excerpt() : get_the_content();
                        echo esc_html( wp_trim_words( $summary, 24 ) );
                        ?>
                    </p>

                    <a class="png-read-more" href="<?php the_permalink(); ?>">
                        <?php esc_html_e( 'View school', 'punch-nextgen' ); ?>
                    </a>
                </div>
            </article>

            <?php
        }

        echo '</div>';

        wp_reset_postdata();
    }
}

/**
 * Get ACF value safely, with post meta fallback.
 */
if ( ! function_exists( 'png_theme_get_field_value' ) ) {
    function png_theme_get_field_value( $field_name, $post_id = null ) {
        $post_id = $post_id ? $post_id : get_the_ID();

        if ( function_exists( 'get_field' ) ) {
            $value = get_field( $field_name, $post_id );

            if ( '' !== $value && null !== $value && false !== $value ) {
                return $value;
            }
        }

        return get_post_meta( $post_id, $field_name, true );
    }
}

/**
 * Homepage School Showcase card.
 */
if ( ! function_exists( 'png_theme_render_home_school_showcase_v2' ) ) {
    function png_theme_render_home_school_showcase_v2() {
        if ( ! post_type_exists( 'png_school' ) ) {
            echo '<p class="png-muted">' . esc_html__( 'School showcase content will appear here.', 'punch-nextgen' ) . '</p>';
            return;
        }

        $school_query = new WP_Query(
            array(
                'post_type'      => 'png_school',
                'posts_per_page' => 1,
                'post_status'    => 'publish',
            )
        );

        if ( ! $school_query->have_posts() ) {
            echo '<p class="png-muted">' . esc_html__( 'School showcase content will appear here.', 'punch-nextgen' ) . '</p>';
            return;
        }

        while ( $school_query->have_posts() ) :
            $school_query->the_post();

            $official_name = png_theme_get_field_value( 'png_school_official_name', get_the_ID() );
            $official_name = $official_name ? $official_name : get_the_title();

            $summary = has_excerpt() ? get_the_excerpt() : get_the_content();

            $location = '';
            $locations = get_the_terms( get_the_ID(), 'png_school_location' );
            if ( ! empty( $locations ) && ! is_wp_error( $locations ) ) {
                $location = $locations[0]->name;
            }
            ?>
            <article class="png-feature-card-v2 png-feature-card-v2--school">
                <a class="png-feature-card-v2__media" href="<?php the_permalink(); ?>">
                    <?php if ( has_post_thumbnail() ) : ?>
                        <?php the_post_thumbnail( 'png_card' ); ?>
                    <?php else : ?>
                        <span>PN</span>
                    <?php endif; ?>
                </a>

                <div class="png-feature-card-v2__body">
                    <span class="png-mini-label">
                        <?php echo esc_html( $location ? $location : __( 'Featured School', 'punch-nextgen' ) ); ?>
                    </span>

                    <h3><a href="<?php the_permalink(); ?>"><?php echo esc_html( $official_name ); ?></a></h3>

                    <p><?php echo esc_html( wp_trim_words( $summary, 22 ) ); ?></p>

                    <a class="png-arrow-link" href="<?php the_permalink(); ?>">
                        <?php esc_html_e( 'View school showcase', 'punch-nextgen' ); ?>
                    </a>
                </div>
            </article>
            <?php
        endwhile;

        wp_reset_postdata();
    }
}

/**
 * Homepage Crack This Lite card.
 */
if ( ! function_exists( 'png_theme_render_home_crack_this_v2' ) ) {
    function png_theme_render_home_crack_this_v2() {
        if ( ! post_type_exists( 'png_crack_this' ) ) {
            ?>
            <div class="png-crack-card-v2">
                <span class="png-mini-label"><?php esc_html_e( 'Weekly Challenge', 'punch-nextgen' ); ?></span>
                <h3><?php esc_html_e( 'Crack This Lite is loading soon.', 'punch-nextgen' ); ?></h3>
                <p><?php esc_html_e( 'A light weekly puzzle will appear here for students and young readers.', 'punch-nextgen' ); ?></p>
                <a class="png-arrow-link" href="<?php echo esc_url( png_theme_get_page_url( 'crack-this-lite', '/crack-this-lite/' ) ); ?>">
                    <?php esc_html_e( 'Try it', 'punch-nextgen' ); ?>
                </a>
            </div>
            <?php
            return;
        }

        $crack_query = new WP_Query(
            array(
                'post_type'      => 'png_crack_this',
                'posts_per_page' => 1,
                'post_status'    => 'publish',
            )
        );

        if ( ! $crack_query->have_posts() ) {
            ?>
            <div class="png-crack-card-v2">
                <span class="png-mini-label"><?php esc_html_e( 'Weekly Challenge', 'punch-nextgen' ); ?></span>
                <h3><?php esc_html_e( 'A new puzzle will appear here.', 'punch-nextgen' ); ?></h3>
                <p><?php esc_html_e( 'Publish a Crack This Lite item to activate this section.', 'punch-nextgen' ); ?></p>
                <a class="png-arrow-link" href="<?php echo esc_url( png_theme_get_page_url( 'crack-this-lite', '/crack-this-lite/' ) ); ?>">
                    <?php esc_html_e( 'Open Crack This Lite', 'punch-nextgen' ); ?>
                </a>
            </div>
            <?php
            return;
        }

        while ( $crack_query->have_posts() ) :
            $crack_query->the_post();

            $points = png_theme_get_field_value( 'png_points_value', get_the_ID() );
            $reveal = png_theme_get_field_value( 'png_reveal_date', get_the_ID() );
            ?>
            <article class="png-crack-card-v2">
                <div class="png-crack-card-v2__top">
                    <span class="png-mini-label"><?php esc_html_e( 'Weekly Challenge', 'punch-nextgen' ); ?></span>

                    <?php if ( $points ) : ?>
                        <span class="png-points-pill">
                            <?php echo esc_html( sprintf( __( '%s pts', 'punch-nextgen' ), $points ) ); ?>
                        </span>
                    <?php endif; ?>
                </div>

                <h3><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>

                <p>
                    <?php
                    $summary = has_excerpt() ? get_the_excerpt() : get_the_content();
                    echo esc_html( wp_trim_words( $summary, 26 ) );
                    ?>
                </p>

                <?php if ( $reveal ) : ?>
                    <small><?php echo esc_html( sprintf( __( 'Answer reveal: %s', 'punch-nextgen' ), $reveal ) ); ?></small>
                <?php endif; ?>

                <a class="png-arrow-link" href="<?php the_permalink(); ?>">
                    <?php esc_html_e( 'Try the puzzle', 'punch-nextgen' ); ?>
                </a>
            </article>
            <?php
        endwhile;

        wp_reset_postdata();
    }
}
