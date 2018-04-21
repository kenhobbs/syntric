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
	add_action( 'init', 'syn_register_microblog_taxonomy' );
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
		if ( 'microblog' == $taxonomy ) {
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
		}
	}

	/**
	 * Get microblog tax term (or create it) and assign to microblog on save post (page)
	 */
	//add_action( 'acf/save_post', 'syn_save_microblog', 20 );
	function ___syn_save_microblog( $post_id ) {
		global $pagenow;
		// don't save for autosave
		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
			return;
		}
		$post_id = syn_resolve_post_id( $post_id );
		$post    = get_post( $post_id );
		// todo: reduce all the "security and validity" conditional wrappers, like the next line, into a function call eg. syn_continue_if('admin','page','teacher_template')...
		if ( is_admin() && $post instanceof WP_Post && 'page' == $post->post_type && isset( $_REQUEST[ 'acf' ] ) && ! wp_is_post_revision( $post->ID ) ) {
			// set term in taxonomy "microblog"
			$microblog_active = get_field( 'syn_microblog_active', $post->ID );
			if ( $microblog_active ) {
				if ( category_exists( 'Microblogs' ) ) {
					$cat    = get_category_by_slug( 'microblogs' );
					$cat_id = $cat->cat_ID;
				} else {
					$cat_id = wp_insert_category( [ 'cat_name' => 'Microblogs' ] );
				}
				$ancestor_ids = array_reverse( get_post_ancestors( $post->ID ) );
				$titles       = '';
				$slugs        = '';
				foreach ( $ancestor_ids as $ancestor_id ) {
					$titles .= get_the_title( $ancestor_id ) . ' > ';
					$slugs  .= get_post_field( 'post_name', $ancestor_id ) . '-';
				}
				$titles .= $post->post_title;
				$slugs  .= $post->post_name;
				if ( term_exists( $slugs, 'microblog' ) ) {
					$term    = get_term_by( 'slug', $slugs, 'microblog' );
					$term_id = $term->term_id;
				} else {
					$tax_term_ids = wp_insert_term( $titles, 'microblog', [
						'slug'        => $slugs,
						'description' => 'Microblog associated with <a href="/wp-admin/post.php?post=' . $post->ID . '&action=edit">' . $titles . '</a> page',
					] );
					$term_id      = $tax_term_ids[ 'term_id' ];
				}
				if ( is_int( $cat_id ) && is_int( $term_id ) ) {
					wp_set_post_categories( $post->ID, [ (int) $cat_id ], false );
					wp_set_post_terms( $post->ID, [ (int) $term_id ], 'microblog', false );
					//update_field( 'syn_microblog_page', $post->ID, 'microblog_' . $term_id );
				}
			}
		}
	}

	function syn_get_microblog_pages() {
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