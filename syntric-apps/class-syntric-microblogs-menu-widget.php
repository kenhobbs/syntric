<?php

	/**
	 * Syntric_Microblogs_Menu_Widget
	 */
	class Syntric_Microblogs_Menu_Widget extends WP_Widget {
		/**
		 * Set up a new widget instance
		 */
		public function __construct() {
			$widget_ops = [
				'classname'                   => 'syn-microblogs-menu-widget',
				'description'                 => __( 'Displays a list of microblogs in a menu.' ),
				'customize_selective_refresh' => true,
			];
			parent::__construct( 'syn-microblogs-menu-widget', __( 'Microblogs Menu' ), $widget_ops );
			$this->alt_option_name = 'syn-microblogs-menu-widget';
		}

		/**
		 * Output widget content
		 *
		 * @param array $args     Display arguments including 'before_title', 'after_title', 'before_widget', and 'after_widget'.
		 * @param array $instance Settings for the current widget instance.
		 */
		public function widget( $args, $instance ) {
			//global $post;
			if( ! isset( $args[ 'widget_id' ] ) ) {
				$args[ 'widget_id' ] = $this->id;
			}
			$all_microblogs = get_field( 'syn_microblogs_menu_widget_all_microblogs', 'widget_' . $args[ 'widget_id' ] );
			if( $all_microblogs ) {
				$microblogs = get_terms( 'microblog', [ 'parent' => 0 ] );
			} else {
				$microblogs = get_field( 'syn_microblogs_menu_widget_microblogs', 'widget_' . $args[ 'widget_id' ] );
			}
			//$microblogs = get_field( 'syn_microblogs_menu_widget_microblogs', 'widget_' . $args[ 'widget_id' ] );
			if( $microblogs && ! is_wp_error( $microblogs ) ) :
				$lb      = "\n";
				$tab     = "\t";
				$sidebar = syn_widget_sidebar( $args[ 'widget_id' ] );
				$title   = get_field( 'syn_microblogs_menu_widget_title', 'widget_' . $args[ 'widget_id' ] );
				echo $args[ 'before_widget' ] . $lb;
				if( ! empty( $title ) ) :
					echo $args[ 'before_title' ] . $title . $args[ 'after_title' ] . $lb;
				endif;
				echo '<ul>' . $lb;
				$has_one = false;
				foreach( $microblogs as $microblog ) {
					//slog($microblog);
					//if ( 0 < $microblog->parent ) {
					echo $tab . '<li>' . $lb;
					echo $tab . $tab . '<a href="' . get_term_link( (int) $microblog->term_id ) . '">' . $lb;
					echo $tab . $tab . $tab . $microblog->name . $lb;
					echo $tab . $tab . '</a>' . $lb;
					echo $tab . '</li>' . $lb;
					$has_one = true;
					//}
				};
				if( ! $has_one ) {
					echo $tab . '<li>' . $lb;
					echo $tab . $tab . $tab . $tab . '<span class="entry-title">' . 'No microblogs' . '</span>' . $lb;
					echo $tab . '</li>' . $lb;
				}
				echo '</ul>' . $lb;
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
