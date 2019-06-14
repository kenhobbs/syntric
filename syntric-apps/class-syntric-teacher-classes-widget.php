<?php

/**
 * Syntric_Teacher_Classes_Widget
 *
 * Displays a table listing the classes a teacher is currently teaching.
 */
class Syntric_Teacher_Classes_Widget extends WP_Widget {
	/**
	 * Set up a new widget instance
	 */
	public function __construct() {
		$widget_ops = [ 'classname'                   => 'syntric-teacher-classes-widget',
		                'description'                 => __( 'Displays teachers classes (only on a teacher page)' ),
		                'customize_selective_refresh' => true,
		];
		parent ::__construct( 'syntric-teacher-classes-widget', __( 'Syntric Teacher Classes' ), $widget_ops );
		$this -> alt_option_name = 'syntric-teacher-classes-widget';
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
		$teacher_class_list_widget = get_field( 'field_5d00d5805f74f', 'widget_' . $widget_id );
		$title                     = $teacher_class_list_widget[ 'title' ];
		echo $args[ 'before_widget' ];
		if( ! empty( $title ) ) :
			echo $args[ 'before_title' ] . $title . $args[ 'after_title' ];
		endif;
		echo '<table class="teacher-classes-table">';
		echo '<thead>';
		echo '<tr>';
		echo '<th scope="col">Period</th>';
		echo '<th scope="col">Course</th>';
		echo '<th scope="col">Room</th>';
		echo '</tr>';
		echo '</thead>';
		echo '<tbody>';
		foreach( $teacher_classes as $class ) {
			$before_course = '';
			$after_course  = '';
			if( $class[ 'include_page' ] ) {
				$class_page    = syntric_get_class_page( $class[ 'id' ] );
				$before_course = '<a href="' . get_the_permalink( $class_page -> ID ) . '">';
				$after_course  = '</a>';
			}
			echo '<tr>';
			echo '<td class="period">' . $class[ 'period' ][ 'label' ] . '</td>';
			echo '<td class="course">' . $before_course . $class[ 'course' ][ 'label' ] . $after_course . '</td>';
			echo '<td class="room">' . $class[ 'room' ][ 'label' ] . '</td>';
			echo '</tr>';
		}
		echo '</tbody>';
		echo '</table>';
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

