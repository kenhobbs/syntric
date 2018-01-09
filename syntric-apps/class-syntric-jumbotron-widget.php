<?php

	/**
	 * Syntric_Jumbotron_Widget
	 *
	 * Dynamic + static widget to display person or organization jumbotron information.
	 */
	class Syntric_Jumbotron_Widget extends WP_Widget {
		/**
		 * Set up a new widget instance
		 */
		public function __construct() {
			$widget_ops = [
				'classname'                   => 'syn-jumbotron-widget',
				'description'                 => __( 'Displays a Jumbotron' ),
				'customize_selective_refresh' => true,
			];
			parent::__construct( 'syn-jumbotron-widget', __( 'Jumbotron' ), $widget_ops );
			$this->alt_option_name = 'syn-jumbotron-widget';
		}

		/**
		 * Output widget content
		 *
		 * @param array $args     Display arguments including 'before_title', 'after_title', 'before_widget', and 'after_widget'.
		 * @param array $instance Settings for the current widget instance.
		 */
		public function widget( $args, $instance ) {
			global $post;
			if( ! isset( $args[ 'widget_id' ] ) ) {
				$args[ 'widget_id' ] = $this->id;
			}
			$jumbotron_dynamic = get_field( 'syn_jumbotron_widget_dynamic', 'widget_' . $args[ 'widget_id' ] );
			$jumbotron         = false;
			if( $jumbotron_dynamic ) {
				$active = get_field( 'syn_jumbotron_active', $post->ID );
				if( ! $active ) {
					return;
				}
				$start_datetime      = get_field( 'syn_jumbotron_start_datetime', $post->ID );
				$end_datetime        = get_field( 'syn_jumbotron_end_datetime', $post->ID );
				$jumbotron_scheduled = syn_process_schedule( $start_datetime, $end_datetime );
				if( ! $jumbotron_scheduled ) {
					return;
				}
				$jumbotron                        = [];
				$jumbotron[ 'headline' ]          = get_field( 'syn_jumbotron_headline', $post->ID );
				$jumbotron[ 'caption' ]           = get_field( 'syn_jumbotron_caption', $post->ID );
				$jumbotron[ 'include_button' ]    = ( 1 == get_field( 'syn_jumbotron_include_button', $post->ID ) ) ? 1 : 0;
				$jumbotron[ 'button_target' ]     = get_field( 'syn_jumbotron_button_target', $post->ID );
				$jumbotron[ 'button_text' ]       = get_field( 'syn_jumbotron_button_text', $post->ID );
				$jumbotron[ 'button_url' ]        = get_field( 'syn_jumbotron_button_url', $post->ID );
				$jumbotron[ 'button_page' ]       = get_field( 'syn_jumbotron_button_page', $post->ID );
				$jumbotron[ 'button_new_window' ] = ( 1 == get_field( 'syn_jumbotron_button_new_window', $post->ID ) ) ? 1 : 0;
			} else {
				if( have_rows( 'syn_jumbotrons', 'option' ) ) :
					while( have_rows( 'syn_jumbotrons', 'option' ) ) : the_row();
						$jumbtron_scheduled = syn_process_schedule( get_sub_field( 'start_datetime' ), get_sub_field( 'end_datetime' ) );
						if( ! $jumbtron_scheduled ) {
							continue;
						}
						$jumbotron_filters  = get_sub_field( 'filters' );
						$jumbotron_filtered = syn_process_filters( $jumbotron_filters, $post );
						if( ! $jumbotron_filtered ) {
							continue;
						}
						$jumbotron                        = [];
						$jumbotron[ 'headline' ]          = get_sub_field( 'headline' );
						$jumbotron[ 'caption' ]           = get_sub_field( 'caption' );
						$jumbotron[ 'include_button' ]    = ( 1 == get_sub_field( 'include_button' ) ) ? 1 : 0;
						$jumbotron[ 'button_target' ]     = get_sub_field( 'button_target' );
						$jumbotron[ 'button_text' ]       = get_sub_field( 'button_text' );
						$jumbotron[ 'button_url' ]        = get_sub_field( 'button_url' );
						$jumbotron[ 'button_page' ]       = get_sub_field( 'button_page' );
						$jumbotron[ 'button_new_window' ] = ( 1 == get_sub_field( 'button_new_window' ) ) ? 1 : 0;
						break;
					endwhile;
				endif;
			}
			if( is_array( $jumbotron ) ) {
				$widget_id_array = explode( '-', $args[ 'widget_id' ] );
				array_pop( $widget_id_array );
				$widget_class = implode( '-', $widget_id_array );
				echo '<div id="' . $args[ 'widget_id' ] . '" class="col widget ' . $widget_class . '">';
				echo '<h2 class="jumbotron-headline">' . $jumbotron[ 'headline' ] . '</h2>';
				echo '<div class="jumbotron-caption">' . $jumbotron[ 'caption' ] . '</div>';
				if( $jumbotron[ 'include_button' ] ) {
					$button_href   = ( 'page' == $jumbotron[ 'button_target' ] ) ? $jumbotron[ 'button_page' ] : $jumbotron[ 'button_url' ];
					$window_target = ( 'page' == $jumbotron[ 'button_target' ] ) ? '_self' : '_blank';
					echo '<div class="jumbotron-button">';
					echo '<a href="' . $button_href . '" class="jumbotron-button" target="' . $window_target . '">';
					echo $jumbotron[ 'button_text' ];
					echo '</a>';
					echo '</div>';
				}
				echo '</div>';
			}
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
