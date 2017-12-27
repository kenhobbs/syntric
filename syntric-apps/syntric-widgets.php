<?php
/**
 * Syntric Apps: Widgets
 */
/**
 * Load custom widget fields
 */
add_filter( 'acf/load_field/name=syn_contact_widget_organization', 'syn_load_organizations' );
add_filter( 'acf/load_field/name=syn_contact_widget_person', 'syn_load_people' );
add_filter( 'acf/load_field/name=syn_upcoming_events_widget_calendar_id', 'syn_load_google_calendars' );

// todo: dynamically load widgets on widget option page instead of using checkbox options
add_filter( 'acf/load_field/name=syn_widgets_wordpress', 'syn_load_wordpress_widgets' );
function syn_load_wordpress_widgets( $field ) {
	$choices     = array();
	$wp_widget_factory = $GLOBALS[ 'wp_widget_factory' ];
	$wp_widgets        = $wp_widget_factory->widgets;
	if ( $wp_widgets ) {
		foreach ( $wp_widgets as $key => $value ) {
			$choices[ $key ] = $value->name;
		}
		$field[ 'choices' ] = $choices;
	}

	return $field;
}

/**
 * Register/unregister custom widgets.
 */
add_action( 'widgets_init', 'syn_widgets_init', 20 );
function syn_widgets_init() {
	/**
	 * Unregister unselected WP widgets according to option page
	 */
	$wp_widget_factory = $GLOBALS[ 'wp_widget_factory' ];
	$wp_widgets        = $wp_widget_factory->widgets;
	$wp_widgets        = array_keys( $wp_widgets );
	if ( $wp_widgets ) {
		$selected_wp_widgets = get_field( 'syn_widgets_wordpress', 'option' );
		$selected_wp_widgets = ( $selected_wp_widgets ) ? $selected_wp_widgets : array();
		for ( $i = 0; $i < count( $wp_widgets ); $i ++ ) {
			if ( ! in_array( $wp_widgets[ $i ], $selected_wp_widgets ) ) {
				unregister_widget( $wp_widgets[ $i ] );
			}
		}
	}
	/**
	 * Register selected Syntric widgets according to option page
	 */
	$selected_syntric_widgets = get_field( 'syn_widgets_syntric', 'option' );
	if ( $selected_syntric_widgets ) {
		$syntric_widgets = array_values( $selected_syntric_widgets );
		if ( $syntric_widgets ) {
			foreach ( $syntric_widgets as $syntric_widget ) {
				$widget_class      = str_replace( '_', '-', $syntric_widget );
				$widget_class_file = 'class-' . strtolower( $widget_class ) . '.php';
				require_once( $widget_class_file );
				register_widget( $syntric_widget );
			}
		}
	}
}

add_filter( 'acf/prepare_field/name=syn_contact_widget_default', 'syn_prepare_contact_widget' );
add_filter( 'acf/prepare_field/name=syn_contact_default', 'syn_prepare_contact_widget' );
function syn_prepare_contact_widget( $field ) {
	if ( 'syn_contact_widget_default' == $field[ '_name' ] ) {
		$field[ 'label' ]   = '';
		$field[ 'message' ] = 'Use ' . get_field( 'syn_organization', 'option' );
	}
	if ( 'syn_contact_default' == $field[ '_name' ] ) {
		$field[ 'label' ]   = get_field( 'syn_organization', 'option' );
		$field[ 'message' ] = 'Use ' . get_field( 'syn_organization', 'option' );
	}

	return $field;
}

/**
 * Return sidebar custom fields for a widget
 *
 * @param $widget_id
 */
function syn_widget_sidebar( $widget_id ) {
	$sidebars_widgets  = get_option( 'sidebars_widgets', array() );
	$widget_sidebar_id = '';
	foreach ( $sidebars_widgets as $key => $value ) {
		if ( in_array( $widget_id, $value ) ) {
			$widget_sidebar_id = $key;
			break;
		}
	}
	$sidebars = get_field( 'syn_sidebars', 'option' );
	foreach ( $sidebars as $sidebar ) {
		$sidebar_id = $sidebar[ 'sidebar_id' ];
		if ( $widget_sidebar_id == $sidebar_id ) {
			return $sidebar;
		}
	}

	return;
}