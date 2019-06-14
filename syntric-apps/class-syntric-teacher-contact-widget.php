<?php

/**
 * Syntric_Teacher_Contact_Widget
 *
 * Dynamic widget automatically renders on teacher and class templates
 */
class Syntric_Teacher_Contact_Widget extends WP_Widget {
	/**
	 * Set up a new widget instance
	 */
	public function __construct() {
		$widget_ops = [ 'classname'                   => 'syntric-teacher-contact-widget',
		                'description'                 => __( 'Automatically displays teacher contact info on teacher or class template pages' ),
		                'customize_selective_refresh' => true,
		];
		parent ::__construct( 'syntric-teacher-contact-widget', __( 'Syntric Teacher Contact' ), $widget_ops );
		$this -> alt_option_name = 'syntric-teacher-contact-widget';
	}

	/**
	 * Output widget content
	 *
	 * @param array $args     Display arguments including 'before_title', 'after_title', 'before_widget', and 'after_widget'.
	 * @param array $instance Settings for the current widget instance.
	 */
	public function widget( $args, $instance ) {
		global $post;
		$page_template = basename( get_page_template(), '.php' );
		if( 'teacher' != $page_template && 'class' != $page_template ) {
			return;
		}
		if( 'teacher' == $page_template ) {
			$user_id = get_field( 'syntric_teacher_page_teacher', $post -> ID );
		}
		if( 'class' == $page_template ) {
			$class_id = get_field( 'syntric_class_page_class', $post -> ID );
			$class    = syntric_get_class( $class_id );
			$user_id  = $class[ 'teacher' ][ 'value' ];
		}
		//$widget_id = ( isset( $args[ 'widget_id' ] ) ) ? $args[ 'widget_id' ] : $this -> id;
		$title = 'Contact';
		echo $args[ 'before_widget' ];
		if( ! empty( $title ) ) :
			echo $args[ 'before_title' ] . $title . $args[ 'after_title' ];
		endif;
		$user = get_user_by( 'ID', $user_id );
		if( $user instanceof WP_User ) {
			$user_custom_fields = get_field( 'field_5c873b64763cd', 'user_' . $user_id );
			$phone              = $user_custom_fields[ 'phone' ];
			$phone              .= ( isset( $user_custom_fields[ 'ext' ] ) && ! empty( $user_custom_fields[ 'ext' ] ) ) ? ' x' . $user_custom_fields[ 'ext' ] : '';
			echo '<div class="contact d-flex flex-row">';
			if( isset( $user_custom_fields[ 'photo' ] ) && ! empty( $user_custom_fields[ 'photo' ] ) ) {
				echo '<div class="pr-4">';
				echo '<img src="' . $user_custom_fields[ 'photo' ][ 'sizes' ][ 'thumbnail' ] . '" class="contact-photo circle-photo">';
				echo '</div>';
			}
			echo '<div>';
			echo '<div class="contact-name">' . trim( $user_custom_fields[ 'prefix' ] . ' ' . $user -> display_name ) . '</div>';
			if( isset( $user_custom_fields[ 'title' ] ) && ! empty( $user_custom_fields[ 'title' ] ) ) {
				echo '<div class="contact-title">' . str_replace( '|', ' / ', $user_custom_fields[ 'title' ] ) . '</div>';
			}
			echo '<div class="contact-email">';
			echo '<a href="mailto:' . antispambot( $user -> user_email, true ) . '" class="user-email" title="Email">' . antispambot( $user -> user_email ) . '</a>';
			echo '</div>';
			if( ! empty( $phone ) ) {
				echo '<div class="contact-phone">' . $phone . '</div>';
			}
			echo '</div>';
			echo '</div>';
		}
		echo $args[ 'after_widget' ];
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
		$instance            = $old_instance;
		$instance[ 'title' ] = '';

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