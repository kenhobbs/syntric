<?php

	/**
	 * Syntric_Recent_Posts_Widget
	 */
	class Syntric_Recent_Posts_Widget extends WP_Widget {
		/**
		 * Set up a new widget instance
		 */
		public function __construct() {
			$widget_ops = [
				'classname'                   => 'syn-recent-posts-widget',
				'description'                 => __( 'Displays posts from one or more categories.' ),
				'customize_selective_refresh' => true,
			];
			parent::__construct( 'syn-recent-posts-widget', __( 'Recent Posts' ), $widget_ops );
			$this->alt_option_name = 'syn-recent-posts-widget';
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
			$category_id = get_field( 'syn_recent_posts_widget_categories', 'widget_' . $args[ 'widget_id' ] );
			$category    = get_category( $category_id );
			$number      = get_field( 'syn_recent_posts_widget_posts', 'widget_' . $args[ 'widget_id' ] );
			$posts       = new WP_Query( apply_filters( 'widget_posts_args', [
				'posts_per_page'      => $number,
				'no_found_rows'       => true,
				'post_status'         => 'publish',
				'ignore_sticky_posts' => true,
				'category__in'        => $category_id,
			] ) );
			if ( syn_remove_whitespace() ) {
				$lb  = '';
				$tab = '';
			} else {
				$lb  = "\n";
				$tab = "\t";
			}
			//$sidebar     = syn_widget_sidebar( $args[ 'widget_id' ] );
			$sidebar_class = syn_get_sidebar_class( $args[ 'widget_id' ] );
			$title       = get_field( 'syn_recent_posts_widget_title', 'widget_' . $args[ 'widget_id' ] );
			echo $args[ 'before_widget' ] . $lb;
			if( ! empty( $title ) ) :
				echo $args[ 'before_title' ] . $title . $args[ 'after_title' ] . $lb;
			endif;
			if( $posts->have_posts() ) :
				echo '<div class="list-group ' . $sidebar_class . '">' . $lb;
				// todo: get ride of include date checkbox...date should always be shown
				$show_date = get_field( 'syn_recent_posts_widget_include_date', 'widget_' . $args[ 'widget_id' ] );
				while( $posts->have_posts() ) : $posts->the_post();
					echo $tab . '<a href="' . get_the_permalink() . '" class="list-group-item">' . $lb;
					if( has_post_thumbnail() ) :
						echo $tab . $tab . '<div class="list-group-item-feature">' . $lb;
						echo $tab . $tab . $tab . the_post_thumbnail( 'thumbnail', array( 'class' => 'post-thumbnail') ) . $lb;
						echo $tab . $tab . '</div>' . $lb;
					endif;
					echo $tab . $tab . '<div class="list-group-item-content">' . $lb;
					echo $tab . $tab . $tab . '<div class="post-title">' . get_the_title() . '</div>' . $lb;
					echo $tab . $tab . $tab . '<div class="post-date small">' . get_the_date() . '</div>' . $lb;
					echo $tab . $tab . $tab . '<div class="post-excerpt small">' . get_the_excerpt() . '</div>' . $lb;
					echo $tab . $tab . '</div>' . $lb;
					echo $tab . '</a>' . $lb;
				endwhile;
				echo $tab . '<a href="' . get_category_link( $category->term_id ) . '" class="list-group-item more-link">Go to ' . strtolower( $category->name ) . '</a>' . $lb;
				echo '</div>' . $lb;
			else :
				echo '<p>No posts</p>' . $lb;
			endif;
			echo $args[ 'after_widget' ] . $lb;
			wp_reset_postdata();
			wp_reset_query();
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
