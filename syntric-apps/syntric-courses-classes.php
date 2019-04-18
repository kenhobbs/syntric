<?php

function syntric_get_class_pages( $include_trash = false ) {
	$post_statuses = [ 'publish',
	                   'draft',
	                   'future',
	                   'pending',
	                   'private', ];
	if( $include_trash ) {
		$post_statuses[] = 'trash';
	}
	$args  = [ 'numberposts'  => - 1,
	           'post_type'    => 'page',
	           'post_status'  => $post_statuses,
	           'meta_key'     => '_wp_page_template',
	           'meta_value'   => 'class.php',
	           'meta_compare' => '=',
	           'orderby'      => 'post_title',
	           'order'        => 'ASC' ];
	$posts = get_posts( $args );

	return $posts;
}

function syntric_manage_class_page( $class_id, $teacher_id = null ) {
	$classes       = get_field( 'syntric_classes', 'option' );
	$class_page_id = '';
	foreach( $classes as $class ) {
		if( $class_id == $class[ 'id' ] ) {
			$class_page = $class[ 'class_page' ]; // Post object
			if( $class_page instanceof WP_Post ) {
				$class_page_id = $class_page -> ID;
				break;
			} else {
				$class_page_id = syntric_create_class_page( $class_id, true );
			}
		}
	}

	return $class_page_id;
}

/**
 * @param $class - can be a class array or class id
 *
 * @return int|void|\WP_Error
 */
function syntric_create_class_page( $class ) {
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

/*add_filter( 'acf/load_value/name=syntric_classes', 'syntric_load_classes_repeater', 10, 3 );
function syntric_load_classes_repeater( $value, $post_id, $field ) {
	//$classes = get_field( 'syntric_classes', 'option' );
	//slog($syntric_classes);
	//global $pagenow;
	//slog($pagenow);
	//slog(get_current_screen());
	//slog($value);
	//slog($post_id);
	//slog($field);
	//slog( $value );

	return $value;
}*/

function syntric_my_classes() {
	$current_user = wp_get_current_user();
	$classes      = get_field( 'syntric_classes', 'option' );
	$my_classes   = [];
	if( $classes ) {
		echo '<table class="my-classes-list">';
		echo '<thead>';
		echo '<tr align="left">';
		echo '<th scope="col">Course</th>';
		echo '<th scope="col">Period</th>';
		echo '<th scope="col">Room</th>';
		echo '<th scope="col">Class Page</th>';
		echo '</thead>';
		echo '<tbody>';
		foreach( $classes as $class ) {
			//if( $current_user -> ID == $class[ 'teacher' ] -> ID ) {
			echo '<tr>';
			echo '<td>' . $class[ 'course' ] . '</td>';
			echo '<td>' . $class[ 'period' ] . '</td>';
			echo '<td>' . $class[ 'room' ] . '</td>';
			echo '<td>' . $class[ 'class_page' ] . '</td>';
			echo '</tr>';
			//}
		}
		echo '</tbody>';
	}

	return $my_classes;
}

function syntric_get_class( $class_id ) {
	if( ! $class_id ) {
		return;
	}
	$classes = get_field( 'syntric_classes', 'option' );
	for( $i = 0; $i < count( $classes ); $i ++ ) {
		if( $classes[ $i ][ 'id' ] == $class_id ) {
			return $classes[ $i ];
		}
	}

	return;
}

/*************************************** Teacher Classes *****************************************/
function __syntric_get_teacher_class_page( $user_id, $class_id ) {
	$classes         = get_field( 'syntric_classes', 'option' );
	$teacher_classes = [];
	foreach( $classes as $class ) {
		if( $user_id == $class[ 'teacher' ][ 'value' ] ) {
			$teacher_classes[] = $class;
		}
	}

	return $teacher_classes;
}

function syntric_get_teacher_classes( $user_id ) {
	$classes         = get_field( 'syntric_classes', 'option' );
	$teacher_classes = [];
	foreach( $classes as $class ) {
		if( $user_id == $class[ 'teacher' ][ 'value' ] ) {
			$teacher_classes[] = $class;
		}
	}

	return $teacher_classes;
}

/**
 * Get a class page
 *
 * @param $class_id (int) required - the ID for the class (not the post ID of the class page)
 *
 * @return \WP_Post
 */
function syntric_get_class_page( $class_id, $include_trash = false ) {
	$post_statuses = [ 'publish',
	                   'draft',
	                   'future',
	                   'pending',
	                   'private', ];
	if( $include_trash ) {
		$post_statuses[] = 'trash';
	}
	var_dump( 'this needs fixin syntric-course-classes.php line 170' );

	return;
	$post_args = [ 'numberposts' => - 1,
	               'post_type'   => 'page',
	               'post_status' => $post_statuses,
	               'post_author' => $teacher_id, // the teacher is the author
	               'meta_query'  => [ [ 'key'     => 'syntric_class_page_class', // class is set to $class_id
	                                    'value'   => $class_id,
	                                    'compare' => '=', ],
	                                  [ 'key'     => '_wp_page_template', // uses the class template
	                                    'value'   => 'class.php',
	                                    'compare' => '=', ], ], ];
	$posts     = get_posts( $post_args );
	if( 1 == count( $posts ) ) {
		return $posts[ 0 ];
	}

	return $posts;
}

/**
 * Get a teacher's class pages
 *
 * @param $teacher_id (int) required - the user ID for the teacher
 *
 * @return array - returns an array of class page objects (WP_Post)
 */
function syntric_get_teacher_class_pages( $teacher_id, $include_trash = false ) {
	$post_statuses = [ 'publish',
	                   'draft',
	                   'future',
	                   'pending',
	                   'private', ];
	if( $include_trash ) {
		$post_statuses[] = 'trash';
	}
	$args  = [ 'numberposts' => - 1,
	           'post_type'   => 'page',
	           'post_status' => $post_statuses,
	           'meta_query'  => [ [ 'key'     => 'syntric_page_class_teacher',
	                                'value'   => $teacher_id,
	                                'compare' => '=', ],
	                              [ 'key'     => '_wp_page_template',
	                                'value'   => 'class.php',
	                                'compare' => '=', ], ], ];
	$posts = get_posts( $args );

	return $posts;
}

/**
 * Trash a teacher's class page
 *
 * @param $teacher_id (int) required - the user ID for the teacher
 * @param $class_id   (int) required - the ID for the class (not the post ID of the class page)
 */
function syntric_trash_class_page( $class_id ) {
	$class_page = syntric_get_class_page( $class_id );
	if( $class_page instanceof WP_Post ) {
		$del_res = wp_delete_post( $class_page -> ID );

		return $del_res;
	}

	return false;
}

/**
 * Trash all of a teacher's class pages
 *
 * @param $teacher_id (int) required - the user ID of the teacher who owns the class pages
 *
 * @return array|bool - array of the wp_delete_post results, one for each class page, false if no classes or error
 */
function syntric_trash_teacher_class_pages( $teacher_id ) {
	$class_pages = syntric_get_teacher_class_pages( $teacher_id );
	if( $class_pages ) {
		$del_res_arr = [];
		foreach( $class_pages as $class_page ) {
			$del_res                          = wp_delete_post( $class_page -> ID );
			$del_res_arr[ $class_page -> ID ] = $del_res;
		}

		return $del_res_arr;
	}

	return false;
}

function syntric_get_default_class_page_content() {
	return '';
	/*return '<h2>Overview</h2>
<p>Write an overview of the class. Consider including:</p>
<ul>
<li>Major topics</li>
<li>Prequisites</li>
<li>Classes that require this class as a prerequisite</li>
<li>Grading policy</li>
</ul>';*/
}