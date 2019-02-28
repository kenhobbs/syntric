<?php
	
	/**
	 * Syntric_Upcoming_Events_Widget
	 */
	class Syntric_Upcoming_Events_Widget extends WP_Widget {
		/**
		 * Set up a new widget instance
		 */
		public function __construct() {
			$widget_ops = [ 'classname'                   => 'syn-upcoming-events-widget',
			                'description'                 => __( 'Displays upcoming calendar events.' ),
			                'customize_selective_refresh' => true,
			];
			parent ::__construct( 'syn-upcoming-events-widget', __( 'Upcoming Events' ), $widget_ops );
			$this -> alt_option_name = 'syn-upcoming-events-widget';
		}
		
		/**
		 * Output widget content
		 *
		 * @param array $args     Display arguments including 'before_title', 'after_title', 'before_widget', and 'after_widget'.
		 * @param array $instance Settings for the current widget instance.
		 */
		public function widget( $args, $instance ) {
			global $post;
			if( ! isset( $args[ 'widget_id' ] ) ) {
				$args[ 'widget_id' ] = $this -> id;
			}
			$dynamic = get_field( 'syn_upcoming_events_widget_dynamic', 'widget_' . $args[ 'widget_id' ] );
			if( $dynamic ) {
				$active = get_field( 'syn_calendar_active', $post -> ID );
				if( ! $active ) {
					return;
				}
				$title       = get_field( 'syn_calendar_title', $post -> ID );
				$calendar_id = get_field( 'syn_calendar_id', $post -> ID );
				$number      = get_field( 'syn_calendar_events', $post -> ID );
				$show_date   = get_field( 'syn_calendar_include_date', $post -> ID );
			} else {
				$title       = get_field( 'syn_upcoming_events_widget_title', 'widget_' . $args[ 'widget_id' ] );
				$calendar_id = get_field( 'syn_upcoming_events_widget_calendar_id', 'widget_' . $args[ 'widget_id' ] );
				$number      = get_field( 'syn_upcoming_events_widget_events', 'widget_' . $args[ 'widget_id' ] );
				$show_date   = get_field( 'syn_upcoming_events_widget_include_date', 'widget_' . $args[ 'widget_id' ] );
			}
			//$sidebar      = syntric_widget_sidebar( $args[ 'widget_id' ] );
			$sidebar_class = syntric_get_sidebar_class( $args[ 'widget_id' ] );
			$lb            = syntric_linebreak();
			$tab           = syntric_tab();
			echo $args[ 'before_widget' ] . $lb;
			if( ! empty( $title ) ) :
				echo $args[ 'before_title' ] . $title . $args[ 'after_title' ] . $lb;
			endif;
			$events = syntric_get_calendar_events( $calendar_id, null, 'next', $number, 'ID,post_title,post_content' );
			echo '<div class="list-group ' . $sidebar_class . '">' . $lb;
			if( $events ) {
				foreach( $events as $event ) :
					$dates       = syntric_get_event_dates( $event -> ID );
					$start_date  = get_field( 'syn_event_start_date', $event -> ID );
					$_start_date = date_create( $start_date );
					$location    = get_field( 'syn_event_location', $event -> ID );
					if( ! empty( $event -> post_content ) ) :
						echo $tab . '<a href="' . get_the_permalink( $event -> ID ) . '" class="list-group-item list-group-item-action">' . $lb;
					else :
						echo $tab . '<div class="list-group-item">' . $lb;
					endif;
					echo $tab . $tab . '<div class="list-group-item-feature">' . $lb;
					echo $tab . $tab . $tab . '<div class="calendar-icon">' . $lb;
					echo $tab . $tab . $tab . $tab . '<div class="month">' . strtoupper( date_format( $_start_date, 'M' ) ) . '</div>' . $lb;
					echo $tab . $tab . $tab . $tab . '<div class="day">' . date_format( $_start_date, 'd' ) . '</div>' . $lb;
					echo $tab . $tab . $tab . '</div>' . $lb;
					echo $tab . $tab . '</div>' . $lb;
					echo $tab . $tab . '<div class="list-group-item-content">' . $lb;
					echo $tab . $tab . $tab . '<div class="event-title">' . $event -> post_title . '</div>' . $lb;
					if( $show_date ) :
						echo $tab . $tab . $tab . '<div class="event-date small">' . $dates . '</div>' . $lb;
					endif;
					if( ! empty( $location ) ) :
						echo $tab . $tab . $tab . '<div class="event-location small">' . $location . '</div>' . $lb;
					endif;
					echo $tab . $tab . '</div>' . $lb;
					if( ! empty( $event -> post_content ) ) :
						echo $tab . '</a>' . $lb;
					else :
						echo $tab . '</div>' . $lb;
					endif;
				endforeach;
				echo $tab . '<a href="' . get_the_permalink( $calendar_id ) . '" class="list-group-item list-group-item-action more-link">Full calendar</a>' . $lb;
			} else {
				echo '<div class="list-group-item">No events</div>' . $lb;
			}
			echo '</div>' . $lb;
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
		public function form( $instance ) {
		}
	}
