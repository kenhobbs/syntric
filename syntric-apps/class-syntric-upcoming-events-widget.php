<?php

/**
 * Syntric_Upcoming_Events_Widget
 */
class Syntric_Upcoming_Events_Widget extends WP_Widget {
	/**
	 * Set up a new widget instance
	 */
	public function __construct() {
		$widget_ops = array(
			'classname'                   => 'syn-upcoming-events-widget',
			'description'                 => __( 'Displays upcoming calendar events.' ),
			'customize_selective_refresh' => true,
		);
		parent::__construct( 'syn-upcoming-events-widget', __( 'Upcoming Events' ), $widget_ops );
		$this->alt_option_name = 'syn-upcoming-events-widget';
	}

	/**
	 * Output widget content
	 *
	 * @param array $args Display arguments including 'before_title', 'after_title', 'before_widget', and 'after_widget'.
	 * @param array $instance Settings for the current widget instance.
	 */
	public function widget( $args, $instance ) {
		global $post;
		if ( ! isset( $args[ 'widget_id' ] ) ) {
			$args[ 'widget_id' ] = $this->id;
		}
		$dynamic = get_field( 'syn_upcoming_events_widget_dynamic', 'widget_' . $args[ 'widget_id' ] );
		if ( $dynamic && ! get_field( 'syn_calendar_active', $post->ID ) ) {
			return;
		}
		$field_prefix = ( $dynamic ) ? 'syn_calendar_' : 'syn_upcoming_events_widget_';
		$post_id      = ( $dynamic ) ? $post->ID : 'widget_' . $args[ 'widget_id' ];
		$lb           = "\n";
		$tab          = "\t";
		$calendar_id  = ( $dynamic ) ? get_field( 'syn_calendar_id', $post_id ) : get_field( 'syn_upcoming_events_widget_calendar_id', $post_id );
		$sidebar      = syn_widget_sidebar( $args[ 'widget_id' ] );
		$number       = get_field( $field_prefix . 'events', $post_id );
		$events       = syn_get_calendar_events( $calendar_id, null, 'next', $number );
		$title        = get_field( $field_prefix . 'title', $post_id );
		$show_date    = get_field( $field_prefix . 'include_date', $post_id );
		echo $args[ 'before_widget' ] . $lb;
		if ( ! empty( $title ) ) :
			echo $args[ 'before_title' ] . $title . $args[ 'after_title' ] . $lb;
		endif;
		echo '<ul class="widget-body nav">' . $lb;
		if ( $events ) :
			foreach ( $events as $event_id ) :
				$event    = get_post( $event_id );
				$dates    = syn_get_event_dates( $event_id );
				$location = get_field( 'syn_event_location', $event_id );
				echo $tab . '<li class="widget-item nav-item">' . $lb;
				if ( ! empty( $event->post_content ) ) :
					echo $tab . $tab . '<a href="' . get_the_permalink( $event_id ) . '" class="widget-entry nav-link">' . $lb;
					echo $tab . $tab . $tab . '<span class="entry-header">' . $lb;
					echo $tab . $tab . $tab . $tab . '<div class="entry-title">' . $event->post_title . '</div>' . $lb;
					if ( $show_date ) :
						echo $tab . $tab . $tab . $tab . '<div class="entry-date">' . $dates . '</div>' . $lb;
					endif;
					if ( ! empty( $location ) ) :
						echo $tab . $tab . $tab . $tab . '<div class="entry-location">' . $location . '</div>' . $lb;
					endif;
					echo $tab . $tab . $tab . '</span>' . $lb;
					echo $tab . $tab . '</a>' . $lb;
				else :
					echo $tab . $tab . '<div class="widget-entry">' . $lb;
					echo $tab . $tab . $tab . '<span class="entry-header">' . $lb;
					echo $tab . $tab . $tab . $tab . '<div class="entry-title">' . $event->post_title . '</div>' . $lb;
					if ( $show_date ) :
						echo $tab . $tab . $tab . $tab . '<div class="entry-date">' . $dates . '</div>' . $lb;
					endif;
					if ( ! empty( $location ) ) :
						echo $tab . $tab . $tab . $tab . '<div class="entry-location">' . $location . '</div>' . $lb;
					endif;
					echo $tab . $tab . $tab . '</span>' . $lb;
					echo $tab . $tab . '</div>' . $lb;
				endif;
				echo $tab . '</li>' . $lb;
			endforeach;
			echo $tab . '<li class="widget-item nav-item">' . $lb;
			echo $tab . $tab . '<a href="' . get_the_permalink( $calendar_id ) . '" class="widget-entry nav-link widget-more-link">' . $lb;
			echo $tab . $tab . $tab . 'more events';
			echo $tab . $tab . '</a>';
			echo $tab . '</li>' . $lb;
		else :
			echo $tab . '<li class="widget-item nav-item">' . $lb;
			echo $tab . $tab . '<div class="widget-entry nav-link">' . $lb;
			echo $tab . $tab . $tab . '<span class="entry-header">' . $lb;
			echo $tab . $tab . $tab . $tab . '<span class="entry-title">No events</span>' . $lb;
			echo $tab . $tab . $tab . '</span>' . $lb;
			echo $tab . $tab . '</div>' . $lb;
			echo $tab . '</li>' . $lb;
		endif;
		echo '</ul>' . $lb;
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
