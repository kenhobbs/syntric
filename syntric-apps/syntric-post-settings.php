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

	//add_action( 'acf/save_post', 'syn_save_post', 20 );
	/*function ___syn_save_post( $post_id ) {
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
	}*/
	function syn_get_post_badges( $post_id = null ) {
		$post_id    = syn_resolve_post_id( $post_id );
		$post       = get_post( $post_id );
		$badge_text = '';
		switch ( $post->post_type ) {
			case 'syn_calendar' :
				$badge_text = 'Calendar';
				break;
			case 'syn_event' :
				$calendar   = get_the_title( get_field( 'syn_event_calendar_id', get_the_ID() ) );
				$badge_text = $calendar;
				break;
			case 'post' :
				$badge_text = syn_get_taxonomies_terms();
				break;
			case 'page' :
				$page_template = syn_get_page_template( $post_id );
				$badge_text    = ucwords( $page_template );
				switch ( $page_template ) {
					case 'class':
						//$class = syn_get_teacher_class_page();
						//$class = syn_get_class_page_content();
						$teacher      = syn_get_class_page_teacher( $post_id );
						$teacher_meta = get_user_meta( $teacher->ID );
						$first_name   = $teacher_meta[ 'first_name' ][ 0 ];
						$last_name    = $teacher_meta[ 'last_name' ][ 0 ];
						$class_id     = get_field( 'syn_page_class', $post_id );
						var_dump( $class_id );
						$class        = syn_get_teacher_class( $teacher->ID, $class_id );
						var_dump( $class );
						$badge_text = $first_name . ' ' . $last_name;
						break;
					/*case 'course':
						//$badge_text = ucwords( $page_template );
						break;
					case 'department':
						//$badge_text = ucwords( $page_template );
						break;
					case 'teacher':
						//$badge_text = ucwords( $page_template );
						break;
					case 'teachers':
						//$badge_text = ucwords( $page_template );
						break;*/
					default:
						$badge_text = '';
						break;
				}
				break;
		}

		//return ( strlen( $badge_text ) > 0 ) ? '<div class="badge badge-pill badge-dark">' . $badge_text . '</div>' : '';
		return '<div class="badge badge-pill badge-secondary">' . $badge_text . '</div>';
	}

	function syn_get_excerpt_badges( $post_id = null ) {
		$lb = syn_get_linebreak();
		//$tab     = syn_get_tab();
		$post_id = syn_resolve_post_id( $post_id );
		$post    = get_post( $post_id );
		switch ( $post->post_type ) {
			case 'syn_event' :
				$calendar = get_the_title( get_field( 'syn_event_calendar_id', get_the_ID() ) );

				return '<div class="badge badge-pill badge-dark">' . $calendar . '</div>' . $lb;
				break;
			case 'post' :
				return '<div class="badge badge-pill badge-dark">' . syn_get_taxonomies_terms() . '</div>' . $lb;
				break;
		}

		return '';
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
