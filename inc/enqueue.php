<?php
/**
 * Enqueue front-end stylesheets and javascript
 *
 * @package syntric
 */
add_action( 'wp_enqueue_scripts', 'syn_enqueue_scripts' );
function syn_enqueue_scripts() {
	$is_dev         = syn_is_dev();
	$the_theme      = wp_get_theme();
	$theme_filename = ( ! $is_dev ) ? 'syntric.min' : 'syntric';
	// Deregister jQuery - in order to enqueue it strategically
	wp_deregister_script( 'jquery' );
	/**
	 * Stylesheets
	 */
	// Google Fonts @ CDN
	wp_enqueue_style( 'google-fonts-stylesheet', '//fonts.googleapis.com/css?family=Roboto:100,300,400:500:700:900|Roboto+Condensed:300:400|Open+Sans' );
	// Font Awesome
	wp_enqueue_style( 'fontawesome-stylesheet', get_stylesheet_directory_uri() . '/assets/libs/fontawesome-v4.7.0/fontawesome.css', null, '4.7.0' );
	// Bootstrap 4.0
	wp_enqueue_style( 'bootstrap-stylesheet', get_stylesheet_directory_uri() . '/assets/libs/bootstrap/4.0.0-beta/dist/css/bootstrap.min.css', null, '4.0.0a' );
	// FullCalendar
	wp_enqueue_style( 'fullcalendar-stylesheet', get_stylesheet_directory_uri() . '/assets/libs/fullcalendar/fullcalendar.min.css', null, '3.6.1' );
	// FullCalendar print
	//wp_enqueue_style( 'fullcalendar-print-stylesheet', get_stylesheet_directory_uri() . '/assets/libs/fullcalendar/fullcalendar.print.css', null, '3.6.1' );
	// Master theme stylesheet
	wp_enqueue_style( 'syntric-stylesheet', get_stylesheet_directory_uri() . '/assets/css/' . $theme_filename . '.css', array(), $the_theme->get( 'Version' ) );
	// Theme stylesheet
	wp_enqueue_style( 'theme-stylesheet', get_stylesheet_uri(), array(), $the_theme->get( 'Version' ) );
	/**
	 * Scripts in header
	 */
	// jQuery
	wp_enqueue_script( 'jquery', get_stylesheet_directory_uri() . '/assets/libs/jquery/3.2.1/jquery.min.js', null, '3.2.1', false );
	// moment
	wp_enqueue_script( 'moment', get_stylesheet_directory_uri() . '/assets/libs/fullcalendar/lib/moment.min.js', null, '3.6.1', true );
	// tether.js for Bootstrap tooltips
	wp_enqueue_script( 'tether', get_stylesheet_directory_uri() . '/assets/libs/bootstrap/4.0.0-beta/assets/js/vendor/popper.min.js', null, '1.4.0', true );
	// Bootstrap script
	wp_enqueue_script( 'bootstrap', get_stylesheet_directory_uri() . '/assets/libs/bootstrap/4.0.0-beta/dist/js/bootstrap.min.js', array(
		'jquery',
		'tether',
	), '4.0.0a', true );
	// FullCalendar
	wp_enqueue_script( 'fullcalendar', get_stylesheet_directory_uri() . '/assets/libs/fullcalendar/fullcalendar.min.js', array(
		'jquery',
		'moment',
	), '3.6.1', false );
	// Google Calendar for FullCalendar
	//wp_enqueue_script( 'gcal', get_stylesheet_directory_uri() . '/assets/libs/fullcalendar/gcal.js', array( 'jquery', 'fullcalendar' ), '3.6.1', true );
	// Syntric theme script
	wp_enqueue_script( 'syntric', get_template_directory_uri() . '/assets/js/' . $theme_filename . '.js', array( 'jquery' ), $the_theme->get( 'Version' ), false );
	// Syntric API script
	wp_enqueue_script( 'syntric-api', get_template_directory_uri() . '/assets/js/syntric-api.min.js', array( 'jquery' ), $the_theme->get( 'Version' ), false );
	/**
	 * Scripts in footer
	 */
	// Google translate
	//wp_enqueue_script( 'google-translate-script', get_stylesheet_directory_uri() . '/assets/libs/google-translate/translate_a/element.js?cb=googleTranslateElementInit', null, null, true );
	// Comment reply
	// if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) wp_enqueue_script( 'comment-reply' );
	//
	// Localize scripts
	//
	// Localize the Syntric API script (syntric-api.js) sending it's requests via /wp-admin/admin-ajax.php
	$fetch_calendar_events_nonce = wp_create_nonce( 'fetch_calendar_events' );
	wp_localize_script( 'syntric-api', 'syntric_api', array(
		'ajax_url' => admin_url( 'admin-ajax.php' ),
		'nonce'    => $fetch_calendar_events_nonce,
	) );
	//
	// CDN versions for stylesheets and javascript scripts
	//
	// Font Awesome stylesheet
	//wp_enqueue_style( 'font-awesome-stylesheet', '//use.fontawesome.com/c68acbffa5.css', null, '4.7.0' );
	// Bootstrap stylesheet
	//wp_enqueue_style( 'bootstrap-stylesheet', '//maxcdn.bootstrapcdn.com/bootstrap/4.0.0-alpha.6/css/bootstrap.min.css', null, '4.0.0a' );
	// jQuery
	//wp_register_script( 'jquery', '//ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js', false, '3.2.1', true );
	// Bootstrap javascript
	//wp_enqueue_script( 'bootstrap-script', '//maxcdn.bootstrapcdn.com/bootstrap/4.0.0-alpha.6/js/bootstrap.min.js', null, '4.0.0a', true );
	// Google Translate
	//wp_enqueue_script( 'google-translate-script', '//translate.google.com/translate_a/element.js?cb=googleTranslateElementInit', null, null, true );
}

/**
 * Enqueue admin stylesheets and javascript
 *
 * $hook is the current admin page filename (same as global $pagenow)
 */
add_action( 'admin_enqueue_scripts', 'syn_enqueue_admin_scripts' );
function syn_enqueue_admin_scripts( $hook ) {
	// Font Awesome
	wp_enqueue_style( 'fontawesome-stylesheet', get_stylesheet_directory_uri() . '/assets/libs/fontawesome-v4.7.0/fontawesome.css', null, '4.7.0' );
	wp_enqueue_style( 'syntric-admin-stylesheet', get_template_directory_uri() . '/assets/css/syntric-admin.min.css' );
	wp_enqueue_script( 'syntric-admin', get_template_directory_uri() . '/assets/js/syntric-admin.min.js' );
}