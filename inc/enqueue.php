<?php
	
	/**
	 * todo: Validate twentynineteen inclusions
	 * Enqueue scripts and stylesheets.
	 * Localize scripts for admin-ajax.php calls.
	 *
	 * @package syntric
	 *
	 * todo: migrate Fontawesome to version 5
	 */
	add_action( 'wp_enqueue_scripts', 'syntric_enqueue_scripts' );
	function syntric_enqueue_scripts() {
		$current_domain     = $_SERVER[ 'HTTP_HOST' ];
		$current_domain_arr = explode( '.', $current_domain );
		$tlds               = [ 'org',
		                        'com',
		                        'net',
		                        'edu' ];
		/**
		 * amadorcoe.org -> ['amadorcoe', 'org']
		 * www.amadorcoe.org -> ['www', 'amadorcoe', 'org']
		 * pinegroveel.amadorcoe.org -> ['pinegroveel', 'amadorcoe', 'org']
		 */
		if( 'www' == $current_domain_arr[ 0 ] ) {
			array_shift( $current_domain_arr );
		}
		$tld = $current_domain_arr[ count( $current_domain_arr ) - 1 ];
		$ntl = $current_domain_arr[ count( $current_domain_arr ) - 2 ];
		if( 'school' == $tld && 'syntric' == $ntl ) {
			// these are staging sites
			if( in_array( $current_domain_arr[ 1 ], $tlds ) ) {
				$domain = $current_domain_arr[ 0 ] . '.syntric.school';
			} elseif( 3 >= count( $current_domain_arr ) && in_array( $current_domain_arr[ 2 ], $tlds ) ) {
				$domain = $current_domain_arr[ 0 ] . '.' . $current_domain_arr[ 1 ] . '.syntric.school';
			} else {
				// just try to match??
			}
		} else {
			$domain = implode( '.', $current_domain_arr );
		}
		$domain_stylesheet_dir  = '/assets/css/' . $domain . '.min.css';
		$domain_stylesheet_path = get_template_directory() . $domain_stylesheet_dir;
		if( file_exists( $domain_stylesheet_path ) ) {
			$domain_stylesheet_uri      = get_template_directory_uri() . $domain_stylesheet_dir;
			$domain_stylesheet_filetime = date( 'YmdGis', filemtime( $domain_stylesheet_path ) );
			wp_enqueue_style( 'domain', $domain_stylesheet_uri, null, $domain_stylesheet_filetime );
		} else {
			$color_stylesheet_dir      = '/assets/css/color.' . get_theme_mod( 'syntric_theme_css' ) . '.min.css';
			$color_stylesheet_path     = get_template_directory() . $color_stylesheet_dir;
			$color_stylesheet_uri      = get_template_directory_uri() . $color_stylesheet_dir;
			$color_stylesheet_filetime = date( 'YmdGis', filemtime( $color_stylesheet_path ) );
			wp_enqueue_style( 'color', $color_stylesheet_uri, null, $color_stylesheet_filetime );
		}
		wp_deregister_script( 'jquery' );
		wp_register_script( 'jquery', '//ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js', null, null, true );
		wp_enqueue_script( 'jquery' );
		wp_enqueue_script( 'fontawesome', '//use.fontawesome.com/f45398b257.js', null, '4.7.0', true );
		wp_enqueue_style( 'google-fonts', '//fonts.googleapis.com/css?family=Roboto|Ubantu+Mono|Ubantu|Itim', null, null );
		wp_enqueue_style( 'theme', get_stylesheet_uri(), [], null );
		//,npm/anchorme@1.1.2
		wp_register_script( 'jsdelivr', '//cdn.jsdelivr.net/combine/npm/bootstrap@4/dist/js/bootstrap.bundle.min.js,npm/moment@2/min/moment.min.js,npm/fullcalendar@3', [ 'jquery' ], null, true );
		$syntric_js_filetime = date( "YmdGis", filemtime( get_template_directory() . '/assets/js/syntric.min.js' ) );
		wp_enqueue_script( 'syntric', get_template_directory_uri() . '/assets/js/syntric.min.js', [ 'jquery',
		                                                                                            'jsdelivr' ], $syntric_js_filetime, true );
		wp_localize_script( 'syntric', 'syntric_calendars_api', [ 'ajax_url'   => self_admin_url( 'admin-ajax.php' ),
		                                                          'nonce'      => wp_create_nonce( 'syntric_fetch_calendar_events' ),
		                                                          'spinnerurl' => get_template_directory_uri() . '/assets/images/syntric-spinner.gif', ] );
		wp_localize_script( 'syntric', 'syntric_maps_api', [ 'ajax_url'   => self_admin_url( 'admin-ajax.php' ),
		                                                     'nonce'      => wp_create_nonce( 'syntric_fetch_map' ),
		                                                     'spinnerurl' => get_template_directory_uri() . '/assets/images/syntric-spinner.gif', ] );
		// todo: From twentynineteen functions.php
		wp_enqueue_style( 'syntric-style', get_stylesheet_uri(), [], wp_get_theme() -> get( 'Version' ) );
		wp_style_add_data( 'syntric-style', 'rtl', 'replace' );
		wp_enqueue_script( 'syntric-skip-link-focus-fix', get_template_directory_uri() . '/assets/js/skip-link-focus-fix.js', [], '20151215', true );
		if( has_nav_menu( 'menu-1' ) ) {
			wp_enqueue_script( 'syntric-priority-menu', get_theme_file_uri( '/assets/js/priority-menu.js' ), [], '1.0', true );
			wp_enqueue_script( 'syntric-touch-navigation', get_theme_file_uri( '/assets/js/touch-keyboard-navigation.js' ), [], '1.0', true );
		}
		wp_enqueue_style( 'syntric-print-style', get_template_directory_uri() . '/print.css', [], wp_get_theme() -> get( 'Version' ), 'print' );
		if( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
			wp_enqueue_script( 'comment-reply' );
		}
	}
	
	/**
	 * todo: Enqueue supplemental block editor styles. From twentynineteen functions.php
	 */
	function syntric_editor_customizer_styles() {
		wp_enqueue_style( 'syntric-editor-customizer-styles', get_theme_file_uri( '/style-editor-customizer.css' ), false, '1.0', 'all' );
		if( 'custom' === get_theme_mod( 'primary_color' ) ) {
			// Include color patterns.
			require_once get_parent_theme_file_path( '/inc/color-patterns.php' );
			wp_add_inline_style( 'syntric-editor-customizer-styles', syntric_custom_colors_css() );
		}
	}
	
	add_action( 'enqueue_block_editor_assets', 'syntric_editor_customizer_styles' );