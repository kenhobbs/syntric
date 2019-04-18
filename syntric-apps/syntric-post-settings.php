<?php

//add_filter( 'pre_get_posts', 'syntric_filter_author_posts_list' );
function syntric_filter_author_posts_list( $query ) {
	global $pagenow;
	global $user_ID;
	if( is_admin() && 'edit.php' == $pagenow && ! syntric_current_user_can( 'editor' ) ) {
		$query -> set( 'author', $user_ID );
	}

	return $query;
}

function syntric_get_post_badges( $post_id = null ) {
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
			$page_template = syntric_get_page_template( $post_id );
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

function syntric_get_excerpt_badges( $post_id = null ) {
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
	
	
