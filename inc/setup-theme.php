<?php
	
	add_action( 'after_setup_theme', 'syntric_setup_theme' );
	if( ! function_exists( 'syntric_setup_theme' ) ) :
		/**
		 * Remove emoji stuff
		 */
		remove_action( 'wp_head', 'print_emoji_detection_script', 7 );
		remove_action( 'admin_print_scripts', 'print_emoji_detection_script' );
		remove_action( 'wp_print_styles', 'print_emoji_styles' );
		remove_action( 'admin_print_styles', 'print_emoji_styles' );
		
		/**
		 * Sets up theme defaults and registers support for various WordPress features.
		 *
		 * Note that this function is hooked into the after_setup_theme hook, which
		 * runs before the init hook. The init hook is too late for some features, such
		 * as indicating support for post thumbnails.
		 */
		function syntric_setup_theme() {
			/*
			 * Make theme available for translation.
			 * Translations can be filed in the /languages/ directory.
			 * If you're building a theme based on Twenty Nineteen, use a find and replace
			 * to change 'syntric' to the name of your theme in all the template files.
			 */
			load_theme_textdomain( 'syntric', get_template_directory() . '/assets/languages' );
			// Add default posts and comments RSS feed links to head.
			//add_theme_support( 'automatic-feed-links' );
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
			/**
			 * Additional image sizes that are auto-generated upon upload of an image into the Media Library
			 */
			add_image_size( 'icon', 50, 50, 1 );
			//add_image_size( 'thumbnail', 100, 100, true );
			update_option( 'thumbnail_size_w', 100 );
			update_option( 'thumbnail_size_h', 100 );
			update_option( 'thumbnail_crop', 1 );
			//add_image_size( 'medium', 200, 200, true );
			update_option( 'medium_size_w', 200 );
			update_option( 'medium_size_h', 200 );
			update_option( 'medium_crop', 0 );
			//add_image_size( 'medium_large', 400, 400, 1 );
			update_option( 'medium_large_size_w', 400 );
			update_option( 'medium_large_size_h', 400 );
			update_option( 'medium_large_crop', 0 );
			//add_image_size( 'large', 800, 800, true );
			update_option( 'large_size_w', 800 );
			update_option( 'large_size_h', 800 );
			update_option( 'large_crop', 0 );
			add_image_size( 'banner', 1920, 500, 0 );
			// set_post_thumbnail_size( 1568, 9999 ); this is set above with update_option()
			// Register nav menu locations
			register_nav_menus( [ 'primary' => __( 'Primary Menu', 'syntric' ),
			                      //'notices' => __( 'Notices Menu', 'syntric' ),
			                      //'quick'   => __( 'Quick Menu', 'syntric' ),
			                      //'sitemap' => __( 'Sitemap', 'syntric' ),
			] );
			/*
			 * Switch default core markup for search form, comment form, and comments
			 * to output valid HTML5.
			 */
			add_theme_support( 'html5', [ 'search-form',
			                              'comment-form',
			                              'comment-list',
			                              'gallery',
			                              'caption', ] );
			/**
			 * Add support for core custom logo.
			 *
			 * @link https://codex.wordpress.org/Theme_Logo
			 */
			add_theme_support( 'custom-logo', [ 'height'      => 100,
			                                    'width'       => 100,
			                                    'flex-width'  => false,
			                                    'flex-height' => false, ] );
			// Add theme support for custom header images
			add_theme_support( 'custom-header', [ 'default-header' => get_template_directory_uri() . '/assets/images/default-header.png',
			                                      'random-default' => true,
			                                      'width'          => 1920,
			                                      'height'         => 500,
			                                      'flex-height'    => true,
			                                      'flex-width'     => true,
			                                      'uploads'        => true, ] );
			// Add theme support for selective refresh for widgets.
			add_theme_support( 'customize-selective-refresh-widgets' );
			// Add support for Block Styles.
			add_theme_support( 'wp-block-styles' );
			// Add support for full and wide align images.
			add_theme_support( 'align-wide' );
			// Add support for editor styles
			add_theme_support( 'editor-styles' );
			// Add custom editor font sizes.
			/*add_theme_support(
				'editor-font-sizes',
				array(
					array(
						'name'      => __( 'Small', 'syntric' ),
						'shortName' => __( 'S', 'syntric' ),
						'size'      => 19.5,
						'slug'      => 'small',
					),
					array(
						'name'      => __( 'Normal', 'syntric' ),
						'shortName' => __( 'M', 'syntric' ),
						'size'      => 22,
						'slug'      => 'normal',
					),
					array(
						'name'      => __( 'Large', 'syntric' ),
						'shortName' => __( 'L', 'syntric' ),
						'size'      => 36.5,
						'slug'      => 'large',
					),
					array(
						'name'      => __( 'Huge', 'syntric' ),
						'shortName' => __( 'XL', 'syntric' ),
						'size'      => 49.5,
						'slug'      => 'huge',
					),
				)
			);*/
			// Disable support for custom font sizes
			add_theme_support( 'disable-custom-font-sizes' );
			// Disable support for custom colors
			add_theme_support( 'disable-custom-colors' );
			// Add support for responsive embedded content.
			add_theme_support( 'responsive-embeds' );
			/**
			 * Remove tag from posts (entirely)
			 */
			unregister_taxonomy_for_object_type( 'post_tag', 'post' );
		}
	endif;
	
	/**
	 * Set site options for Syntric theme on initial setup and when theme is switched
	 */
	function syntric_set_settings() {
		/**
		 * General settings
		 */
		update_option( 'time_format', 'g:i A' );
		update_option( 'links_updated_date_format', 'F j, Y g:i A' );
		/**
		 * Reading settings
		 */
		$home_page  = syntric_get_home_page();
		$posts_page = syntric_get_posts_page();
		update_option( 'show_on_front', 'page' );
		update_option( 'page_on_front', $home_page -> ID );
		update_option( 'page_for_posts', $posts_page -> ID );
		/**
		 * Writing settings
		 */
		update_option( 'default_category', 1 ); // News
		update_option( 'default_post_format', 0 ); // Standard
		update_option( 'default_pingback_flag', 0 );
		update_option( 'default_ping_status', 0 );
		/**
		 * Discussion settings
		 */
		update_option( 'default_comment_status', 'closed' );
		update_option( 'require_name_email', 1 );
		update_option( 'comment_registration', 1 );
		update_option( 'close_comments_for_old_posts', 1 );
		update_option( 'close_comments_days_old', 7 );
		update_option( 'show_comments_cookies_opt_in', 1 );
		update_option( 'thread_comments', 1 );
		update_option( 'thread_comments_depth', 4 );
		update_option( 'page_comments', 1 );
		update_option( 'comments_per_page', 50 );
		update_option( 'default_comments_page', 'newest' );
		update_option( 'comment_order', 'desc' );
		update_option( 'comments_notify', 1 );
		update_option( 'moderation_notify', 1 );
		update_option( 'comment_moderation', 1 );
		update_option( 'comment_whitelist', 0 );
		
		// Avatars
		update_option( 'show_avatars', 0 );
		
		// Do not use year and month folders for media
		update_option( 'uploads_use_yearmonth_folders', 0 );
		
		// This is from a plugin...caution!
		$pp_page = syntric_get_privacy_policy_page();
		update_option( 'wp_page_for_privacy_policy', $pp_page -> ID );
		
		/**
		 * Default values for Syntric customizer settings
		 * todo: Are these necessary given the settings (in customizer.php) have defaults?
		 */
		/**
		 * Default site width
		 */
		$syntric_container_type = get_theme_mod( 'syntric_site_width' );
		if( '' == $syntric_container_type ) {
			set_theme_mod( 'syntric_container_type', 'container-fluid' );
		}
		/**
		 * Default page template
		 */
		$syntric_sidebar_position = get_theme_mod( 'syntric_sidebar_position' );
		if( '' == $syntric_sidebar_position ) {
			set_theme_mod( 'syntric_sidebar_position', 'left' );
		}
		/**
		 * Default theme css - base css available are blue, green, grey, orange, purple, red & teal
		 */
		$syntric_theme_css = get_theme_mod( 'syntric_theme_css' );
		if( '' == $syntric_theme_css ) {
			set_theme_mod( 'syntric_theme_css', 'blue' );
		}
	}
	
	function syntric_set_permalink_structure() {
		global $wp_rewrite;
		$wp_rewrite -> set_permalink_structure( '/%postname%/' );
		$wp_rewrite -> flush_rules();
	}
	
	function syntric_set_default_post_categories() {
		// Set default term (Uncategorized) to News
		$default_term = get_term_by( 'id', 1, 'category' );
		if( $default_term instanceof WP_Term && 'News' != $default_term -> name ) {
			$news_args = [ 'term_id'     => 1,
			               'name'        => 'News',
			               'slug'        => 'news',
			               'taxonomy'    => 'category',
			               'description' => 'Default post category', ];
			wp_update_term( 1, 'category', $news_args );
		}
		
		return;
		/**
		 * Microblog category
		 * // Deprecating microblogs, so remove the category and any posts in/under the category
		 * $microblog_terms = get_terms(
		 * [ 'taxonomy'   => 'category',
		 * 'hide_empty' => false,
		 * 'slug' => 'microblog',
		 * ]
		 * );
		 * // Get all posts in/under microblog or microblogs
		 * $microblog_posts = get_posts(
		 * [ 'numberposts' => - 1,
		 * 'post_type'   => 'post',
		 * 'tax_query'   => [
		 * 'taxonomy' => 'category',
		 * 'field'    => 'term_id',
		 * 'terms'    => $microblog_term_ids,
		 * ] ]
		 * );
		 * // If there are posts, delete them, but not permanently
		 * if( count( $microblog_posts ) ) {
		 * foreach( $microblog_posts as $microblog_post ) {
		 * wp_delete_post( $microblog_post -> id, false );
		 * }
		 * }
		 * // Delete the microblog categories
		 * wp_delete_category( $microblog_term -> term_id );
		 * wp_delete_category( $microblogs_term -> term_id );
		 * // todo: Need to raise some kind of warning so that the microblog posts are not deleted silently
		 */
	}