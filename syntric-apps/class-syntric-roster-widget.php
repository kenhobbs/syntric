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
		$widget_ops = array(
			'classname'                   => 'syn-roster-widget',
			'description'                 => __( 'Displays roster on pages where "Roster" is enabled.' ),
			'customize_selective_refresh' => true,
		);
		parent::__construct( 'syn-roster-widget', __( 'Roster' ), $widget_ops );
		$this->alt_option_name = 'syn-roster-widget';
	}

	/**
	 * Output widget content
	 * todo: implement custom classes
	 *
	 * @param array $args Display arguments including 'before_title', 'after_title', 'before_widget', and 'after_widget'.
	 * @param array $instance Settings for the current widget instance.
	 */
	public function widget( $args, $instance ) {
		global $post;
		$active = get_field( 'syn_roster_active', $post->ID );
		if ( ! $active ) {
			return;
		}
		if ( ! isset( $args[ 'widget_id' ] ) ) {
			$args[ 'widget_id' ] = $this->id;
		}
		$lb              = "\n";
		$tab             = "\t";
		$sidebar         = syn_widget_sidebar( $args[ 'widget_id' ] );
		$layout          = ( 'main' == $sidebar[ 'section' ][ 'value' ] && in_array( $sidebar[ 'location' ][ 'value' ], array(
				'left',
				'right',
			) ) ) ? 'aside' : 'table';
		$title           = get_field( 'syn_roster_title', $post->ID );
		$_include_fields = get_field( 'syn_roster_include_fields', $post->ID );
		$include_fields  = array_column( $_include_fields, 'value' );
		$table_cols      = 0;
		echo $args[ 'before_widget' ] . $lb;
		if ( ! empty( $title ) ) :
			echo $args[ 'before_title' ] . $title . $args[ 'after_title' ] . $lb;
		endif;
		if ( 'aside' == $layout ) {
			echo '<div class="textwidget">' . $lb;
		}
		if ( 'table' == $layout ) {
			echo '<table>' . $lb;
			echo $tab . '<thead>' . $lb;
			echo $tab . $tab . '<tr>' . $lb;
			echo $tab . $tab . $tab . '<th scope="col">Name</th>' . $lb;
			$table_cols ++;
			if ( in_array( 'titles', $include_fields ) ) {
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
		//$people = syn_people_collection();
		if ( have_rows( 'syn_roster_people', $post->ID ) ) :
			while ( have_rows( 'syn_roster_people', $post->ID ) ) : the_row();
				$user_id    = get_sub_field( 'person' );
				$user       = get_user_by( 'ID', $user_id );
				$user_meta  = get_user_meta( $user_id );
				$first_name = $user_meta[ 'first_name' ][ 0 ];
				$last_name  = $user_meta[ 'last_name' ][ 0 ];
				$title      = get_field( 'syn_user_title', 'user_' . $user_id );
				$title      = str_replace( ',', '<br>', $title );
				$email      = $user->data->user_email;
				$phone      = get_field( 'syn_user_phone', 'user_' . $user_id );
				$ext        = get_field( 'syn_user_extension', 'user_' . $user_id );
				$ext        = ( isset( $ext ) && ! empty( $ext ) ) ? ' x' . $ext : '';
				if ( 'aside' == $layout ) {
					echo $tab . '<div class="contact-entry d-flex flex-column">' . $lb;
					echo $tab . $tab . '<span class="entry-name">' . $first_name . ' ' . $last_name . '</span>' . $lb;
					if ( in_array( 'titles', $include_fields ) ) {
						echo $tab . $tab . '<span class="entry-title">' . $title . '</span>' . $lb;
					}
					if ( in_array( 'email', $include_fields ) ) {
						echo $tab . $tab . '<a href="mailto:' . antispambot( $email, true ) . '" class="entry-email" title="Email">' . antispambot( $email ) . '</a>';
					}
					if ( in_array( 'phone', $include_fields ) ) {
						echo $tab . $tab . '<span class="entry-phone">' . $phone . $ext . '</span>' . $lb;
					}
					echo $tab . '</div>' . $lb;
				}
				if ( 'table' == $layout ) {
					echo $tab . $tab . '<tr valign="top">' . $lb;
					echo $tab . $tab . $tab . '<td nowrap="nowrap">' . $first_name . ' ' . $last_name . '</td>' . $lb;
					if ( in_array( 'titles', $include_fields ) ) {
						echo $tab . $tab . $tab . '<td>' . $title . '</td>' . $lb;
					}
					if ( in_array( 'email', $include_fields ) ) {
						echo $tab . $tab . $tab . '<td><a href="mailto:' . antispambot( $email, true ) . '" class="roster-email" title="Email">' . antispambot( $email ) . '</a></td>' . $lb;
					}
					if ( in_array( 'phone', $include_fields ) ) {
						echo $tab . $tab . $tab . '<td>' . $phone . $ext . '</td>' . $lb;
					}
					echo $tab . $tab . '</tr>' . $lb;
				}
			endwhile;
		else :
			if ( 'aside' == $layout ) :
				/*echo $tab . '<li>' . $lb;
				echo $tab . $tab . '<span class="entry-title">No people</span>' . $lb;
				echo $tab . '</li>' . $lb;*/
			endif;
			if ( 'table' == $layout ) :
				echo $tab . $tab . '<tr>' . $lb;
				echo $tab . $tab . $tab . '<td colspan="' . $table_cols . '">No people</td>' . $lb;
				echo $tab . $tab . '</tr>' . $lb;
			endif;
		endif;
		if ( 'aside' == $layout ) {
			echo '</div>' . $lb;
		}
		if ( 'table' == $layout ) {
			echo $tab . '</tbody>' . $lb;
			echo '</table>' . $lb;
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
