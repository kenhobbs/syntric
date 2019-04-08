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
				'classname'                   => 'syntric-roster-widget',
				'description'                 => __( 'Displays roster on pages where "Roster" is enabled.' ),
				'customize_selective_refresh' => true,
			];
			parent ::__construct( 'syntric-roster-widget', __( 'Roster' ), $widget_ops );
			$this -> alt_option_name = 'syntric-roster-widget';
		}
		
		public function widget( $args, $instance ) {
			global $post;
			$widget_id = ( isset( $args[ 'widget_id' ] ) ) ? $args[ 'widget_id' ] : $this -> id;;
			/*$sidebar         = syntric_widget_sidebar( $widget_id );
			$sidebar_class   = syntric_get_sidebar_class( $widget_id );
			$layout          = ( 'main' == $sidebar[ 'section' ][ 'value' ] && in_array( $sidebar[ 'location' ][ 'value' ], [
					'left',
					'right',
				] ) ) ? 'aside' : 'table';
			$layout = 'table'; // make this a custom field*/
			$roster_widget = get_field( 'syntric_roster_widget', 'widget_' . $widget_id );
			$title         = $roster_widget[ 'title' ];
			$people        = $roster_widget[ 'people' ];
			if( $people ) {
				echo $args[ 'before_widget' ];
				if( ! empty( $title ) ) :
					echo $args[ 'before_title' ] . $title . $args[ 'after_title' ];
				endif;
				echo '<table class="roster-table">';
				echo '<thead>';
				echo '<tr>';
				echo '<th scope="col">Name</th>';
				echo '<th scope="col">Title</th>';
				echo '<th scope="col">Email</th>';
				echo '<th scope="col">Phone</th>';
				echo '</tr>';
				echo '</thead>';
				echo '<tbody>';
				$row_counter = 1;
				foreach( $people as $person ) {
					$user_id = $person[ 'person' ]; // returns User ID
					$user    = get_user_by( 'ID', $user_id );
					if( $user instanceof WP_User ) {
						$user_meta_data     = get_user_meta( $user_id );
						$user_custom_fields = get_field( 'syntric_user', 'user_' . $user_id );
						$phone              = $user_custom_fields[ 'phone' ];
						$phone              .= ( isset( $user_custom_fields[ 'ext' ] ) && ! empty( $user_custom_fields[ 'ext' ] ) ) ? ' x' . $user_custom_fields[ 'ext' ] : '';
						echo '<tr valign="top">';
						echo '<td class="contact-name">' . trim( $user_custom_fields[ 'prefix' ] . ' ' . $user -> display_name ) . '</td>';
						echo '<td class="contact-title">' . str_replace( '|', ' / ', $user_custom_fields[ 'title' ] ) . '</td>';
						echo '<td class="contact-email"><a href="mailto:' . antispambot( $user -> user_email, true ) . '" class="user-email" title="Email">' . antispambot( $user -> user_email ) . '</a></td>';
						echo '<td class="contact-phone">' . $phone . '</td>';
						echo '</tr>';
					} else {
						$delete_result = delete_row( 'syntric_roster_widget_contacts', $row_counter, 'widget_' . $widget_id );
						if( ! $delete_result ) {
							echo '<!-- failed to delete row ' . $row_counter;
						}
					}
					$row_counter ++;
				}
				echo '</tbody>';
				echo '</table>';
				echo $args[ 'after_widget' ];
			} else {
				echo '<!-- no records in roster -->';
			}
			
			//}
			/*		} else {
						delete_row( 'syntric_roster_people', $row_counter, $post -> ID );
					}
					$row_counter ++;
				endwhile;
			} else {
				echo '<!-- no records in roster -->';
			}*/
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
		public function form( $instance ) {
		}
	}
