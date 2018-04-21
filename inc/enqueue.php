<?php
	/**
	 * Enqueue front-end stylesheets and javascript
	 *
	 * @package syntric
	 */
	add_action( 'wp_enqueue_scripts', 'syn_enqueue_scripts' );
	function syn_enqueue_scripts() {
		// Deregister, reregister and enqueue jQuery todo: does deregistering jQuery have any unforseen consequences?
		wp_deregister_script( 'jquery' );
		wp_register_script( 'jquery', '//ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js', null, null, true );
		wp_enqueue_script( 'jquery' );
		// Dev styles and scripts
		/*if ( syn_is_dev() ) {
			wp_enqueue_style( 'nestable', '//cdnjs.cloudflare.com/ajax/libs/nestable2/1.6.0/jquery.nestable.min.css', null, null, null );
			wp_enqueue_script( 'nestable', '//cdnjs.cloudflare.com/ajax/libs/nestable2/1.6.0/jquery.nestable.min.js', null, '1.6.0', true );
			////cdnjs.cloudflare.com/ajax/libs/nestable2/1.6.0/jquery.nestable.min.js
		}*/
		// Fontawesome
		wp_enqueue_script( 'fontawesome', '//use.fontawesome.com/f45398b257.js', null, '4.7.0', true );
		// Fontawesome 5 SVG version todo: migrate to FA 5
		//wp_enqueue_script( 'fontawesome', 'https://use.fontawesome.com/releases/v5.0.6/js/all.js', null, '5.0.6', false );
		// Google Fonts
		wp_enqueue_style( 'google-fonts', '//fonts.googleapis.com/css?family=Roboto|Ubantu+Mono|Ubantu|Itim', null, null, null );
		// Fullcalendar
		//wp_enqueue_style( 'fullcalendar', get_template_directory_uri() . '/assets/vendor/fullcalendar-2.2.7-yearview/dist/fullcaledar.min.css', null, null, null );
		// Master theme stylesheet
		$syntric_css_version = date("ymdGis", filemtime( get_template_directory() . '/assets/css/' . $_SERVER['HTTP_HOST'] . '.min.css' ) );
		wp_enqueue_style( 'syntric', syn_get_theme_stylesheet_uri(), [], $syntric_css_version );
		// Child theme stylesheet
		wp_enqueue_style( 'theme', get_stylesheet_uri(), [], null );
		// Vendor scripts delivered from jsdelivr CDN
		//wp_register_script( 'jsdelivr', '//cdn.jsdelivr.net/combine/npm/bootstrap@4.0.0-beta.2/dist/js/bootstrap.bundle.min.js,npm/moment@2.19.4,npm/fullcalendar@3.7.0,npm/chart.js@2.7.1/dist/Chart.min.js,npm/scroll-js@1.8.8/dist/scroll-min.min.js', null, null, true );
		//wp_register_script( 'jsdelivr', '//cdn.jsdelivr.net/combine/npm/bootstrap@4.0.0/dist/js/bootstrap.bundle.min.js,npm/moment@2.19.4,npm/fullcalendar@3.7.0', null, null, true );
		//wp_register_script( 'jsdelivr', '//cdn.jsdelivr.net/combine/npm/bootstrap@4.0.0/dist/js/bootstrap.bundle.min.js,npm/moment@2.19.4,npm/fullcalendar@3.7.0', [ 'jquery' ], null, true );
		//https://cdn.jsdelivr.net/combine/npm/moment@2.22.0/min/moment.min.js,npm/bootstrap@4.1.0/dist/js/bootstrap.bundle.min.js,npm/fullcalendar@3.9.0,npm/anchorme@1.1.2
		wp_register_script( 'jsdelivr', '//cdn.jsdelivr.net/combine/npm/bootstrap@4/dist/js/bootstrap.bundle.min.js,npm/moment@2/min/moment.min.js,npm/fullcalendar@3,npm/anchorme@1.1.2', [ 'jquery' ], null, true );
		// Fullcalendar branch with year view
		//fullcalendar-2.2.7-yearview
		//wp_enqueue_script( 'fullcalendar', get_template_directory_uri() . '/assets/vendor/fullcalendar-2.2.7-yearview/dist/fullcalendar.min.js', [ 'jquery', 'jsdelivr' ], null, true );
		// Syntric theme script
		$syntric_js_version = date("ymdGis", filemtime( get_template_directory() . '/assets/js/syntric.min.js' ) );
		wp_enqueue_script( 'syntric', get_template_directory_uri() . '/assets/js/syntric.min.js', [ 'jquery', 'jsdelivr' ], $syntric_js_version, true );
		//wp_enqueue_script( 'accessibility-rules', get_template_directory_uri() . '/assets/js/axs_testing.js', [ 'syntric', ], $syntric_js_version, true );
		// Google translate
		//wp_enqueue_script( 'google-translate', get_stylesheet_directory_uri() . '/assets/libs/google-translate/translate_a/element.js?cb=googleTranslateElementInit', null, null, true );
		// Comment reply
		// if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) wp_enqueue_script( 'comment-reply' );
		// Localized scripts
		$calendars_nonce = wp_create_nonce( 'syn_fetch_calendar_events' );
		wp_localize_script( 'syntric', 'syntric_calendars_api', [
			'ajax_url'   => admin_url( 'admin-ajax.php' ),
			'nonce'      => $calendars_nonce,
			'spinnerurl' => get_template_directory_uri() . '/assets/images/syntric-spinner.gif',
		] );
		$maps_nonce = wp_create_nonce( 'syn_fetch_map' );
		wp_localize_script( 'syntric', 'syntric_maps_api', [
			'ajax_url'   => admin_url( 'admin-ajax.php' ),
			'nonce'      => $maps_nonce,
			'spinnerurl' => get_template_directory_uri() . '/assets/images/syntric-spinner.gif',
		] );
	}