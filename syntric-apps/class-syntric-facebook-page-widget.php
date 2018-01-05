<?php

	/**
	 * Syntric_Facebook_Page_Widget
	 */
	class Syntric_Facebook_Page_Widget extends WP_Widget {
		/**
		 * Set up a new widget instance
		 */
		public function __construct() {
			$widget_ops = [
				'classname'                   => 'syn-facebook-page-widget',
				'description'                 => __( 'Displays posts from a Facebook page.' ),
				'customize_selective_refresh' => true,
			];
			parent::__construct( 'syn-facebook-page-widget', __( 'Facebook Page' ), $widget_ops );
			$this->alt_option_name = 'syn-facebook-page-widget';
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
			$dynamic = get_field( 'syn_facebook_page_widget_dynamic', 'widget_' . $args[ 'widget_id' ] );
			if( $dynamic ) {
				$active = get_field( 'syn_facebook_page_active', $post->ID );
				if( ! $active ) {
					return;
				}
				$title         = get_field( 'syn_facebook_page_title', $post->ID );
				$facebook_page = get_field( 'syn_facebook_page_page', $post->ID );
				$include_image = get_field( 'syn_facebook_page_include_image', $post->ID );
				$number        = get_field( 'syn_facebook_page_posts', $post->ID );
			} else {
				$title         = get_field( 'syn_facebook_page_widget_title', 'widget_' . $args[ 'widget_id' ] );
				$facebook_page = get_field( 'syn_facebook_page_widget_page', 'widget_' . $args[ 'widget_id' ] );
				$include_image = get_field( 'syn_facebook_page_widget_include_image', 'widget_' . $args[ 'widget_id' ] );
				$number        = get_field( 'syn_facebook_page_widget_posts', 'widget_' . $args[ 'widget_id' ] );
			}
			if( isset( $facebook_page ) && ! empty( $facebook_page ) ) {
				$posts_to_fetch = $number + 5; // get extra because a post won't display if it doesn't have a 'message'
				$facebook_posts = syn_get_facebook_page_posts( $facebook_page, $posts_to_fetch );
				$sidebar        = syn_widget_sidebar( $args[ 'widget_id' ] );
				$lb             = "\n";
				$tab            = "\t";
				echo $args[ 'before_widget' ] . $lb;
				if( ! empty( $title ) ) :
					echo $args[ 'before_title' ] . $title . $args[ 'after_title' ] . $lb;
				endif;
				if( $facebook_posts ) {
					echo '<ul>' . $lb;
					if( property_exists( $facebook_posts, 'data' ) ) {
						$post_counter = 1;
						foreach( $facebook_posts->data as $facebook_post ) :
							if( property_exists( $facebook_post, 'message' ) ) {
								echo $tab . '<li>' . $lb;
								echo $tab . $tab . '<a href="' . $facebook_post->permalink_url . '" target="_blank">' . $lb;
								if( $include_image ) :
									echo $tab . $tab . $tab . '<div class="d-flex flex-row">' . $lb;
									echo $tab . $tab . $tab . $tab . '<span class="entry-image">' . $lb;
									echo $tab . $tab . $tab . $tab . $tab . '<img src="' . $facebook_post->picture . '" class="entry-picture" alt="Related photo" aria-hidden="true">' . $lb;
									echo $tab . $tab . $tab . $tab . '</span>' . $lb;
									echo $tab . $tab . $tab . $tab . '<div class="d-flex flex-column">' . $lb;
								endif;
								$publish_date = date_create( $facebook_post->created_time );
								$publish_date = date_format( $publish_date, 'F j, Y' );
								echo $tab . $tab . $tab . $tab . $tab . '<span class="entry-date">' . $publish_date . '</span>' . $lb;
								$more = ( 250 < strlen( $facebook_post->message ) ) ? '<span class="widget-more-link">Read More</span>' . $lb : '';
								echo $tab . $tab . $tab . $tab . $tab . '<span class="entry-excerpt">' . substr( $facebook_post->message, 0, 250 ) . $more . '</span>' . $lb;
								if( $include_image ) {
									echo $tab . $tab . $tab . $tab . '</div>' . $lb;
									echo $tab . $tab . $tab . '</div>' . $lb;
								}
								echo $tab . $tab . '</a>' . $lb;
								echo $tab . '</li>' . $lb;
							}
							if( $post_counter == $number ) {
								break;
							}
							$post_counter ++;
						endforeach;
					} else {
						echo $tab . '<li>' . $lb;
						if( property_exists( $facebook_posts, 'error' ) ) {
							echo $tab . $tab . '<span class="entry-title">Posts unavailable</span>' . $lb;
						} else {
							echo $tab . $tab . '<span class="entry-title">No posts</span>';
						}
						echo $tab . '</li>' . $lb;
					}
					echo '</ul>' . $lb;
				}
				echo $args[ 'after_widget' ] . $lb;
			}
			/*$dynamic        = get_field( 'syn_facebook_page_widget_dynamic', 'widget_' . $args[ 'widget_id' ] );
			$facebook_page  = get_field( 'syn_facebook_page_widget_page', 'widget_' . $args[ 'widget_id' ] );
			$number         = get_field( 'syn_facebook_page_widget_posts', 'widget_' . $args[ 'widget_id' ] );
			if( $facebook_posts ) {
				$title         = get_field( 'syn_facebook_page_widget_title', 'widget_' . $args[ 'widget_id' ] );
				$include_image = get_field( 'syn_facebook_page_widget_include_image', 'widget_' . $args[ 'widget_id' ] );
				$lb            = "\n";
				$tab           = "\t";
				$img_width       = 100;
				$img_height      = 100;
				echo $args[ 'before_widget' ] . $lb;
				if( ! empty( $title ) ) :
					echo $args[ 'before_title' ] . $title . $args[ 'after_title' ] . $lb;
				endif;
				echo '<ul>' . $lb;
				$post_counter = 1;
				if( property_exists( $facebook_posts, 'data') ) {
					foreach( $facebook_posts->data as $facebook_post ) :
						if( property_exists( $facebook_post, 'message' ) ) {
							echo $tab . '<li>' . $lb;
							echo $tab . $tab . '<a href="' . $facebook_post->permalink_url . '" target="_blank">' . $lb;
							if( $include_image && isset( $facebook_post->picture ) ) :
								echo $tab . $tab . $tab . '<span class="entry-image">' . $lb;
								echo $tab . $tab . $tab . $tab . '<img src="' . $facebook_post->picture . '" class="entry-image" alt="Related photo" aria-hidden="true">' . $lb;
								echo $tab . $tab . $tab . '</span>' . $lb;
							endif;
							$publish_date = date_create( $facebook_post->created_time );
							$publish_date = date_format( $publish_date, 'F j, Y' );
							echo $tab . $tab . $tab . $tab . '<span class="entry-date">' . $publish_date . '</span>' . $lb;
							$more = ( 250 < strlen( $facebook_post->message ) ) ? '<span class="widget-more-link">Read More</span>' . $lb : '';
							echo $tab . $tab . $tab . $tab . '<span class="entry-excerpt">' . substr( $facebook_post->message, 0, 250 ) . $more . '</span>' . $lb;
							echo $tab . $tab . '</a>' . $lb;
							echo $tab . '</li>' . $lb;
							if( $post_counter == $number ) {
								break;
							}
							$post_counter ++;
						}
					endforeach;
				} else {
					echo $tab . '<li>' . $lb;
					if ( property_exists( $facebook_posts, 'error' ) ) {
						echo $tab . $tab . '<span class="entry-title">Posts unavailable</span>' . $lb;
					} else {
						echo $tab . $tab . '<span class="entry-title">No posts</span>';
					}
					echo $tab . '</li>' . $lb;
				}
				echo '</ul>' . $lb;
				echo $args[ 'after_widget' ] . $lb;
			}*/
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
