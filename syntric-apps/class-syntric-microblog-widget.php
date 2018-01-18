<?php

	/**
	 * Syntric_Microblog_Widget
	 */
	class Syntric_Microblog_Widget extends WP_Widget {
		/**
		 * Set up a new widget instance
		 */
		public function __construct() {
			$widget_ops = [
				'classname'                   => 'syn-microblog-widget',
				'description'                 => __( 'Displays microblog posts on pages where "Microblog" is enabled.' ),
				'customize_selective_refresh' => true,
			];
			parent::__construct( 'syn-microblog-widget', __( 'Microblog' ), $widget_ops );
			$this->alt_option_name = 'syn-microblog-widget';
		}

		/**
		 * Output widget content
		 *
		 * @param array $args     Display arguments including 'before_title', 'after_title', 'before_widget', and 'after_widget'.
		 * @param array $instance Settings for the current widget instance.
		 */
		public function widget( $args, $instance ) {
			global $post;
			$active = get_field( 'syn_microblog_active', $post->ID );
			if( 1 != $active ) {
				return;
			}
			if( ! isset( $args[ 'widget_id' ] ) ) {
				$args[ 'widget_id' ] = $this->id;
			}
			$category  = get_field( 'syn_microblog_category', $post->ID );
			$term      = get_field( 'syn_microblog_term', $post->ID );
			$number    = get_field( 'syn_microblog_posts', $post->ID );
			$posts     = new WP_Query( apply_filters( 'widget_posts_args', [
					'posts_per_page'      => $number,
					'no_found_rows'       => true,
					'post_status'         => 'publish',
					'ignore_sticky_posts' => true,
					'tax_query'           => [
						'relation' => 'AND',
						[
							'taxonomy' => 'category',
							'field'    => 'ID',
							'terms'    => [ $category->term_id ],
						],
						[
							'taxonomy' => 'microblog',
							'field'    => 'ID',
							'terms'    => [ $term->term_id ],
						],
					],
				] )
			);
			$lb        = "\n";
			$tab       = "\t";
			$sidebar   = syn_widget_sidebar( $args[ 'widget_id' ] );
			$title     = get_field( 'syn_microblog_title', $post->ID );
			$show_date = get_field( 'syn_microblog_include_date', $post->ID );
			echo $args[ 'before_widget' ] . $lb;
			if( ! empty( $title ) ) :
				echo $args[ 'before_title' ] . $title . $args[ 'after_title' ] . $lb;
			endif;
			echo '<ul class="nav">' . $lb;
			if( $posts->have_posts() ) :
				while( $posts->have_posts() ) : $posts->the_post();
					echo $tab . '<li class="nav-item">' . $lb;
					echo $tab . $tab . '<a href="' . get_the_permalink() . '" class="nav-link">' . $lb;
					echo $tab . $tab . $tab . $tab . '<div class="entry-title">' . get_the_title() . '</div>';
					if( $show_date ) :
						echo $tab . $tab . $tab . $tab . '<div class="entry-date">' . get_the_date() . '</div>';
					endif;
					echo $tab . $tab . '</a>';
					echo $tab . '</li>' . $lb;
				endwhile;
				echo $tab . '<li class="nav-item">' . $lb;
				echo $tab . $tab . '<a href="' . get_term_link( $term->term_id ) . '" class="nav-link entry-more">more posts</a>' . $lb;
				echo $tab . '</li>' . $lb;
			else :
				echo $tab . '<li class="nav-item">' . $lb;
				echo $tab . $tab . '<div class="nav-link entry-title">No posts</div>' . $lb;
				echo $tab . '</li>' . $lb;
			endif;
			echo '</ul>' . $lb;
			echo $args[ 'after_widget' ] . $lb;
			wp_reset_postdata();
			wp_reset_query();
			if( is_user_logged_in() && ( current_user_can( 'administrator' ) || current_user_can( 'editor' ) || $post->post_author == get_current_user_id() ) ) {
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
