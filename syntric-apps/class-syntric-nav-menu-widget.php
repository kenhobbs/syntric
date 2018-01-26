<?php

	/**
	 * Syntric_Nav_Menu_Widget
	 */
	class Syntric_Nav_Menu_Widget extends WP_Widget {
		/**
		 * Set up a new widget instance
		 */
		public function __construct() {
			$widget_ops = [ 'classname' => 'syn-nav-menu-widget', 'description' => __( 'Displays menu of child navs under a top-level nav.' ), 'customize_selective_refresh' => true, ];
			parent::__construct( 'syn-nav-menu-widget', __( 'Nav Menu' ), $widget_ops );
		}

		/**
		 * Output widget content
		 *
		 * @param array $args     Display arguments including 'before_title', 'after_title', 'before_widget', and 'after_widget'.
		 * @param array $instance Settings for the current widget instance.
		 */
		public function widget( $args, $instance ) {
			global $post;
			if ( 'page' != $post->post_type ) {
				return;
			}
			if ( ! isset( $args[ 'widget_id' ] ) ) {
				$args[ 'widget_id' ] = $this->id;
			}
			$nav_menu = get_field( 'syn_nav_menu_widget_menu', 'widget_' . $args[ 'widget_id' ] );
			if ( $nav_menu ) :
				if ( syn_remove_whitespace() ) {
					$lb  = '';
					$tab = '';
				} else {
					$lb  = "\n";
					$tab = "\t";
				}
				$sidebar     = syn_widget_sidebar( $args[ 'widget_id' ] );
				$depth       = get_field( 'syn_nav_menu_widget_depth', 'widget_' . $args[ 'widget_id' ] );
				$ancestor_id = syn_get_top_ancestor_id( $post->ID );
				$ancestor    = get_post( $ancestor_id );
				echo $lb;
				echo $args[ 'before_widget' ] . $lb;
				echo $args[ 'before_title' ] . $ancestor->post_title . '<span class="sr-only"> section navigiation</span>' . $args[ 'after_title' ] . $lb;
				$nav_menu_args = [
					'container' => '',
					'menu'       => $nav_menu,
					'menu_id' => syn_generate_permanent_id(), //'menu_class' => 'nav',
					'menu_class' => 'list-group',
					'items_wrap' => '<div id="%1$s" class="%2$s">%3$s</div>',
					'before_link' => '',
					'after_link' => '',
					'depth'      => $depth,
					'walker' => new Syntric_Nav_Menu_Walker()
				];
				//$nav_menu_filtered_args = apply_filters( 'widget_nav_menu_args', $nav_menu_args, $nav_menu, $args, $instance );
				wp_nav_menu( $nav_menu_args );
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
			$instance = [];
			if ( ! empty( $new_instance[ 'syn-nav-menu-widget' ] ) ) {
				$instance[ 'syn-nav-menu-widget' ] = (int) $new_instance[ 'syn-nav-menu-widget' ];
			}

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
