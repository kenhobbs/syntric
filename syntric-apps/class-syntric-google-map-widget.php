<?php

/**
 * Syntric_Google_Map_Widget
 */
class Syntric_Google_Map_Widget extends WP_Widget {
	/**
	 * Set up a new widget instance
	 */
	public function __construct() {
		$widget_ops = array(
			'classname'                   => 'syn-google-map-widget',
			'description'                 => __( 'Displays a Google Map.' ),
			'customize_selective_refresh' => true,
		);
		parent::__construct( 'syn-google-map-widget', __( 'Google Map' ), $widget_ops );
		$this->alt_option_name = 'syn-google-map-widget';
	}

	/**
	 * Output widget content
	 *
	 * @param array $args Display arguments including 'before_title', 'after_title', 'before_widget', and 'after_widget'.
	 * @param array $instance Settings for the current widget instance.
	 */
	public function widget( $args, $instance ) {
		if ( ! isset( $args[ 'widget_id' ] ) ) {
			$args[ 'widget_id' ] = $this->id;
		}
		$google_map_id = get_field( 'syn_google_map_widget_map', 'widget_' . $args[ 'widget_id' ] );
		$sidebar       = syn_widget_sidebar( $args[ 'widget_id' ] );
		if ( isset( $google_map_id ) && ! empty( $google_map_id ) ) :
			$lb        = "\n";
			$tab       = "\t";
			$unique_id = syn_generate_permanent_id();
			$title     = get_field( 'syn_google_map_widget_title', 'widget_' . $args[ 'widget_id' ] );
			echo $args[ 'before_widget' ] . $lb;
			if ( ! empty( $title ) ) :
				echo $args[ 'before_title' ] . $title . $args[ 'after_title' ] . $lb;
			endif;
			echo $tab . '<div id="' . $unique_id . '" class="map-wrapper">' . $lb;
			//syn_get_google_map( $google_map_id, $unique_id );
			echo $tab . '</div>' . $lb;
			echo $args[ 'after_widget' ] . $lb;
		endif;
	}

	/**
	 * Update settings for the current widget instance
	 *
	 * @param array $new_instance New settings for this instance as input by the user via WP_Widget::form().
	 * @param array $old_instance Old settings for this instance.
	 *
	 * @return array Updated settings to save.
	 */
	public function update( $new_instance, $old_instance ) {
		$instance = $old_instance;

		return $instance;
	}

	/**
	 * Render settings form for the widget
	 *
	 * @param array $instance Current settings
	 *
	 * @return void Displays settings form
	 */
	public function form( $instance ) { }
}