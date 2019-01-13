<?php
	/**
	 * Enqueue scripts and stylesheets.
	 * Localize scripts for admin-ajax.php calls.
	 *
	 * @package syntric
	 *
	 * todo: migrate Fontawesome to version 5
	 */
	add_action( 'wp_enqueue_scripts', 'syn_enqueue_scripts' );
	function syn_enqueue_scripts() {
		$domain_stylesheet_dir  = '/assets/css/' . $_SERVER[ 'HTTP_HOST' ] . '.min.css';
		$domain_stylesheet_path = get_template_directory() . $domain_stylesheet_dir;
		$domain_stylesheet_file = $_SERVER[ 'HTTP_HOST' ] . '.min.css';
		//if ( file_exists( $domain_stylesheet_path ) ) {
		if ( file_exists( $domain_stylesheet_path . $domain_stylesheet_file ) ) {
			$domain_stylesheet_uri      = get_template_directory_uri() . $domain_stylesheet_dir;
			$domain_stylesheet_filetime = date( 'YmdGis', filemtime( $domain_stylesheet_path ) );
			wp_enqueue_style( 'domain', $domain_stylesheet_uri, null, $domain_stylesheet_filetime );
		} else {
			$color_stylesheet_dir      = '/assets/css/' . get_theme_mod( 'syntric_theme_css' ) . '.min.css';
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
		wp_enqueue_script( 'syntric', get_template_directory_uri() . '/assets/js/syntric.min.js', [ 'jquery', 'jsdelivr' ], $syntric_js_filetime, true );
		wp_localize_script( 'syntric', 'syntric_calendars_api', [ 'ajax_url'   => self_admin_url( 'admin-ajax.php' ),
		                                                          'nonce'      => wp_create_nonce( 'syn_fetch_calendar_events' ),
		                                                          'spinnerurl' => get_template_directory_uri() . '/assets/images/syntric-spinner.gif',
		] );
		wp_localize_script( 'syntric', 'syntric_maps_api', [ 'ajax_url'   => self_admin_url( 'admin-ajax.php' ),
		                                                     'nonce'      => wp_create_nonce( 'syn_fetch_map' ),
		                                                     'spinnerurl' => get_template_directory_uri() . '/assets/images/syntric-spinner.gif',
		] );
	}