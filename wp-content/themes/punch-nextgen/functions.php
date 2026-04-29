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
