<?php

	/**
	 * Syntric_Embed_Widget
	 */
	class Syntric_Embed_Widget extends WP_Widget {
		/**
		 * Set up a new widget instance
		 */
		public function __construct() {
			$widget_ops = [ 'classname'                   => 'syn-embed-widget',
			                'description'                 => __( 'Displays an embedded object (oEmbed)' ),
			                'customize_selective_refresh' => true,
			];
			parent::__construct( 'syn-embed-widget', __( 'Embed' ), $widget_ops );
			$this->alt_option_name = 'syn-embed-widget';
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
			$dynamic = get_field( 'syn_embed_widget_dynamic', 'widget_' . $args[ 'widget_id' ] );
			if ( $dynamic ) {
				$active = get_field( 'syn_embed_active', $post->ID );
				if ( ! $active ) {
					return;
				}
				$title      = get_field( 'syn_embed_title', $post->ID );
				$videos     = get_field( 'syn_embed_videos', $post->ID );
				$media_type = get_field( 'syn_embed_type', $post->ID );
				$orientation = get_field( 'syn_embed_orientation', $post->ID );
				/*$caption = get_field( 'syn_embed_caption', $post->ID );
				$type    = strtolower( get_field( 'syn_embed_type', $post->ID ) );
				$host    = strtolower( get_field( 'syn_embed_host', $post->ID ) );
				if ( 'single' == $type ) {
					$video_id = ( 'youtube' == $host ) ? get_field( 'syn_embed_youtube_id', $post->ID ) : get_field( 'syn_embed_vimeo_id', $post->ID );
				} elseif ( 'youtube' == $host && 'playlist' == $type ) {
					$video_id = ( 'youtube' == $host ) ? get_field( 'syn_embed_youtube_playlist_id', $post->ID ) : 0;
				}*/
			} else {
				$title      = get_field( 'syn_embed_widget_title', 'widget_' . $args[ 'widget_id' ] );
				$videos     = get_field( 'syn_embed_widget_videos', 'widget_' . $args[ 'widget_id' ] );
				$media_type = get_field( 'syn_embed_widget_type', 'widget_' . $args[ 'widget_id' ] );
				$orientation = get_field( 'syn_embed_widget_orientation', 'widget_' . $args[ 'widget_id' ] );



				/**
				 *
				 *
				 *
				 * Left off with adding $media_type and $orientation, need to add to the ACF field group
				 *
				 * $media_type might be PDF, YouTube video, Google form, Google drive, other
				 * $orientation might be landscape, portrait
				 *
				 * consider height and width if they become necessary
				 *
				 *
				 *
				 *
				 */
				/*$caption = get_field( 'syn_embed_widget_caption', 'widget_' . $args[ 'widget_id' ] );
				$type    = strtolower( get_field( 'syn_embed_widget_type', 'widget_' . $args[ 'widget_id' ] ) );
				$host    = strtolower( get_field( 'syn_embed_widget_host', 'widget_' . $args[ 'widget_id' ] ) );
				if ( 'single' == $type ) {
					$video_id = ( 'youtube' == $host ) ? get_field( 'syn_embed_widget_youtube_id', $post->ID ) : get_field( 'syn_embed_widget_vimeo_id', $post->ID );
				} elseif ( 'youtube' == $host && 'playlist' == $type ) {
					$video_id = ( 'youtube' == $host ) ? get_field( 'syn_embed_widget_youtube_playlist_id', $post->ID ) : 0;
				}*/
			}
			if ( $videos ) :
				/*if ( 'youtube' == $host ) {
					if ( 'single' == $type ) {
						$iframe = '<iframe id="youtube-player-' . $video_id . '" class="embed-responsive-item" src="https://www.youtube.com/embed/' . $video_id . '?rel=0&controls=1&showinfo=0&enablejsapi=1" frameborder="0" gesture="media" allow="encrypted-media" allowfullscreen=""></iframe>';
					} elseif ( 'playlist' == $type ) {
						$iframe = '<iframe id="youtube-player-' . $video_id . '" class="embed-responsive-item" src="https://www.youtube.com/embed/videoseries?list=' . $video_id . '&enablejsapi=1" frameborder="0" allow="autoplay; encrypted-media" allowfullscreen></iframe>';
					}
				} elseif ( 'vimeo' == $host ) {
					$iframe = '<iframe id="vimeo-player-' . $video_id . '" class="embed-responsive-item" src="https://player.vimeo.com/video/' . $video_id . '?title=0&byline=0&portrait=0" frameborder="0" allowfullscreen></iframe>';
				}*/
				//$sidebar      = syn_widget_sidebar( $args[ 'widget_id' ] );
				$sidebar_class = syn_get_sidebar_class( $args[ 'widget_id' ] );
				$lb            = syn_get_linebreak();
				$tab           = syn_get_tab();
				echo $args[ 'before_widget' ] . $lb;
				if ( ! empty( $title ) ) :
					echo $args[ 'before_title' ] . $title . $args[ 'after_title' ] . $lb;
				endif;
				echo '<div class="d-flex">';
				foreach ( $videos as $video ) :
					$video_title = $video[ 'title' ];
					$description = $video[ 'description' ];
					echo '<div class="video embed-responsive embed-responsive-16by9">';
					echo '<h3 class="video-title">' . $video_title . '</h3>';
					echo $video[ 'video' ];
					echo '</div>';
					if ( $description ) {
						echo '<div class="video-description px-3">' . $description . '</div>';
					}
				endforeach;
				echo '</div>';
				/*				<div class="embed-responsive embed-responsive-4by3">
					<iframe src="https://docs.google.com/forms/d/e/1FAIpQLSfB7-LwwZEClDg3KJ4xboDdd2tzrSWzPnPZSNSn2BS1Y_aT5g/viewform?embedded=true" scrolling="no" class="embed-responsive-item" allowfullscreen>Loading...</iframe>
				</div>*/
				/*echo '<div class="video ' . $sidebar_class . ' ' . strtolower( $host ) . ' embed-responsive embed-responsive-16by9">' . $lb;
				echo $iframe;
				echo '</div>' . $lb;
				if ( $caption && ! empty( $caption ) ) {
					echo '<div class="video-caption">' . $lb;
					echo $caption;
					echo '</div>' . $lb;
				}*/
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