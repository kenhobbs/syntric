<?php
/**
 * Theme basic setup.
 *
 * @package syntric
 */
// Remove emoji bloat
remove_action( 'wp_head', 'print_emoji_detection_script', 7 );
remove_action( 'admin_print_scripts', 'print_emoji_detection_script' );
remove_action( 'wp_print_styles', 'print_emoji_styles' );
remove_action( 'admin_print_styles', 'print_emoji_styles' );
/**
 * Set number of revisions to keep by post type
 */
add_filter( 'wp_revisions_to_keep', 'syn_revisions_to_keep', 10, 2 );
if ( ! function_exists( 'syn_revisions_to_keep' ) ) {
	function syn_revisions_to_keep( $num, $post ) {
		$post_type = $post->post_type;
		if ( 'syn_calendar' == $post_type || 'syn_event' == $post_type ) {
			$num = 0;
		} else {
			$num = 5;
		}

		return $num;
	}
}
/**
 * Remove tag support from posts
 */
add_action( 'init', 'syn_unregister_taxonomy' );
if ( ! function_exists( 'syn_unregister_taxonomy' ) ) {
	function syn_unregister_taxonomy() {
		unregister_taxonomy_for_object_type( 'post_tag', 'post' );
	}
}
/**
 * Sets up theme defaults and registers support for various WordPress features.
 *
 * Note that this function is hooked into the after_setup_theme hook, which
 * runs before the init hook. The init hook is too late for some features, such
 * as indicating support for post thumbnails.
 */
add_action( 'after_setup_theme', 'syn_theme_setup' );
if ( ! function_exists( 'syn_theme_setup' ) ) {
	function syn_theme_setup() {
		//~~~~~~~~~ Add theme supports
		/**
		 * Add support for RSS feeds
		 * Wordpress adds the feed tags to the document head automatically
		 */
		//add_theme_support( 'automatic-feed-links' );
		/**
		 * Add support for title tag
		 * WordPress manages the document title - remove <title> tag from document head.
		 */
		add_theme_support( 'title-tag' );
		/**
		 * Add support for HTML5 forms
		 */
		add_theme_support(
			'html5', array(
				'search-form',
				'comment-form',
				'comment-list',
				'gallery',
				'caption',
			)
		);
		/**
		 * Add support for post thumbnails
		 */
		//add_theme_support( 'post-thumbnails' );
		/**
		 * Add thumbnail size for admin lists called icon
		 */
		add_image_size( 'icon', 40, 40, true );
		/**
		 * Add support for widget edit icons in customizer
		 */
		add_theme_support( 'customize-selective-refresh-widgets' );
		/**
		 * Add theme support for post formats
		 */
		//add_theme_support( 'post-formats', array('aside', 'image', 'video', 'quote', 'link', ) );
		/**
		 * Add theme support for custom background
		 */
		//add_theme_support( 'custom-background', apply_filters( 'syntric_custom_background_args', array( 'default-color' => 'ffffff', 'default-image' => '', ) ) );
		/**
		 * Add theme support for custom logo
		 */
		add_theme_support( 'custom-logo' );
		/**
		 * Add support for custom header images
		 */
		add_theme_support( 'custom-header', array(
			'default-image'  => get_template_directory_uri() . '/assets/images/default-header-1.png',
			'random-default' => true,
			'width'          => 1920,
			'height'         => 500,
			'flex-height'    => true,
			'flex-width'     => true,
			'uploads'        => true,
		) );
		/**
		 * Add support for editor stylesheet
		 */
		add_theme_support( 'editor-style' );
		//~~~~~~~~~ Misc theme features
		/**
		 * Add ability to integrate translations using textdomain
		 * Translations can be filed in the /assets/languages/ directory.
		 */
		load_theme_textdomain( 'syntric', get_template_directory() . '/assets/languages' );
		//~~~~~~~~~ Register theme features
		/**
		 * Register nav menu locations
		 */
		register_nav_menus(
			array(
				'primary' => __( 'Primary Menu', 'syntric' ),
			)
		);
		/**
		 * Register multiple default headers when randomizing headers by default
		 */
		register_default_headers( array(
			'header-1' => array(
				'url'           => get_template_directory_uri() . '/assets/images/default-header-1.png',
				'thumbnail_url' => get_template_directory_uri() . '/assets/images/default-header-1.png',
				'description'   => __( 'Default header 1', 'syntric' ),
			),
			'header-2' => array(
				'url'           => get_template_directory_uri() . '/assets/images/default-header-2.png',
				'thumbnail_url' => get_template_directory_uri() . '/assets/images/default-header-2.png',
				'description'   => __( 'Default header 2', 'syntric' ),
			),
			'header-3' => array(
				'url'           => get_template_directory_uri() . '/assets/images/default-header-3.png',
				'thumbnail_url' => get_template_directory_uri() . '/assets/images/default-header-3.png',
				'description'   => __( 'Default header 3', 'syntric' ),
			),
			'header-4' => array(
				'url'           => get_template_directory_uri() . '/assets/images/default-header-4.png',
				'thumbnail_url' => get_template_directory_uri() . '/assets/images/default-header-4.png',
				'description'   => __( 'Default header 4', 'syntric' ),
			),
			'header-5' => array(
				'url'           => get_template_directory_uri() . '/assets/images/default-header-5.png',
				'thumbnail_url' => get_template_directory_uri() . '/assets/images/default-header-5.png',
				'description'   => __( 'Default header 5', 'syntric' ),
			),
			'header-6' => array(
				'url'           => get_template_directory_uri() . '/assets/images/default-header-6.png',
				'thumbnail_url' => get_template_directory_uri() . '/assets/images/default-header-6.png',
				'description'   => __( 'Default header 6', 'syntric' ),
			),
			'header-7' => array(
				'url'           => get_template_directory_uri() . '/assets/images/default-header-7.png',
				'thumbnail_url' => get_template_directory_uri() . '/assets/images/default-header-7.png',
				'description'   => __( 'Default header 7', 'syntric' ),
			),
			'header-8' => array(
				'url'           => get_template_directory_uri() . '/assets/images/default-header-8.png',
				'thumbnail_url' => get_template_directory_uri() . '/assets/images/default-header-8.png',
				'description'   => __( 'Default header 8', 'syntric' ),
			),
		) );
		//~~~~~~~~~ Setup defaults for customizer settings
		/**
		 * Default posts index style
		 */
		/*$syntric_posts_index_style = get_theme_mod( 'syntric_posts_index_style' );
		if ( '' == $syntric_posts_index_style ) {
			set_theme_mod( 'syntric_posts_index_style', 'default' );
		}*/
		// todo: clean this up...all but nav & body container width have been abstracted to components, even page formats (e.g. sidebars)
		/**
		 * Default nav container width
		 */
		/*$syntric_nav_container_type = get_theme_mod( 'syntric_nav_container_type' );
		if ( '' == $syntric_nav_container_type ) {
			set_theme_mod( 'syntric_nav_container_type', 'container-fluid' );
		}*/
		/**
		 * Default body container width
		 */
		$syntric_container_type = get_theme_mod( 'syntric_container_type' );
		if ( '' == $syntric_container_type ) {
			set_theme_mod( 'syntric_container_type', 'container' );
		}
		/**
		 * Default footer container width
		 */
		/*$syntric_footer_container_type = get_theme_mod( 'syntric_footer_container_type' );
		if ( '' == $syntric_footer_container_type ) {
			set_theme_mod( 'syntric_footer_container_type', 'container-fluid' );
		}*/
		/**
		 * Default page template
		 */
		$syntric_sidebar_position = get_theme_mod( 'syntric_sidebar_position' );
		if ( '' == $syntric_sidebar_position ) {
			set_theme_mod( 'syntric_sidebar_position', 'left' );
		}
	}
}
/**
 * Disable automatic email sent upon password or email change
 */
add_filter( 'send_email_change_email', '__return_false' );
/**
 * Print <style> section in footer for custom header height (by screen size)
 */
add_action( 'wp_print_footer_scripts', 'syn_print_banner_styles', 99 );
if ( ! function_exists( 'syn_print_banner_styles' ) ) {
	function syn_print_banner_styles() {
		$cookies = $_COOKIE;
		if ( isset( $cookies[ 'bannerHeight' ] ) ) {
			$n = "\n";
			echo $n . '<style type="text/css">';
			echo '.banner-wrapper {';
			echo 'height: ' . $cookies[ 'bannerHeight' ] . 'px;';
			echo '}';
			echo '</style>';
		}
	}
}
/**
 * Set content width
 * todo: is this necessary?  wth does it do?
 */
/*if ( ! isset( $content_width ) ) {
	$content_width = 640; // pixels
}*/