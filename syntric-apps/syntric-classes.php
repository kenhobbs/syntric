<?php
	
	add_action( 'init', 'syntric_register_class' );
	function syntric_register_class() {
		$labels = [
			'name'                  => _x( 'Classes', 'syntric' ),
			'singular_name'         => _x( 'Class', 'syntric' ),
			'menu_name'             => _x( 'Classes', 'syntric' ),
			'name_admin_bar'        => _x( 'Class', 'syntric' ),
			'add_new'               => __( 'Add New Class', 'syntric' ),
			'add_new_item'          => __( 'Add New Class', 'syntric' ),
			'new_item'              => __( 'New Class', 'syntric' ),
			'edit_item'             => __( 'Edit Class', 'syntric' ),
			'view_item'             => __( 'View Class', 'syntric' ),
			'all_items'             => __( 'All Classes', 'syntric' ),
			'search_items'          => __( 'Search Classes', 'syntric' ),
			'parent_item_colon'     => __( 'Parent Classes:', 'syntric' ),
			'not_found'             => __( 'No classes found.', 'syntric' ),
			'not_found_in_trash'    => __( 'No classes found in trash.', 'syntric' ),
			'archives'              => _x( 'Class archives', 'syntric' ),
			'insert_into_item'      => _x( 'Insert into class', 'syntric' ),
			'uploaded_to_this_item' => _x( 'Uploaded to this class', 'syntric' ),
			'filter_items_list'     => _x( 'Filter classes list', 'syntric' ),
			'items_list_navigation' => _x( 'Classes list navigation', 'syntric' ),
			'items_list'            => _x( 'Classes list', 'syntric' ),
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
			'rest_base'         => 'classes',
			'can_export'        => true,
			'show_ui'           => true,
			'show_in_menu'      => true,
			'show_in_nav_menus' => true,
			'show_in_admin_bar' => false,
			'menu_icon'         => 'dashicons-class-alt',
			'query_var'         => true,
			'rewrite'           => [
				'slug'       => 'classes',
				'with_front' => true,
			],
			'redirect'          => true,
		];
		register_post_type( 'syntric_class', $args );
	}