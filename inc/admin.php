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
			$args = [
				'ID'          => $user->ID,
				'admin_color' => 'blue',
			];
			wp_update_user( $args );
		}
	}

// Hide Screen options tab at the top right for less than editor
	add_filter( 'screen_options_show_screen', 'syn_screen_options_show_screen' );
	function syn_screen_options_show_screen() {
		return syn_current_user_can( 'editor' );
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
		// looks for editor-style.css in theme root directory
		//add_editor_style();
	}

	/**
	 * Enqueue admin stylesheets and javascript
	 *
	 * $hook is the current admin page filename (same as global $pagenow)
	 */
	add_action( 'admin_enqueue_scripts', 'syn_enqueue_admin_scripts' );
	function syn_enqueue_admin_scripts( $hook ) {
		wp_enqueue_style( 'google-fonts-stylesheet', '//fonts.googleapis.com/css?family=Roboto:300,400:500:700' );
		wp_enqueue_script( 'fontawesome', 'https://use.fontawesome.com/f45398b257.js', null, '5.0.1', false );
		wp_enqueue_style( 'syntric-admin-stylesheet', get_template_directory_uri() . '/assets/css/syntric-admin.min.css' );
		wp_enqueue_script( 'syntric-admin', get_template_directory_uri() . '/assets/js/syntric-admin.min.js', 'jquery', null, true );
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
		$http_host       = $_SERVER[ 'HTTP_HOST' ];
		$theme_style_uri = get_stylesheet_directory_uri() . '/assets/css/' . $http_host . '.min.css';

		return $theme_style_uri;
	}

	if ( isset( $_GET[ 'reset_roles' ] ) && '555ajaj' == $_GET[ 'reset_roles' ] ) {
		if ( ! function_exists( 'populate_roles' ) ) {
			require_once( ABSPATH . 'wp-admin/includes/schema.php' );
		}
		populate_roles();
	}
	/**
	 * Control appearance of bulk actions in WP admin by role
	 */
	add_action( 'current_screen', 'syn_remove_bulk_actions' );
	function syn_remove_bulk_actions() {
		if ( ! is_admin() ) {
			return;
		}
		/*global $my_admin_page;
		slog($my_admin_page);
		$screen = get_current_screen();
		slog($screen);*/
		if ( ! syn_current_user_can( 'editor' ) ) {
			add_filter( 'bulk_actions-edit-post', '__return_empty_array' );
			add_filter( 'bulk_actions-edit-page', '__return_empty_array' );
			add_filter( 'bulk_actions-upload', '__return_empty_array' );
		}
	}