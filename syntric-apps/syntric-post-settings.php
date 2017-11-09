<?php
add_action( 'acf/save_post', 'syn_save_post', 20 );
function syn_save_post( $post_id ) {
	global $pagenow;
	// don't save for autosave
	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
		return;
	}
	if ( is_admin() && is_numeric( $post_id ) && 'post' == get_post_type( $post_id ) && isset( $_REQUEST[ 'acf' ] ) && ! wp_is_post_revision( $post_id ) ) {
		$category_id = get_field( 'syn_post_category', $post_id );
		if ( 0 == $category_id ) {
			$new_category = get_field( 'syn_post_new_category', $post_id );
			$category_id  = wp_insert_category( array( 'cat_name' => $new_category ) );
		}
		if ( $category_id ) {
			wp_set_post_categories( $post_id, array( (int) $category_id ), false );
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
						$microblog_term    = wp_insert_term( $new_microblog_term, 'microblog', array( 'description' => 'Microblog term created manually' ) );
						$microblog_term_id = $microblog_term[ 'term_id' ];
					} else {
						$microblog_term_id = $microblog_term->term_id;
					}
				}
				if ( $microblog_term_id ) {
					wp_set_post_terms( $post_id, array( (int) $microblog_term_id ), 'microblog', false );
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
add_filter( 'acf/prepare_field/key=field_59e7f2f7049e2', 'syn_prepare_post_fields' ); // category enhanced message field
add_filter( 'acf/prepare_field/key=field_59e7f370049e3', 'syn_prepare_post_fields' ); // microblog enhanced message field
function syn_prepare_post_fields( $field ) {
	global $pagenow;
	global $post;
	if ( 'post.php' == $pagenow && 'post' == $post->post_type ) {
		if ( 'syn_post_category' == $field[ '_name' ] ) {
			if ( $field[ 'value' ] ) {
				$field[ 'wrapper' ][ 'class' ] = 'hidden';
			}
		}
		if ( 'syn_post_microblog' == $field[ '_name' ] ) {
			if ( $field[ 'value' ] ) {
				$field[ 'wrapper' ][ 'class' ] = 'hidden';
			}
		}
		if ( 'field_59e7f2f7049e2' == $field[ 'key' ] ) {
			$category_id = get_field( 'syn_post_category', $post->ID );
			if ( $category_id ) {
				$category = get_category( $category_id );
				if ( $category ) {
					$field[ 'wrapper' ][ 'class' ] = '';
					$field[ 'message' ]            = $category->name;
				}
			}
		}
		if ( 'field_59e7f370049e3' == $field[ 'key' ] ) {
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
