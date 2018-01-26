<?php

	/**
	 * Syntric_Attachments_Widget
	 */
	class Syntric_Attachments_Widget extends WP_Widget {
		/**
		 * Set up a new widget instance
		 */
		public function __construct() {
			$widget_ops = [ 'classname'                   => 'syn-attachments-widget', 'description' => __( 'Displays grouped documents and links to pages or other websites (dynamic)' ),
			                'customize_selective_refresh' => true, ];
			parent::__construct( 'syn-attachments-widget', __( 'Attachments' ), $widget_ops );
			$this->alt_option_name = 'syn-attachments-widget';
		}

		/**
		 * Output widget content
		 *
		 * @param array $args     Display arguments including 'before_title', 'after_title', 'before_widget', and 'after_widget'.
		 * @param array $instance Settings for the current widget instance.
		 */
		public function widget( $args, $instance ) {
			global $post;
			$active = get_field( 'syn_attachments_active', $post->ID );
			if ( 1 != $active ) {
				return;
			}
			if ( ! isset( $args[ 'widget_id' ] ) ) {
				$args[ 'widget_id' ] = $this->id;
			}
			if ( syn_remove_whitespace() ) {
				$lb  = '';
				$tab = '';
			} else {
				$lb  = "\n";
				$tab = "\t";
			}
			$sidebar = syn_widget_sidebar( $args[ 'widget_id' ] );
			$title   = get_field( 'syn_attachments_title', $post->ID );
			echo $args[ 'before_widget' ] . $lb;
			if ( ! empty( $title ) ) :
				echo $args[ 'before_title' ] . $title . $args[ 'after_title' ] . $lb;
			endif;
			if ( have_rows( 'syn_attachments', $post->ID ) ) :
				echo '<div class="list-group">' . $lb;
				while( have_rows( 'syn_attachments', $post->ID ) ) : the_row();
					$header      = get_sub_field( 'header' );
					$description = get_sub_field( 'description' );
					if ( $header || $description ) {
						echo $tab . $tab . '<div class="list-group-item">' . $lb;
						if ( $header ) {
							echo $tab . $tab . $tab . '<h3>' . $header . '</h3>' . $lb;
						}
						if ( $description ) {
							echo $tab . $tab . $tab . '<div class="small">' . $description . '</div>' . $lb;
						}
						echo $tab . $tab . '</div>' . $lb;
					}
					if ( have_rows( 'attachments' ) ) {
						while( have_rows( 'attachments' ) ) : the_row();
							$attachment_type = get_sub_field( 'attachment_type' );
							switch ( $attachment_type[ 'value' ] ) :
								case 'file' :
									$file = get_sub_field( 'file' );
									echo $tab . $tab . '<a href="' . $file[ 'url' ] . '" class="list-group-item" target="_blank">' . $file[ 'title' ] . '</a>' . $lb;
									break;
								case 'internal_link' :
									$internal_link = get_sub_field( 'internal_link' );
									echo $tab . $tab . '<a href="' . get_the_permalink( $internal_link->ID ) . '" class="list-group-item">' . $internal_link->post_title . '</a>' . $lb;
									break;
								case 'external_link' :
									$title           = get_sub_field( 'title' );
									$url             = get_sub_field( 'url' );
									$open_new_window = get_sub_field( 'new_window' );
									$target          = ( $open_new_window ) ? '_blank' : '_self';
									echo $tab . $tab . '<a href="' . $url . '" class="list-group-item" target="' . $target . '">' . $title . '</a>' . $lb;
									break;
							endswitch;
						endwhile;
					}
				endwhile;
				echo '</div>' . $lb;
			else :
				echo '<p>No attachments</p>' . $lb;
			endif;

			echo $args[ 'after_widget' ] . $lb;
			wp_reset_postdata();
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
