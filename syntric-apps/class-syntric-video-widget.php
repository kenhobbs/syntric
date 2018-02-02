<?php

	/**
	 * Syntric_Video_Widget
	 */
	class Syntric_Video_Widget extends WP_Widget {
		/**
		 * Set up a new widget instance
		 */
		public function __construct() {
			$widget_ops = [ 'classname' => 'syn-video-widget', 'description' => __( 'Displays a YouTube or Vimeo video.' ), 'customize_selective_refresh' => true, ];
			parent::__construct( 'syn-video-widget', __( 'Video' ), $widget_ops );
			$this->alt_option_name = 'syn-video-widget';
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
			$dynamic = get_field( 'syn_video_widget_dynamic', 'widget_' . $args[ 'widget_id' ] );
			if ( $dynamic ) {
				$active = get_field( 'syn_video_active', $post->ID );
				if ( ! $active ) {
					return;
				}
				$title    = get_field( 'syn_video_title', $post->ID );
				$host     = get_field( 'syn_video_host', $post->ID );
				$video_id = ( 'YouTube' == $host ) ? get_field( 'syn_video_youtube_id', $post->ID ) : get_field( 'syn_video_vimeo_id', $post->ID );
			} else {
				$title    = get_field( 'syn_video_widget_title', 'widget_' . $args[ 'widget_id' ] );
				$host     = get_field( 'syn_video_widget_host', 'widget_' . $args[ 'widget_id' ] );
				$video_id = ( 'YouTube' == $host ) ? get_field( 'syn_video_widget_youtube_id', 'widget_' . $args[ 'widget_id' ] ) : get_field( 'syn_video_widget_vimeo_id', 'widget_' . $args[ 'widget_id' ] );
			}
			if ( isset( $video_id ) && ! empty( $video_id ) ) :
				//$sidebar      = syn_widget_sidebar( $args[ 'widget_id' ] );
				$sidebar_class = syn_get_sidebar_class( $args[ 'widget_id' ] );
				if ( syn_remove_whitespace() ) {
					$lb  = '';
					$tab = '';
				} else {
					$lb  = "\n";
					$tab = "\t";
				}
				echo $args[ 'before_widget' ] . $lb;
				if ( ! empty( $title ) ) :
					echo $args[ 'before_title' ] . $title . $args[ 'after_title' ] . $lb;
				endif;
				echo '<div class="video ' . $sidebar_class . ' ' . strtolower( $host ) . ' embed-responsive embed-responsive-16by9">' . $lb;
				if ( 'YouTube' == $host ) {
					echo $tab . '<iframe class="embed-responsive-item" src="https://www.youtube-nocookie.com/embed/' . $video_id . '?rel=0&amp;controls=1&amp;showinfo=0" frameborder="0" gesture="media" allow="encrypted-media" allowfullscreen></iframe>';
				} elseif ( 'Vimeo' == $host ) {
					echo $tab . '<iframe class="embed-responsive-item" src="https://player.vimeo.com/video/' . $video_id . '?title=0&amp;byline=0&amp;portrait=0" frameborder="0" allowfullscreen></iframe>';
				}
				echo '</div>' . $lb;
				echo $args[ 'after_widget' ] . $lb;
			else:
				echo '<p>Video unavailable</p>';
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