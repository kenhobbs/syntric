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
				'classname'                   => 'syn-google-map-widget',
				'description'                 => __( 'Displays a Google Map.' ),
				'customize_selective_refresh' => true,
			];
			parent::__construct( 'syn-google-map-widget', __( 'Google Map' ), $widget_ops );
			$this->alt_option_name = 'syn-google-map-widget';
		}

		/**
		 * Output widget content
		 *
		 * @param array $args     Display arguments including 'before_title', 'after_title', 'before_widget', and 'after_widget'.
		 * @param array $instance Settings for the current widget instance.
		 */
		public function widget( $args, $instance ) {
			global $post;
			if ( ! isset( $args[ 'widget_id' ] ) ) {
				$args[ 'widget_id' ] = $this->id;
			}
			$dynamic = get_field( 'syn_google_map_widget_dynamic', 'widget_' . $args[ 'widget_id' ] );
			if ( $dynamic ) {
				$active = get_field( 'syn_google_map_active', $post->ID );
				if ( ! $active ) {
					return;
				}
				$title         = get_field( 'syn_google_map_title', $post->ID );
				$google_map_id = get_field( 'syn_google_map_id', $post->ID );
			} else {
				$title         = get_field( 'syn_google_map_widget_title', 'widget_' . $args[ 'widget_id' ] );
				$google_map_id = get_field( 'syn_google_map_widget_map_id', 'widget_' . $args[ 'widget_id' ] );
			}
			if ( isset( $google_map_id ) && ! empty( $google_map_id ) ) :
				$unique_id = syn_generate_permanent_id();
				//$sidebar   = syn_widget_sidebar( $args[ 'widget_id' ] );
				$sidebar_class = syn_get_sidebar_class( $args[ 'widget_id' ] );
				$lb            = syn_get_linebreak();
				$tab           = syn_get_tab();
				echo $args[ 'before_widget' ] . $lb;
				if ( ! empty( $title ) ) :
					echo $args[ 'before_title' ] . $title . $args[ 'after_title' ] . $lb;
				endif;
				echo $tab . '<div id="' . $unique_id . '" data-id="' . $google_map_id . '" class="map ' . $sidebar_class . '"></div>' . $lb;
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