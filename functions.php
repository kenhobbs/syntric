<?php

// Theme check
$theme      = wp_get_theme();
$theme_name = $theme -> get( 'Name' );

	/**
	 * Only run if theme is Syntric
	 */
if( 'syntric' == strtolower( $theme_name ) ) {
	// Backwards compatibility check
	require get_template_directory() . '/inc/backwards-compatibility.php';

	require get_template_directory() . '/inc/global-functions.php';
		/**
		 * Setup theme including:
		 *
		 * Theme supports
		 * Defaults (categories, settings, etc.)
		 * Options
		 * Register navigation menus
		 * Register widget sidebars
		 */
		require get_template_directory() . '/inc/setup-theme.php';
		
		/**
		 * Set number of revisions to keep by post type
		 */
		add_filter( 'wp_revisions_to_keep', 'syntric_revisions_to_keep', 10, 2 );
		if( ! function_exists( 'syntric_revisions_to_keep' ) ) {
			function syntric_revisions_to_keep( $num, $post ) {
				$post_type = $post -> post_type;
				if( 'syntric_calendar' == $post_type || 'syntric_event' == $post_type ) {
					$num = 0;
				} else {
					$num = 5;
				}
				
				return $num;
			}
		}
		
		/**
		 * Disable user notification of email change confirmation
		 */
		add_filter( 'send_email_change_email', '__return_false' );
		
		/**
		 * Send password change email if in production only
		 */
		add_filter( 'send_password_change_email', 'syntric_send_password_change_email' );
		function syntric_send_password_change_email() {
			$host     = $_SERVER[ 'HTTP_HOST' ];
			$host_arr = explode( '.', $host );
			if( 'localhost' != $host_arr[ count( $host_arr ) - 1 ] && 'syntric' != $host_arr[ count( $host_arr ) - 2 ] ) {
				// Email will be sent
				return true;
			}
			
			// Email will not be sent
			return false;
		}
		
		/**
		 * Todo: Set the content width in pixels, based on the theme's design and stylesheet.
		 *
		 * Priority 0 to make it available to lower priority callbacks.
		 *
		 * @global int $content_width Content width.
		 */
		add_action( 'after_setup_theme', 'syntric_content_width', 0 );
		function syntric_content_width() {
			// This variable is intended to be overruled from themes.
			// Open WPCS issue: {@link https://github.com/WordPress-Coding-Standards/WordPress-Coding-Standards/issues/1043}.
			// phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedVariableFound
			$GLOBALS[ 'content_width' ] = apply_filters( 'syntric_content_width', 900 );
		}
		
		/**
		 * Enqueue scripts and stylesheets
		 */
		require get_template_directory() . '/inc/enqueue.php';
		/**
		 * Admin customizations
		 */
		if( is_admin() ) {
			require get_template_directory() . '/inc/admin.php';
		}
		/**
		 * Customizer customizations
		 */
		require get_template_directory() . '/inc/customizer.php';
		/**
		 * Comments customizations
		 */
		require get_template_directory() . '/inc/comments.php';
		/**
		 * Paginatgion customizations - combine into something else...
		 */
		require get_template_directory() . '/inc/pagination.php';
		/**
		 * Search customizations
		 */
		require get_template_directory() . '/inc/search.php';
		/**
		 * Security measures
		 */
		require get_template_directory() . '/inc/security.php';
		/**
		 * Page template customizations
		 */
		require get_template_directory() . '/inc/templates.php';
		/**
		 * Navigation customizations
		 */
		require get_template_directory() . '/inc/navigation.php';
		
		// Include plugin.php so we can use is_plugin_active function here (it's loaded later)
		include_once( ABSPATH . 'wp-admin/includes/plugin.php' );
		if( is_plugin_active( 'advanced-custom-fields-pro/acf.php' ) ) {
			// If ACF is loaded, include syntric-apps.php which in turn includes theme apps build with ACF
			require get_template_directory() . '/syntric-apps/syntric-apps.php';
		} else {
			// If ACF isn't loaded, present a "not configured" screen
			$die_message = '<div style="text-align: center">';
			$die_message .= '<img src="' . get_template_directory_uri() . '/screenshot.png" alt="Syntric Framework & Theme for Districts & Schools" style="height: 524px; width: 700px;">';
			$die_message .= '<p style="font-size: 1.5rem;">Syntric Theme + Framework is not configured</p>';
			$die_message .= '</div>';
			wp_die( $die_message );
		}
	}

// todo: what is all this?
	
	/**
	 * Relocated or excluded
	 */
	/**
	 * Setup the theme supports, etc.
	 */
	//require get_template_directory() . '/inc/setup.php';
	/**
	 * Enhance the theme by hooking into WordPress.
	 */
	// require get_template_directory() . '/inc/template-functions.php';
	/**
	 * Template customizations
	 */
	//require get_template_directory() . '/inc/template-tags.php';
	/**
	 * Classic editor customizations
	 */
	//require get_template_directory() . '/inc/editor.php';
	/**
	 * Misc customizations - turned off for now
	 */
	//require get_template_directory() . '/inc/extras.php';
	//require get_template_directory() . '/inc/utility.php';
	/**
	 * Did not include...Display custom color CSS in customizer and on frontend.
	 *
	 * function syntric_colors_css_wrap() {
	 * // Only include custom colors in customizer or frontend.
	 * if ( ( ! is_customize_preview() && 'default' === get_theme_mod( 'primary_color', 'default' ) ) || is_admin() ) {
	 * return;
	 * }
	 * require_once get_parent_theme_file_path( '/inc/color-patterns.php' );
	 * if ( 'default' === get_theme_mod( 'primary_color', 'default' ) ) {
	 * $primary_color = 199;
	 * } else {
	 * $primary_color = absint( get_theme_mod( 'primary_color_hue', 199 ) );
	 * }
	 * ?>
	 * <style type="text/css" id="custom-theme-colors" <?php echo is_customize_preview() ? 'data-hue="' . $primary_color . '"' : ''; ?>>
	 * <?php echo syntric_custom_colors_css(); ?>
	 * </style>
	 * <?php
	 * } */
	//add_action( 'wp_head', 'syntric_colors_css_wrap' );
	/**
	 * Enqueue scripts and styles. Moved to inc/enqueue.php
	 *
	 * function syntric_scripts() {
	 * wp_enqueue_style( 'syntric-style', get_stylesheet_uri(), [], wp_get_theme()->get( 'Version' ) );
	 * wp_style_add_data( 'syntric-style', 'rtl', 'replace' );
	 * wp_enqueue_script( 'syntric-skip-link-focus-fix', get_template_directory_uri() . '/js/skip-link-focus-fix.js', [], '20151215', true );
	 * if ( has_nav_menu( 'menu-1' ) ) {
	 * wp_enqueue_script( 'syntric-priority-menu', get_theme_file_uri( '/js/priority-menu.js' ), [], '1.0', true );
	 * wp_enqueue_script( 'syntric-touch-navigation', get_theme_file_uri( '/js/touch-keyboard-navigation.js' ), [], '1.0', true );
	 * }
	 * wp_enqueue_style( 'syntric-print-style', get_template_directory_uri() . '/print.css', [], wp_get_theme()->get( 'Version' ), 'print' );
	 * if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
	 * wp_enqueue_script( 'comment-reply' );
	 * }
	 * }*/
	//add_action( 'wp_enqueue_scripts', 'syntric_scripts' );
	/**
	 * Enqueue supplemental block editor styles. Moved to inc/enqueue.php
	 *
	 * function syntric_editor_customizer_styles() {
	 *
	 * wp_enqueue_style( 'syntric-editor-customizer-styles', get_theme_file_uri( '/style-editor-customizer.css' ), false, '1.0', 'all' );
	 * if ( 'custom' === get_theme_mod( 'primary_color' ) ) {
	 * // Include color patterns.
	 * require_once get_parent_theme_file_path( '/inc/color-patterns.php' );
	 * wp_add_inline_style( 'syntric-editor-customizer-styles', syntric_custom_colors_css() );
	 * }
	 * } */
	//add_action( 'enqueue_block_editor_assets', 'syntric_editor_customizer_styles' );
	/**
	 * SVG Icons class.
	 */
	//require get_template_directory() . '/classes/class-syntric-svg-icons.php';
	/**
	 * Custom Comment Walker template.
	 */
	//require get_template_directory() . '/classes/class-syntric-walker-comment.php';
	/**
	 * SVG Icons related functions.
	 */
	//require get_template_directory() . '/inc/icon-functions.php';