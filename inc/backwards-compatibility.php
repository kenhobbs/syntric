<?php

/**
 * Syntric backwards compatibility functionality
 *
 * Prevents Syntric from running on WordPress versions prior to 4.7,
 * since this theme is not meant to be backward compatible beyond that and
 * relies on many newer functions and markup changes introduced in 4.7.
 *
 * @package    WordPress
 * @subpackage Syntric
 * @since      Syntric 1.0.0
 */

if( version_compare( $GLOBALS[ 'wp_version' ], '4.7', '<' ) ) {
	/**
	 * Prevent switching to Syntric on old versions of WordPress.
	 *
	 * Switches to the default theme.
	 *
	 * @since Syntric 1.0.0
	 */
	add_action( 'after_switch_theme', 'syntric_switch_theme' );
	function syntric_switch_theme() {
		switch_theme( WP_DEFAULT_THEME );
		unset( $_GET[ 'activated' ] );
		add_action( 'admin_notices', 'syntric_upgrade_notice' );
	}

	/**
	 * Adds a message for unsuccessful theme switch.
	 *
	 * Prints an update nag after an unsuccessful attempt to switch to
	 * Syntric on WordPress versions prior to 4.7.
	 *
	 * @since Syntric 1.0.0
	 *
	 * @global string $wp_version WordPress version.
	 */
	function syntric_upgrade_notice() {
		$message = sprintf( __( 'Syntric requires at least WordPress version 4.7. You are running version %s. Please upgrade and try again.', 'syntric' ), $GLOBALS[ 'wp_version' ] );
		printf( '<div class="error"><p>%s</p></div>', $message );
	}

	/**
	 * Prevents the Customizer from being loaded on WordPress versions prior to 4.7.
	 *
	 * @since Syntric 1.0.0
	 *
	 * @global string $wp_version WordPress version.
	 */
	add_action( 'load-customize.php', 'syntric_customize' );
	function syntric_customize() {
		wp_die(
			sprintf(
				__( 'Syntric requires at least WordPress version 4.7. You are running version %s. Please upgrade and try again.', 'syntric' ),
				$GLOBALS[ 'wp_version' ]
			),
			'',
			[
				'back_link' => true,
			]
		);
	}

	/**
	 * Prevents the Theme Preview from being loaded on WordPress versions prior to 4.7.
	 *
	 * @since Syntric 1.0.0
	 *
	 * @global string $wp_version WordPress version.
	 */
	add_action( 'template_redirect', 'syntric_preview' );
	function syntric_preview() {
		if( isset( $_GET[ 'preview' ] ) ) {
			wp_die( sprintf( __( 'Syntric requires at least WordPress version 4.7. You are running version %s. Please upgrade and try again.', 'syntric' ), $GLOBALS[ 'wp_version' ] ) );
		}
	}

	wp_die( 'Sorry, Syntric cannot be loaded with this version of Wordpress.' );
}