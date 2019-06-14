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
			'classname'                   => 'syntric-facebook-page-widget',
			'description'                 => __( 'Displays posts from a Facebook page.' ),
			'customize_selective_refresh' => true,
		];
		parent ::__construct( 'syntric-facebook-page-widget', __( 'Syntric Facebook Page' ), $widget_ops );
		$this -> alt_option_name = 'syntric-facebook-page-widget';
	}

	public function widget( $args, $instance ) {
		/**
		 * Output widget content
		 *
		 * @param array $args     Display arguments including 'before_title', 'after_title', 'before_widget', and 'after_widget'.
		 * @param array $instance Settings for the current widget instance.
		 */
		$widget_id       = ( isset( $args[ 'widget_id' ] ) ) ? $args[ 'widget_id' ] : $this -> id;
		$facebook_widget = get_field( 'field_5c9d666abd2e1', 'widget_' . $widget_id );
		if( isset( $facebook_widget ) && ! empty( $facebook_widget ) ) {
			$title             = $facebook_widget[ 'title' ];
			$facebook_page     = $facebook_widget[ 'facebook_page' ];
			$posts_to_display  = $facebook_widget[ 'posts_to_display' ];
			$include_image     = $facebook_widget[ 'include_image' ];
			$settings          = get_field( 'syntric_settings', 'options' );
			$facebook_settings = $settings[ 'facebook' ];
			$app_id            = $facebook_settings[ 'app_id' ];
			$app_secret        = $facebook_settings[ 'app_secret' ];
			$facebook_posts    = [];
			if( ! empty( $facebook_page ) && isset( $app_id ) && ! empty( $app_id ) && isset( $app_secret ) && ! empty( $app_secret ) ) {
				$url      = 'https://graph.facebook.com/' . $facebook_page . '/feed?fields=name,created_time,description,message,picture,status_type,type,link,permalink_url,actions,is_published,from,full_picture,attachments{media,type,url,title,target,description,subattachments}&limit=' . $posts_to_display . '&access_token=' . $app_id . '|' . $app_secret;
				$response = wp_remote_get( $url );
				if( $response ) {
					$response_body = wp_remote_retrieve_body( $response );
					if( $response_body ) {
						$facebook_posts = json_decode( $response_body );
					}
				}
			}
			if( $facebook_posts ) {
				echo $args[ 'before_widget' ];
				if( ! empty( $title ) ) :
					echo $args[ 'before_title' ] . $title . $args[ 'after_title' ];
				endif;
				echo '<div class="list-group">';
				if( property_exists( $facebook_posts, 'data' ) ) {
					$post_counter = 1;
					foreach( $facebook_posts -> data as $facebook_post ) :
						if( property_exists( $facebook_post, 'message' ) ) {
							echo '<a href = "' . $facebook_post -> permalink_url . '" class="list-group-item list-group-item-action" target = "_blank">';
							if( $include_image && property_exists( $facebook_post, 'picture' ) ) :
								echo '<div class="list-group-item-feature">';
								echo '<img src = "' . $facebook_post -> picture . '" class="fb-image" alt="Facebook post photo" aria-hidden="true">';
								echo '</div>';
							endif;
							$publish_date = date_create( $facebook_post -> created_time );
							$publish_date = date_format( $publish_date, 'F j, Y' );
							echo '<div class="list-group-item-content">';
							echo '<div class="fb-date small">' . $publish_date . '</div> ';
							$more = ( 300 < strlen( $facebook_post -> message ) ) ? '... <span class="read-more-link">More</span>' : '';
							echo '<p class="fb-message">' . substr( $facebook_post -> message, 0, 300 ) . $more . '</p> ';
							echo '</div>';
							echo '</a>';
						}
						/**
						 * todo: revisit this
						 * Abandoned - download the full picture from Facebook and store in the media library
						 * if( property_exists( $facebook_post, 'full_picture' ) ) {
						 * $upload_dir = wp_get_upload_dir();
						 * if( ! file_exists( $upload_dir[ 'path' ] . basename( $facebook_post -> full_picture ) ) ) {
						 * require_once( ABSPATH . 'wp-admin/includes/file.php' );
						 * $temp_file = download_url( $facebook_post -> full_picture, 5 );
						 * if( ! is_wp_error( $temp_file ) ) {
						 * $file = [
						 * 'name' => basename( $facebook_post -> full_picture ),
						 * 'type' => wp_check_filetype_and_ext()
						 * ];
						 * }
						 * }
						 * }*/
						if( $post_counter == $posts_to_display ) {
							break;
						}
						$post_counter ++;
					endforeach;
					echo '<a href="http://www.facebook.com/' . $facebook_page . '" class="list-group-item list-group-item-action more-link">Go to Facebook</a>';
				} else {
					if( property_exists( $facebook_posts, 'error' ) ) {
						echo '<div class="list-group-item">Facebook unavailable</div>';
					} else {
						echo '<div class="list-group-item">No Facebook posts</div>';
					}
				}
				echo '</div>';
				echo $args[ 'after_widget' ];
			}
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
	public
	function update( $new_instance, $old_instance ) {
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
	public
	function form( $instance ) {
	}
}
