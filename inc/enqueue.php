<?php
/**
 * Enqueue front-end stylesheets and javascript
 *
 * @package syntric
 */
add_action( 'wp_enqueue_scripts', 'syn_enqueue_scripts' );
function syn_enqueue_scripts() {
	$the_theme      = wp_get_theme();
	// Deregister jQuery - in order to enqueue it strategically
	wp_deregister_script( 'jquery' );
	/**
	 * Stylesheets
	 */
	// Google Fonts @ CDN
	wp_enqueue_style( 'google-fonts-stylesheet', '//fonts.googleapis.com/css?family=Roboto:300,400:500|Open+Sans' );
	// Font Awesome
	wp_enqueue_style( 'fontawesome-stylesheet', get_stylesheet_directory_uri() . '/assets/libs/fontawesome-v4.7.0/fontawesome.css', null, null );
	// Bootstrap 4.0
	wp_enqueue_style( 'bootstrap-stylesheet', get_stylesheet_directory_uri() . '/assets/libs/bootstrap/4.0.0-beta/dist/css/bootstrap.min.css', null, null );
	// FullCalendar
	wp_enqueue_style( 'fullcalendar-stylesheet', get_stylesheet_directory_uri() . '/assets/libs/fullcalendar/fullcalendar.min.css', null, null );
	// FullCalendar print
	//wp_enqueue_style( 'fullcalendar-print-stylesheet', get_stylesheet_directory_uri() . '/assets/libs/fullcalendar/fullcalendar.print.css', null, '3.6.1' );
	// Master theme stylesheet
	wp_enqueue_style( 'master-theme-stylesheet', syn_get_theme_stylesheet_uri(), array(), null );
	// Child theme stylesheet
	wp_enqueue_style( 'child-theme-stylesheet', get_stylesheet_uri(), array(), null );
	/**
	 * Scripts in header
	 */
	// jQuery
	wp_enqueue_script( 'jquery', get_stylesheet_directory_uri() . '/assets/libs/jquery/3.2.1/jquery.min.js', null, null, false );
	// moment
	wp_enqueue_script( 'moment', get_stylesheet_directory_uri() . '/assets/libs/fullcalendar/lib/moment.min.js', null, null, true );
	// tether.js for Bootstrap tooltips
	wp_enqueue_script( 'tether', get_stylesheet_directory_uri() . '/assets/libs/bootstrap/4.0.0-beta/assets/js/vendor/popper.min.js', null, null, true );
	// Bootstrap script
	wp_enqueue_script( 'bootstrap', get_stylesheet_directory_uri() . '/assets/libs/bootstrap/4.0.0-beta/dist/js/bootstrap.min.js', array(
		'jquery',
		'tether',
	), null, true );
	// FullCalendar
	wp_enqueue_script( 'fullcalendar', get_stylesheet_directory_uri() . '/assets/libs/fullcalendar/fullcalendar.min.js', array(
		'jquery',
		'moment',
	), null, false );
	// Google Calendar for FullCalendar
	//wp_enqueue_script( 'gcal', get_stylesheet_directory_uri() . '/assets/libs/fullcalendar/gcal.js', array( 'jquery', 'fullcalendar' ), '3.6.1', true );
	// Syntric theme script
	wp_enqueue_script( 'syntric', get_template_directory_uri() . '/assets/js/syntric.min.js', array( 'jquery' ), null, false );
	// Syntric API script
	wp_enqueue_script( 'syntric-api', get_template_directory_uri() . '/assets/js/syntric-api.min.js', array( 'jquery' ), null, false );
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