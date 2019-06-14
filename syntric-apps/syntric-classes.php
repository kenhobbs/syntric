<?php

/**
 * On save departments creates and deletes department pages
 *
 * @param $post_id
 */
add_action( 'acf/save_post', 'syntric_on_save_classes' );
function syntric_on_save_classes( $post_id ) {
	global $pagenow;
	if( ! isset( $_POST[ 'acf' ] ) || wp_is_post_revision( $post_id ) || ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) ) {
		return;
	}
	$class_ids = [];
	if( 'admin.php' == $pagenow && 'syntric-classes' == $_REQUEST[ 'page' ] ) {
		if( have_rows( 'field_5cb5b9eac5f21', 'options' ) ) {
			while( have_rows( 'field_5cb5b9eac5f21', 'options' ) ) {
				the_row();
				$include_page = get_sub_field( 'include_page' );
				$class_id     = get_sub_field( 'id' );
				$class_ids[]  = $class_id;
				if( $include_page ) {
					syntric_save_class_page( $class_id );
				} else {
					syntric_delete_class_page( $class_id, 1, 1 );
				}
			}
		}
	}
	$class_pages = syntric_get_class_pages();
	foreach( $class_pages as $class_page ) {
		$_class_id = get_field( 'syntric_class_page_class', $class_page -> ID );
		if( ! in_array( $_class_id, $class_ids ) || ! $_class_id ) {
			syntric_delete_class_page( $class_page -> ID, 1, 1 );
		}
	}
}

//******************* Class ******************************//

/**
 * Get a class (option)
 *
 * @param $class_id
 *
 * @return array/bool
 */
function syntric_get_class( $class_id = null ) {
	global $post;
	if( ! $class_id ) {
		if( 'class' == basename( get_page_template(), '.php' ) ) {
			$class_id = get_field( 'syntric_class_page_class', $post -> ID );
		}
	}
	if( ! $class_id ) {
		return;
	}
	$classes = syntric_get_classes();
	if( $classes ) {
		foreach( $classes as $class ) {
			if( $class_id == $class[ 'id' ] ) {
				return $class;
			}
		}
	}

	return false;
}

/**
 * Get all classes
 */
function syntric_get_classes() {
	$classes = get_field( 'field_5cb5b9eac5f21', 'options' );

	return $classes;
}

/**
 * Save a class
 */
function syntric_save_class( $class_id, array $args ) {
	if( ! is_numeric( $class_id ) || ! count( $args ) ) {
		return false;
	}
	if( have_rows( 'field_5cb5b9eac5f21', 'options' ) ) {
		while( have_rows( 'field_5cb5b9eac5f21 ', 'options' ) ) {
			the_row();
			$id = get_sub_field( 'id' );
			if( $class_id == $id ) {
				foreach( $args as $key => $val ) {
					update_sub_field( $key, $val );
					break 2;
				}
			}
		}
	}
}

//******************* Class Page ******************************//

/**
 * Get a class page
 *
 * @param $class_id (int) required - the ID for the class (not the post ID of the class page)
 *
 * @return \WP_Post
 */
function syntric_get_class_page( $class_id ) {
	$class = syntric_get_class( $class_id );
	if( ! $class ) {
		return false;
	}
	$class_pages = get_posts( [
		'numberposts' => - 1,
		'post_type'   => 'page',
		'post_status' => [ 'publish', 'draft', 'future', 'pending', 'private', ],
		'meta_query'  => [
			'relation' => 'AND',
			[ 'key'     => '_wp_page_template',
			  'value'   => 'class.php',
			  'compare' => '=', ],
			[ 'key'     => 'syntric_class_page_class',
			  'value'   => $class_id,
			  'compare' => '=', ],
		],
		'orderby'     => 'post_date', // oldest page returned first
		'order'       => 'ASC', ] );
	if( 0 == count( $class_pages ) ) {
		return false;
	}
	if( 1 < count( $class_pages ) ) {
		$class_page = array_shift( $class_pages );
		foreach( $class_pages as $page ) {
			syntric_delete_class_page( $page -> ID );
		}
	}
	if( 1 == count( $class_pages ) ) {
		$class_page = $class_pages[ 0 ];
	}

	return $class_page;
}

/**
 * Get all class pages
 */
function syntric_get_class_pages() {
	$class_pages = get_posts( [
		'numberposts'  => - 1,
		'post_type'    => 'page',
		'post_status'  => [ 'publish', 'draft', 'future', 'pending', 'private', ],
		'meta_key'     => '_wp_page_template',
		'meta_value'   => 'class.php',
		'meta_compare' => '=',
		'orderby'      => 'post_date',
		'order'        => 'ASC', ] );

	return $class_pages;
}

/**
 * Save a class page
 *
 * @param $class_id
 *
 * @return bool|int|\WP_Error
 */
function syntric_save_class_page( $class_id ) {
	$class = syntric_get_class( $class_id );
	if( ! $class ) {
		return false;
	}
	$teacher_page = syntric_get_teacher_page( $class[ 'teacher' ][ 'value' ] );
	if( ! $teacher_page instanceof WP_Post ) {
		return false;
	}
	$user             = get_user_by( 'ID', $class[ 'teacher' ][ 'value' ] );
	$user_cf          = get_field( 'syntric_user', 'user_' . $user -> ID );
	$class_page_title = $class[ 'course' ][ 'label' ] . ' (' . 'Period ' . $class[ 'period' ][ 'label' ] . '/' . $user -> user_lastname . ')';
	$args             = [
		'post_title'     => $class_page_title,
		'post_name'      => syntric_sluggify( $class_page_title ),
		'post_status'    => 'publish',
		'post_type'      => 'page',
		'comment_status' => 'closed',
		'post_parent'    => $teacher_page -> ID,
	];
	$class_page       = syntric_get_class_page( $class_id );
	if( $class_page instanceof WP_Post ) {
		$args[ 'ID' ] = $class_page -> ID;
	} else {
		$args[ 'post_content' ] = syntric_get_default_class_page_content();
	}
	$page_id = wp_insert_post( $args );
	update_post_meta( $page_id, '_wp_page_template', 'class.php' );
	update_field( 'syntric_class_page_class', $class_id, $page_id );

	return $page_id;
}

/**
 * Delete a class page
 *
 * @param     $class_id
 * @param int $recursive
 * @param int $force_delete
 *
 * @return bool
 */
function syntric_delete_class_page( $class_id, $recursive = 1, $force_delete = 0 ) {
	$class = syntric_get_class( $class_id );
	if( ! $class ) {
		return false;
	}
	$class_page = syntric_get_class_page( $class_id );
	if( ! $class_page instanceof WP_Post ) {
		return false;
	}
	if( $recursive ) {
		$child_pages = syntric_get_page_children( $class_page -> ID );
		foreach( $child_pages as $child_page ) {
			wp_delete_post( $child_page -> ID, $force_delete );
		}
	}
	wp_delete_post( $class_page -> ID, $force_delete );

	return true;
}

function syntric_get_default_class_page_content() {
	return
		'<!-- wp:heading -->
<h2>Class Objectives</h2>
<!-- /wp:heading -->

<!-- wp:paragraph -->
<p>Write a brief description of the class objectives.</p>
<!-- /wp:paragraph -->

<!-- wp:heading -->
<h2>Classroom Rules</h2>
<!-- /wp:heading -->

<!-- wp:list {"ordered":true} -->
<ol><li>Be on time<br>On time is in your seat when the bell rings.</li><li>Be prepared<br>Bring the required materials listed below and be ready to learn right away.</li><li>Be on task<br>You are expected to participate and in fact it makes for part of your grade so pay attention</li><li>Be respectful<br>Be courteous and respectful to me and each other.</li></ol>
<!-- /wp:list -->

<!-- wp:heading -->
<h2>Required Materials</h2>
<!-- /wp:heading -->

<!-- wp:paragraph -->
<p>Bring the following items to class every day.</p>
<!-- /wp:paragraph -->

<!-- wp:list -->
<ul><li>Textbook Title</li><li>Notebook</li><li>Pen</li></ul>
<!-- /wp:list -->

<!-- wp:heading -->
<h2>Attendance</h2>
<!-- /wp:heading -->

<!-- wp:paragraph -->
<p>Students are expected to attend every class unless an absence is cleared through the Attendance Office.  Unexcused absences will negatively impact a students grades and academic standing as described in the Student Handbook. </p>
<!-- /wp:paragraph -->

<!-- wp:heading -->
<h2>Tardy Policy</h2>
<!-- /wp:heading -->

<!-- wp:paragraph -->
<p>Tardiness is disruptive and will not be accepted. Students are expected to be in class and in their seats when the tardy bell rings.  The rules described in the Student Handbook will be strictly enforced including Saturday school.</p>
<!-- /wp:paragraph -->

<!-- wp:heading -->
<h2>Late Work</h2>
<!-- /wp:heading -->

<!-- wp:paragraph -->
<p>All assignments need to be turned in on the due date and the beginning of class to receive credit. There will not be any late work accepted without an excused absence.</p>
<!-- /wp:paragraph -->

<!-- wp:heading -->
<h2>Absent Work</h2>
<!-- /wp:heading -->

<!-- wp:paragraph -->
<p>Absent assignments and quizzes/tests make-ups will be allowed with an excused absence. Make sure you check in with me the day you come back. It is the student’s responsibility to arrange a time to make up absent work.</p>
<!-- /wp:paragraph -->

<!-- wp:heading -->
<h2>Discipline Policy</h2>
<!-- /wp:heading -->

<!-- wp:list -->
<ul><li>First offense: Verbal warning</li><li>Second offense: Meeting with teacher after class</li><li>Third offense: Phone call to parent of guardian and detention</li><li>Fourth offense will result in a referral</li></ul>
<!-- /wp:list -->

<!-- wp:paragraph -->
<p>Depending on the severity of the infraction, students may be suspended or removed from the course without prior actions.</p>
<!-- /wp:paragraph -->

<!-- wp:heading -->
<h2>Grading</h2>
<!-- /wp:heading -->

<!-- wp:paragraph -->
<p>All work must be submitted in a professional matter. Students are highly encouraged to keep all work. Mistakes happen and it is easy to correct if all graded work has been kept.</p>
<!-- /wp:paragraph -->

<!-- wp:paragraph -->
<p>Grades are weighted as listed in the following table.</p>
<!-- /wp:paragraph -->

<!-- wp:table {"hasFixedLayout":true,"backgroundColor":"subtle-light-gray","align":"wide","className":"is-style-stripes"} -->
<table class="wp-block-table alignwide has-subtle-light-gray-background-color has-fixed-layout has-background is-style-stripes"><thead><tr><th>ITEM</th><th>PERCENT OF GRADE</th></tr></thead><tbody><tr><td>Midterm &amp; Final Exam</td><td>15%</td></tr><tr><td>Exams</td><td>30%</td></tr><tr><td>Quizzes</td><td>10%</td></tr><tr><td>Projects</td><td>10%</td></tr><tr><td>Presentations</td><td>10%</td></tr><tr><td>Class work</td><td>15%</td></tr><tr><td>Homework</td><td>5%</td></tr><tr><td>Study Skills Calendar</td><td>5%</td></tr></tbody></table>
<!-- /wp:table -->

<!-- wp:heading -->
<h2>Grades</h2>
<!-- /wp:heading -->

<!-- wp:paragraph -->
<p>A = 90-100%<br>B = 80-89%<br>C = 70-79%<br>D = 60-69%<br>F = 59% or lower</p>
<!-- /wp:paragraph -->

<!-- wp:paragraph -->
<p>Cheating or plagiarism will result in zero credit for the assignment. See the Student Handbook for additional information.</p>
<!-- /wp:paragraph -->

<!-- wp:heading -->
<h2>Tutoring</h2>
<!-- /wp:heading -->

<!-- wp:paragraph -->
<p>Tuesdays and Thursdays 3:05 PM – 3:35 PM or by appointment</p>
<!-- /wp:paragraph -->';
}

//******************* Boneyard ******************************//

/**
 * @param $class - can be a class array or class id
 *
 * @return int|void|\WP_Error
 */
function __syntric_create_class_page( $class ) {
	if( is_string( $class ) ) {
		$class = syntric_get_class( $class );
	}
	//slog( 'syntric_create_class_page' );
	//slog( $class );
	$teacher       = $class[ 'teacher' ]; // WP_User
	$post_title    = $class[ 'course' ];
	$post_name     = syntric_sluggify( $post_title );
	$teacher_page  = syntric_get_teacher_page( $teacher -> ID ); // todo: make sure the only thing that can return is a valid, published teachers page
	$class_page_id = wp_insert_post( [ 'post_type'    => 'page',
	                                   'post_title'   => $post_title,
	                                   'post_name'    => $post_name,
	                                   'post_author'  => $teacher -> ID,
	                                   'post_parent'  => $teacher_page -> ID,
	                                   'post_status'  => 'publish',
	                                   'post_content' => '' ] );
	update_post_meta( $class_page_id, '_wp_page_template', 'class.php' );

	return $class_page_id;
}