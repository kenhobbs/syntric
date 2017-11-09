<?php
/**
 * Inspired by Simon Bradbury. See https://github.com/SimonPadbury/b4st
 *
 * @package syntric
 */
/**
 * Remove insecure or unneeded features from wp_head()
 */
remove_action( 'wp_head', 'wp_generator' );
remove_action( 'wp_head', 'rsd_link' );
remove_action( 'wp_head', 'wlwmanifest_link' );
remove_action( 'wp_head', 'index_rel_link' );
remove_action( 'wp_head', 'feed_links', 2 );
remove_action( 'wp_head', 'feed_links_extra', 3 );
remove_action( 'wp_head', 'adjacent_posts_rel_link_wp_head', 10 );
remove_action( 'wp_head', 'wp_shortlink_wp_head', 10 );
add_filter( 'xmlrpc_enabled', '__return_false' );
// Disable X-Pingback to header
add_filter( 'wp_headers', 'syn_disable_x_pingback' );
function syn_disable_x_pingback( $headers ) {
	unset( $headers[ 'X-Pingback' ] );

	return $headers;
}

/**
 * Don't let user know username is valid on failed login
 *
 * @return string
 */
add_filter( 'login_errors', 'syn_less_login_info' );
function syn_less_login_info() {
	return 'Login failed';
}