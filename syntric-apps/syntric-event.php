<?php

/**
 * Register syntric_event custom post type
 */
add_action( 'init', 'syntric_register_event' );
function syntric_register_event() {
	$labels = [
		'name'                  => _x( 'Events', 'syntric' ),
		'singular_name'         => _x( 'Event', 'syntric' ),
		'menu_name'             => _x( 'Events', 'syntric' ),
		'name_admin_bar'        => _x( 'Event', 'syntric' ),
		'add_new'               => __( 'Add New', 'syntric' ),
		'add_new_item'          => __( 'Add New Event', 'syntric' ),
		'new_item'              => __( 'New Event', 'syntric' ),
		'edit_item'             => __( 'Edit Event', 'syntric' ),
		'view_item'             => __( 'View Event', 'syntric' ),
		'all_items'             => __( 'All Events', 'syntric' ),
		'search_items'          => __( 'Search Events', 'syntric' ),
		'parent_item_colon'     => __( 'Parent Events:', 'syntric' ),
		'not_found'             => __( 'No events found.', 'syntric' ),
		'not_found_in_trash'    => __( 'No events found in Trash.', 'syntric' ),
		'archives'              => _x( 'Event archives', 'syntric' ),
		'insert_into_item'      => _x( 'Insert into event', 'syntric' ),
		'uploaded_to_this_item' => _x( 'Uploaded to this event', 'syntric' ),
		'filter_items_list'     => _x( 'Filter events list', 'syntric' ),
		'items_list_navigation' => _x( 'Events list navigation', 'syntric' ),
		'items_list'            => _x( 'Events list', 'syntric' ),
	];
	$args   = [
		'labels'              => $labels,
		'public'              => true,
		'exclude_from_search' => false,
		'publicly_queryable'  => true,
		'capability_type'     => 'post',
		'has_archive'         => true,
		'map_meta_cap'        => true,
		'menu_position'       => 7,
		'hierarchical'        => false,
		'delete_with_user'    => false,
		'supports'            => [ 'title', 'editor' ],
		'show_in_rest'        => true,
		'rest_base'           => 'events',
		'can_export'          => true,
		'show_ui'             => true,
		//'show_in_menu'       => true,
		'show_in_menu'        => 'edit.php?post_type=syntric_calendar',
		'show_in_nav_menus'   => false,
		'show_in_admin_bar'   => false,
		'query_var'           => true,
		'rewrite'             => [
			'slug'       => 'events',
			'with_front' => true,
		],
		'redirect'            => true,
	];
	register_post_type( 'syntric_event', $args );
}