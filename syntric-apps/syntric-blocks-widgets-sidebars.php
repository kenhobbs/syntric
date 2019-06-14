<?php
/*********************************************************************************
 * Blocks
 *********************************************************************************/

add_action( 'acf/init', 'syntric_register_blocks' );
function syntric_register_blocks() {
	// check function exists
	acf_update_setting( 'row_index_offset', 0 );
	acf_update_setting( 'remove_wp_meta_box', false );
	//acf_update_setting('show_admin', false );
	if( function_exists( 'acf_register_block' ) ) {
		// register a calendar block
		acf_register_block( [
			'name'            => 'syntric-calendar',
			'title'           => __( 'Google Calendar' ),
			'description'     => __( 'A Google Calendar block by Syntric' ),
			'category'        => 'syntric',
			'icon'            => 'calendar-alt',
			'mode'            => 'auto',
			'post_types'      => [ 'post', 'page' ],
			'align'           => 'full',
			'keywords'        => [ 'Google', 'calendar' ],
			'render_callback' => 'syntric_calendar_block_callback',
		] );

		acf_register_block( [
			'name'            => 'syntric-roster',
			'title'           => __( 'Roster' ),
			'description'     => __( 'A roster of people such as in a Staff or directory page' ),
			'category'        => 'syntric',
			//'icon'            => 'calendar-alt',
			'mode'            => 'auto',
			'post_types'      => [ 'post', 'page' ],
			'align'           => 'full',
			'keywords'        => [ 'Google', 'roster' ],
			'render_callback' => 'syntric_roster_block_callback',
			'render_template' => 'syntric-apps/syntric-roster.php',
		] );

		/*acf_register_block( [
			'name'            => 'syntric-calendar',
			'title'           => __( 'Google Calendar' ),
			'description'     => __( 'A Google Calendar block by Syntric' ),
			'category'        => 'syntric',
			'icon'            => 'calendar-alt',
			'mode'            => 'preview',
			'post_types'      => [ 'post', 'page' ],
			'align'           => 'full',
			'keywords'        => [ 'Google', 'calendar' ],
			'render_callback' => 'syntric_calendar_block_callback',
		] );*/
		/*acf_register_block( [
			'name'            => 'syntric-calendar',
			'title'           => __( 'Google Calendar' ),
			'description'     => __( 'A Google Calendar block by Syntric' ),
			'category'        => 'syntric',
			'icon'            => 'calendar-alt',
			'mode'            => 'preview',
			'post_types'      => [ 'post', 'page' ],
			'align'           => 'full',
			'keywords'        => [ 'Google', 'calendar' ],
			'render_callback' => 'syntric_calendar_block_callback',
		] );*/
	}
}

add_filter( 'block_categories', 'syntric_block_categories', 10, 2 );
function syntric_block_categories( $categories, $post ) {
	return array_merge(
		$categories,
		[
			[
				'slug'  => 'syntric',
				'title' => __( 'Syntric', 'syntric' ),
				//'icon'  => 'wordpress',
			],
		]
	);
}

function syntric_roster_block_callback( $block, $content = '', $is_preview = false, $post_id = 0 ) {
	//slog( 'syntric_roster_block_callback called');
	//slog($block);
	//return;
	slog( $block );
	if( $is_preview ) {
		$roster  = $block[ 'data' ][ 'field_5d02d2f8aa310' ];
		$title   = $roster[ 'field_5d02d2f8b1c19' ];
		$display = $roster[ 'field_5d02d2f8b1ffe' ];
		$people  = $roster[ 'field_5d02d2f8b23e0' ];
	} else {
		$rooster = get_field( 'field_5d02d2f8aa310', $post_id );
		slog( $rooster );
		$roster = $block[ 'data' ];
		slog( $roster );
		$title   = $roster[ 'syntric_roster_title' ];
		$display = $roster[ 'syntric_roster_display' ];
		$people  = $roster[ 'syntric_roster_people' ];
	}
	if( $people ) {
		echo '<h2>' . $title . '</h2>';
		echo '<table class="roster-table">';
		echo '<thead>';
		echo '<tr>';
		echo '<th scope="col">Name</th>';
		echo '<th scope="col">Title</th>';
		if( in_array( 'email', $display ) ) {
			echo '<th scope="col">Email</th>';
		}
		if( in_array( 'phone', $display ) ) {
			echo '<th scope="col">Phone</th>';
		}
		echo '</tr>';
		echo '</thead>';
		echo '<tbody>';
		$row_counter = 1;

		foreach( $people as $person ) {
			$user_id = $person; // returns User ID
			$user    = get_user_by( 'ID', $user_id );
			if( $user instanceof WP_User ) {
				$user_cf = get_field( 'syntric_user', 'user_' . $user_id );
				echo '<tr valign="top">';
				echo '<td class="contact-name">' . trim( $user_cf[ 'prefix' ] . ' ' . $user -> display_name ) . '</td>';
				echo '<td class="contact-title">' . str_replace( '|', ' / ', $user_cf[ 'title' ] ) . '</td>';
				if( in_array( 'email', $display ) ) {
					echo '<td class="contact-email"><a href="mailto:' . antispambot( $user -> user_email, true ) . '" class="user-email" title="Email">' . antispambot( $user -> user_email ) . '</a></td>';
				}
				if( in_array( 'phone', $display ) ) {
					$phone = $user_cf[ 'phone' ];
					$phone .= ( isset( $user_cf[ 'ext' ] ) && ! empty( $user_cf[ 'ext' ] ) ) ? ' x' . $user_cf[ 'ext' ] : '';
					echo '<td class="contact-phone">' . $phone . '</td>';
				}
				echo '</tr>';
			}
			$row_counter ++;
		}

		echo '</tbody>';
		echo '</table>';
	}
}

/**
 *  Callback for the calendar block
 *
 * @param   array  $block      The block settings and attributes.
 * @param   string $content    The block content (emtpy string).
 * @param   bool   $is_preview True during AJAX preview.
 */
function syntric_calendar_block_callback( $block, $content = '', $is_preview = false ) {
	//slog($block);
	//slog($content);
	//slog($is_preview);
	// get image field (array)
	//$avatar = get_field('avatar');

	// create id attribute for specific styling
	//$id = 'testimonial-' . $block['id'];

	// create align class ("alignwide") from block setting ("wide")
	//$align_class = $block['align'] ? 'align' . $block['align'] : '';

	//echo '<div class="fullcalendar">';
	/*echo '<div class="foobar">';
	echo '<p>Foo bar</p>';
	echo '</div>';*/
}

/*********************************************************************************
 * Widgets
 *********************************************************************************/

/**
 * Register/unregister custom widgets
 */
add_action( 'widgets_init', 'syntric_widgets_init', 20 );
function syntric_widgets_init() {
	$settings = get_field( 'syntric_settings', 'options' );
	$widgets  = $settings[ 'widgets' ];
	if( $widgets ) {
		foreach( $widgets as $widget ) {
			$widget_class      = str_replace( '_', '-', $widget );
			$widget_class_file = 'class-' . strtolower( $widget_class ) . '.php';
			require_once( $widget_class_file );
			register_widget( $widget );
		}
	}
}

/*********************************************************************************
 * Sidebars
 *********************************************************************************/

/**
 * Register sidebars
 */
add_action( 'widgets_init', 'syntric_sidebars_init' );
function syntric_sidebars_init() {
	$settings = get_field( 'syntric_settings', 'options' );
	$sidebars = $settings[ 'sidebars' ];
	if( $sidebars ) {
		foreach( $sidebars as $sidebar ) {
			$id       = $sidebar[ 'value' ];
			$name     = $sidebar[ 'label' ];
			$id_array = explode( '-', $id );
			if( 'header' == $id_array[ 0 ] || 'footer' == $id_array[ 0 ] ) {
				$class = 'sidebar ' . $id_array[ 0 ] . '-sidebar';
			} elseif( 'super' == $id_array[ 0 ] && 'header' == $id_array[ 1 ] ) {
				$class = 'sidebar super-header-sidebar';
			} else {
				$class = 'sidebar ' . $id;
			}
			register_sidebar( [
				'name'          => $name,
				'id'            => $id,
				'description'   => '',
				'class'         => $class,
				'before_widget' => '<div' . ' id="%1$s" class="widget %2$s">',
				'after_widget'  => '</div>',
				'before_title'  => '<h2>',
				'after_title'   => '</h2>',
			] );
		}
	}
}

/**
 * Get active widgets for sidebar
 *
 * @param $sidebar_id
 * @param $post_id
 *
 * @return array
 */
function syntric_sidebar_active_widgets( $sidebar_id ) {
	//slog( 'syntric_sidebar_active_widgets<br>' );
	$sidebars_widgets = get_option( 'sidebars_widgets', [] );
	if( isset( $sidebars_widgets[ $sidebar_id ] ) ) {
		//slog( 'widgets for ' . $sidebar_id . ' are:');
		//slog( $sidebars_widgets[$sidebar_id]);// array of widgets in sidebar
		return $sidebars_widgets[ $sidebar_id ];
	}

	return [];
}

/**
 * Dynamically create responsive columns for horizontal sidebars
 *
 * todo: handle cases where number of widgets is an odd number
 */
add_filter( 'dynamic_sidebar_params', 'syntric_dynamic_sidebar_params' );
function syntric_dynamic_sidebar_params( $params ) {
	//slog( 'syntric_dynamic_sidebar_params<br>' );
	if( ! is_admin() ) {
		$settings = get_field( 'syntric_settings', 'options' );
		$sidebars = $settings[ 'sidebars' ];
		foreach( $sidebars as $sidebar ) {
			if( $params[ 0 ][ 'id' ] == $sidebar[ 'value' ] ) {
				$active_widgets = syntric_sidebar_active_widgets( $params[ 0 ][ 'id' ] );
				$widget_count   = count( $active_widgets );
				if( 0 < $widget_count && in_array( $sidebar[ 'value' ], [ 'super-header-sidebar', 'header-sidebar-1', 'header-sidebar-2', 'header-sidebar-3', 'footer-sidebar-1', 'footer-sidebar-2', 'footer-sidebar-3', 'sub-footer-sidebar' ] ) ) {
					$params[ 0 ][ 'before_widget' ] = str_replace( 'class="', 'class="col-xl-' . 12 / $widget_count . ' ' . 'widgets-' . $widget_count . ' ', $params[ 0 ][ 'before_widget' ] );
				}
			}
		}
	}

	return $params;
}

// Called in order...

/**
 * Wraps the dynamic_sidebar() function to test schedules and filters and also to wrap the sidebar in it's
 * HTML container
 *
 * @param $sidebar_id
 */
function syntric_sidebar( $sidebar_id ) {
	if( is_active_sidebar( $sidebar_id ) && syntric_widget_will_display( $sidebar_id ) ) {
		dynamic_sidebar( $sidebar_id );
	}
}

function syntric_widget_will_display( $sidebar_id ) {
	$sidebars_widgets = get_option( 'sidebars_widgets', [] );
	if( isset( $sidebars_widgets[ $sidebar_id ] ) ) {
		foreach( $sidebars_widgets[ $sidebar_id ] as $widget_id ) {
			$filters      = get_field( 'field_5cca3b3111d0f', 'widget_' . $widget_id );
			$pass_filters = syntric_process_filters( $filters );
			if( $pass_filters ) {
				return true;
			}
		}
	}

	return false;
}

add_action( 'dynamic_sidebar_before', 'syntric_dynamic_sidebar_before', 10, 2 );
function syntric_dynamic_sidebar_before( $index, $has_widgets ) {
	global $wp_registered_sidebars;
	//slog( '<b>syntric_dynamic_sidebar_before()</b><br>' );
	if( $has_widgets ) {
		$wp_sidebar       = $wp_registered_sidebars[ $index ];
		$wp_sidebar_class = $wp_sidebar[ 'class' ];
		if( in_array( $index, [ 'main-left-sidebar', 'main-right-sidebar' ] ) ) {
			echo '<aside id="' . $index . '" class="' . $wp_sidebar_class . ' col-xl-3">';
		} elseif( in_array( $index, [ 'main-top-sidebar', 'main-bottom-sidebar' ] ) ) {
			echo '<section id="' . $index . '" class="' . $wp_sidebar_class . '">';
		} elseif( in_array( $index, [
			// todo: this shouldn't be hardcoded into the function
			'header-sidebar-1',
			'header-sidebar-2',
			'header-sidebar-3',
			'footer-sidebar-1',
			'footer-sidebar-2',
			'footer-sidebar-3',
		] ) ) {
			echo '<section id="' . $index . '" class="' . $wp_sidebar_class . '">';
			echo '<div class="container-fluid">';
			echo '<div class="row">';
		} elseif( 'super-header-sidebar' == $index ) {
			echo '<header id="' . $index . '" class="' . $wp_sidebar_class . '">';
		} elseif( 'sub-footer-sidebar' == $index ) {
			echo '<footer id="' . $index . '" class="' . $wp_sidebar_class . '">';
		}
	}
}

add_action( 'dynamic_sidebar_after', 'syntric_dynamic_sidebar_after', 10, 2 );
function syntric_dynamic_sidebar_after( $index, $has_widgets ) {
	//slog( '<b>syntric_dynamic_sidebar_after()</b><br>');
	if( $has_widgets ) {
		if( in_array( $index, [ 'main-left-sidebar', 'main-right-sidebar' ] ) ) {
			echo '</aside>';
		} elseif( in_array( $index, [ 'main-top-sidebar', 'main-bottom-sidebar' ] ) ) {
			echo '</section>';
		} elseif( in_array( $index, [
			// todo: this shouldn't be hardcoded into the function
			'header-sidebar-1',
			'header-sidebar-2',
			'header-sidebar-3',
			'footer-sidebar-1',
			'footer-sidebar-2',
			'footer-sidebar-3',
		] ) ) {
			echo '</div>';
			echo '</div>';
			echo '</section>';
		} elseif( 'super-header-sidebar' == $index ) {
			echo '</header>';
		} elseif( 'sub-footer-sidebar' == $index ) {
			echo '</footer>';
		}
	}
}

/**
 * Suppress the display of widgets is they don't pass schedule and filter tests
 */
add_filter( 'widget_display_callback', 'syntric_widget_display_callback', 1, 3 );
function syntric_widget_display_callback( $instance, $widget_class, $args ) {
	//slog( $args );
	$filters      = get_field( 'filters', 'widget_' . $args[ 'widget_id' ] );
	$pass_filters = syntric_process_filters( $filters );
	if( $pass_filters ) {
		return $instance;
	}

	return false;
}