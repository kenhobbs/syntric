<?php
	/**
	 * Set the permalink structure programmatically
	 */
	add_action( 'after_switch_theme', 'syn_setup_syntric_theme', 10, 2 );
	function syn_setup_syntric_theme( $old_theme_name, $old_theme ) {
		$theme      = wp_get_theme();
		$theme_name = $theme->get( 'Name' );
		if ( 'syntric' == strtolower( $theme_name ) ) {
			syn_set_site_options();
			syn_set_permalink_structure();
			syn_set_default_post_categories();
			syn_setup_primary_menu();
			if ( is_super_admin() && is_admin() ) {
				//wp_redirect( 'options-permalink.php' );
			}
		}
	}

	function syn_get_home_page() {
		$home_page = get_page_by_path( 'home' );
		if ( ! $home_page instanceof WP_Post ) {
			$home_props = [ 'post_title'  => 'Home', 'post_content' => '', 'post_type' => 'page', 'post_name' => 'home', 'post_status' => 'publish', 'post_author' => get_current_user_id(),
			                'post_parent' => 0, 'menu_order' => 0, ];
			$home_id    = wp_insert_post( $home_props );
			$home_page  = get_post( $home_id, OBJECT );
			update_post_meta( $home_id, '_np_nav_status', 'hide' );
		}

		return $home_page;
	}

	function syn_get_posts_page() {
		$posts_page = get_page_by_path( 'news' );
		if ( ! $posts_page instanceof WP_Post ) {
			$posts_props = [ 'post_title'  => 'News', 'post_content' => '', 'post_type' => 'page', 'post_name' => 'news', 'post_status' => 'publish', 'post_author' => get_current_user_id(),
			                 'post_parent' => 0, 'menu_order' => 1, ];
			$posts_id    = wp_insert_post( $posts_props );
			$posts_page  = get_post( $posts_id, OBJECT );
		}

		return $posts_page;
	}

	function syn_get_privacy_policy_page() {
		$pp_page = get_page_by_path( 'privacy-policy' );
		if ( ! $pp_page instanceof WP_Post ) {
			$pp_props = [ 'post_title'  => 'Privacy Policy', 'post_content' => '', 'post_type' => 'page', 'post_name' => 'privacy-policy', 'post_status' => 'publish',
			              'post_author' => get_current_user_id(), 'post_parent' => 0, 'menu_order' => 999999, ];
			$pp_id    = wp_insert_post( $pp_props );
			$pp_page  = get_post( $pp_id, OBJECT );
			update_post_meta( $pp_id, '_np_nav_status', 'hide' );
		}

		return $pp_page;
	}

	function syn_set_site_options() {
		/////////////////////// General /////////////////////
		update_option( 'time_format', 'g:i A' );
		update_option( 'links_updated_date_format', 'F j, Y g:i A' );
		/////////////////////// Reading /////////////////////
		$home_page  = syn_get_home_page();
		$posts_page = syn_get_posts_page();
		update_option( 'show_on_front', 'page' );
		update_option( 'page_on_front', $home_page->ID );
		update_option( 'page_for_posts', $posts_page->ID );
		/////////////////////// Writing /////////////////////
		update_option( 'default_category', 1 ); // News
		update_option( 'default_post_format', 0 ); // Standard
		/////////////////////// Discussion /////////////////////
		// default article settings
		update_option( 'default_pingback_flag', 0 );
		update_option( 'default_ping_status', 0 );
		update_option( 'default_comment_status', 'closed' );
		// other comment settings
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
		// email me whenever
		update_option( 'comments_notify', 1 );
		update_option( 'moderation_notify', 1 );
		// before a comment appears
		update_option( 'comment_moderation', 1 );
		update_option( 'comment_whitelist', 0 );
		// avatars
		update_option( 'show_avatars', 0 );
		///////////////////// Media /////////////////////
		// Image sizes are setup in after_theme_setup filter below - see syn_after_theme_setup
		update_option( 'uploads_use_yearmonth_folders', 0 );
		// Uploads dir, path, url are all handled in wp-config.php
		///////////////////// Permalinks /////////////////////
		// Setup with function syn_set_permalink_structure
		///////////////////// Privacy /////////////////////
		// This is from a plugin...caution!
		$pp_page = syn_get_privacy_policy_page();
		update_option( 'wp_page_for_privacy_policy', $pp_page->ID );
	}

	function syn_set_permalink_structure() {
		global $wp_rewrite;
		$wp_rewrite->set_permalink_structure( '/%postname%/' );
		$wp_rewrite->flush_rules();
	}

	function syn_set_default_post_categories() {
		// default term aka Uncategorized or News
		$default_term = get_term_by( 'id', 1, 'category' );
		$news_args    = [ 'term_id' => 1, 'name' => 'News', 'slug' => 'news', 'taxonomy' => 'category', 'description' => 'Default post category', ];
		wp_update_term( 1, 'category', $news_args );
		// Microblog term
		$microblog_args  = [ 'name' => 'Microblog', 'slug' => 'microblog', 'taxonomy' => 'category', 'description' => 'Generated by theme', ];
		$microblog_term  = '';
		$microblog_props = [ 'name' => 'Microblog', 'name' => 'Microblogs', 'slug' => 'microblog', 'slug' => 'microblogs' ];
		foreach ( $microblog_props as $prop => $val ) {
			$microblog_term = get_term_by( $prop, $val, 'category' );
			if ( $microblog_term instanceof WP_Term ) {
				break;
			}
		};
		if ( $microblog_term instanceof WP_Term ) {
			wp_update_term( $microblog_term->term_id, 'category', $microblog_args );
		} else {
			wp_create_term( 'Microblog', 'category' );
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
	 * Sets up theme defaults and registers support for various WordPress features.
	 *
	 * Note that this function is hooked into the after_setup_theme hook, which
	 * runs before the init hook. The init hook is too late for some features, such
	 * as indicating support for post thumbnails.
	 */
	add_action( 'after_setup_theme', 'syn_after_theme_setup' );
	if ( ! function_exists( 'syn_after_theme_setup' ) ) {
		function syn_after_theme_setup() {
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
			 * Add support for post thumbnails (Featured Image) in posts (only)
			 */
			add_theme_support( 'post-thumbnails', [ 'post', 'page', ] );
			/**
			 * Set default Post Thumbnail size
			 */
			//set_post_thumbnail_size( 'large' ); // 1920 x 500, cropped from center preferred size for banner (custom header)
			/**
			 * Add support for widget refreshing in the customizer
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
			add_theme_support( 'custom-header', [ 'default-image' => get_template_directory_uri() . '/assets/images/default-header.png', 'random-default' => true, 'width' => 1920,
			                                      'height'        => 500, 'flex-height' => true, 'flex-width' => true, 'uploads' => true, ] );
			/**
			 * Add support for editor stylesheet
			 *
			 * This is deprecated.  Using add_editor_style() in admin_init action hook
			 */
			//add_theme_support( 'editor-style' );
			//~~~~~~~~~ Misc theme features
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
			/**
			 * Add ability to integrate translations using textdomain
			 * Translations can be filed in the /assets/languages/ directory.
			 */
			load_theme_textdomain( 'syntric', get_template_directory() . '/assets/languages' );
			//~~~~~~~~~ Register theme features
			/**
			 * Register nav menu locations
			 */
			register_nav_menus( [ 'primary' => __( 'Primary Menu', 'syntric' ), //'notices' => __( 'Notices Menu', 'syntric' ),
			                      //'quick'   => __( 'Quick Menu', 'syntric' ),
			                      //'sitemap' => __( 'Sitemap', 'syntric' ),
			] );
			/**
			 * Register multiple default headers when randomizing headers by default
			 */
			register_default_headers( [ 'header-1' => [ 'url'           => get_template_directory_uri() . '/assets/images/default-header.png',
			                                            'thumbnail_url' => get_template_directory_uri() . '/assets/images/default-header.png',
			                                            'description'   => __( 'Default header', 'syntric' ), ], /*'header-2' => [
					'url'           => get_template_directory_uri() . '/assets/images/default-header-2.png',
					'thumbnail_url' => get_template_directory_uri() . '/assets/images/default-header-2.png',
					'description'   => __( 'Default header 2', 'syntric' ),
				],
				'header-3' => [
					'url'           => get_template_directory_uri() . '/assets/images/default-header-3.png',
					'thumbnail_url' => get_template_directory_uri() . '/assets/images/default-header-3.png',
					'description'   => __( 'Default header 3', 'syntric' ),
				],
				'header-4' => [
					'url'           => get_template_directory_uri() . '/assets/images/default-header-4.png',
					'thumbnail_url' => get_template_directory_uri() . '/assets/images/default-header-4.png',
					'description'   => __( 'Default header 4', 'syntric' ),
				],*/ ] );
			//~~~~~~~~~ Setup defaults for customizer settings
			// todo: can these be eliminated?
			/**
			 * Default body container width
			 */
			$syntric_container_type = get_theme_mod( 'syntric_container_type' );
			if ( '' == $syntric_container_type ) {
				set_theme_mod( 'syntric_container_type', 'container-fluid' );
			}
			/**
			 * Default page template
			 */
			$syntric_sidebar_position = get_theme_mod( 'syntric_sidebar_position' );
			if ( '' == $syntric_sidebar_position ) {
				set_theme_mod( 'syntric_sidebar_position', 'left' );
			}
			/**
			 * Default theme css - base css available are blue, green, grey, orange, purple, red & teal
			 */
			$syntric_theme_css = get_theme_mod( 'syntric_theme_css' );
			if ( '' == $syntric_theme_css ) {
				set_theme_mod( 'syntric_theme_css', 'blue' );
			}
		}
	}
	function syn_setup_primary_menu() {
		// Check if the menu exists
		$menu_name   = 'Primary Menu';
		$menu_exists = wp_get_nav_menu_object( $menu_name );
// If it doesn't exist, let's create it.
		if ( ! $menu_exists ) {
			$menu_id = wp_create_nav_menu( $menu_name );
			/*// Set up default menu items
			wp_update_nav_menu_item( $menu_id, 0, array(
				'menu-item-title'   =>  __( 'Home', 'textdomain' ),
				'menu-item-classes' => 'home',
				'menu-item-url'     => home_url( '/' ),
				'menu-item-status'  => 'publish'
			) );

			wp_update_nav_menu_item( $menu_id, 0, array(
				'menu-item-title'  =>  __( 'Custom Page', 'textdomain' ),
				'menu-item-url'    => home_url( '/custom/' ),
				'menu-item-status' => 'publish'
			) );*/
		}
	}

	/**
	 * Rename image sizes
	 */
	//add_filter( 'image_size_names_choose', 'syn_image_size_names' );
	function syn_image_size_names( $sizes ) {
		return array_merge( $sizes, [ 'icon'         => __( '50x50 (icon)' ), 'thumbnail' => __( '100x100 (thumbnail)' ), 'medium' => __( '200x200 (medium)' ),
		                              'medium_large' => __( '400x400 (medium_large)' ), 'large' => __( '800x800 (large)' ), 'banner' => __( '1920x500 (banner)' ), ] );
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
		if ( syn_is_dev() || syn_is_staging() || syn_current_user_can( 'syntric' ) ) {
			// Email will not be sent
			return false;
		}

		// Email will be sent
		return true;
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
		if ( syn_is_dev() || syn_is_staging() ) {
			$syntric_user = syn_syntric_user();
			$to           = $syntric_user->user_email;
		} else {
			$to = $userdata[ 'user_email' ];
		}
		$email = [ 'to' => $to, 'subject' => 'Website email address changed', 'message' => $email[ 'message' ], 'headers' => $email[ 'headers' ], ];

		return $email;
	}

	add_filter( 'password_change_email', 'syn_password_change_email', 10, 3 );
	function syn_password_change_email( $email, $user, $userdata ) {
		if ( syn_is_dev() || syn_is_staging() ) {
			$syntric_user = syn_syntric_user();
			$to           = $syntric_user->user_email;
		} else {
			$to = $userdata[ 'user_email' ];
		}
		$email = [ 'to' => $to, 'subject' => 'Website password changed', 'message' => $email[ 'message' ], 'headers' => $email[ 'headers' ], ];

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