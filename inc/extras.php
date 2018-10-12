<?php
	/**
	 * Custom functions that act independently of the theme templates
	 *
	 * Eventually, some of the functionality here could be replaced by core features.
	 *
	 * @package Syntric
	 */
	/**
	 * Adjust body classes.
	 *
	 * @param array $classes Classes for the body element.
	 *
	 * @return array
	 */
	add_filter( 'body_class', 'syn_body_classes' );
	function syn_body_classes( $classes ) {
		// Remove tag classes to avoid Bootstrap conflicts
		foreach ( $classes as $key => $value ) {
			if ( 'tag' == $value ) {
				unset( $classes[ $key ] );
			}
		}
		// Adds a class of group-blog to blogs with more than 1 published author.
		if ( is_multi_author() ) {
			$classes[] = 'group-blog';
		}
		// Adds a class of hfeed to non-singular pages.
		if ( ! is_singular() ) {
			$classes[] = 'hfeed';
		}

		return $classes;
	}

	/**
	 * Add a pingback url auto-discovery header for singularly identifiable articles.
	 */
	add_action( 'wp_head', 'syn_pingback_header' );
	function syn_pingback_header() {
		if ( is_singular() && pings_open() ) {
			echo '<link rel="pingback" href="', esc_url( get_bloginfo( 'pingback_url' ) ), '">';
		}
	}