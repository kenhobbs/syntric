<?php

/**
 * Syntric_Calendars_Menu_Widget
 */
class Syntric_Calendars_Menu_Widget extends WP_Widget {
	/**
	 * Set up a new widget instance
	 */
	public function __construct() {
		$widget_ops = [
			'classname'                   => 'syntric-calendars-menu-widget',
			'description'                 => __( 'Displays a list of calendars.' ),
			'customize_selective_refresh' => true,
		];
		parent ::__construct( 'syntric-calendars-menu-widget', __( 'Calendars Menu' ), $widget_ops );
		$this -> alt_option_name = 'syntric-calendars-menu-widget';
	}

	/**
	 * Output widget content
	 *
	 * @param array $args     Display arguments including 'before_title', 'after_title', 'before_widget', and 'after_widget'.
	 * @param array $instance Settings for the current widget instance.
	 */
	public function widget( $args, $instance ) {
		$widget_id = ( isset( $args[ 'widget_id' ] ) ) ? $args[ 'widget_id' ] : $this -> id;
		//$all_calendars = get_field( 'syn_calendars_menu_widget_all_calendars', 'widget_' . $widget_id );
		//if( $all_calendars ) {
		//$calendars = syntric_get_calendars();
		//} else {
		$calendars_menu_widget = get_field( 'syntric_calendars_menu_widget', 'widget_' . $args[ 'widget_id' ] );
		$calendars             = $calendars_menu_widget[ 'calendars' ];//}

		;
		//$sidebar = syntric_widget_sidebar( $widget_id );
		//$sidebar_class = syntric_get_sidebar_class( $widget_id );
		//$title         = get_field( 'syn_calendars_menu_widget_title', 'widget_' . $widget_id );
		$title = $calendars_menu_widget[ 'title' ];
		echo $args[ 'before_widget' ];
		if( ! empty( $title ) ) :
			echo $args[ 'before_title' ] . $title . $args[ 'after_title' ];
		endif;
		echo '<div class="list-group">';
		if( $calendars ) :
			$ref_date = ( isset( $_GET[ 'ref_date' ] ) ) ? $_GET[ 'ref_date' ] : date( 'Ymd' );
			foreach( $calendars as $calendar ) {
				echo '<a href="' . get_the_permalink( $calendar -> ID ) . '?ref_date=' . $ref_date . '" data-id="' . $calendar -> ID . '" class="list-group-item list-group-item-action">';
				echo $calendar -> post_title;
				echo '</a>';
			};
		else :
			echo '<div class="list-group-item">No calendars</div>';
		endif;
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
