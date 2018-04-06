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
			if ( ! isset( $args[ 'widget_id' ] ) ) {
				$args[ 'widget_id' ] = $this->id;
			}
			$dynamic = get_field( 'syn_facebook_page_widget_dynamic', 'widget_' . $args[ 'widget_id' ] );
			if ( $dynamic ) {
				$active = get_field( 'syn_facebook_page_active', $post->ID );
				if ( ! $active ) {
					return;
				}
				$title         = get_field( 'syn_facebook_page_title', $post->ID );
				$fb_page_id    = get_field( 'syn_facebook_page_page', $post->ID );
				$include_image = get_field( 'syn_facebook_page_include_image', $post->ID );
				$number        = get_field( 'syn_facebook_page_posts', $post->ID );
			} else {
				$title         = get_field( 'syn_facebook_page_widget_title', 'widget_' . $args[ 'widget_id' ] );
				$fb_page_id    = get_field( 'syn_facebook_page_widget_page', 'widget_' . $args[ 'widget_id' ] );
				$include_image = get_field( 'syn_facebook_page_widget_include_image', 'widget_' . $args[ 'widget_id' ] );
				$number        = get_field( 'syn_facebook_page_widget_posts', 'widget_' . $args[ 'widget_id' ] );
			}
			if ( isset( $fb_page_id ) && ! empty( $fb_page_id ) ) {
				$posts_to_fetch = $number; // get extra because a post won't display if it doesn't have a 'message'
				$facebook_posts = syn_get_facebook_page_posts( $fb_page_id, $posts_to_fetch );
				$facebook_page  = syn_get_facebook_page( $fb_page_id );
				//slog($facebook_page);
				//$sidebar = syn_widget_sidebar( $args[ 'widget_id' ] );
				$sidebar_class = syn_get_sidebar_class( $args[ 'widget_id' ] );
				$lb            = syn_get_linebreak();
				$tab           = syn_get_tab();
				echo $args[ 'before_widget' ] . $lb;
				if ( ! empty( $title ) ) :
					echo $args[ 'before_title' ] . $title . $args[ 'after_title' ] . $lb;
				endif;
				if ( $facebook_posts ) {
					$posts_layout = 'foo';
					if ( 'cards' == $posts_layout ) {
						if ( property_exists( $facebook_posts, 'data' ) ) {
							echo '<div class="card-deck ' . $sidebar_class . '">';
							foreach ( $facebook_posts->data as $facebook_post ) :
								if ( property_exists( $facebook_post, 'message' ) ) {
									$publish_date = date_create( $facebook_post->created_time );
									$publish_date = date_format( $publish_date, 'F j, Y' );
									echo $tab . '<div class="card">';
									echo $tab . $tab . '<img src = "' . $facebook_post->picture . '" class="card-img-top" alt = "Facebook post photo" aria-hidden = "true">' . $lb;
									echo $tab . $tab . '<div class="card-body">';
									echo $tab . $tab . $tab . '<h5 class="card-title">' . $publish_date . '</h5>' . $lb;
									echo $tab . $tab . $tab . '<p class="card-text">' . substr( $facebook_post->message, 0, 250 ) . '</p>';
									echo $tab . $tab . '</div>';
									echo $tab . '</div>';
								}
							endforeach;
							echo '</div>';
						}
					} else {
						echo '<div class="list-group ' . $sidebar_class . '">' . $lb;
						if ( property_exists( $facebook_posts, 'data' ) ) {
							//print_r( $facebook_posts );
							$post_counter = 1;
							foreach ( $facebook_posts->data as $facebook_post ) :
								if ( property_exists( $facebook_post, 'message' ) ) {
									echo $tab . $tab . '<a href = "' . $facebook_post->permalink_url . '" class="list-group-item list-group-item-action" target = "_blank">' . $lb;
									if ( $include_image && property_exists( $facebook_post, 'picture' ) ) :
										echo $tab . $tab . $tab . $tab . '<div class="list-group-item-feature">' . $lb;
										echo $tab . $tab . $tab . $tab . $tab . '<img src = "' . $facebook_post->picture . '" class="fb-image" alt="Facebook post photo" aria-hidden="true">' . $lb;
										echo $tab . $tab . $tab . $tab . '</div>' . $lb;
									endif;
									$publish_date = date_create( $facebook_post->created_time );
									$publish_date = date_format( $publish_date, 'F j, Y' );
									echo $tab . $tab . $tab . $tab . '<div class="list-group-item-content">' . $lb;
									echo $tab . $tab . $tab . $tab . $tab . '<div class="fb-date small">' . $publish_date . '</div> ' . $lb;
									//$more = ( 250 < strlen( $facebook_post->message ) ) ? '<span class="read-more-link">More</span>' . $lb : '';
									$more = ( 300 < strlen( $facebook_post->message ) ) ? '...<span class="read-more-link">More</span>' . $lb : '';
									echo $tab . $tab . $tab . $tab . $tab . '<p class="fb-message">' . substr( $facebook_post->message, 0, 300 ) . $more . '</p> ' . $lb;
									echo $tab . $tab . $tab . $tab . '</div>' . $lb;
									echo $tab . $tab . '</a>' . $lb;
								}
								if ( $post_counter == $number ) {
									break;
								}
								$post_counter ++;
							endforeach;
							echo $tab . '<a href="http://www.facebook.com/' . $fb_page_id . '" class="list-group-item list-group-item-action more-link">Go to Facebook</a>' . $lb;
						} else {
							if ( property_exists( $facebook_posts, 'error' ) ) {
								echo $tab . $tab . '<div class="list-group-item">Facebook unavailable</div>' . $lb;
							} else {
								echo $tab . $tab . '<div class="list-group-item">No Facebook posts</div>';
							}
						}
						echo '</div>' . $lb;
					}
				}
				echo $args[ 'after_widget' ] . $lb;
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
