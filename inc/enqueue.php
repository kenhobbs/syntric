<?php
	/**
	 * Enqueue front-end stylesheets and javascript
	 *
	 * @package syntric
	 */
	add_action( 'wp_enqueue_scripts', 'syn_enqueue_scripts' );
	function syn_enqueue_scripts() {
		// $my_js_ver  = date("ymd-Gis", filemtime( plugin_dir_path( __FILE__ ) . 'js/custom.js' ));
		// $my_css_ver = date("ymd-Gis", filemtime( plugin_dir_path( __FILE__ ) . 'style.css' ));
		// Deregister WP copy of jQuery - is loaded from CDN below
		wp_deregister_script( 'jquery' );
		// Fontawesome
		wp_enqueue_script( 'fontawesome', 'https://use.fontawesome.com/f45398b257.js', null, '5.0.1', false );
		// Fontawesome 5 SVG version
		//wp_enqueue_script( 'fontawesome', 'https://use.fontawesome.com/releases/v5.0.6/js/all.js', null, '5.0.6', false );
		// Google Fonts
		wp_enqueue_style( 'google-fonts', 'https://fonts.googleapis.com/css?family=Roboto|Ubantu+Mono|Ubantu', null, null, null );
		// Master theme stylesheet
		$syntric_css_version = date("ymdGis", filemtime( get_template_directory() . '/assets/css/' . $_SERVER['HTTP_HOST'] . '.min.css' ) );
		wp_enqueue_style( 'syntric', syn_get_theme_stylesheet_uri(), [], $syntric_css_version );
		// Child theme stylesheet
		wp_enqueue_style( 'theme', get_stylesheet_uri(), [], null );
		// Vendor scripts delivered from jsdelivr CDN
		wp_register_script( 'jsdelivr', 'https://cdn.jsdelivr.net/combine/npm/jquery@3.2.1,npm/bootstrap@4.0.0-beta.2/dist/js/bootstrap.bundle.min.js,npm/moment@2.19.4,npm/fullcalendar@3.7.0', null, null, true );
		// Syntric theme script
		$syntric_js_version = date("ymdGis", filemtime( get_template_directory() . '/assets/js/syntric.min.js' ) );
		wp_enqueue_script( 'syntric-js', get_template_directory_uri() . '/assets/js/syntric.min.js', [ 'jsdelivr' ], $syntric_js_version, true );
		// Google translate
		//wp_enqueue_script( 'google-translate', get_stylesheet_directory_uri() . '/assets/libs/google-translate/translate_a/element.js?cb=googleTranslateElementInit', null, null, true );
		// Comment reply
		// if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) wp_enqueue_script( 'comment-reply' );
		// Localized scripts
		$calendars_nonce = wp_create_nonce( 'syn_fetch_calendar_events' );
		wp_localize_script( 'syntric-js', 'syntric_calendars_api', [ 'ajax_url' => admin_url( 'admin-ajax.php' ), 'nonce' => $calendars_nonce, ] );
		$maps_nonce = wp_create_nonce( 'syn_fetch_map' );
		wp_localize_script( 'syntric-js', 'syntric_maps_api', [ 'ajax_url' => admin_url( 'admin-ajax.php' ), 'nonce' => $maps_nonce, ] );
	}