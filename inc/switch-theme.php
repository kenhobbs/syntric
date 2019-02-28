<?php
	
	/**
	 * Set up Syntric theme after
	 */
	add_action( 'after_switch_theme', 'syntric_setup_syntric_theme', 10, 2 );
	function syntric_setup_syntric_theme( $old_theme_name, $old_theme ) {
		$theme      = wp_get_theme();
		$theme_name = $theme -> get( 'Name' );
		if( 'syntric' == strtolower( $theme_name ) ) {
			syntric_set_settings();
			syntric_set_permalink_structure();
			syntric_set_default_post_categories();
			syntric_setup_primary_menu();
			if( is_super_admin() && is_admin() ) {
				//wp_redirect( 'options-permalink.php' );
			}
		}
	}
	
	