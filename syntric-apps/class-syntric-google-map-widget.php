<?php
	
	/**
	 * Syntric_Google_Map_Widget
	 */
	class Syntric_Google_Map_Widget extends WP_Widget {
		/**
		 * Set up a new widget instance
		 */
		public function __construct() {
			$widget_ops = [
				'classname'                   => 'syntric-google-map-widget',
				'description'                 => __( 'Displays a Google Map.' ),
				'customize_selective_refresh' => true,
			];
			parent ::__construct( 'syntric-google-map-widget', __( 'Google Map' ), $widget_ops );
			$this -> alt_option_name = 'syntric-google-map-widget';
		}
		
		/**
		 * Output widget content
		 *
		 * @param array $args     Display arguments including 'before_title', 'after_title', 'before_widget', and 'after_widget'.
		 * @param array $instance Settings for the current widget instance.
		 */
		public function widget( $args, $instance ) {
			$widget_id         = ( isset( $args[ 'widget_id' ] ) ) ? $args[ 'widget_id' ] : $this -> id;
			$google_map_widget = get_field( 'syntric_google_map_widget', 'widget_' . $widget_id );
			$map               = $google_map_widget[ 'map' ];
			if( isset( $map[ 'lat' ] ) && ! empty( $map[ 'lat' ] ) && isset( $map[ 'lng' ] ) && ! empty( $map[ 'lng' ] ) ) {
				;
				//syntric_google_api_key( $api );
				$title   = $google_map_widget[ 'title' ];
				$id      = syntric_unique_id();
				$id_arr  = explode( '-', $id );
				$id      = $id_arr[ 1 ];
				$address = ( isset( $map[ 'address' ] ) && ! empty( $map[ 'address' ] ) ) ? $map[ 'address' ] : '';
				echo $args[ 'before_widget' ];
				if( ! empty( $title ) ) :
					echo $args[ 'before_title' ] . $title . $args[ 'after_title' ];
				endif;
				echo '<div id="map_' . $id . '" class="map">';
				//if( ! empty( $address ) ) {
				echo '<div class="marker" data-lat="' . $map[ 'lat' ] . '" data-lng="' . $map[ 'lng' ] . '">';
				//echo    '<h4>' . 'Make a field for title and use here' . '</h4>';
				//echo    '<p class="address">' . $address . '</p>';
				echo '</div>';
				//}
				echo '</div>';
				echo $args[ 'after_widget' ];
			}
			/*if( isset( $google_map_id ) && ! empty( $google_map_id ) ) :
				$unique_id = syntric_generate_permanent_id();
				//$sidebar   = syntric_widget_sidebar( $widget_id );
				//$sidebar_class = syntric_get_sidebar_class( $widget_id );
				
				;
				echo $args[ 'before_widget' ];
				if( ! empty( $title ) ) :
					echo $args[ 'before_title' ] . $title . $args[ 'after_title' ];
				endif;
				echo  '<div id="' . $unique_id . '" data-id="' . $google_map_id . '" class="map"></div>';
				echo $args[ 'after_widget' ];
			endif;*/
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
		public function form( $instance ) {
		}
	}