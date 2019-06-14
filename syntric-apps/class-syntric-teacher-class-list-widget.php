<?php

/**
 * Syntric_Teacher_Class_List_Widget
 *
 * Displays a list of classes teacher is currently teaching.
 */
class Syntric_Teacher_Class_List_Widget extends WP_Widget {
	/**
	 * Set up a new widget instance
	 */
	public function __construct() {
		$widget_ops = [ 'classname'                   => 'syntric-teacher-class-list-widget',
		                'description'                 => __( 'Displays class list for a teacher' ),
		                'customize_selective_refresh' => true,
		];
		parent ::__construct( 'syntric-teacher-class-list-widget', __( 'Syntric Teacher Class List' ), $widget_ops );
		$this -> alt_option_name = 'syntric-teacher-class-list-widget';
	}

	/**
	 * Output widget content
	 *
	 * @param array $args     Display arguments including 'before_title', 'after_title', 'before_widget', and 'after_widget'.
	 * @param array $instance Settings for the current widget instance.
	 */
	public function widget( $args, $instance ) {
		global $post;
		if( 'teacher' != basename( get_page_template(), '.php' ) ) {
			return;
		}
		$widget_id       = ( isset( $args[ 'widget_id' ] ) ) ? $args[ 'widget_id' ] : $this -> id;
		$user_id         = get_field( 'syntric_teacher_page_teacher', $post -> ID );
		$teacher_classes = syntric_get_teacher_classes( $user_id );
		if( ! $teacher_classes ) {
			return;
		}
		$teacher_class_list_widget = get_field( 'field_5d00880c2c972', 'widget_' . $widget_id );
		$title                     = $teacher_class_list_widget[ 'title' ];
		echo $args[ 'before_widget' ];
		if( ! empty( $title ) ) :
			echo $args[ 'before_title' ] . $title . $args[ 'after_title' ];
		endif;
		echo '<div class="list-group">';
		foreach( $teacher_classes as $class ) {
			$class_page  = syntric_get_class_page( $class[ 'id' ] );
			$course      = syntric_get_course( $class[ 'course' ][ 'value' ] );
			$before_item = ( $class_page instanceof WP_Post ) ? '<a href="' . get_the_permalink( $class_page -> ID ) . '" class="list-group-item list-group-item-action">' : '<div class="list-group-item">';
			$after_item  = ( $class_page instanceof WP_Post ) ? '</a>' : '</div>';
			echo $before_item;
			echo '<div class="d-flex w-100">';
			if( isset( $class[ 'period' ] ) && $class[ 'period' ] ) {
				echo '<div class="class-period small">Period ' . $class[ 'period' ][ 'label' ] . '</div>';
			}
			if( isset( $class[ 'period' ] ) && $class[ 'period' ] && isset( $class[ 'room' ] ) && $class[ 'room' ] ) {
				echo '<div class="small mx-1">/</div>';
			}
			if( isset( $class[ 'room' ] ) && $class[ 'room' ] ) {
				echo '<div class="class-room small">Room ' . $class[ 'room' ][ 'label' ] . '</div>';
			}

			echo '</div>';
			echo '<div class="class-course">' . $class[ 'course' ][ 'label' ] . '</div>';

			if( $course[ 'description' ] ) {
				echo '<div class="course-description">' . $course[ 'description' ] . '</div>';
			}
			echo $after_item;
		}
		echo '</div>';
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

