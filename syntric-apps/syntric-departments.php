<?php
	
	add_action( 'init', 'syntric_register_department' );
	function syntric_register_department() {
		$labels = [
			'name'                  => _x( 'Departments', 'syntric' ),
			'singular_name'         => _x( 'Department', 'syntric' ),
			'menu_name'             => _x( 'Departments', 'syntric' ),
			'name_admin_bar'        => _x( 'Department', 'syntric' ),
			'add_new'               => __( 'Add New Department', 'syntric' ),
			'add_new_item'          => __( 'Add New Department', 'syntric' ),
			'new_item'              => __( 'New Department', 'syntric' ),
			'edit_item'             => __( 'Edit Department', 'syntric' ),
			'view_item'             => __( 'View Department', 'syntric' ),
			'all_items'             => __( 'All Departments', 'syntric' ),
			'search_items'          => __( 'Search Departments', 'syntric' ),
			'parent_item_colon'     => __( 'Parent Departments:', 'syntric' ),
			'not_found'             => __( 'No departments found.', 'syntric' ),
			'not_found_in_trash'    => __( 'No departments found in trash.', 'syntric' ),
			'archives'              => _x( 'Department archives', 'syntric' ),
			'insert_into_item'      => _x( 'Insert into department', 'syntric' ),
			'uploaded_to_this_item' => _x( 'Uploaded to this department', 'syntric' ),
			'filter_items_list'     => _x( 'Filter departments list', 'syntric' ),
			'items_list_navigation' => _x( 'Departments list navigation', 'syntric' ),
			'items_list'            => _x( 'Departments list', 'syntric' ),
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
			'rest_base'         => 'departments',
			'can_export'        => true,
			'show_ui'           => true,
			'show_in_menu'      => true,
			'show_in_nav_menus' => true,
			'show_in_admin_bar' => false,
			'menu_icon'         => 'dashicons-department-alt',
			'query_var'         => true,
			'rewrite'           => [
				'slug'       => 'departments',
				'with_front' => true,
			],
			'redirect'          => true,
		];
		register_post_type( 'syntric_department', $args );
	}