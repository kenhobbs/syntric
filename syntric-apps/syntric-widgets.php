<?php
	/**
	 * Syntric Apps: Widgets
	 */
	
	/**
	 * Register/unregister custom widgets.
	 */
	add_action( 'widgets_init', 'syntric_widgets_init', 20 );
	function syntric_widgets_init() {
		/**
		 * Register selected Syntric widgets according to option page
		 */
		$settings = get_field( 'syntric_settings', 'option' );
		$widgets  = $settings[ 'widgets' ];
		if( $widgets ) {
			foreach( $widgets as $widget ) {
				$widget_class      = str_replace( '_', '-', $widget );
				$widget_class_file = 'class-' . strtolower( $widget_class ) . '.php';
				require_once( $widget_class_file );
				register_widget( $widget );
			}
		}
	}
	
	/**
	 * Return sidebar custom fields for a widget
	 *
	 * @param $widget_id
	 */
	function syntric_widget_sidebar( $widget_id ) {
		$sidebars_widgets  = get_option( 'sidebars_widgets', [] );
		$widget_sidebar_id = '';
		foreach( $sidebars_widgets as $key => $value ) {
			if( in_array( $widget_id, $value ) ) {
				$widget_sidebar_id = $key;
				break;
			}
		}
		$settings = get_field( 'syntric_settings', 'option' );
		$sidebars = $settings[ 'sidebars' ];
		//$sidebars = get_field( 'syn_sidebars', 'option' );
		foreach( $sidebars as $sidebar ) {
			if( $widget_sidebar_id == $sidebar[ 'value' ] ) {
				return $sidebar;
			}
		}
		
		return;
	}
	
	add_filter( 'widget_display_callback', 'syntric_widget_display_callback', 100, 3 );
	function syntric_widget_display_callback( $instance, $widget_class, $args ) {
		$filters      = get_field( 'filters', 'widget_' . $args[ 'widget_id' ] );
		$pass_filters = syntric_process_filters( $filters );
		if( $pass_filters ) {
			return $instance;
		}
		
		return false;
	}