<?php
	/**
	 * Microblog
	 *
	 * Microblogs are attached to a page and use a custom taxonomy. A microblog should not
	 * exist outside of being attached to a page.
	 *
	 * todo: don't allow microblog taxonomy to be deleted except if a page is deleted
	 * todo: delete posts and taxonomy if aa page is deleted.
	 * todo: don't display microblog posts (even in on home/archive/etc if microblog is not enabled on it's page (it can exist but not be enabled)
	 * todo: create an "orphaned" or "unassociated" term for Microblog taxonomy to collect abandoned posts
	 * todo: auto-create category "Microblogs" as part of the taxonomomy registion below
	 *
	 */
	/**
	 * Register Microblog taxonomy for page microblogs
	 *
	 */
	//add_action( 'init', 'syn_register_microblog_taxonomy' );
	function syn_register_microblog_taxonomy() {
		// Add new taxonomy, make it hierarchical (like categories)
		$tax_args = [
			'labels'             => [
				'name'              => _x( 'Microblogs', 'syntric' ),
				'singular_name'     => _x( 'Microblog', 'syntric' ),
				'search_items'      => __( 'Search Microblogs', 'syntric' ),
				'all_items'         => __( 'All Microblogs', 'syntric' ),
				'parent_item'       => __( 'Parent Microblog', 'syntric' ),
				'parent_item_colon' => __( 'Parent Microblog:', 'syntric' ),
				'edit_item'         => __( 'Edit Microblog', 'syntric' ),
				'update_item'       => __( 'Update Microblog', 'syntric' ),
				'add_new_item'      => __( 'Add New Microblog', 'syntric' ),
				'new_item_name'     => __( 'New Microblog Name', 'syntric' ),
				'menu_name'         => __( 'Microblogs', 'syntric' ),
			],
			'description'        => 'Microblogs post taxonomy - retired',
			'public'             => false,
			'publicly_queryable' => false,
			'hierarchical'       => true,
			'show_ui'            => current_user_can( 'administrator' ),
			'show_in_menu'       => current_user_can( 'administrator' ),
			'show_in_nav_menus'  => false,
			'show_in_rest'       => false,
			'rest_base'          => 'microblogs',
			'show_in_quick_edit' => true,
			'show_admin_column'  => true,
			'rewrite'            => [
				'slug'       => 'microblog',
				'with_front' => true,
			],
			'query_var'          => false,
		];
		register_taxonomy( 'microblog', 'post', $tax_args );
	}

	/**
	 * Setup default categories, taxonomies and terms after 'Microblog' taxonomy is registered
	 *
	 * News should be default category with ID of 1
	 */
	add_action( 'registered_taxonomy', 'syn_setup_terms', 10, 3 );
	function syn_setup_terms( $taxonomy, $object_type, $args ) {
		//slog($taxonomy);
		//slog($object_type);
		//slog($args);
		/*if ( 'microblog' == $taxonomy ) {
			$uncat_term_tax_ids = term_exists( 'uncategorized', 'category' );
			$uncat_exists       = ( $uncat_term_tax_ids ) ? true : false;
			$uncat_is_default   = ( $uncat_exists && 1 == $uncat_term_tax_ids[ 'term_id' ] ) ? true : false;
			$news_term_tax_ids  = term_exists( 'news', 'category' );
			$news_exists        = ( $news_term_tax_ids ) ? true : false;
			$new_is_default     = ( $news_exists && 1 == $news_term_tax_ids[ 'term_id' ] ) ? true : false;
			if ( ! $uncat_is_default && ! $new_is_default ) {
				$default_term     = get_term_by( 'id', 1, 'category', OBJECT );
				$other_is_default = ( $default_term instanceof WP_Term ) ? true : false;
			} else {
				$other_is_default = false;
			}
			if ( $uncat_is_default ) {
				if ( $news_exists ) {
					// move news posts to default
					$news_posts = get_posts( [
						'numberposts' => - 1,
						'category'    => $news_term_tax_ids[ 'term_id' ],
					] );
					if ( $news_posts ) {
						foreach ( $news_posts as $nwsp ) {
							$set_ret = wp_set_post_categories( $nwsp->ID, [ 1 ], false );
							update_field( 'syn_post_category', 1, $nwsp->ID );
						}
					}
					// delete news category
					$del_ret = wp_delete_category( $news_term_tax_ids[ 'term_id' ] );
				}
				// rename default
				$upd_ret = wp_update_term( 1, 'category', [
					'name'   => 'News',
					'slug'   => 'news',
					'parent' => 0,
				] );
			} elseif ( $other_is_default ) {
				$other_term  = get_term_by( 'id', 1, 'category', ARRAY_A ); // array keys are term_id, name, slug, term_group, term_taxonomy_id, taxonomy, description, parent
				$other_term_ = wp_insert_term( $other_term[ 'name' ], 'category', [
					'slug'        => $other_term[ 'slug' ],
					'parent'      => $other_term[ 'parent' ],
					'description' => $other_term[ 'description' ],
				] ); // Returns array with term_id and term_taxonomy_id
				$other_posts = get_posts( [
					'numberposts' => - 1,
					'category'    => 1,
				] );
				if ( $other_posts ) {
					$cat_arr   = [];
					$cat_arr[] = $other_term_[ 'term_id' ];
					foreach ( $other_posts as $othp ) {
						$set_ret = wp_set_post_categories( $othp->ID, $cat_arr, false );
						update_field( 'syn_post_category', $other_term_[ 'term_id' ], $othp->ID );
					}
				}
				if ( $news_exists ) {
					// move news posts to default
					$news_posts = get_posts( [
						'numberposts' => - 1,
						'category'    => $news_term_tax_ids[ 'term_id' ],
					] );
					if ( $news_posts ) {
						foreach ( $news_posts as $nwsp ) {
							$set_ret = wp_set_post_categories( $nwsp->ID, [ 1 ], false );
							update_field( 'syn_post_category', 1, $nwsp->ID );
						}
					}
					// delete news category
					$del_ret = wp_delete_term( 'news', 'category' );
				}
				// rename default
				$upd_ret = wp_update_term( 1, 'category', [
					'name'   => 'News',
					'slug'   => 'news',
					'parent' => 0,
				] );
			}
		}*/
	}

	/**
	 * Save page microblog widget.
	 *
	 * If active...
	 *
	 * 1. Verify/create Microblogs category
	 * 2. Verify/create category for microblog under Microblogs category
	 *      Category should be named as the breadcrumb for this page with right arrow separators
	 * 3. Update category syn_category_page to this page
	 * 4. Update page syn_microblog_category (message) field
	 * 5. Check if a new post is part of the submission and if so, save the post in this microblog
	 *
	 * If not active...
	 *
	 * 1. Check a category for this page
	 * 2. If there is a category, delete all it's posts (should prompt if there are todo: prompt for post deletion here)
	 * 3. Delete category associated with this page
	 * 4. Delete all microblog page widget fields for this page
	 *
	 * @param $post_id
	 *
	 * @return void
	 */
	function syn_save_page_microblog( $post_id ) {
		$post_id          = syn_resolve_post_id( $post_id );
		$microblog_active = get_field( 'syn_microblog_active', $post_id );
		if ( $microblog_active ) {
			syn_get_page_microblog_category( $post_id );
		}
		/*slog( $microblog_category );
			return;

			$new_microblog_post = get_field( 'syn_new_microblog_post', $post_id );
			if ( $new_microblog_post ) {
				$post_title   = get_field( 'syn_new_microblog_post_title', $post_id );
				$post_name    = syn_sluggify( $post_title );
				$post_content = get_field( 'syn_new_microblog_post_content', $post_id );
				$category     = get_field( 'syn_microblog_category', $post_id );
				$term         = get_field( 'syn_microblog_term', $post_id );
				slog( $term );
				// todo: post_author should be set to teacher if is a teacher or class page microblog post, not current user id
				$author        = get_current_user_id();
				$page_template = strtolower( syn_get_page_template( $post_id ) );
				if ( 'teacher' == $page_template ) {
					$author = get_field( 'syn_page_teacher', $post_id );
				} elseif ( 'class' == $page_template ) {
					$author = get_field( 'syn_page_class_teacher', $post_id );
				}
				$post_args         = [
					'post_type'     => 'post',
					'post_title'    => $post_title,
					'post_name'     => $post_name,
					// slug
					'post_status'   => 'publish',
					'post_author'   => $author,
					'post_content'  => $post_content,
					'post_category' => [ $category->term_id ],
					'tax_input'     => [ 'microblog' => [ $term->term_id ] ],
				];
				$microblog_post_id = wp_insert_post( $post_args );
				update_field( 'syn_post_category', $category->term_id, $microblog_post_id );
				update_field( 'syn_post_microblog', $term->term_id, $microblog_post_id );
			}
			update_field( 'syn_new_microblog_post', '', $post_id );
			update_field( 'syn_new_microblog_post_title', '', $post_id );
			update_field( 'syn_new_microblog_post_content', '', $post_id );*/
	}

	/**
	 * Get Microblogs category, create if it doesn't exist
	 */
	function syn_get_microblogs_category() {
		$microblogs_categories = get_terms( [
			'taxonomy' => 'category',
			'name'     => 'Microblogs',
			'parent'   => 0,
		] );
		if ( 0 == count( $microblogs_categories ) ) {
			$microblogs_category_id = wp_create_category( 'Microblogs', 0 );
			$microblogs_category    = get_term( $microblogs_category_id );
		} elseif ( 1 == count( $microblogs_categories ) ) {
			$microblogs_category = $microblogs_categories[ 0 ];
		} else {
			// error condition if there are more than one Microblogs category with parent = 0
		}

		return $microblogs_category;
	}

	/**
	 * Get a page's microblog category (under Microblogs), create if it doesn't exist.
	 * Also takes care of checking for and creating the Microblogs category (parent = 0)
	 */
	function syn_get_page_microblog_category( $post_id ) {
		$post_id             = syn_resolve_post_id( $post_id );
		$post                = get_post( $post_id );
		$microblogs_category = syn_get_microblogs_category();
		$microblog_category  = get_terms( [
			'taxonomy'   => 'category',
			'parent'     => $microblogs_category->term_id,
			'meta_key'   => 'syn_category_page',
			'meta_value' => $post_id,
		] );
		if ( 0 == count( $microblog_category ) ) {
			$ancestor_ids = array_reverse( get_post_ancestors( $post_id ) );
			$titles       = '';
			//$slugs        = '';
			foreach ( $ancestor_ids as $ancestor_id ) {
				$titles .= get_the_title( $ancestor_id ) . ' > ';
				//$slugs  .= get_post_field( 'post_name', $ancestor_id ) . '-';
			}
			$titles .= $post->post_title;
			//$slugs                 .= $post->post_name;
			$microblog_category_id = wp_create_category( $titles, $microblogs_category->term_id );
			update_field( 'syn_category_page', $post_id, 'category_' . $microblog_category_id );
			$microblog_category    = get_term( $microblog_category_id );
		} elseif ( 1 == count( $microblog_category ) ) {
			$microblog_category = $microblog_category[ 0 ];
		} else {
			// error condition if there are more than one Microblogs category with parent = 0
		}

		return $microblog_category;
	}

	/**
	 * Get microblog categories (categories with Microblogs as a parent)
	 *
	 * @return array
	 */
	function syn_get_microblog_categories() {
		$microblogs_cat = syn_get_microblogs_category();
		$categories     = get_categories( [ 'parent' => $microblogs_cat->term_id, 'hide_empty' => 0 ] );

		return $categories;
	}

	function syn_get_page_microblogs() {
		$args            = [
			'numberposts'  => - 1,
			'post_type'    => 'page',
			'post_status'  => [
				'publish',
				'pending',
				'draft',
				'future',
			],
			'meta_key'     => 'syn_microblog_active',
			'meta_value'   => 1,
			'meta_compare' => '=',
		];
		$microblog_pages = get_posts( $args );

		return $microblog_pages;
	}

	function syn_get_user_microblog_pages( $user_id ) {
		$args                 = [
			'numberposts'  => - 1,
			'post_type'    => 'page',
			'author'       => $user_id,
			'post_status'  => [
				'publish',
				'pending',
				'draft',
				'future',
			],
			'meta_key'     => 'syn_microblog_active',
			'meta_value'   => 1,
			'meta_compare' => '=',
		];
		$user_microblog_pages = get_posts( $args );

		return $user_microblog_pages;
	}

	function syn_get_microblog_title( $category_id ) {
		$post = get_field( 'syn_category_page', 'category_' . $category_id ); // WP_Post
		$ancestor_ids = array_reverse( get_post_ancestors( $post->ID ) );
		$titles       = '';
		foreach ( $ancestor_ids as $ancestor_id ) {
			$titles .= get_the_title( $ancestor_id ) . ' > ';
		}
		$titles .= $post->post_title;
	}



