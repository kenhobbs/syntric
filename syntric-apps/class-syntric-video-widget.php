<?php

	/**
	 * Syntric_Video_Widget
	 */
	class Syntric_Video_Widget extends WP_Widget {
		/**
		 * Set up a new widget instance
		 */
		public function __construct() {
			$widget_ops = [ 'classname'                   => 'syn-video-widget',
			                'description'                 => __( 'Displays a YouTube or Vimeo video.' ),
			                'customize_selective_refresh' => true,
			];
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
				$title   = get_field( 'syn_video_title', $post->ID );
				$caption = get_field( 'syn_video_caption', $post->ID );
				$type    = strtolower( get_field( 'syn_video_type', $post->ID ) );
				$host    = strtolower( get_field( 'syn_video_host', $post->ID ) );
				if ( 'single' == $type ) {
					$video_id = ( 'youtube' == $host ) ? get_field( 'syn_video_youtube_id', $post->ID ) : get_field( 'syn_video_vimeo_id', $post->ID );
				} elseif ( 'youtube' == $host && 'playlist' == $type ) {
					$video_id = ( 'youtube' == $host ) ? get_field( 'syn_video_youtube_playlist_id', $post->ID ) : 0;
				}
			} else {
				$title   = get_field( 'syn_video_widget_title', 'widget_' . $args[ 'widget_id' ] );
				$caption = get_field( 'syn_video_widget_caption', 'widget_' . $args[ 'widget_id' ] );
				$type    = strtolower( get_field( 'syn_video_widget_type', 'widget_' . $args[ 'widget_id' ] ) );
				$host    = strtolower( get_field( 'syn_video_widget_host', 'widget_' . $args[ 'widget_id' ] ) );
				if ( 'single' == $type ) {
					$video_id = ( 'youtube' == $host ) ? get_field( 'syn_video_widget_youtube_id', 'widget_' . $args[ 'widget_id' ] ) : get_field( 'syn_video_widget_vimeo_id', 'widget_' . $args[ 'widget_id' ] );
				} elseif ( 'youtube' == $host && 'playlist' == $type ) {
					$video_id = ( 'youtube' == $host ) ? get_field( 'syn_video_widget_youtube_playlist_id', 'widget_' . $args[ 'widget_id' ] ) : 0;
				}
			}
			if ( isset( $video_id ) && ! empty( $video_id ) ) :
				if ( 'youtube' == $host ) {
					if ( 'single' == $type ) {
						$iframe = '<iframe id="youtube-player-' . $video_id . '" class="embed-responsive-item" src="https://www.youtube.com/embed/' . $video_id . '?autoplay=0&modestbranding=1&rel=0&controls=1&showinfo=0&enablejsapi=1" frameborder="0" allow="autoplay; fullscreen; encrypted-media" allowfullscreen></iframe>';
					} elseif ( 'playlist' == $type ) {
						$iframe = '<iframe id="youtube-player-' . $video_id . '" class="embed-responsive-item" src="https://www.youtube.com/embed/videoseries?list=' . $video_id . '&autoplay=0&modestbranding=1&rel=0&controls=1&showinfo=0&enablejsapi=1" frameborder="0" allow="autoplay; fullscreen; encrypted-media" allowfullscreen></iframe>';
					}
				} elseif ( 'vimeo' == $host ) {
					$iframe = '<iframe id="vimeo-player-' . $video_id . '" class="embed-responsive-item" src="https://player.vimeo.com/video/' . $video_id . '?title=0&byline=0&portrait=0" frameborder="0" allow="autoplay; fullscreen; encrypted-media" allowfullscreen></iframe>';
				}
				//$sidebar      = syn_widget_sidebar( $args[ 'widget_id' ] );
				$sidebar_class = syn_get_sidebar_class( $args[ 'widget_id' ] );
				$lb            = syn_get_linebreak();
				$tab           = syn_get_tab();
				echo $args[ 'before_widget' ] . $lb;
				if ( ! empty( $title ) ) :
					echo $tab . $args[ 'before_title' ] . $title . $args[ 'after_title' ] . $lb;
				endif;
				echo $tab . '<div class="video ' . $sidebar_class . ' ' . strtolower( $host ) . ' embed-responsive embed-responsive-16by9">' . $lb;
				echo $tab . $tab . $iframe . $lb;
				echo $tab . '</div>' . $lb;
				if ( $caption && ! empty( $caption ) ) {
					echo $tab . '<div class="video-caption">' . $lb;
					echo $tab . $tab . $caption;
					echo $tab . '</div>' . $lb;
				}
				echo $args[ 'after_widget' ] . $lb;
				?>
				<script type="text/javascript">
					// todo: finish up the remaining states in toggleVideoHeader
					var tag = document.createElement('script');
					tag.id = 'youtube-jsapi-<?php echo $video_id; ?>';
					tag.src = 'https://www.youtube.com/iframe_api';
					var firstScriptTag = document.getElementsByTagName('script')[0];
					firstScriptTag.parentNode.insertBefore(tag, firstScriptTag);

					var player;

					function onYouTubeIframeAPIReady() {
						player = new YT.Player('youtube-player-<?php echo $video_id; ?>', {
							events: {
								'onReady': onPlayerReady,
								'onStateChange': onPlayerStateChange
							}
						});
					}

					function onPlayerReady(event) {
						//console.log('YTPlayer is ready');
					}

					function toggleVideoHeader(e) {
						var playerStatus = e.data;
						var widget_id = e.target.a.parentNode.parentNode.id;
						var widget = document.getElementById(widget_id);
						if (playerStatus == -1) {
							// unstarted
						} else if (playerStatus == 0) {
							// ended
							widget.children[0].style.display = 'block';
						} else if (playerStatus == 1) {
							// playing
							widget.children[0].style.display = 'none';
						} else if (playerStatus == 2) {
							// paused
							widget.children[0].style.display = 'block';
						} else if (playerStatus == 3) {
							// buffering
							widget.children[0].style.display = 'block';
						} else if (playerStatus == 5) {
							// video cued
						}
					}

					function onPlayerStateChange(event) {
						toggleVideoHeader(event);
					}
				</script>
			<?php
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