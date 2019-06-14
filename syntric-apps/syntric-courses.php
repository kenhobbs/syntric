<?php

/**
 * On save courses creates and deletes course pages
 *
 * @param $post_id
 */
add_action( 'acf/save_post', 'syntric_on_save_courses' );
function syntric_on_save_courses( $post_id ) {
	global $pagenow;
	if( ! isset( $_POST[ 'acf' ] ) || wp_is_post_revision( $post_id ) || ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) ) {
		return;
	}
	if( 'admin.php' == $pagenow && 'syntric-courses' == $_REQUEST[ 'page' ] ) {
		if( have_rows( 'field_5c6fa90a18def', 'options' ) ) {
			while( have_rows( 'field_5c6fa90a18def', 'options' ) ) {
				the_row();
				$include_page = get_sub_field( 'include_page' );
				$course_id    = get_sub_field( 'id' );
				if( $include_page ) {
					syntric_save_course_page( $course_id );
				} else {
					// todo: warn on this
					syntric_delete_course_page( $course_id, 1, 1 );
				}
			}
		}
	}
}

//******************* Course ******************************//

/**
 * Get a course (option)
 *
 * @param $course_id
 *
 * @return array/bool
 */
function syntric_get_course( $course_id ) {
	$courses = syntric_get_courses();
	if( $courses ) {
		foreach( $courses as $course ) {
			if( $course_id == $course[ 'id' ] ) {
				return $course;
			}
		}
	}

	return false;
}

/**
 * Get all courses
 *
 * @return mixed
 */
function syntric_get_courses() {
	$courses = get_field( 'field_5c6fa90a18def', 'options' );

	return $courses;
}

/**
 * Save a course
 */
function syntric_save_course( $course_id, array $args ) {
	if( ! is_numeric( $course_id ) || ! count( $args ) ) {
		return false;
	}
	if( have_rows( 'field_5c6fa90a18def', 'options' ) ) {
		while( have_rows( 'field_5c6fa90a18def ', 'options' ) ) {
			the_row();
			$id = get_sub_field( 'field_5ca72c4ff7e31' );
			if( $course_id == $id ) {
				foreach( $args as $key => $val ) {
					update_sub_field( $key, $val );
					break 2;
				}
			}
		}
	}
}

//******************* Course Page ******************************//

/**
 * Get a course page
 *
 * @param $course_id
 *
 * @return int[]|\WP_Post[]
 */
function syntric_get_course_page( $course_id ) {
	$course = syntric_get_course( $course_id );
	if( ! $course ) {
		return false;
	}
	$course_pages = get_posts( [
		'numberposts' => - 1,
		'post_type'   => 'page',
		'post_status' => [ 'publish', 'draft', 'future', 'pending', 'private', ],
		'meta_query'  => [
			'relation' => 'AND',
			[ 'key'     => '_wp_page_template',
			  'value'   => 'course.php',
			  'compare' => '=', ],
			[ 'key'     => 'syntric_course_page_course',
			  'value'   => $course_id,
			  'compare' => '=', ],
		],
		'orderby'     => 'post_date', // oldest page returned first
		'order'       => 'ASC', ] );
	if( 0 == count( $course_pages ) ) {
		// todo: make a course page?
		return false;
	}
	if( 1 < count( $course_pages ) ) {
		$course_page = array_shift( $course_pages );
		foreach( $course_pages as $page ) {
			syntric_delete_course_page( $page -> ID );
		}
	}
	if( 1 == count( $course_pages ) ) {
		$course_page = $course_pages[ 0 ];
	}

	return $course_page;
}

/**
 * Save a course page.
 *
 * @param $course_id
 *
 * @return bool|int|\WP_Error
 */
function syntric_save_course_page( $course_id ) {
	//todo: save course description as page content?
	$course = syntric_get_course( $course_id );
	if( ! $course ) {
		return false;
	}
	$department_page = syntric_get_department_page( $course[ 'department' ][ 'value' ] );
	if( ! $department_page instanceof WP_Post ) {
		return false;
	}
	$args        = [
		'post_title'     => $course[ 'course' ],
		'post_name'      => syntric_sluggify( $course[ 'course' ] ),
		'post_status'    => 'publish',
		'post_type'      => 'page',
		'comment_status' => 'closed',
		'post_parent'    => $department_page -> ID,
	];
	$course_page = syntric_get_course_page( $course_id );
	if( $course_page instanceof WP_Post ) {
		$args[ 'ID' ] = $course_page -> ID;
	}
	$page_id = wp_insert_post( $args );
	update_post_meta( $page_id, '_wp_page_template', 'course.php' );
	update_field( 'syntric_course_page_course', $course_id, $page_id );

	return $page_id;
}

/**
 * Delete a course page.
 *
 * @param     $course_id
 * @param int $recursive
 * @param int $force_delete
 *
 * @return bool
 */
function syntric_delete_course_page( $course_id, $recursive = 1, $force_delete = 0 ) {
	$course = syntric_get_course( $course_id );
	if( ! $course ) {
		return false;
	}
	$course_page = syntric_get_course_page( $course_id );
	if( ! $course_page instanceof WP_Post ) {
		return false;
	}
	if( $recursive ) {
		$child_pages = syntric_get_page_children( $course_page -> ID );
		if( $child_pages ) {
			foreach( $child_pages as $child_page ) {
				if( 'class' == get_post_type( $child_page -> ID ) ) {
					$class_id = get_field( 'syntric_class_page_class', $child_page -> ID );
					syntric_save_class( $class_id, [ 'include_page' => 0, ] );
				}
				wp_delete_post( $child_page -> ID, $force_delete );
			}
		}
	}
	wp_delete_post( $course_page -> ID, $force_delete );

	return true;
}

//******************* Misc ******************************//

/**
 * Get teachers teaching a specific course
 *
 * @param $course_id
 *
 * @return array
 */
function syntric_get_course_teachers( $course_id ) {
	$classes         = syntric_get_classes();
	$course_teachers = [];
	if( $classes ) {
		foreach( $classes as $class ) {
			//slog($class);
			if( $course_id == $class[ 'course' ][ 'value' ] ) {
				$course_teachers[] = get_user_by( 'ID', $class[ 'teacher' ][ 'value' ] );
			}
		}
	}

	return $course_teachers;
}

/**
 * Get course classes
 */
function syntric_get_course_classes( $course_id ) {
	$classes        = syntric_get_classes();
	$course_classes = [];
	foreach( $classes as $class ) {
		if( $course_id == $class[ 'course' ][ 'value' ] ) {
			$course_classes[] = $class;
		}
	}

	return $course_classes;
}

function syntric_display_course( $post_id ) {
	$page_course_id = get_field( 'syntric_course_page_course', $post_id );
	$courses        = get_field( 'field_5c6fa90a18def', 'options' );
	//slog( $page_course_id );
	//slog($courses);
	if( $courses ) {
		foreach( $courses as $course ) {
			if( $course[ 'id' ] == $page_course_id ) {
				echo $course[ 'description' ];
				/*$meta = $course[ 'meta' ];
				if( $meta ) {
					foreach( $meta as $meta_item ) {
						echo '<div class="badge badge-primary">' . $meta_item[ 'label' ] . '</div>';
					}
				}*/
			}
		}
	}
}

//******************* Boneyard ******************************//

/**
 * Get all course pages
 *
 * @param string $fields
 *
 * @return int[]|\WP_Post[]
 */
function __syntric_get_course_pages( $fields = '' ) {
	//slog( '*************************** syntric_get_courses_pages syntric-courses ln 103 *******************************************************' );
	$args = [ 'numberposts'  => - 1,
	          'post_type'    => 'page',
	          'post_status'  => [ 'publish', 'draft', 'future', 'pending', 'private', ],
	          'meta_key'     => '_wp_page_template',
	          'meta_value'   => 'course.php',
	          'meta_compare' => '=',
	          'orderby'      => 'post_title',
	          'order'        => 'ASC', ];
	if( strlen( $fields ) ) {
		$args[ 'fields' ] = $fields;
	}
	$posts = get_posts( $args );

	return $posts;
}

