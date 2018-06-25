<?php
	if ( function_exists( 'ac_register_columns' ) ) {
		add_action( 'init', 'syn_admin_columns' );
		function syn_admin_columns() {
			ac_register_columns( 'post', array(
				array(
					'columns' => array(
						'5ade81ebc311e' => array(
							'type' => 'column-featured_image',
							'label' => 'F/I',
							'width' => '55',
							'width_unit' => 'px',
							'image_size' => 'icon',
							'image_size_w' => '60',
							'image_size_h' => '60',
							'edit' => 'on',
							'sort' => 'off',
							'filter' => 'off',
							'filter_label' => '',
							'name' => '5ade81ebc311e'
						),
						'title' => array(
							'type' => 'title',
							'label' => 'Title',
							'width' => '',
							'width_unit' => '%',
							'edit' => 'off',
							'sort' => 'on',
							'name' => 'title'
						),
						'categories' => array(
							'type' => 'categories',
							'label' => 'Category',
							'width' => '',
							'width_unit' => 'px',
							'edit' => 'on',
							'enable_term_creation' => 'off',
							'sort' => 'on',
							'filter' => 'on',
							'name' => 'categories',
							'filter_label' => ''
						),
						'author' => array(
							'type' => 'author',
							'label' => 'Owner',
							'width' => '',
							'width_unit' => '%',
							'edit' => 'off',
							'sort' => 'on',
							'name' => 'author'
						),
						'5ac1849f2afa2' => array(
							'type' => 'column-date_published',
							'label' => 'Published',
							'width' => '120',
							'width_unit' => 'px',
							'date_format' => 'diff',
							'edit' => 'off',
							'sort' => 'on',
							'filter' => 'off',
							'filter_label' => '',
							'filter_format' => '',
							'name' => '5ac1849f2afa2'
						),
						'5acf4ae5c4dfb' => array(
							'type' => 'column-acf_field',
							'label' => 'ACF Category',
							'width' => '',
							'width_unit' => '%',
							'name' => '5acf4ae5c4dfb',
							'field' => ''
						),
						'5acf4ae5c9c6d' => array(
							'type' => 'column-acf_field',
							'label' => 'ACF Microblog',
							'width' => '',
							'width_unit' => '%',
							'name' => '5acf4ae5c9c6d',
							'field' => ''
						)
					),
					'layout' => array(
						'id' => '5ac1f627258ef',
						'name' => 'Imported',
						'roles' => false,
						'users' => false,
						'read_only' => false
					)

				)
			) );
			ac_register_columns( 'page', array(
				array(
					'columns' => array(
						'title' => array(
							'type' => 'title',
							'label' => 'Title',
							'width' => '',
							'width_unit' => '%',
							'edit' => 'off',
							'sort' => 'on',
							'name' => 'title'
						),
						'5abeb1739e916' => array(
							'type' => 'column-acf_field',
							'label' => 'Calendar',
							'width' => '150',
							'width_unit' => 'px',
							'field' => 'field_59b720b6e759d',
							'edit' => 'off',
							'sort' => 'off',
							'filter' => 'off',
							'filter_label' => 'Ca',
							'name' => '5abeb1739e916'
						),
						'5abeb21116016' => array(
							'type' => 'column-acf_field',
							'label' => 'Microblog',
							'width' => '150',
							'width_unit' => 'px',
							'field' => 'field_59ab366c21327',
							'edit' => 'off',
							'sort' => 'off',
							'filter' => 'off',
							'filter_label' => 'Mb',
							'name' => '5abeb21116016'
						),
						'5abeb21117e5b' => array(
							'type' => 'column-acf_field',
							'label' => 'Video/Playlist',
							'width' => '150',
							'width_unit' => 'px',
							'field' => 'field_5a41c16e2a64d',
							'edit' => 'off',
							'sort' => 'off',
							'filter' => 'off',
							'filter_label' => 'Vi',
							'name' => '5abeb21117e5b'
						)
					),
					'layout' => array(
						'id' => '5af41d7283d6c',
						'name' => 'Teacher',
						'roles' => array( 'author' ),
						'users' => false,
						'read_only' => false
					)

				),
				array(
					'columns' => array(
						'title' => array(
							'type' => 'title',
							'label' => 'Title',
							'width' => '',
							'width_unit' => '%',
							'edit' => 'off',
							'sort' => 'on',
							'name' => 'title'
						),
						'5abeb1739663d' => array(
							'type' => 'column-page_template',
							'label' => 'Page Template',
							'width' => '',
							'width_unit' => '%',
							'edit' => 'off',
							'sort' => 'on',
							'filter' => 'on',
							'filter_label' => 'Page Template',
							'name' => '5abeb1739663d'
						),
						'author' => array(
							'type' => 'author',
							'label' => 'Owner',
							'width' => '',
							'width_unit' => '%',
							'edit' => 'off',
							'sort' => 'on',
							'name' => 'author'
						),
						'5abeb1739a2c7' => array(
							'type' => 'column-acf_field',
							'label' => 'Co',
							'width' => '25',
							'width_unit' => 'px',
							'field' => 'field_59ab362921325',
							'edit' => 'off',
							'sort' => 'off',
							'filter' => 'on',
							'filter_label' => 'Co',
							'name' => '5abeb1739a2c7'
						),
						'5abeb1739d954' => array(
							'type' => 'column-acf_field',
							'label' => 'Ro',
							'width' => '25',
							'width_unit' => 'px',
							'field' => 'field_59ab36d12132b',
							'edit' => 'off',
							'sort' => 'off',
							'filter' => 'on',
							'filter_label' => 'Ro',
							'name' => '5abeb1739d954'
						),
						'5abeb1739e916' => array(
							'type' => 'column-acf_field',
							'label' => 'Ca',
							'width' => '25',
							'width_unit' => 'px',
							'field' => 'field_59b720b6e759d',
							'edit' => 'off',
							'sort' => 'off',
							'filter' => 'on',
							'filter_label' => 'Ca',
							'name' => '5abeb1739e916'
						),
						'5abeb21116016' => array(
							'type' => 'column-acf_field',
							'label' => 'Mb',
							'width' => '25',
							'width_unit' => 'px',
							'field' => 'field_59ab366c21327',
							'edit' => 'off',
							'sort' => 'off',
							'filter' => 'on',
							'filter_label' => 'Mb',
							'name' => '5abeb21116016'
						),
						'5abeb21116f69' => array(
							'type' => 'column-acf_field',
							'label' => 'GM',
							'width' => '25',
							'width_unit' => 'px',
							'field' => 'field_5a41c0a82a649',
							'edit' => 'off',
							'sort' => 'off',
							'filter' => 'on',
							'filter_label' => 'GM',
							'name' => '5abeb21116f69'
						),
						'5abeb21117e5b' => array(
							'type' => 'column-acf_field',
							'label' => 'Vi',
							'width' => '25',
							'width_unit' => 'px',
							'field' => 'field_5a41c16e2a64d',
							'edit' => 'off',
							'sort' => 'off',
							'filter' => 'on',
							'filter_label' => 'Vi',
							'name' => '5abeb21117e5b'
						)
					),
					'layout' => array(
						'id' => '5ac1f6276cad5',
						'name' => 'Editor',
						'roles' => array( 'administrator',
						                  'editor' ),
						'users' => false,
						'read_only' => false
					)

				)
			) );
			ac_register_columns( 'tinymcetemplates', array(
				array(
					'columns' => array(
						'5abcb001ba50e' => array(
							'type' => 'column-title_raw',
							'label' => 'Title',
							'width' => '',
							'width_unit' => '%',
							'post_link_to' => 'edit_post',
							'edit' => 'on',
							'sort' => 'on',
							'name' => '5abcb001ba50e'
						),
						'5abcab68da79c' => array(
							'type' => 'column-content',
							'label' => 'Content',
							'width' => '75',
							'width_unit' => '%',
							'string_limit' => '',
							'before' => '',
							'after' => '',
							'edit' => 'on',
							'sort' => 'off',
							'filter' => 'off',
							'filter_label' => '',
							'name' => '5abcab68da79c'
						),
						'5ad333be90f58' => array(
							'type' => 'author',
							'label' => 'Owner',
							'width' => '',
							'width_unit' => '%',
							'edit' => 'off',
							'sort' => 'on',
							'name' => '5ad333be90f58'
						)
					),
					'layout' => array(
						'id' => '5ac1f627870c5',
						'name' => 'Imported',
						'roles' => false,
						'users' => false,
						'read_only' => false
					)

				)
			) );
			ac_register_columns( 'syn_calendar', array(
				array(
					'columns' => array(
						'title' => array(
							'type' => 'title',
							'label' => 'Title',
							'width' => '',
							'width_unit' => '%',
							'edit' => 'off',
							'sort' => 'on',
							'name' => 'title'
						),
						'5ac1c5d9c60b2' => array(
							'type' => 'column-acf_field',
							'label' => 'Google Calendar ID',
							'width' => '',
							'width_unit' => '%',
							'field' => 'field_59b80aa817de3',
							'character_limit' => '100',
							'edit' => 'off',
							'sort' => 'on',
							'filter' => 'off',
							'filter_label' => '',
							'name' => '5ac1c5d9c60b2'
						),
						'5ac1c5d9cdfeb' => array(
							'type' => 'column-author_name',
							'label' => 'Owner',
							'width' => '',
							'width_unit' => '%',
							'display_author_as' => 'display_name',
							'user_link_to' => '',
							'edit' => 'off',
							'sort' => 'on',
							'filter' => 'on',
							'filter_label' => 'Author/Owner',
							'name' => '5ac1c5d9cdfeb'
						),
						'5ac1c5d9d14df' => array(
							'type' => 'column-meta',
							'label' => 'Last Sync',
							'width' => '150',
							'width_unit' => 'px',
							'field' => 'syn_calendar_last_sync',
							'field_type' => 'date',
							'date_format' => 'diff',
							'before' => '',
							'after' => '',
							'edit' => 'off',
							'sort' => 'on',
							'filter' => 'off',
							'filter_label' => '',
							'name' => '5ac1c5d9d14df'
						)
					),
					'layout' => array(
						'id' => '5ac1f627925b7',
						'name' => 'Imported',
						'roles' => false,
						'users' => false,
						'read_only' => false
					)

				)
			) );
			ac_register_columns( 'syn_event', array(
				array(
					'columns' => array(
						'title' => array(
							'type' => 'title',
							'label' => 'Title',
							'width' => '',
							'width_unit' => '%',
							'edit' => 'off',
							'sort' => 'on',
							'name' => 'title'
						),
						'5ac1c6c389087' => array(
							'type' => 'column-acf_field',
							'label' => 'Start Date',
							'width' => '150',
							'width_unit' => 'px',
							'field' => 'field_590b73c246359',
							'date_format' => 'F j, Y',
							'edit' => 'off',
							'sort' => 'on',
							'filter' => 'off',
							'filter_label' => '',
							'filter_format' => '',
							'name' => '5ac1c6c389087'
						),
						'5ac1c6c38eb37' => array(
							'type' => 'column-acf_field',
							'label' => 'End Date',
							'width' => '150',
							'width_unit' => 'px',
							'field' => 'field_590b74004635b',
							'date_format' => 'F j, Y',
							'edit' => 'off',
							'sort' => 'on',
							'filter' => 'off',
							'filter_label' => '',
							'filter_format' => '',
							'name' => '5ac1c6c38eb37'
						),
						'5ac1c6c331e6b' => array(
							'type' => 'column-meta',
							'label' => 'Calendar',
							'width' => '',
							'width_unit' => '%',
							'field' => 'syn_event_calendar_id',
							'field_type' => 'title_by_id',
							'post_property_display' => 'title',
							'post_link_to' => 'edit_post',
							'before' => '',
							'after' => '',
							'edit' => 'off',
							'sort' => 'on',
							'filter' => 'on',
							'filter_label' => 'Calendar',
							'name' => '5ac1c6c331e6b'
						)
					),
					'layout' => array(
						'id' => '5ac1f627a2517',
						'name' => 'Imported',
						'roles' => false,
						'users' => false,
						'read_only' => false
					)

				)
			) );
			/*ac_register_columns( 'wp-media', array(
				array(
					'columns' => array(
						'title' => array(
							'type' => 'title',
							'label' => 'Title & Alt Text',
							'width' => '',
							'width_unit' => '%',
							'edit' => 'on',
							'sort' => 'on',
							'name' => 'title'
						),
						'5ac186c82a24c' => array(
							'type' => 'column-caption',
							'label' => 'Caption',
							'width' => '',
							'width_unit' => '%',
							'edit' => 'on',
							'sort' => 'on',
							'name' => '5ac186c82a24c'
						),
						'5ac189a81eec1' => array(
							'type' => 'column-description',
							'label' => 'Description',
							'width' => '',
							'width_unit' => '%',
							'string_limit' => '',
							'before' => '',
							'after' => '',
							'edit' => 'on',
							'sort' => 'on',
							'filter' => 'off',
							'filter_label' => '',
							'name' => '5ac189a81eec1'
						),
						'taxonomy-media_category' => array(
							'type' => 'taxonomy-media_category',
							'label' => 'Categories',
							'width' => '',
							'width_unit' => '%',
							'edit' => 'on',
							'enable_term_creation' => 'on',
							'sort' => 'on',
							'filter' => 'on',
							'filter_label' => 'Categories',
							'name' => 'taxonomy-media_category'
						),
						'author' => array(
							'type' => 'author',
							'label' => 'Owner',
							'width' => '',
							'width_unit' => '%',
							'edit' => 'off',
							'sort' => 'on',
							'filter' => 'on',
							'filter_label' => 'Owner',
							'name' => 'author'
						),
						'parent' => array(
							'type' => 'parent',
							'label' => 'Attached to',
							'width' => '',
							'width_unit' => '%',
							'sort' => 'on',
							'filter' => 'off',
							'filter_label' => '',
							'name' => 'parent'
						),
						'5ac187d55f0ce' => array(
							'type' => 'column-dimensions',
							'label' => 'Dimensions',
							'width' => '120',
							'width_unit' => 'px',
							'before' => '',
							'after' => '',
							'sort' => 'on',
							'name' => '5ac187d55f0ce'
						),
						'5ac18bbf2c850' => array(
							'type' => 'column-available_sizes',
							'label' => 'Sizes',
							'width' => '110',
							'width_unit' => 'px',
							'include_missing_sizes' => '1',
							'sort' => 'off',
							'name' => '5ac18bbf2c850'
						),
						'5ac1a2d1370ff' => array(
							'type' => 'column-mime_type',
							'label' => 'Mime Type',
							'width' => '150',
							'width_unit' => 'px',
							'edit' => 'off',
							'sort' => 'on',
							'filter' => 'off',
							'filter_label' => 'Mime Type',
							'name' => '5ac1a2d1370ff'
						),
						'5acfebc4980a1' => array(
							'type' => 'column-file_name',
							'label' => 'Filename',
							'width' => '',
							'width_unit' => '%',
							'sort' => 'on',
							'name' => '5acfebc4980a1'
						),
						'5acfebc498ab8' => array(
							'type' => 'column-taxonomy',
							'label' => 'Folder',
							'width' => '',
							'width_unit' => '%',
							'taxonomy' => 'wpmf-category',
							'edit' => 'off',
							'sort' => 'on',
							'filter' => 'off',
							'filter_label' => '',
							'name' => '5acfebc498ab8',
							'enable_term_creation' => 'off'
						)
					),
					'layout' => array(
						'id' => '5ac1f627b1152',
						'name' => 'Imported',
						'roles' => false,
						'users' => false,
						'read_only' => false
					)

				)
			) );*/
			ac_register_columns( 'wp-users', array(
				array(
					'columns' => array(
						'5ac1cadc9a0b4' => array(
							'type' => 'column-first_name',
							'label' => 'First Name',
							'width' => '',
							'width_unit' => 'px',
							'edit' => 'on',
							'sort' => 'on',
							'filter' => 'off',
							'filter_label' => '',
							'name' => '5ac1cadc9a0b4'
						),
						'5ac1cadc9ac48' => array(
							'type' => 'column-last_name',
							'label' => 'Last Name',
							'width' => '',
							'width_unit' => '%',
							'edit' => 'on',
							'sort' => 'on',
							'filter' => 'off',
							'filter_label' => '',
							'name' => '5ac1cadc9ac48'
						),
						'5ac1cbcf304a0' => array(
							'type' => 'column-acf_field',
							'label' => 'Prefix',
							'width' => '100',
							'width_unit' => 'px',
							'field' => 'field_5a6570eff3d03',
							'edit' => 'on',
							'sort' => 'on',
							'filter' => 'on',
							'filter_label' => 'Prefix',
							'name' => '5ac1cbcf304a0'
						),
						'5abe434f71334' => array(
							'type' => 'column-acf_field',
							'label' => 'Title',
							'width' => '',
							'width_unit' => '%',
							'field' => 'field_59c3de9804864',
							'character_limit' => '100',
							'edit' => 'on',
							'sort' => 'on',
							'filter' => 'off',
							'filter_label' => '',
							'name' => '5abe434f71334'
						),
						'email' => array(
							'type' => 'email',
							'label' => 'Email',
							'width' => '',
							'width_unit' => '%',
							'edit' => 'on',
							'sort' => 'on',
							'filter' => 'off',
							'filter_label' => '',
							'name' => 'email'
						),
						'5ac1cadc9e591' => array(
							'type' => 'column-acf_field',
							'label' => 'Phone',
							'width' => '150',
							'width_unit' => 'px',
							'field' => 'field_59c3de9804c7e',
							'character_limit' => '20',
							'edit' => 'on',
							'sort' => 'on',
							'filter' => 'off',
							'filter_label' => '',
							'name' => '5ac1cadc9e591'
						),
						'5ac1cadc9f61e' => array(
							'type' => 'column-acf_field',
							'label' => 'Ext',
							'width' => '100',
							'width_unit' => 'px',
							'field' => 'field_59c3de9804e7c',
							'character_limit' => '10',
							'edit' => 'on',
							'sort' => 'on',
							'filter' => 'off',
							'filter_label' => '',
							'name' => '5ac1cadc9f61e'
						),
						'role' => array(
							'type' => 'role',
							'label' => 'Role',
							'width' => '140',
							'width_unit' => 'px',
							'edit' => 'on',
							'sort' => 'on',
							'filter' => 'on',
							'filter_label' => 'Role',
							'name' => 'role'
						),
						'5abe434f78214' => array(
							'type' => 'column-acf_field',
							'label' => 'Teacher?',
							'width' => '100',
							'width_unit' => 'px',
							'field' => 'field_59c3de980525e',
							'edit' => 'on',
							'sort' => 'on',
							'filter' => 'off',
							'filter_label' => 'Teacher?',
							'name' => '5abe434f78214'
						),
						'5ace844dd652e' => array(
							'type' => 'column-meta',
							'label' => 'Teacher Page',
							'width' => '',
							'width_unit' => '%',
							'field' => 'syn_user_page',
							'field_type' => 'title_by_id',
							'post_property_display' => 'title',
							'post_link_to' => 'edit_post',
							'before' => '',
							'after' => '',
							'edit' => 'off',
							'sort' => 'on',
							'filter' => 'off',
							'filter_label' => '',
							'name' => '5ace844dd652e'
						)
					),
					'layout' => array(
						'id' => '5ac1f627c7b0f',
						'name' => 'Imported',
						'roles' => false,
						'users' => false,
						'read_only' => false
					)

				)
			) );
			ac_register_columns( 'wp-taxonomy_category', array(
				array(
					'columns' => array(
						'name' => array(
							'type' => 'name',
							'label' => 'Name',
							'width' => '',
							'width_unit' => '%',
							'edit' => 'off',
							'sort' => 'on',
							'name' => 'name'
						),
						'5ad34aa37435d' => array(
							'type' => 'column-acf_field',
							'label' => 'Home Page',
							'width' => '',
							'width_unit' => '%',
							'field' => 'field_5ad3454acb6a2',
							'post_property_display' => 'title',
							'post_link_to' => 'edit_post',
							'edit' => 'off',
							'name' => '5ad34aa37435d'
						),
						'posts' => array(
							'type' => 'posts',
							'label' => 'Posts',
							'width' => '',
							'width_unit' => '%',
							'sort' => 'on',
							'name' => 'posts'
						)
					),
					'layout' => array(
						'id' => '5ac1f627e3b30',
						'name' => 'Imported',
						'roles' => false,
						'users' => false,
						'read_only' => false
					)

				)
			) );
			ac_register_columns( 'wp-taxonomy_post_tag', array(
				array(
					'columns' => array(
						'name' => array(
							'type' => 'name',
							'label' => 'Name',
							'width' => '',
							'width_unit' => '%',
							'edit' => 'on',
							'sort' => 'on',
							'name' => 'name'
						),
						'slug' => array(
							'type' => 'slug',
							'label' => 'Slug',
							'width' => '',
							'width_unit' => '%',
							'edit' => 'on',
							'sort' => 'on',
							'name' => 'slug'
						),
						'posts' => array(
							'type' => 'posts',
							'label' => 'Posts',
							'width' => '',
							'width_unit' => '%',
							'sort' => 'on',
							'name' => 'posts'
						)
					),
					'layout' => array(
						'id' => '5ac1f627ec9d7',
						'name' => 'Imported',
						'roles' => false,
						'users' => false,
						'read_only' => false
					)

				)
			) );
			ac_register_columns( 'wp-taxonomy_media_category', array(
				array(
					'columns' => array(
						'name' => array(
							'type' => 'name',
							'label' => 'Name',
							'width' => '',
							'width_unit' => '%',
							'edit' => 'on',
							'sort' => 'on',
							'name' => 'name'
						),
						'slug' => array(
							'type' => 'slug',
							'label' => 'Slug',
							'width' => '',
							'width_unit' => '%',
							'edit' => 'on',
							'sort' => 'on',
							'name' => 'slug'
						),
						'posts' => array(
							'type' => 'posts',
							'label' => 'Posts',
							'width' => '',
							'width_unit' => '%',
							'sort' => 'on',
							'name' => 'posts'
						)
					),
					'layout' => array(
						'id' => '5ac1f627f3909',
						'name' => 'Imported',
						'roles' => false,
						'users' => false,
						'read_only' => false
					)

				)
			) );
		}
	}
	/**
	 * Fires after a inline-edit saved a value
	 *
	 * @param AC_Column $column Column instance
	 * @param int       $id     Item ID
	 * @param string    $value  User submitted input
	 */
	add_action( 'acp/editing/saved', 'syn_admin_columns_inline_save', 10, 3 );
	function syn_admin_columns_inline_save( $column, $id, $value ) {
		$field_key        = $column->get_option( 'field' );
		$is_teacher_field = get_field_object( 'syn_user_is_teacher', 'user_' . $id );
		if ( $is_teacher_field[ 'key' ] == $field_key ) {
			syn_do_teacher_page( $id );
		}
	}


	/**
	 * Called when a column is saved, but the saving is not handled by Admin Columns core
	 * This should be used for saving columns that are editable but do not have their own AC_Column class
	 * The first parameter, $result, should only be used if an error occurs
	 *
	 * Filter acp/editing/save
	 * Filter acp/editing/save/{$column_type}
	 *
	 * @param WP_Error          $result         Result of saving
	 * @param AC_Column         $column         Column object
	 * @param int               $object_id      ID of item to be saved
	 * @param mixed             $value          Value to be saved
	 * @param ACP_Editing_Model $editable_model Editability storage model
	 */
	/*add_filter( 'acp/editing/save', 'syn_admin_columns_save', 10, 5 );
	//add_filter( 'acp/editing/save/{$column_type}', 'my_acp_editing_save_value', 10, 5 );
	function syn_admin_columns_save( $result, $column, $object_id, $value ) {
		slog($result); // 1
		slog($column); // 230
		slog($object_id); // 0
		slog($value); //
		//slog($editable_model);
		// Check for specific $column or $editable_model
		// Save the $value for a specific $object_id and pass the $result

		return $result;
	}*/