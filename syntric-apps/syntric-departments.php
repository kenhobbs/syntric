<?php

/**
 * On save department saves and deletes department pages
 *
 * @param $post_id
 */
add_action( 'acf/save_post', 'syntric_on_save_departments' );
function syntric_on_save_departments( $post_id ) {
	global $pagenow;
	if( ! isset( $_POST[ 'acf' ] ) || wp_is_post_revision( $post_id ) || ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) ) {
		return;
	}
	if( 'admin.php' == $pagenow && 'syntric-departments' == $_REQUEST[ 'page' ] ) {
		if( have_rows( 'field_5c6fa8f118ded', 'options' ) ) {
			while( have_rows( 'field_5c6fa8f118ded', 'options' ) ) {
				the_row();
				$include_page  = get_sub_field( 'include_page' );
				$department_id = get_sub_field( 'id' );
				if( $include_page ) {
					syntric_save_department_page( $department_id );
				} else {
					syntric_delete_department_page( $department_id, 1, 1 );
				}
			}
		}
	}
}

//******************* Department ******************************//

/**
 * Get a department (option)
 *
 * @param $department_id
 *
 * @return bool
 */
function syntric_get_department( $department_id ) {
	$departments = get_field( 'field_5c6fa8f118ded', 'options' ); // syntric_departments
	foreach( $departments as $department ) {
		if( $department_id == $department[ 'id' ] ) {
			return $department;
		}
	}

	return false;
}

//******************* Department Page ******************************//

/**
 * Get a department page
 *
 * @param $department_id
 *
 * @return int[]|\WP_Post[]
 */
function syntric_get_department_page( $department_id ) {
	$department = syntric_get_department( $department_id );
	if( ! $department ) {
		return false;
	}
	$department_pages = get_posts(
		[
			'numberposts' => - 1,
			'post_type'   => 'page',
			'post_status' => [ 'publish', 'draft', 'future', 'pending', 'private', ],
			'meta_query'  => [
				'relation' => 'AND',
				[ 'key'     => '_wp_page_template',
				  'value'   => 'department.php',
				  'compare' => '=', ],
				[ 'key'     => 'syntric_department_page_department',
				  'value'   => $department_id,
				  'compare' => '=', ],
			],
			'orderby'     => 'post_date', // oldest page returned first
			'order'       => 'ASC',
		]
	);
	if( 0 == count( $department_pages ) ) {
		return false;
	}
	if( 1 < count( $department_pages ) ) {
		$department_page = array_shift( $department_pages ); // get oldest department page
		foreach( $department_pages as $page ) {
			syntric_delete_department_page( $page -> ID );
		}
	}
	if( 1 == count( $department_pages ) ) {
		$department_page = $department_pages[ 0 ];
	}

	return $department_page;
}

/**
 * Save a department page.
 *
 * @param $department_id
 *
 * @return bool|int|\WP_Error
 */
function syntric_save_department_page( $department_id ) {
	$department = syntric_get_department( $department_id );
	if( ! $department ) {
		return false;
	}
	$academics_page = syntric_get_academics_page();
	if( ! $academics_page instanceof WP_Post ) {
		return false;
	}
	$args            = [
		'post_title'     => $department[ 'department' ],
		'post_name'      => syntric_sluggify( $department[ 'department' ] ),
		'post_status'    => 'publish',
		'post_type'      => 'page',
		'comment_status' => 'closed',
		'post_parent'    => $academics_page -> ID,
	];
	$department_page = syntric_get_department_page( $department_id );
	if( $department_page instanceof WP_Post ) {
		$args[ 'ID' ] = $department_page -> ID;
	}
	$page_id = wp_insert_post( $args );
	update_post_meta( $page_id, '_wp_page_template', 'department.php' );
	update_field( 'syntric_department_page_department', $department_id, $page_id );

	return $page_id;
}

/**
 * Delete a department page
 *
 * @param $department_id
 *
 * @return bool
 */
function syntric_delete_department_page( $department_id, $recursive = 1, $force_delete = 0 ) {
	$department = syntric_get_department( $department_id );
	if( ! $department ) {
		return false;
	}
	$department_page = syntric_get_department_page( $department_id );
	if( ! $department_page instanceof WP_Post ) {
		return false;
	}
	if( $recursive ) {
		$child_pages = syntric_get_page_children( $department_page -> ID );
		if( $child_pages ) {
			foreach( $child_pages as $child_page ) {
				if( 'course' == get_post_type( $child_page -> ID ) ) {
					$course_id = get_field( 'syntric_course_page_course', $child_page -> ID );
					syntric_save_course( $course_id, [ 'include_page' => 0, ] );
				}
				wp_delete_post( $child_page -> ID, $force_delete );
			}
		}
	}
	wp_delete_post( $department_page -> ID, $force_delete );

	return true;
}

function syntric_get_department_courses( $department_id ) {
	$courses            = syntric_get_courses();
	$department_courses = [];
	foreach( $courses as $course ) {
		if( $department_id == $course[ 'department' ][ 'value' ] ) {
			$department_courses[] = $course;
		}
	}

	return $department_courses;
}

/**
 * Display a table of courses on a department page.
 *
 * @param $post_id
 */
function syntric_display_department_courses( $post_id ) {
	$department_id = get_field( 'syntric_department_page_department', $post_id );
	$courses       = get_field( 'syntric_courses', 'options' );
	if( $courses ) {
		$department_courses = [];
		foreach( $courses as $course ) {
			if( $course[ 'department' ][ 'value' ] == $department_id ) {
				$department_courses[] = $course;
			}
		}
		if( ! $department_courses ) {
			echo '<p>No courses listed assigned to this department.</p>';

			return;
		}
		echo '<table class="department-courses-table">';
		echo '<thead>';
		echo '<tr>';
		echo '<th scope="col">Course</th>';
		echo '<th scope="col">Teachers</th>';
		echo '</tr>';
		echo '</thead>';
		echo '<tbody>';
		foreach( $department_courses as $department_course ) {
			$course_id        = $department_course[ 'id' ];
			$course_page      = syntric_get_course_page( $course_id );
			$course_permalink = get_permalink( $course_page -> ID );
			$course_teachers  = syntric_get_course_teachers( $course_id );
			$teachers         = [];
			$teachers_cell    = 'Not currently offered';
			if( $course_teachers ) {
				foreach( $course_teachers as $course_teacher ) {
					$teacher_page  = syntric_get_teacher_page( $course_teacher -> ID );
					$teacher_label = ( $teacher_page instanceof WP_Post ) ? '<a href="' . get_the_permalink( $teacher_page -> ID ) . '">' : '';
					$teacher_label .= $course_teacher -> display_name;
					$teacher_label .= '</a>';
					$teachers[]    = $teacher_label;
				}
				$teachers_cell = implode( ' / ', $teachers );
			}
			echo '<tr>';
			echo '<td><a href="' . $course_permalink . '">' . $department_course[ 'course' ] . '</a></td>';
			echo '<td>';
			echo $teachers_cell;
			echo '</td>';
			//echo '<td>' . 'Teachers...' . '</td>';
			echo '</tr>';
		}
		echo '</tbody>';
		echo '</table>';
	}
	//}
}

//******************* Boneyard ******************************//

/**
 * Get all department pages
 *
 * @param string $fields
 *
 * @return int[]|\WP_Post[]
 */
function __syntric_get_department_pages( $fields = '' ) {
	$args = [ 'numberposts'  => - 1,
	          'post_type'    => 'page',
	          'post_status'  => [ 'publish', 'draft', 'future', 'pending', 'private', ],
	          'meta_key'     => '_wp_page_template',
	          'meta_value'   => 'department.php',
	          'meta_compare' => '=',
	          'orderby'      => 'post_title',
	          'order'        => 'ASC', ];
	if( strlen( $fields ) ) {
		$args[ 'fields' ] = $fields;
	}
	$posts = get_posts( $args );

	return $posts;
}