<?php

/**
 * Syntric_Upcoming_Events_Widget
 */
class Syntric_Upcoming_Events_Widget extends WP_Widget {
	/**
	 * Set up a new widget instance
	 */
	public function __construct() {
		$widget_ops = [ 'classname'                   => 'syntric-upcoming-events-widget',
		                'description'                 => __( 'Displays upcoming calendar events.' ),
		                'customize_selective_refresh' => true,
		];
		parent ::__construct( 'syntric-upcoming-events-widget', __( 'Upcoming Events' ), $widget_ops );
		$this -> alt_option_name = 'syntric-upcoming-events-widget';
	}

	/**
	 * Output widget content
	 *
	 * @param array $args     Display arguments including 'before_title', 'after_title', 'before_widget', and 'after_widget'.
	 * @param array $instance Settings for the current widget instance.
	 */
	public function widget( $args, $instance ) {
		global $post;
		$widget_id         = ( isset( $args[ 'widget_id' ] ) ) ? $args[ 'widget_id' ] : $this -> id;
		$title             = get_field( 'syntric_upcoming_events_widget_title', 'widget_' . $widget_id );
		$calendar          = get_field( 'syntric_upcoming_events_widget_calendar', 'widget_' . $widget_id );
		$events_to_display = get_field( 'syntric_upcoming_events_widget_events_to_display', 'widget_' . $widget_id );;
		echo $args[ 'before_widget' ];
		if( ! empty( $title ) ) :
			echo $args[ 'before_title' ] . $title . $args[ 'after_title' ];
		endif;
		$events = syntric_get_calendar_events( $calendar, null, 'next', $events_to_display, 'ID,post_title,post_content' );
		echo '<div class="list-group">';
		if( $events ) {
			foreach( $events as $event ) :
				$dates       = syntric_get_event_dates( $event -> ID );
				$start_date  = get_field( 'syntric_event_start_date', $event -> ID );
				$_start_date = date_create( $start_date );
				$location    = get_field( 'syntric_event_location', $event -> ID );
				if( ! empty( $event -> post_content ) ) :
					echo '<a href="' . get_the_permalink( $event -> ID ) . '" class="list-group-item list-group-item-action">';
				else :
					echo '<div class="list-group-item">';
				endif;
				echo '<div class="list-group-item-feature">';
				echo '<div class="calendar-icon">';
				echo '<div class="month">' . strtoupper( date_format( $_start_date, 'M' ) ) . '</div>';
				echo '<div class="day">' . date_format( $_start_date, 'd' ) . '</div>';
				echo '</div>';
				echo '</div>';
				echo '<div class="list-group-item-content">';
				echo '<div class="event-title">' . $event -> post_title . '</div>';
				echo '<div class="event-date small">' . $dates . '</div>';
				if( ! empty( $location ) ) :
					echo '<div class="event-location small">' . $location . '</div>';
				endif;
				echo '</div>';
				if( ! empty( $event -> post_content ) ) :
					echo '</a>';
				else :
					echo '</div>';
				endif;
			endforeach;
			echo '<a href="' . get_the_permalink( $calendar ) . '" class="list-group-item list-group-item-action more-link">Full calendar</a>';
		} else {
			echo '<div class="list-group-item">No events</div>';
		}
		echo '</div>';
		echo $args[ 'after_widget' ];
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
	public function form( $instance ) {
	}
}
