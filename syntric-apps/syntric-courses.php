<?php
	
	add_action( 'init', 'syntric_register_course' );
	function syntric_register_course() {
		$labels = [
			'name'                  => _x( 'Courses', 'syntric' ),
			'singular_name'         => _x( 'Course', 'syntric' ),
			'menu_name'             => _x( 'Courses', 'syntric' ),
			'name_admin_bar'        => _x( 'Course', 'syntric' ),
			'add_new'               => __( 'Add New Course', 'syntric' ),
			'add_new_item'          => __( 'Add New Course', 'syntric' ),
			'new_item'              => __( 'New Course', 'syntric' ),
			'edit_item'             => __( 'Edit Course', 'syntric' ),
			'view_item'             => __( 'View Course', 'syntric' ),
			'all_items'             => __( 'All Courses', 'syntric' ),
			'search_items'          => __( 'Search Courses', 'syntric' ),
			'parent_item_colon'     => __( 'Parent Courses:', 'syntric' ),
			'not_found'             => __( 'No courses found.', 'syntric' ),
			'not_found_in_trash'    => __( 'No courses found in trash.', 'syntric' ),
			'archives'              => _x( 'Course archives', 'syntric' ),
			'insert_into_item'      => _x( 'Insert into course', 'syntric' ),
			'uploaded_to_this_item' => _x( 'Uploaded to this course', 'syntric' ),
			'filter_items_list'     => _x( 'Filter courses list', 'syntric' ),
			'items_list_navigation' => _x( 'Courses list navigation', 'syntric' ),
			'items_list'            => _x( 'Courses list', 'syntric' ),
		];
		$args   = [
			'labels'            => $labels,
			'public'            => true,
			//'publicly_queryable' => true,
			//'capability_type'    => 'post',
			'has_archive'       => true,
			'map_meta_cap'      => true,
			'menu_position'     => 30,
			'hierarchical'      => true,
			'delete_with_user'  => false,
			'supports'          => [ 'title', 'editor' ],
			'show_in_rest'      => true,
			'rest_base'         => 'courses',
			'can_export'        => true,
			'show_ui'           => true,
			'show_in_menu'      => true,
			'show_in_nav_menus' => true,
			'show_in_admin_bar' => false,
			'menu_icon'         => 'dashicons-course-alt',
			'query_var'         => true,
			'rewrite'           => [
				'slug'       => 'courses',
				'with_front' => true,
			],
			'redirect'          => true,
		];
		register_post_type( 'syntric_course', $args );
	}