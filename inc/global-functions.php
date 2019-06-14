<?php

function syntric_current_theme() {
	$theme = wp_get_theme();

	return $theme -> get( 'Name' );
}

function syntric_set_home_and_post_pages() {
	$show_on_front = get_option( 'show_on_front' );
	if( 'page' != $show_on_front ) {
		$temp_home_page  = syntric_create_temp_home_page();
		$temp_posts_page = syntric_create_temp_posts_page();
		if( $temp_home_page instanceof WP_Post && $temp_posts_page instanceof WP_Post ) {
			update_option( 'show_on_front', 'page' );
			update_option( 'page_on_front', $temp_home_page -> ID );
			update_option( 'page_for_posts', $temp_posts_page -> ID );
		}
	}

	return true;
}

function syntric_create_temp_home_page() {
	$temp_home_props = [ 'post_title'   => 'Temp Home',
	                     'post_content' => '',
	                     'post_type'    => 'page',
	                     'post_name'    => 'temp-home',
	                     'post_status'  => 'publish',
	                     'post_author'  => get_current_user_id(),
	                     'post_parent'  => 0,
	                     'menu_order'   => 0, ];
	$temp_home_id    = wp_insert_post( $temp_home_props );
	$temp_home_page  = get_post( $temp_home_id, OBJECT );
	update_post_meta( $temp_home_id, '_np_nav_status', 'hide' );

	return $temp_home_page;
}

function syntric_get_home_page() {
	$home_page = get_page_by_path( 'home' );
	if( ! $home_page instanceof WP_Post ) {
		$home_props = [ 'post_title'   => 'Home',
		                'post_content' => '',
		                'post_type'    => 'page',
		                'post_name'    => 'home',
		                'post_status'  => 'publish',
		                'post_author'  => get_current_user_id(),
		                'post_parent'  => 0,
		                'menu_order'   => 0, ];
		$home_id    = wp_insert_post( $home_props );
		$home_page  = get_post( $home_id, OBJECT );
		update_post_meta( $home_id, '_np_nav_status', 'hide' );
	}

	return $home_page;
}

function syntric_create_temp_posts_page() {
	$temp_posts_props = [ 'post_title'   => 'Temp Posts',
	                      'post_content' => '',
	                      'post_type'    => 'page',
	                      'post_name'    => 'temp-posts',
	                      'post_status'  => 'publish',
	                      'post_author'  => get_current_user_id(),
	                      'post_parent'  => 0,
	                      'menu_order'   => 0, ];
	$temp_posts_id    = wp_insert_post( $temp_posts_props );
	$temp_posts_page  = get_post( $temp_posts_id, OBJECT );
	update_post_meta( $temp_posts_id, '_np_nav_status', 'hide' );

	return $temp_posts_page;
}

function syntric_get_posts_page() {
	$posts_page = get_page_by_path( 'news' );
	if( ! $posts_page instanceof WP_Post ) {
		$posts_props = [ 'post_title'   => 'News',
		                 'post_content' => '',
		                 'post_type'    => 'page',
		                 'post_name'    => 'news',
		                 'post_status'  => 'publish',
		                 'post_author'  => get_current_user_id(),
		                 'post_parent'  => 0,
		                 'menu_order'   => 1, ];
		$posts_id    = wp_insert_post( $posts_props );
		$posts_page  = get_post( $posts_id, OBJECT );
	}

	return $posts_page;
}

function syntric_get_privacy_policy_page() {
	$pp_page = get_page_by_path( 'privacy-policy' );
	if( ! $pp_page instanceof WP_Post ) {
		$pp_props = [ 'post_title'   => 'Privacy Policy',
		              'post_content' => '',
		              'post_type'    => 'page',
		              'post_name'    => 'privacy-policy',
		              'post_status'  => 'publish',
		              'post_author'  => get_current_user_id(),
		              'post_parent'  => 0,
		              'menu_order'   => 999999, ];
		$pp_id    = wp_insert_post( $pp_props );
		$pp_page  = get_post( $pp_id, OBJECT );
		update_post_meta( $pp_id, '_np_nav_status', 'hide' );
	}

	return $pp_page;
}

function syntric_linebreak() {
	if( syntric_remove_whitespace() ) {
		return '';
	}

	return "\n";
}

function syntric_tab() {
	if( syntric_remove_whitespace() ) {
		return '';
	}

	return "\t";
}

// todo: improve the next 3 (syntric_is_dev, syntric_is_staging, syntric_remove_whitespace), they get run every request
function syntric_is_dev() {
	$host_parts = explode( '.', $_SERVER[ 'HTTP_HOST' ] );
	$last_part  = $host_parts[ count( $host_parts ) - 1 ];
	if( 'localhost' == $last_part || $_SERVER[ 'SERVER_ADDR' ] == '127.0.0.1' || '::1' == $_SERVER[ 'SERVER_ADDR' ] ) {
		return true;
	}

	return false;
}

function syntric_is_staging() {
	$host_parts        = explode( '.', $_SERVER[ 'HTTP_HOST' ] );
	$next_to_last_part = $host_parts[ count( $host_parts ) - 2 ];
	$last_part         = $host_parts[ count( $host_parts ) - 1 ];
	if( 3 == count( $host_parts ) && ( ( 'syntric' == $next_to_last_part && 'com' == $last_part ) || 'syntric' == $next_to_last_part && 'school' == $last_part ) ) {
		return true;
	}

	return false;
}

function syntric_remove_whitespace() {
	// Temporary
	if( ! syntric_is_dev() || is_admin() ) {
		return true;
	} else {
		return true;
	}

	return false;
}

function syntric_get_top_ancestor_id( $post_id ) {
	$post = get_post( $post_id );
	if( $post instanceof WP_Post && $post -> post_parent ) {
		$ancestors = array_reverse( get_post_ancestors( $post_id ) );

		return $ancestors[ 0 ];
	}

	return $post_id;
}

function syntric_unique_id() {
	// generates similar to this fEmG5ca72ad3f13678.91924460
	//$rp = wp_generate_password( 4, true, true );

	//return uniqid( $rp, true );
	return time() . syntric_generate_random_number( 1000, 9999 );
//15561806148201
}

function syntric_generate_random_number( $min = 1, $max = 10000 ) {
	$random = mt_rand( $min, $max );

	return $random;
}

function syntric_generate_password( $length = 8 ) {
	return wp_generate_password( $length, true, true );
}

function syntric_sanitize_string_to_class( $string ) {
	$class = strtolower( $string );
	$class = str_replace( ' ', '-', $class );
	$class = preg_replace( "/[^a-z0-9-]/", "", $class );
	$class = preg_replace( "/[-]+/", "-", $class );
	$class = sanitize_html_class( $class );

	return $class;
}

function syntric_get_taxonomies_terms() {
	global $post;
	$ret         = '';
	$taxes_terms = wp_get_object_terms( $post -> ID, get_object_taxonomies( $post ), [ 'orderby' => 'term_group' ] );
	if( count( $taxes_terms ) ) {
		foreach( $taxes_terms as $tax_term ) {
			if( 'microblog' == $tax_term -> taxonomy ) {
				$ret = $tax_term -> name;

				return $ret;
			} elseif( 'category' == $tax_term -> taxonomy && 'microblogs' != $tax_term -> slug ) {
				$ret = $tax_term -> name;

				return $ret;
			}
		}
	}

	return $ret;
}

function syntric_resolve_post_id( $post_id ) {
	global $post;
	if( null == $post_id ) {
		return $post -> ID;
	} elseif( $post_id instanceof WP_Post ) {
		return $post_id -> ID;
	} elseif( is_numeric( $post_id ) ) {
		return (int) $post_id;
	}

	return 0;
}

function slog( $log, $type = 'info', $js = 0 ) {
	//$message = ( is_array( $log ) || is_object( $log ) ) ? implode( ' / ', $log ) : $log;
	//list( , $caller ) = debug_backtrace( false );
	//$caller2 = debug_backtrace( DEBUG_BACKTRACE_IGNORE_ARGS, 2 )[ 1 ][ 'function' ];
	/*$date = date("Y-m-d h:m:s");
	$file = __FILE__;*/
	if( is_array( $log ) || is_object( $log ) ) {
		error_log( print_r( $log, true ) );
	} else {
		//error_log( print_r( $caller, true ) );
		//error_log( print_r( $caller2, true ) );
		error_log( $log );
	}
}

// Boneyard
function syntric_has_content( $str ) {
	return strlen( trim( str_replace( '&nbsp;', '', strip_tags( $str ) ) ) );
}

//add_filter( 'private_title_format', 'syntric_private_title_format', 10, 2 );
function syntric_private_title_format( $prepend, $post ) {
	//return '%s <i class="fa fa-eye-slash" aria-hidden="true"></i><span class="sr-only">(Private)</span>';
	return '%s';
}

//add_filter( 'protected_title_format', 'syntric_protected_title_format', 10, 2 );
function syntric_protected_title_format( $prepend, $post ) {
	//return '%s <i class="fa fa-lock" aria-hidden="true"></i><span class="sr-only">(Protected)</span>';
	return '%s';
}

function syntric_posts_distinct() {
	return 'DISTINCT';
}

//add_filter( 'pre_get_posts', 'syntric_filter_author_posts_list' );
function syntric_filter_author_posts_list( $query ) {
	global $pagenow;
	global $user_ID;
	if( is_admin() && 'edit.php' == $pagenow && ! syntric_current_user_can( 'editor' ) ) {
		$query -> set( 'author', $user_ID );
	}

	return $query;
}

////////////////////////// Boneyard ////////////////////////////////////////////////////

/// Moved to syntric-apps.php
function ___syntric_get_post_badges( $post_id = null ) {
	$post_id    = syntric_resolve_post_id( $post_id );
	$post       = get_post( $post_id );
	$badge_text = '';
	switch( $post -> post_type ) {
		case 'syntric_calendar' :
			$badge_text = 'Calendar';
		break;
		case 'syntric_event' :
			$calendar   = get_the_title( get_field( 'syntric_event_calendar_id', get_the_ID() ) );
			$badge_text = $calendar;
		break;
		case 'post' :
			$badge_text = syntric_get_taxonomies_terms();
		break;
		case 'page' :
			$page_template = get_post_type( $post_id );
			$badge_text    = ucwords( $page_template );
			switch( $page_template ) {
				case 'class':
					$teacher      = syntric_get_class_page_teacher( $post_id );
					$teacher_meta = get_user_meta( $teacher -> ID );
					$first_name   = $teacher_meta[ 'first_name' ][ 0 ];
					$last_name    = $teacher_meta[ 'last_name' ][ 0 ];
					$class_id     = get_field( 'syntric_class_page_class', $post_id );
					$class        = syntric_get_teacher_class( $teacher -> ID, $class_id );
					$badge_text   = $first_name . ' ' . $last_name;
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

/// Moved to syntric-apps.php
function ___syntric_get_excerpt_badges( $post_id = null ) {
	$post_id = syntric_resolve_post_id( $post_id );
	$post    = get_post( $post_id );
	switch( $post -> post_type ) {
		case 'syntric_event' :
			$calendar = get_the_title( get_field( 'syntric_event_calendar_id', get_the_ID() ) );

			return '<div class="badge badge-pill badge-dark">' . $calendar . '</div>';
		break;
		case 'post' :
			return '<div class="badge badge-pill badge-dark">' . syntric_get_taxonomies_terms() . '</div>';
		break;
	}

	return '';
}