<?php

	/**
	 * Syntric_Roster_Widget
	 *
	 * Displays page-level contact information.
	 */
	class Syntric_Roster_Widget extends WP_Widget {
		/**
		 * Set up a new widget instance
		 */
		public function __construct() {
			$widget_ops = [
				'classname'                   => 'syn-roster-widget',
				'description'                 => __( 'Displays roster on pages where "Roster" is enabled.' ),
				'customize_selective_refresh' => true,
			];
			parent::__construct( 'syn-roster-widget', __( 'Roster' ), $widget_ops );
			$this->alt_option_name = 'syn-roster-widget';
		}

		public function widget( $args, $instance ) {
			global $post;
			$active = get_field( 'syn_roster_active', $post->ID );
			if ( ! $active ) {
				return;
			}
			if ( ! isset( $args[ 'widget_id' ] ) ) {
				$args[ 'widget_id' ] = $this->id;
			}
			$lb = syn_get_linebreak();
			$tab = syn_get_tab();
			$sidebar         = syn_widget_sidebar( $args[ 'widget_id' ] );
			$sidebar_class   = syn_get_sidebar_class( $args[ 'widget_id' ] );
			$layout          = ( 'main' == $sidebar[ 'section' ][ 'value' ] && in_array( $sidebar[ 'location' ][ 'value' ], [
					'left',
					'right',
				] ) ) ? 'aside' : 'table';
			$title           = get_field( 'syn_roster_title', $post->ID );
			$_include_fields = get_field( 'syn_roster_include_fields', $post->ID );
			$include_fields  = array_column( $_include_fields, 'value' );
			$table_cols      = 0;
			echo $args[ 'before_widget' ] . $lb;
			if ( ! empty( $title ) ) :
				echo $args[ 'before_title' ] . $title . $args[ 'after_title' ] . $lb;
			endif;
			if ( have_rows( 'syn_roster_people', $post->ID ) ) {
				if ( 'aside' == $layout ) {
					echo '<div class="list-group ' . $sidebar_class . '">' . $lb;
				}
				if ( 'table' == $layout ) {
					echo '<table class="roster-table ' . $sidebar_class . '">' . $lb;
					echo $tab . '<thead>' . $lb;
					echo $tab . $tab . '<tr>' . $lb;
					echo $tab . $tab . $tab . '<th scope="col">Name</th>' . $lb;
					$table_cols ++;
					if ( in_array( 'title', $include_fields ) ) {
						echo $tab . $tab . $tab . '<th scope="col">Title</th>' . $lb;
						$table_cols ++;
					}
					if ( in_array( 'email', $include_fields ) ) {
						echo $tab . $tab . $tab . '<th scope="col">Email</th>' . $lb;
						$table_cols ++;
					}
					if ( in_array( 'phone', $include_fields ) ) {
						echo $tab . $tab . $tab . '<th scope="col">Phone</th>' . $lb;
						$table_cols ++;
					}
					echo $tab . $tab . '</tr>' . $lb;
					echo $tab . '</thead>' . $lb;
					echo $tab . '<tbody>' . $lb;
				}
				$row_counter = 1;
				while( have_rows( 'syn_roster_people', $post->ID ) ) : the_row();
					$user_id      = get_sub_field( 'person' );
					$user         = get_user_by( 'ID', $user_id );
					if ( $user instanceof WP_User ) {
						$user_meta    = get_user_meta( $user_id );
						$first_name   = $user_meta[ 'first_name' ][ 0 ];
						$last_name    = $user_meta[ 'last_name' ][ 0 ];
						$prefix       = get_field( 'syn_user_prefix', 'user_' . $user_id );
						$display_name = '';
						$display_name .= ( in_array( 'prefix', $include_fields ) && $prefix ) ? $prefix . ' ' : '';
						$display_name .= ( in_array( 'first_name', $include_fields ) && $first_name ) ? $first_name . ' ' : '';
						$display_name .= ( $last_name ) ? $last_name : ''; // always included
						$titles       = get_field( 'syn_user_title', 'user_' . $user_id );
						$titles       = str_replace( ',', ' / ', $titles );
						$titles       = str_replace( '|', ' / ', $titles );
						$email        = $user->data->user_email;
						$phone        = get_field( 'syn_user_phone', 'user_' . $user_id );
						$ext          = get_field( 'syn_user_extension', 'user_' . $user_id );
						$ext          = ( isset( $ext ) && ! empty( $ext ) && ! empty( $phone ) ) ? ' x' . $ext : '';
						$display_name = ( ! empty( $display_name ) ) ? $display_name : '';
						$titles       = ( ! empty( $titles ) ) ? $titles : '';
						$email        = ( ! empty( $email ) ) ? $email : '';
						$phone        = ( ! empty( $phone ) ) ? $phone . $ext : '';
						if ( 'aside' == $layout ) {
							echo $tab . '<div class="list-group-item">' . $lb;
							// todo: add ability to attach a photo to person
							/*echo $tab . $tab . '<div class="list-group-item-feature">' . $lb;
							echo $tab . $tab . '</div>' . $lb;*/
							echo $tab . $tab . '<div class="list-group-item-content">' . $lb;
							echo $tab . $tab . $tab . '<div class="person-name">' . $display_name . '</div>' . $lb;
							if ( in_array( 'title', $include_fields ) && ! empty( $titles ) ) :
								echo $tab . $tab . $tab . '<div class="person-title">' . $titles . '</div>' . $lb;
							endif;
							if ( in_array( 'email', $include_fields ) && ! empty( $email ) ) :
								echo $tab . $tab . $tab . '<a href="mailto:' . antispambot( $email, true ) . '" class="person-email" title="Email">' . antispambot( $email ) . '</a>' . $lb;
							endif;
							if ( in_array( 'phone', $include_fields ) && ! empty( $phone ) ) :
								echo $tab . $tab . $tab . '<div class="person-phone">' . $phone . $ext . '</div>' . $lb;
							endif;
							echo $tab . $tab . '</div>' . $lb;
							echo $tab . '</div>' . $lb;
						}
						if ( 'table' == $layout ) {
							echo $tab . $tab . '<tr valign="top">' . $lb;
							echo $tab . $tab . $tab . '<td class="full-name">' . $display_name . '</td>' . $lb;
							if ( in_array( 'title', $include_fields ) ) {
								echo $tab . $tab . $tab . '<td class="titles">' . $titles . '</td>' . $lb;
							}
							if ( in_array( 'email', $include_fields ) ) {
								echo $tab . $tab . $tab . '<td class="email"><a href="mailto:' . antispambot( $email, true ) . '" class="person-email" title="Email">' . antispambot( $email ) . '</a></td>' . $lb;
							}
							if ( in_array( 'phone', $include_fields ) ) {
								echo $tab . $tab . $tab . '<td class="phone">' . $phone . '</td>' . $lb;
							}
							echo $tab . $tab . '</tr>' . $lb;
						}
					} else {
						delete_row( 'syn_roster_people', $row_counter, $post->ID );
					}
					$row_counter ++;
				endwhile;
				if ( 'aside' == $layout ) {
					echo '</div>' . $lb;
				}
				if ( 'table' == $layout ) {
					echo $tab . '</tbody>' . $lb;
					echo '</table>' . $lb;
				}
			} else {
				echo '<p>No people in roster</p>';
			}
			echo $args[ 'after_widget' ] . $lb;
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
