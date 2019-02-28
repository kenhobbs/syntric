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
			parent ::__construct( 'syn-embed-widget', __( 'Embed' ), $widget_ops );
			$this -> alt_option_name = 'syn-embed-widget';
		}
		
		/**
		 * Output widget content
		 *
		 * @param array $args     Display arguments including 'before_title', 'after_title', 'before_widget', and 'after_widget'.
		 * @param array $instance Settings for the current widget instance.
		 */
		/*
		 <iframe src="https://docs.google.com/forms/d/e/1FAIpQLSfsvRFIdjyBAPzAETPiNBE2dpnnWstcwcYP-tSae8GM_W-dwA/viewform?embedded=true" width="640" height="1143" frameborder="0" marginheight="0" marginwidth="0">Loading...</iframe>
		https://drive.google.com/drive/folders/1KgSJydyhTwJckUkzRHZHELacx05mqBJw?usp=sharing
		*/
		public function widget( $args, $instance ) {
			global $post;
			if( ! isset( $args[ 'widget_id' ] ) ) {
				$args[ 'widget_id' ] = $this -> id;
			}
			$dynamic   = get_field( 'syn_embed_widget_dynamic', 'widget_' . $args[ 'widget_id' ] );
			$widget_id = ( $dynamic ) ? $post -> ID : 'widget_' . $args[ 'widget_id' ];
			$name_base = ( $dynamic ) ? 'syntric_embed_' : 'syntric_embed_widget_';
			$active    = get_field( $name_base . 'active', $widget_id );
			if( ! $active ) {
				return;
			}
			$title       = get_field( $name_base . 'title', $widget_id );
			$type        = get_field( $name_base . 'type', $widget_id );
			$folder_view = get_field( $name_base . 'folder_view', $widget_id );
			$id          = get_field( $name_base . 'id', $widget_id );
			$src         = get_field( $name_base . 'src', $widget_id );
			switch( $type ) {
				case 'doc':
					break;
				case 'form':
					break;
				case 'map':
					break;
				case 'sheet':
					break;
				case 'youtube':
					break;
				case 'playlist':
					break;
				case 'folder':
					$iframe = '<iframe id="google-drive-folder-' . $id . '" src="https://drive.google.com/embeddedfolderview?id=' . $id . '#' . $folder_view . '" class="embed-responsive-item" frameborder="0"></iframe>';
					break;
				case 'pdf':
					break;
				case 'other':
					break;
				case 'xxxxx':
					break;
			}
			/*if ( $dynamic ) {
				$active = get_field( 'syn_embed_active', $widget_id );
				if ( ! $active ) {
					return;
				}
				$title      = get_field( 'syn_embed_title', $widget_id );
				$videos     = get_field( 'syn_embed_videos', $widget_id );
				$media_type = get_field( 'syn_embed_type', $widget_id );
				$orientation = get_field( 'syn_embed_orientation', $widget_id );
				$caption = get_field( 'syn_embed_caption', $widget_id );
				$type    = strtolower( get_field( 'syn_embed_type', $widget_id ) );
				$host    = strtolower( get_field( 'syn_embed_host', $widget_id ) );
				if ( 'single' == $type ) {
					$video_id = ( 'youtube' == $host ) ? get_field( 'syn_embed_youtube_id', $post->ID ) : get_field( 'syn_embed_vimeo_id', $widget_id );
				} elseif ( 'youtube' == $host && 'playlist' == $type ) {
					$video_id = ( 'youtube' == $host ) ? get_field( 'syn_embed_youtube_playlist_id', $widget_id ) : 0;
				}
			} else {
				$active = get_field( 'syn_embed_widget_active', $widget_id );
				$title      = get_field( 'syn_embed_widget_title', $widget_id );
				$videos     = get_field( 'syn_embed_widget_videos', $widget_id );
				$media_type = get_field( 'syn_embed_widget_type', $widget_id );
				$orientation = get_field( 'syn_embed_widget_orientation', $widget_id );

				//* Left off with adding $media_type and $orientation, need to add to the ACF field group
				//*
				//* $media_type might be PDF, YouTube video, Google form, Google drive, other
				//* $orientation might be landscape, portrait
				//*
				//* consider height and width if they become necessary

				$caption = get_field( 'syn_embed_widget_caption', $widget_id );
				$type    = strtolower( get_field( 'syn_embed_widget_type', $widget_id) );
				$host    = strtolower( get_field( 'syn_embed_widget_host', $widget_id ) );
				if ( 'single' == $type ) {
					$video_id = ( 'youtube' == $host ) ? get_field( 'syn_embed_widget_youtube_id', $post->ID ) : get_field( 'syn_embed_widget_vimeo_id', $widget_id );
				} elseif ( 'youtube' == $host && 'playlist' == $type ) {
					$video_id = ( 'youtube' == $host ) ? get_field( 'syn_embed_widget_youtube_playlist_id', $widget_id ) : 0;
				}
			}*/
			/*if ( $videos ) :
				if ( 'youtube' == $host ) {
					if ( 'single' == $type ) {
						$iframe = '<iframe id="youtube-player-' . $video_id . '" class="embed-responsive-item" src="https://www.youtube.com/embed/' . $video_id . '?rel=0&controls=1&showinfo=0&enablejsapi=1" frameborder="0" gesture="media" allow="encrypted-media" allowfullscreen=""></iframe>';
					} elseif ( 'playlist' == $type ) {
						$iframe = '<iframe id="youtube-player-' . $video_id . '" class="embed-responsive-item" src="https://www.youtube.com/embed/videoseries?list=' . $video_id . '&enablejsapi=1" frameborder="0" allow="autoplay; encrypted-media" allowfullscreen></iframe>';
					}
				} elseif ( 'vimeo' == $host ) {
					$iframe = '<iframe id="vimeo-player-' . $video_id . '" class="embed-responsive-item" src="https://player.vimeo.com/video/' . $video_id . '?title=0&byline=0&portrait=0" frameborder="0" allowfullscreen></iframe>';
				}*/
			//$sidebar      = syntric_widget_sidebar( $args[ 'widget_id' ] );
			$sidebar_class = syntric_get_sidebar_class( $args[ 'widget_id' ] );
			$lb            = syntric_linebreak();
			$tab           = syntric_tab();
			echo $args[ 'before_widget' ] . $lb;
			if( ! empty( $title ) ) :
				echo $args[ 'before_title' ] . $title . $args[ 'after_title' ] . $lb;
			endif;
			echo '<div class="' . $type . ' ' . $sidebar_class . ' embed-responsive embed-responsive-16by9">' . $lb;
			echo $tab . $iframe . $lb;
			//echo '<div class="d-flex">';
			/*foreach ( $videos as $video ) :
				$video_title = $video[ 'title' ];
				$description = $video[ 'description' ];
				echo '<div class="video embed-responsive embed-responsive-16by9">';
				echo '<h3 class="video-title">' . $video_title . '</h3>';
				echo $video[ 'video' ];
				echo '</div>';
				if ( $description ) {
					echo '<div class="video-description px-3">' . $description . '</div>';
				}
			endforeach;*/
			echo '</div>' . $lb;
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
			//endif;
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