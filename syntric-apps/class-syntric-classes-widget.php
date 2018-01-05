<?php

	/**
	 * Syntric_Classes_Widget
	 */
	class Syntric_Classes_Widget extends WP_Widget {
		/**
		 * Set up a new widget instance
		 */
		public function __construct() {
			$widget_ops = [
				'classname'                   => 'syn-classes-widget',
				'description'                 => __( 'Displays classes on department and teacher pages.' ),
				'customize_selective_refresh' => true,
			];
			parent::__construct( 'syn-classes-widget', __( 'Classes' ), $widget_ops );
			$this->alt_option_name = 'syn-classes-widget';
		}

		/**
		 * Output widget content
		 *
		 * @param array $args     Display arguments including 'before_title', 'after_title', 'before_widget', and 'after_widget'.
		 * @param array $instance Settings for the current widget instance.
		 */
		public function widget( $args, $instance ) {
			global $post;
			$active = get_field( 'syn_classes_active', $post->ID );
			if( 1 != $active ) {
				return;
			}
			if( ! isset( $args[ 'widget_id' ] ) ) {
				$args[ 'widget_id' ] = $this->id;
			}
			$classes = get_field( 'syn_classes', $post->ID );
			if( $classes ) {
				// left off here
			}

			/*$page_template = get_page_template();
			$page_template_array = explode( '/', $page_template );
			$page_template_file = $page_template_array[count($page_template_array)-1];
			$page_template = str_replace( '.php', '', $page_template_file);*/

			return;
			$category  = get_field( 'syn_classes_category', $post->ID );
			$number    = get_field( 'syn_classes_posts', $post->ID );
			$posts     = new WP_Query( apply_filters( 'widget_posts_args', [
					'posts_per_page'      => $number,
					'no_found_rows'       => true,
					'post_status'         => 'publish',
					'ignore_sticky_posts' => true,
					'cat'                 => $category[ 0 ]->term_id,
				] )
			);
			$lb        = "\n";
			$tab       = "\t";
			$sidebar   = syn_widget_sidebar( $args[ 'widget_id' ] );
			$title     = get_field( 'syn_classes_title', $post->ID );
			$show_date = get_field( 'syn_classes_include_date', $post->ID );
			echo $args[ 'before_widget' ] . $lb;
			if( ! empty( $title ) ) :
				echo $args[ 'before_title' ] . $title . $args[ 'after_title' ] . $lb;
			endif;
			echo '<ul>' . $lb;
			if( $posts->have_posts() ) :
				while( $posts->have_posts() ) : $posts->the_post();
					echo $tab . '<li>' . $lb;
					echo $tab . $tab . '<a href="' . get_the_permalink() . '">' . $lb;
					echo $tab . $tab . $tab . $tab . '<span class="entry-title">' . get_the_title() . '</span>';
					if( $show_date ) :
						echo $tab . $tab . $tab . $tab . '<span class="entry-date">' . get_the_date() . '</span>';
					endif;
					echo $tab . $tab . '</a>';
					echo $tab . '</li>' . $lb;
				endwhile;
			else :
				echo $tab . '<li>' . $lb;
				echo $tab . $tab . '<span class="entry-title">No posts</span>' . $lb;
				echo $tab . '</li>' . $lb;
			endif;
			echo '</ul>' . $lb;
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
