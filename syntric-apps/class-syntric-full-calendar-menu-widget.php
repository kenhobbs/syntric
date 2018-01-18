<?php

	/**
	 * Syntric_Full Calendars_Menu_Widget
	 */
	class Syntric_Full_Calendars_Menu_Widget extends WP_Widget {
		/**
		 * Set up a new widget instance
		 */
		public function __construct() {
			$widget_ops = [
				'classname'                   => 'syn-full-calendars-menu-widget',
				'description'                 => __( 'Displays a menu/control panel for full calender.' ),
				'customize_selective_refresh' => true,
			];
			parent::__construct( 'syn-full-calendars-menu-widget', __( 'Full Calendars Menu' ), $widget_ops );
			$this->alt_option_name = 'syn-full-calendars-menu-widget';
		}

		/**
		 * Output widget content
		 *
		 * @param array $args     Display arguments including 'before_title', 'after_title', 'before_widget', and 'after_widget'.
		 * @param array $instance Settings for the current widget instance.
		 */
		public function widget( $args, $instance ) {
			if( ! isset( $args[ 'widget_id' ] ) ) {
				$args[ 'widget_id' ] = $this->id;
			}
			$calendars = get_field( 'syn_full_calendars_menu_widget_calendars', 'widget_' . $args[ 'widget_id' ] );
			if( $calendars ) :
				$lb       = "\n";
				$tab      = "\t";
				$sidebar  = syn_widget_sidebar( $args[ 'widget_id' ] );
				$title    = get_field( 'syn_full_calendars_menu_widget_title', 'widget_' . $args[ 'widget_id' ] );
				$ref_date = ( isset( $_GET[ 'ref_date' ] ) ) ? $_GET[ 'ref_date' ] : date( 'Ymd' );
				echo $args[ 'before_widget' ] . $lb;
				if( ! empty( $title ) ) :
					echo $args[ 'before_title' ] . $title . $args[ 'after_title' ] . $lb;
				endif;
				echo '<form id="full-calendar-menu">';
				echo '<ul>' . $lb;
				foreach( $calendars as $calendar ) {
					echo $tab . '<li>' . $lb;
					//echo $tab . $tab . '<a href="' . get_the_permalink( $calendar->ID ) . '?ref_date=' . $ref_date . '" class="' . $link_classes . '">' . $lb;
					//echo $tab . $tab . '<div class="form-check form-check-inline"><label class="form-check-label" for="\' + google_calendar_id + \'"><input class="form-check-input" id="\' + google_calendar_id + \'" type="checkbox" name="googleCalendarId" checked value="\' + google_calendar_id + \'">\' + calendar_title + \'</label></div>';
					/*
					echo $tab . $tab . $tab . $tab . '<div class="entry-title">' . $calendar->post_title . '</div>' . $lb;
					*/
					//echo $tab . $tab . '</a>' . $lb;
					echo $tab . '</li>' . $lb;
				};
				echo '</ul>' . $lb;
				echo '</form>';
				echo $args[ 'after_widget' ] . $lb;
			endif;
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
