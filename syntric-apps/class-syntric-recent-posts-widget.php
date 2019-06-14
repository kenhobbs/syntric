<?php

/**
 * Syntric_Recent_Posts_Widget
 */
class Syntric_Recent_Posts_Widget extends WP_Widget {
	/**
	 * Set up a new widget instance
	 */
	public function __construct() {
		$widget_ops = [
			'classname'                   => 'syntric-recent-posts-widget',
			'description'                 => __( 'Displays posts from one or more categories.' ),
			'customize_selective_refresh' => true,
		];
		parent ::__construct( 'syntric-recent-posts-widget', __( 'Syntric Recent Posts' ), $widget_ops );
		$this -> alt_option_name = 'syntric-recent-posts-widget';
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
		$recent_posts_widget = get_field( 'field_5ca2821de55e2', 'widget_' . $args[ 'widget_id' ] );
		$title               = ! empty( $recent_posts_widget[ 'title' ] ) ? $recent_posts_widget[ 'title' ] : '';
		echo $args[ 'before_widget' ];
		if( $title ) {
			echo $args[ 'before_title' ] . $title . $args[ 'after_title' ];
		}
		$category_ids     = $recent_posts_widget[ 'categories' ];
		$posts_to_display = $recent_posts_widget[ 'posts_to_display' ];

		$posts = new WP_Query( apply_filters( 'widget_posts_args', [
			'posts_per_page'      => $posts_to_display,
			'no_found_rows'       => true,
			'post_status'         => 'publish',
			'ignore_sticky_posts' => true,
			'category__in'        => ( is_array( $category_ids ) ) ? $category_ids : [ $category_ids ],
		] ) );

		echo '<div class="list-group">';
		if( $posts -> have_posts() ) :
			while( $posts -> have_posts() ) : $posts -> the_post();
				$post_date  = get_the_date();
				$_post_date = date_create( $post_date );
				echo '<a href="' . get_the_permalink() . '" class="list-group-item list-group-item-action">';
				if( has_post_thumbnail() ) :
					echo '<div class="list-group-item-feature">';
					echo the_post_thumbnail( 'thumbnail', [ 'class' => 'feature-thumbnail' ] );
					echo '</div>';
				else :
					echo '<div class="list-group-item-feature">';
					echo '<div class="calendar-icon">';
					echo '<div class="month">' . strtoupper( date_format( $_post_date, 'M' ) ) . '</div>';
					echo '<div class="day">' . date_format( $_post_date, 'd' ) . '</div>';
					echo '</div>';
					echo '</div>';
				endif;
				echo '<div class="list-group-item-content">';
				echo '<div class="post-title">' . get_the_title() . '</div>';
				echo '<div class="post-date small">' . $post_date . '</div>';
				echo '</div>';
				echo '</a>';
			endwhile;
			echo '<a href="' . get_post_type_archive_link( 'post' ) . '" class="list-group-item list-group-item-action more-link">More ' . $title . '</a>';
		else :
			echo '<div class="list-group-item">No posts</div>';
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
