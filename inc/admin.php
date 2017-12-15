<?php
/**
 * User profile contact methods mods
 */
add_filter( 'user_contactmethods', 'syn_remove_user_profile_fields' );
function syn_remove_user_profile_fields( $fields ) {
	unset( $fields[ 'aim' ] );
	unset( $fields[ 'jabber' ] );
	unset( $fields[ 'yim' ] );

	return $fields;
}

// Set admin color for all users, overriding their options
add_action( 'wp_login', 'syn_set_admin_color', 10, 2 );
function syn_set_admin_color( $user_login, $user ) {
	if ( $user instanceof WP_User ) {
		$args = array(
			'ID'          => $user->ID,
			'admin_color' => 'light',
		);
		wp_update_user( $args );
	}
}

// Hide Screen options tab at the top right for all but administrators
add_filter( 'screen_options_show_screen', 'syn_screen_options_show_screen' );
function syn_screen_options_show_screen() {
	return current_user_can( 'administrator' );
}

// Remove the Help tab at the top right of the main admin frame
add_action( 'admin_head', 'syn_remove_admin_help_tab' );
function syn_remove_admin_help_tab() {
	$screen = get_current_screen();
	$screen->remove_help_tabs();
}

// Set stylesheet in TinyMCE editor
add_action( 'admin_init', 'syn_add_editor_stylesheet' );
function syn_add_editor_stylesheet() {
	$editor_stylesheet = syn_get_theme_stylesheet_uri();
	add_editor_style( $editor_stylesheet );
}

/**
 * Enqueue admin stylesheets and javascript
 *
 * $hook is the current admin page filename (same as global $pagenow)
 */
add_action( 'admin_enqueue_scripts', 'syn_enqueue_admin_scripts' );
function syn_enqueue_admin_scripts( $hook ) {
	wp_enqueue_style( 'google-fonts-stylesheet', '//fonts.googleapis.com/css?family=Roboto:300,400:500:700' );
	wp_enqueue_style( 'fontawesome-stylesheet', get_stylesheet_directory_uri() . '/assets/libs/fontawesome-v4.7.0/fontawesome.css', null, '4.7.0' );
	wp_enqueue_style( 'syntric-admin-stylesheet', get_template_directory_uri() . '/assets/css/syntric-admin.min.css' );
	wp_enqueue_script( 'syntric-admin', get_template_directory_uri() . '/assets/js/syntric-admin.min.js' );
}

/**
 * Returns the full URI to the theme stylesheet
 *
 * Checks to see if a .css file exists corresponding the the server.http_host value (eg /path/to/master.syntric.com.min.css) and if so
 * returns it's URI.  If not return the default stylesheet (/path/to/syntric.min.css). Returns un-minified version if on dev server (xxxx.localhost).
 *
 * @return string Full URI to a theme stylesheet
 */
function syn_get_theme_stylesheet_uri() {
	$is_dev          = syn_is_dev();
	$http_host       = syn_get_http_host();
	$host_style      = ( ! $is_dev ) ? $http_host . '.min' : $http_host;
	$default_style   = ( ! $is_dev ) ? 'syntric.min' : 'syntric';
	$host_file       = get_stylesheet_directory() . '/assets/css/' . $host_style . '.css';
	$theme_style     = ( file_exists( $host_file ) ) ? $host_style : $default_style;
	$theme_style_uri = get_stylesheet_directory_uri() . '/assets/css/' . $theme_style . '.css';

	return $theme_style_uri;
}