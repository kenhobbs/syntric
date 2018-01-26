<?php

	/**
	 * Syntric_Twitter_Feed_Widget
	 */
	class Syntric_Twitter_Feed_Widget extends WP_Widget {
		/**
		 * Set up a new widget instance
		 */
		public function __construct() {
			$widget_ops = [
				'classname'                   => 'syn-twitter-feed-widget',
				'description'                 => __( 'Displays posts from a Twitter feed.' ),
				'customize_selective_refresh' => true,
			];
			parent::__construct( 'syn-twitter-feed-widget', __( 'Twitter Feed' ), $widget_ops );
			$this->alt_option_name = 'syn-twitter-feed-widget';
		}

		/**
		 * Output widget content
		 *
		 * @param array $args     Display arguments including 'before_title', 'after_title', 'before_widget', and 'after_widget'.
		 * @param array $instance Settings for the current widget instance.
		 */
		public function widget( $args, $instance ) {
			if( ! isset( $args[ 'widget_id' ] ) ) {
				$args[ 'widget_id' ] = $this->id;
			}
			$sidebar = syn_widget_sidebar( $args[ 'widget_id' ] );
			/*$facebook_posts = syn_facebook_page_widget( $args[ 'widget_id' ] );
			if ( $facebook_posts && ! isset( $facebook_posts->error ) ) :
				if ( syn_is_dev() ) {
				$lb  = '';
				$tab = '';
			} else {
				$lb  = "\n";
				$tab = "\t";
			}
				$title          = get_field( 'syn_facebook_page_widget_title', 'widget_' . $args[ 'widget_id' ] );
				$list_classes   = 'widget-body';
				$item_classes   = 'widget-item';
				$link_classes   = 'widget-entry';
				$img_width = 100;
				$img_height = 100;
				$include_img = true;
				$include_date = true;
				$include_content = true;
				$apply_classes  = get_field( 'syn_facebook_page_widget_apply_classes', 'option' );
				if ( $apply_classes ) {
					$custom_list_classes   = get_field( 'syn_facebook_page_widget_menu_classes', 'option' );
					$custom_item_classes   = get_field( 'syn_facebook_page_widget_item_classes', 'option' );
					$custom_link_classes   = get_field( 'syn_facebook_page_widget_link_classes', 'option' );
					$img_width               = get_field( 'syn_facebook_page_widget_img_width', 'option' );
					$img_height              = get_field( 'syn_facebook_page_widget_img_height', 'option' );
					$include_date            = get_field( 'syn_facebook_page_widget_include_date', 'widget_' . $args[ 'widget_id' ] );
					$include_content         = get_field( 'syn_facebook_page_widget_include_content', 'widget_' . $args[ 'widget_id' ] );
					$include_img             = get_field( 'syn_facebook_page_widget_include_image', 'widget_' . $args[ 'widget_id' ] );
					$list_classes          .= ( $custom_list_classes ) ? ' ' . $custom_list_classes : '';
					$item_classes          .= ( $custom_item_classes ) ? ' ' . $custom_item_classes : '';
					$link_classes          .= ( $custom_link_classes ) ? ' ' . $custom_link_classes : '';
				}
				echo $args[ 'before_widget' ] . $lb;
				if ( ! empty( $title ) ) :
					echo $args[ 'before_title' ] . $title . $args[ 'after_title' ] . $lb;
				endif;
				echo '<ul class="' . $list_classes . '">' . $lb;
				foreach ( $facebook_posts->data as $facebook_post ) :
					echo $tab . '<li class="' . $item_classes . '">' . $lb;
					echo $tab . $tab . '<a href="' . $facebook_post->permalink_url . '" class="' . $link_classes . '" target="_blank">' . $lb;
					echo $tab . $tab . $tab . '<div class="entry-col">' . $lb;
					if ( $include_img && isset( $facebook_post->picture ) ) :
						echo $tab . $tab . $tab . $tab . $tab .  '<img src="' . $facebook_post->picture . '" class="entry-image" alt="' . $facebook_post->name . '">' . $lb;
					endif;
					echo $tab . $tab . $tab . '</div>' . $lb;
					echo $tab . $tab . $tab . '<div class="entry-col">' . $lb;
					//echo $tab . $tab . $tab . $tab . '<div class="entry-header">' . $lb;
					//echo $tab . $tab . $tab . $tab . $tab . '<div class="entry-title">' . $facebook_post->name . '</div>' . $lb;
					if ( $include_date ) :
						$publish_date = date_create( $facebook_post->created_time );
						$publish_date = date_format( $publish_date, 'F j, Y' );
						echo $tab . $tab . $tab . $tab . $tab . '<div class="entry-date">' . $publish_date . '</div>' . $lb;
					endif;
					echo $tab . $tab . $tab . $tab . '</div>' . $lb;
					if ( $include_content ) :
						$more = ( 250 < strlen( $facebook_post->message ) ) ? '...read more <i class="fa fa-angle-right" aria-hidden="true"></i>' : '';
						echo $tab . $tab . $tab . $tab . '<div class="entry-excerpt">' . substr( $facebook_post->message, 0, 250 ) . $more . '</div>' . $lb;
					endif;
					//echo $tab . $tab . $tab . '</div>' . $lb; // entry-col
					if ( $include_img && isset( $facebook_post->picture ) ) :

					endif;
					echo $tab . $tab . '</a>' . $lb;
					echo $tab . '</li>' . $lb;
				endforeach;
				echo '</ul>' . $lb;
				echo $args[ 'after_widget' ] . $lb;
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
		public function form( $instance ) { }
	}
