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
			add_theme_support( 'html5', [ 'search-form', 'comment-form', 'comment-list', 'gallery', 'caption', ] );
			/**
			 * Additional image sizes that are auto-generated upon upload of an image into the Media Library
			 */
			//add_image_size( 'icon', 50, 50 );
			//remove_image_size( 'thumbnail' );
			//remove_image_size( 'medium' );
			//remove_image_size( 'medium_large' );
			//remove_image_size( 'large' );
			add_image_size( 'icon', 50, 50, true );
			add_image_size( 'thumbnail', 100, 100, true );
			add_image_size( 'medium', 200, 200, true );
			add_image_size( 'medium_large', 400, 400, true );
			add_image_size( 'large', 800, 800, true );
			//add_image_size( 'large', 800, 800, true );
			//add_image_size( 'x-large', 800, 800, true );
			add_image_size( 'banner', 1920, 500, true );
			/**
			 * Add support for post thumbnails (Featured Image) in posts (only)
			 */
			//add_theme_support( 'post-thumbnails' );
			add_theme_support( 'post-thumbnails', [
				'post',
				'page',
			] );
			/**
			 * Set default Post Thumbnail size
			 */
			//set_post_thumbnail_size( 'large' ); // 1920 x 500, cropped from center preferred size for banner (custom header)
			/**
			 * Add support for widget edit icons in customizer
			 */
			add_theme_support( 'customize-selective-refresh-widgets' );
			/**
			 * Add theme support for post formats
			 */
			//add_theme_support( 'post-formats', array( 'aside', 'image', 'video', 'quote', 'link' ) );
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
			add_theme_support( 'custom-header', [ 'default-image' => get_template_directory_uri() . '/assets/images/default-header-1.png', 'random-default' => true, 'width' => 1920,
			                                      'height'        => 500, 'flex-height' => true, 'flex-width' => true, 'uploads' => true, ] );
			/**
			 * Add support for editor stylesheet
			 *
			 * This is deprecated.  Using add_editor_style() in admin_init action hook
			 */
			//add_theme_support( 'editor-style' );
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
			register_nav_menus( [ 'primary' => __( 'Primary Menu', 'syntric' ), 'notices' => __( 'Notices Menu', 'syntric' ), 'quick' => __( 'Quick Menu', 'syntric' ),
			                      'sitemap' => __( 'Sitemap', 'syntric' ), ] );
			/**
			 * Register multiple default headers when randomizing headers by default
			 */
			register_default_headers( [ 'header-1' => [ 'url'           => get_template_directory_uri() . '/assets/images/default-header-1.png',
			                                            'thumbnail_url' => get_template_directory_uri() . '/assets/images/default-header-1.png',
			                                            'description'   => __( 'Default header 1', 'syntric' ) ],
			                            'header-2' => [ 'url'           => get_template_directory_uri() . '/assets/images/default-header-2.png',
			                                            'thumbnail_url' => get_template_directory_uri() . '/assets/images/default-header-2.png',
			                                            'description'   => __( 'Default header 2', 'syntric' ) ],
			                            'header-3' => [ 'url'           => get_template_directory_uri() . '/assets/images/default-header-3.png',
			                                            'thumbnail_url' => get_template_directory_uri() . '/assets/images/default-header-3.png',
			                                            'description'   => __( 'Default header 3', 'syntric' ) ],
			                            'header-4' => [ 'url'           => get_template_directory_uri() . '/assets/images/default-header-4.png',
			                                            'thumbnail_url' => get_template_directory_uri() . '/assets/images/default-header-4.png',
			                                            'description'   => __( 'Default header 4', 'syntric' ) ] ] );
			//~~~~~~~~~ Setup defaults for customizer settings
			// todo: can these be eliminated?
			/**
			 * Default body container width
			 */
			$syntric_container_type = get_theme_mod( 'syntric_container_type' );
			if ( '' == $syntric_container_type ) {
				set_theme_mod( 'syntric_container_type', 'container' );
			}
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
	 * Rename image sizes
	 */
	add_filter( 'image_size_names_choose', 'syn_image_size_names' );
	function syn_image_size_names( $sizes ) {
		return array_merge( $sizes, [ 'icon'         => __( 'Icon (50x50)' ), 'thumbnail' => __( 'Thumbnail (100x100)' ), 'medium' => __( 'Medium (200x200)' ),
		                              'medium_large' => __( 'Medium-Large (400x400)' ), 'large' => __( 'Large (800x800)' ), ] );
	}

	/**
	 * Disable automatic email sent upon password or email change
	 *
	 * $user - The original user array
	 * $userdata - The updated user array
	 *
	 * send_password_change_email - Filters whether to send the password change email: $send, $user, $userdata
	 * send_email_change_email - Filters whether to send the email change email: $send, $user, $userdata
	 *
	 * $pass_change_email/$email_change_email = [ to, subject, message, headers]
	 * $user
	 * $userdata
	 *
	 * password_change_email - Filters the contents of the email sent when the user’s password is changed.
	 * email_change_email - Filters the contents of the email sent when the user’s email is changed.
	 */
	add_filter( 'send_email_change_email', 'syn_send_change_email' );
	add_filter( 'send_password_change_email', 'syn_send_change_email' );
	function syn_send_change_email() {
		if ( ( ! syn_is_dev() && ! syn_is_staging() ) || syn_current_user_can( 'syntric' ) ) {
			//slog( 'email will be sent');
			return true;
		}

		//slog( 'email will not be sent' );
		return false;
	}

	/*
	 * The content of the email.
	 * The following strings have a special meaning and will get replaced dynamically:
	 * ###USERNAME### The current user's username
	 * ###ADMIN_EMAIL### The admin email in case this was unexpected.
	 * ###EMAIL### The user's email address.
	 * ###SITENAME### The name of the site.
	 * ###SITEURL### The URL to the site.
	 */
	add_filter( 'email_change_email', 'syn_email_change_email', 10, 3 );
	function syn_email_change_email( $email, $user, $userdata ) {
		/*slog( 'syn_email_change_email triggered' );
		slog( $email );
		slog( $user );
		slog( $userdata );*/
		if ( syn_is_dev() || syn_is_staging() ) {
			$syntric_user = syn_syntric_user();
			$to           = $syntric_user->user_email;
		} else {
			$to = $userdata[ 'user_email' ];
		}
		$email = [ 'to' => $to, 'subject' => 'Website email address changed', 'message' => $email[ 'message' ], 'headers' => $email[ 'headers' ] ];

		return $email;
	}

	add_filter( 'password_change_email', 'syn_password_change_email', 10, 3 );
	function syn_password_change_email( $email, $user, $userdata ) {
		/*slog( 'syn_password_change_email triggered' );
		slog( $email );
		slog( $user );
		slog( $userdata );*/
		if ( syn_is_dev() || syn_is_staging() ) {
			$syntric_user = syn_syntric_user();
			$to           = $syntric_user->user_email;
		} else {
			$to = $userdata[ 'user_email' ];
		}
		$email = [ 'to' => $to, 'subject' => 'Website password changed', 'message' => $email[ 'message' ], 'headers' => $email[ 'headers' ] ];

		return $email;
	}

	/**
	 * Print <style> section in footer for custom header height (by screen size)
	 */
	//add_action( 'wp_print_footer_scripts', 'syn_print_banner_styles', 99 );
	function syn_print_banner_styles() {
		$cookies = $_COOKIE;
		if ( isset( $cookies[ 'bannerHeight' ] ) ) {
			$lb  = "\n";
			$tab = "\t";
			echo $lb . '<style type="text/css">' . $lb;
			echo $tab . '.banner-wrapper {' . $lb;
			echo $tab . $tab . 'height: ' . $cookies[ 'bannerHeight' ] . 'px;' . $lb;
			echo $tab . '}' . $lb;
			echo '</style>' . $lb;
		}
	}

	/**
	 * Set content width - no in use
	 */
	/*if ( ! isset( $content_width ) ) {
		$content_width = 50; // pixels
	}*/