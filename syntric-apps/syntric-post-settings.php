<?php
	add_filter( 'pre_get_posts', 'syn_filter_author_posts_list' );
	function syn_filter_author_posts_list( $query ) {
		global $pagenow;
		global $user_ID;
		if ( is_admin() && 'edit.php' == $pagenow && ! syn_current_user_can( 'editor' ) ) {
			$query->set( 'author', $user_ID );
		}

		return $query;
	}

	add_action( 'acf/save_post', 'syn_save_post', 20 );
	function syn_save_post( $post_id ) {
		global $pagenow;
		$post_id = syn_resolve_post_id( $post_id );
		// don't save for autosave
		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
			return;
		}
		if ( is_admin() && is_numeric( $post_id ) && 'post' == get_post_type( $post_id ) && isset( $_REQUEST[ 'acf' ] ) && ! wp_is_post_revision( $post_id ) ) {
			$category_id = get_field( 'syn_post_category', $post_id );
			if ( 0 == $category_id ) {
				$new_category = get_field( 'syn_post_new_category', $post_id );
				$category_id  = wp_insert_category( [ 'cat_name' => $new_category ] );
			}
			if ( $category_id ) {
				wp_set_post_categories( $post_id, [ (int) $category_id ], false );
				$microblog_category = get_category_by_slug( 'microblogs' );
				if ( $category_id == $microblog_category->cat_ID ) {
					$microblog_id = get_field( 'syn_post_microblog', $post_id );
					if ( $microblog_id ) {
						$microblog_term    = get_term_by( 'id', $microblog_id, 'microblog' );
						$microblog_term_id = $microblog_term->term_id;
					} else {
						$new_microblog_term = get_field( 'syn_post_new_microblog', $post_id );
						$microblog_term     = get_term_by( 'name', $new_microblog_term, 'microblog' );
						if ( ! $microblog_term ) {
							$microblog_term    = wp_insert_term( $new_microblog_term, 'microblog', [ 'description' => 'Microblog term created manually' ] );
							$microblog_term_id = $microblog_term[ 'term_id' ];
						} else {
							$microblog_term_id = $microblog_term->term_id;
						}
					}
					if ( $microblog_term_id ) {
						wp_set_post_terms( $post_id, [ (int) $microblog_term_id ], 'microblog', false );
						update_field( 'syn_post_category', $category_id, $post_id );
						update_field( 'syn_post_microblog', $microblog_term_id, $post_id );
						update_field( 'syn_post_new_category', '', $post_id );
						update_field( 'syn_post_new_microblog', '', $post_id );
					}
				} else {
					update_field( 'syn_post_microblog', null, $post_id );
					update_field( 'syn_post_new_category', '', $post_id );
					update_field( 'syn_post_new_microblog', '', $post_id );
				}
			}
		}
	}

// load_field filters
	add_filter( 'acf/load_field/name=syn_post_category', 'syn_load_categories' );
	add_filter( 'acf/load_field/name=syn_post_microblog', 'syn_load_microblogs' );
// prepare_field filters
	add_filter( 'acf/prepare_field/name=syn_post_category', 'syn_prepare_post_fields' );
	add_filter( 'acf/prepare_field/name=syn_post_microblog', 'syn_prepare_post_fields' );
	add_filter( 'acf/prepare_field/key=field_59e7f2f7049e2', 'syn_prepare_post_fields' ); // Category enhanced message field
	add_filter( 'acf/prepare_field/key=field_59e7f370049e3', 'syn_prepare_post_fields' ); // Microblog enhanced message field
	function syn_prepare_post_fields( $field ) {
		global $pagenow;
		global $post;
		if ( 'post.php' == $pagenow && 'post' == $post->post_type ) {
			if ( 'syn_post_category' == $field[ '_name' ] ) {
				if ( $field[ 'value' ] ) {
					$field[ 'wrapper' ][ 'class' ] = 'hidden';
				}
				if ( ! $field[ 'value' ] && ! syn_current_user_can( 'editor' ) ) {
					$microblogs_cat = get_category_by_slug( 'microblogs' );
					if ( $microblogs_cat instanceof WP_Term ) {
						$field[ 'value' ] = $microblogs_cat->term_id;
					}
				}
			}
			if ( 'syn_post_microblog' == $field[ '_name' ] ) {
				if ( $field[ 'value' ] ) {
					$field[ 'wrapper' ][ 'class' ] = 'hidden';
				}
				/*if ( ! syn_current_user_can( 'editor' ) ) {
					$choices = [];
					$user_microblogs = syn_get_user_microblogs( get_current_user_id() ); // returns get_posts
					if ( count( $user_microblogs ) ) {

					}
				}*/
			}
			if ( 'field_59e7f2f7049e2' == $field[ 'key' ] ) { // Category display
				$category_id = get_field( 'syn_post_category', $post->ID );
				if ( $category_id ) {
					$category = get_category( $category_id );
					if ( $category ) {
						$field[ 'wrapper' ][ 'class' ] = '';
						$field[ 'message' ]            = $category->name;
					}
				}
			}
			if ( 'field_59e7f370049e3' == $field[ 'key' ] ) { // Microblog display
				$microblog_id = get_field( 'syn_post_microblog', $post->ID );

				if ( $microblog_id ) {
					$microblog = get_term( $microblog_id );
					if ( $microblog ) {
						$field[ 'wrapper' ][ 'class' ] = '';
						$field[ 'message' ]            = $microblog->name;
					}
				}
			}
		}

		return $field;
	}

	function syn_post_badges( $post_id = null ) {
		$post_id = syn_resolve_post_id( $post_id );
		$post    = get_post( $post_id );
		switch ( $post->post_type ) {
			case 'syn_event' :
				$calendar = get_the_title( get_field( 'syn_event_calendar_id', get_the_ID() ) );
				echo '<span class="badge badge-pill badge-secondary">' . $calendar . '</span>';
				break;
			case 'post' :
				echo '<span class="badge badge-pill badge-secondary">' . syn_get_taxonomies_terms() . '</span>';
				break;
		}
	}

	function syn_excerpt_badges( $post_id = null ) {
		$post_id = syn_resolve_post_id( $post_id );
		$post    = get_post( $post_id );
		switch ( $post->post_type ) {
			case 'syn_event' :
				$calendar = get_the_title( get_field( 'syn_event_calendar_id', get_the_ID() ) );
				echo '<span class="badge badge-pill badge-dark">' . $calendar . '</span>';
				break;
			case 'post' :
				echo '<span class="badge badge-pill badge-dark">' . syn_get_taxonomies_terms() . '</span>';
				break;
		}
	}

	function syn_resolve_post_id( $post_id ) {
		global $post;
		if ( null == $post_id ) {
			return $post->ID;
		} elseif ( $post_id instanceof WP_Post ) {
			return $post_id->ID;
		} elseif ( is_numeric( $post_id ) ) {
			return (int) $post_id;
		}

		return false;
	}
