<?php
	/**
	 * Enqueue front-end stylesheets and javascript
	 *
	 * @package syntric
	 */
	add_action( 'wp_enqueue_scripts', 'syn_enqueue_scripts' );
	function syn_enqueue_scripts() {
		wp_deregister_script( 'jquery' );
		//wp_enqueue_style( 'cdn-css', 'https://cdn.jsdelivr.net/combine/npm/fullcalendar@3.7.0/dist/fullcalendar.min.css', null, '2017121701261', null );
		// Fontawesome
		wp_enqueue_script( 'fontawesome', 'https://use.fontawesome.com/f45398b257.js', null, '5.0.1', false );
		// Google Fonts @ CDN
		wp_enqueue_style( 'google-fonts', 'https://fonts.googleapis.com/css?family=Roboto|Ubantu+Mono|Ubantu', null, null, null );
		// Master theme stylesheet
		wp_enqueue_style( 'syntric', syn_get_theme_stylesheet_uri(), [], null );
		// Child theme stylesheet
		wp_enqueue_style( 'theme', get_stylesheet_uri(), [], null );
		// https://cdn.jsdelivr.net/combine/npm/jquery@3.2.1,npm/bootstrap@4.0.0-beta.2/dist/js/bootstrap.bundle.min.js,npm/moment@2.19.4,npm/fullcalendar@3.7.0,npm/load-google-maps-api-2@1.0.2
		wp_register_script( 'jsdelivr', 'https://cdn.jsdelivr.net/combine/npm/jquery@3.2.1,npm/bootstrap@4.0.0-beta.2/dist/js/bootstrap.bundle.min.js,npm/moment@2.19.4,npm/fullcalendar@3.7.0', null, '2017121701261', true );
		//wp_enqueue_script( 'vendor-js', get_template_directory_uri() . '/assets/js/vendor.min.js', null, null, true );
		//wp_register_script( 'jsdelivr', 'jsdelivr', [ 'jsdelivr' ], null );
		// Syntric theme script
		wp_enqueue_script( 'syntric-js', get_template_directory_uri() . '/assets/js/syntric.min.js', [ 'jsdelivr' ], null, true );
		// Syntric API script
		//wp_enqueue_script( 'syntric-api', get_template_directory_uri() . '/assets/js/syntric-api.min.js', array( 'jquery' ), null, true );
		//Scripts in footer
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