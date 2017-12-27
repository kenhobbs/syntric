<?php

/**
 * Syntric_Categories_Menu_Widget
 */
class Syntric_Categories_Menu_Widget extends WP_Widget {
	/**
	 * Set up a new widget instance
	 */
	public function __construct() {
		$widget_ops = array(
			'classname'                   => 'syn-categories-menu-widget',
			'description'                 => __( 'Displays a list of post categories.' ),
			'customize_selective_refresh' => true,
		);
		parent::__construct( 'syn-categories-menu-widget', __( 'Categories Menu' ), $widget_ops );
		$this->alt_option_name = 'syn-categories-menu-widget';
	}

	/**
	 * Output widget content
	 *
	 * @param array $args Display arguments including 'before_title', 'after_title', 'before_widget', and 'after_widget'.
	 * @param array $instance Settings for the current widget instance.
	 */
	public function widget( $args, $instance ) {
		if ( ! isset( $args[ 'widget_id' ] ) ) {
			$args[ 'widget_id' ] = $this->id;
		}
		$all_categories = get_field( 'syn_categories_menu_widget_all_categories', 'widget_' . $args[ 'widget_id' ] );
		if ( $all_categories ) {
			$categories = get_categories( array( 'taxonomy' => 'category' ) );
		} else {
			$categories = get_field( 'syn_categories_menu_widget_categories', 'widget_' . $args[ 'widget_id' ] );
		}
		if ( $categories ) :
			$lb           = "\n";
			$tab          = "\t";
			$sidebar      = syn_widget_sidebar( $args[ 'widget_id' ] );
			$title        = get_field( 'syn_categories_menu_widget_title', 'widget_' . $args[ 'widget_id' ] );
			echo $args[ 'before_widget' ] . $lb;
			if ( ! empty( $title ) ) :
				echo $args[ 'before_title' ] . $title . $args[ 'after_title' ] . $lb;
			endif;
			echo '<ul>' . $lb;
			foreach ( $categories as $category ) {
				echo $tab . '<li>' . $lb;
				echo $tab . $tab . '<a href="' . get_category_link( $category ) . '">' . $lb;
				echo $tab . $tab . $tab . $tab . '<span class="entry-title">' . $category->name . '</span>' . $lb;
				echo $tab . $tab . '</a>' . $lb;
				echo $tab . '</li>' . $lb;
			};
			echo '</ul>' . $lb;
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
