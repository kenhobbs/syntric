<?php

/**
 * Syntric_Calendar_Menu_Widget
 */
class Syntric_Calendar_Menu_Widget extends WP_Widget {
	/**
	 * Set up a new widget instance
	 */
	public function __construct() {
		$widget_ops = [
			'classname'                   => 'syntric-calendar-menu-widget',
			'description'                 => __( 'Displays a menu of selected calendars.' ),
			'customize_selective_refresh' => true,
		];
		parent ::__construct( 'syntric-calendar-menu-widget', __( 'Syntric Calendar Menu' ), $widget_ops );
		$this -> alt_option_name = 'syntric-calendar-menu-widget';
	}

	/**
	 * Output widget content
	 *
	 * @param array $args     Display arguments including 'before_title', 'after_title', 'before_widget', and 'after_widget'.
	 * @param array $instance Settings for the current widget instance.
	 */
	public function widget( $args, $instance ) {
		if( ! isset( $args[ 'widget_id' ] ) ) {
			$args[ 'widget_id' ] = $this -> id;
		}
		$calendar_menu_widget = get_field( 'field_5cca3285b9c38', 'widget_' . $args[ 'widget_id' ] );
		$title                = ! empty( $calendar_menu_widget[ 'title' ] ) ? $calendar_menu_widget[ 'title' ] : '';
		echo $args[ 'before_widget' ];
		if( $title ) {
			echo $args[ 'before_title' ] . $title . $args[ 'after_title' ];
		}
		echo '<ul class="list-group menu">';
		$calendars = $calendar_menu_widget[ 'calendars' ];
		$ref_date  = ( isset( $_GET[ 'ref_date' ] ) ) ? $_GET[ 'ref_date' ] : date( 'Ymd' );
		if( $calendars ) {
			foreach( $calendars as $calendar ) {
				$_calendar = $calendar[ 'calendar' ];
				echo '<li class="list-group-item list-group-item-action">';
				echo '<a href="' . get_the_permalink( $_calendar[ 'value' ] ) . '?ref_date=' . $ref_date . '" data-id="' . $_calendar[ 'value' ] . '">';
				echo $_calendar[ 'label' ];
				echo '</a>';
				echo '</li>';
			}
		} else {
			echo '<li class="list-group-item">No calendars</li>';
		}
		echo '</ul>';
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
	public function form( $instance ) { }
}
