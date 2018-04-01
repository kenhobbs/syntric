<?php
/**
 * Theme functions and definitions
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package Syntric
 */
/**
 * Admin functions.
 */
require get_template_directory() . '/inc/admin.php';
/**
 * Comment customizations
 */
require get_template_directory() . '/inc/comments.php';
/**
 * Customizer additions.
 */
require get_template_directory() . '/inc/customizer.php';
/**
 * WYSIWYG Editor
 */
require get_template_directory() . '/inc/editor.php';
/**
 * Enqueue scripts and styles.
 */
require get_template_directory() . '/inc/enqueue.php';
/**
 * Custom functions that act independently of the theme templates.
 */
require get_template_directory() . '/inc/extras.php';
/**
 * Custom template tags for this theme.
 */
require get_template_directory() . '/inc/pagination.php';
/**
 * Search functions
 */
require get_template_directory() . '/inc/search.php';
/**
 * Load functions to secure your WP install.
 */
require get_template_directory() . '/inc/security.php';
/**
 * Theme setup and custom theme supports.
 */
require get_template_directory() . '/inc/setup.php';
/**
 * Custom template tags for this theme.
 */
require get_template_directory() . '/inc/templates.php';
/**
 * Utility functions.
 */
require get_template_directory() . '/inc/utility.php';
	/**
 * Syntric Apps
 */
// since is_plugin_active is in plugin.php and is loaded late, need to include the file before calling the function
include_once( ABSPATH . 'wp-admin/includes/plugin.php' );
if ( is_plugin_active( 'advanced-custom-fields-pro/acf.php' ) ) {
	require get_template_directory() . '/syntric-apps/syntric-apps.php';
}