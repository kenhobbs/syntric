<?php

// todo: make this work for admin colums ajax edits from list page
add_action( 'acf/save_post', 'syntric_on_save_user', 20 );
function syntric_on_save_user( $post_id ) {
	global $pagenow;
	if( ! isset( $_POST[ 'acf' ] ) || wp_is_post_revision( $post_id ) || ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) ) {
		return;
	}
	if( is_admin() && in_array( $pagenow, [ 'user-new.php', 'user-edit.php', 'profile.php', 'user.php' ] ) ) {
		$post_id_arr = explode( '_', $post_id );
		$user_id     = $post_id_arr[ 1 ];
		$user_cf     = get_field( 'syntric_user', $post_id );
		$is_teacher  = get_field( 'syntric_user_is_teacher', $post_id );
		if( $is_teacher ) {
			$include_page = get_field( 'syntric_user_include_page', $post_id );
			if( $include_page ) {
				syntric_save_teacher_page( $user_id );
			} else {
				syntric_delete_teacher_page( $user_id, 1, 1 );
			}
		} else {
			syntric_delete_teacher_page( $user_id, 1, 1 );
			update_field( 'syntric_user_include_page', 0, $post_id );
		}
	}
}

//******************* Teacher(s) ******************************//

/**
 * Teachers
 *
 *
 */
// todo: used in one place
function syntric_get_teachers() {
	$teachers = get_users( [
		'meta_key'   => 'last_name',
		'meta_query' => [
			[
				'key'   => 'syntric_user_is_teacher',
				'value' => 1,
			],
		],
		'orderby'    => 'meta_value',
	] );

	return $teachers;
}

// todo: this belongs below in Teacher Page section
function syntric_get_teacher_pages() {
	$posts = get_posts( [
		[
			'numberposts' => - 1,
			'post_type'   => 'page',
			'post_status' => [ 'publish', 'draft', 'future', 'pending', 'private', ],
			'meta_query'  => [
				[ 'key'     => '_wp_page_template',
				  'value'   => 'teacher.php',
				  'compare' => '=', ],
			],
			'orderby'     => 'post_title',
			'order'       => 'ASC',
		],
	] );

	return $posts;
}

//******************* Teacher Page ******************************//

/**
 * Get a teacher page.
 *
 * @param $user_id
 *
 * @return bool|mixed|\WP_Post
 */
function syntric_get_teacher_page( $user_id ) {
	$user = get_user_by( 'ID', $user_id );
	if( ! $user instanceof WP_User ) {
		return false;
	}
	$teacher_pages = get_posts( [
		'numberposts' => - 1,
		'post_type'   => 'page',
		'post_status' => [ 'publish', 'draft', 'future', 'pending', 'private', ],
		'meta_query'  => [
			'relation' => 'AND',
			[ 'key'     => '_wp_page_template',
			  'value'   => 'teacher.php',
			  'compare' => '=', ],
			[ 'key'     => 'syntric_teacher_page_teacher',
			  'value'   => $user_id,
			  'compare' => '=', ],
		],
		'orderby'     => 'post_date', // oldest page returned first
		'order'       => 'ASC', ] );
	if( 0 == count( $teacher_pages ) ) {
		return false;
	}
	if( 1 < count( $teacher_pages ) ) {
		$teacher_page = array_shift( $teacher_pages );
		foreach( $teacher_pages as $page ) {
			syntric_delete_teacher_page( $page -> ID );
		}
	}
	if( 1 == count( $teacher_pages ) ) {
		$teacher_page = $teacher_pages[ 0 ];
	}

	return $teacher_page;
}

/**
 * Save a teacher page.
 *
 * @param $user_id
 */
function syntric_save_teacher_page( $user_id ) {
	$user = get_user_by( 'ID', $user_id );
	//slog( $user );
	if( ! $user instanceof WP_User ) {
		return false;
	}
	$teachers_page = syntric_get_teachers_page();
	if( ! $teachers_page instanceof WP_Post ) {
		return false;
	}
	$args         = [ 'post_status' => 'publish',
	                  'post_type'   => 'page',
	                  'post_title'  => $user -> first_name . ' ' . $user -> last_name,
	                  'post_name'   => syntric_sluggify( $user -> first_name . ' ' . $user -> last_name ),
	                  'post_author' => $user -> ID,
	                  'post_parent' => $teachers_page -> ID, ];
	$teacher_page = syntric_get_teacher_page( $user_id );
	if( $teacher_page instanceof WP_Post ) {
		$args[ 'ID' ] = $teacher_page -> ID;
	} else {
		$args[ 'post_content' ] = syntric_get_default_teacher_page_content();
	}
	$page_id = wp_insert_post( $args );
	update_post_meta( $page_id, '_wp_page_template', 'teacher.php' );
	update_field( 'syntric_teacher_page_teacher', $user_id, $page_id );
	syntric_save_teacher_role( $user_id );

	return $page_id;
	/*if( count( $teacher_pages ) && $teacher_pages[ 0 ] instanceof WP_Post ) {
		$teacher_page_id = wp_update_post( [
			'ID'          => $teacher_pages[ 0 ] -> ID,
			'post_title'  => $user -> first_name . ' ' . $user -> last_name,
			'post_author' => $user -> ID,
			'post_parent' => $teachers_page -> ID,
		] );
	} else {
		$teacher_page_id = wp_insert_post( [
			'post_status' => 'publish',
			'post_type'   => 'page',
			'post_title'  => $user -> first_name . ' ' . $user -> last_name,
			'post_name'   => syntric_sluggify( $user -> first_name . ' ' . $user -> last_name ),
			'post_author' => $user -> ID,
			'post_parent' => $teachers_page -> ID,
		] );
	}
	update_post_meta( $teacher_page_id, '_wp_page_template', 'teacher.php' );
	update_field( 'syntric_teacher_page_teacher', $user -> ID, $teacher_page_id );*/
}

/**
 * Set/save teacher role.
 *
 * Teacher role is set on the teacher's page, if any, and all it's children, if any.  Role is set
 * to author is the user has a current role set to author or less.  If the user's role is editor or
 * greater, nothing is done and the current role is retained.
 *
 * @param $user_id
 *
 * @return bool
 */
function syntric_save_teacher_role( $user_id ) {
	$user = get_user_by( 'ID', $user_id );
	if( ! $user instanceof WP_User ) {
		return false;
	}
	$user_role  = syntric_get_user_role( $user_id );
	$is_teacher = get_field( 'field_5c80dc4a54653', 'user_' . $user_id );
	if( $is_teacher ) {
		if( ! in_array( $user_role, [ 'author', 'editor', 'administrator' ] ) ) {
			$user -> set_role( 'author' );
		}
	} else {
		if( 'author' == $user_role ) {
			$user -> set_role( 'subscriber' );
		}
	}

	return true;
}

/**
 * Delete a teacher page.
 *
 * @param $user_id
 */
function syntric_delete_teacher_page( $user_id, $recursive = 1, $force_delete = 0 ) {
	$user = get_user_by( 'ID', $user_id );
	if( ! $user instanceof WP_User ) {
		return false;
	}
	$teacher_page = syntric_get_teacher_page( $user_id );
	if( ! $teacher_page instanceof WP_Post ) {
		return false;
	}
	if( $recursive ) {
		$child_pages = syntric_get_page_children( $teacher_page -> ID );
		if( $child_pages ) {
			foreach( $child_pages as $child_page ) {
				if( 'class' == get_post_type( $child_page -> ID ) ) {
					$class_id = get_field( 'syntric_class_page_class', $child_page -> ID );
					syntric_save_class( $class_id, [ 'include_page' => 0, ] );
				}
				wp_delete_post( $child_page -> ID );
			}
		}
	}
	wp_delete_post( $teacher_page -> ID );
}

//******************* User role ******************************//

/**
 * Custom role checker - "has role or higher"
 *
 * @param $role - syntric, administrator, editor, author, contributor, subscriber
 *
 * @return int (1/0 as booleans)
 */
// todo: fix this to account for user having multiple roles (eg multisite where user is superadmin, admin and editor)
function syntric_current_user_can( $role ) {
	if( in_array( strtolower( $role ), [ 'superadmin', 'super_admin', 'super admin' ] ) && is_super_admin() ) {
		return 1;
	}
	$current_user_role = syntric_current_user_role();
	switch( strtolower( $role ) ) :
		case 'administrator' :
		case 'admin':
			return in_array( $current_user_role, [ 'administrator', 'admin', ] );
		break;
		case 'editor' :
			return in_array( $current_user_role, [ 'editor', 'administrator', 'admin', ] );
		break;
		case 'author' :
			return in_array( $current_user_role, [ 'author', 'editor', 'administrator', 'admin', ] );
		break;
		case 'contributor' :
			return in_array( $current_user_role, [ 'contributor', 'author', 'editor', 'administrator', 'admin', ] );
		break;
		case 'subscriber' :
			return in_array( $current_user_role, [ 'subscriber', 'contributor', 'author', 'editor', 'administrator', 'admin', ] );
		break;
	endswitch;

	return 0;
}

// returns the highest role available to current user
function syntric_current_user_role() {
	if( is_user_logged_in() ) {
		if( is_super_admin() ) {
			return 'superadmin';
		}
		$user = wp_get_current_user();
		$role = syntric_get_user_role( $user -> ID );

		return $role;
	}

	return '';
}

// Returns the highest role available to a user
function syntric_get_user_role( $user_id = null ) {
	if( ! is_numeric( $user_id ) || 1 > $user_id ) {
		return false;
	}
	$user  = get_user_by( 'ID', $user_id );
	$roles = (array) $user -> roles;
	if( 1 == count( $roles ) ) {
		return $roles[ 0 ];
	} else {
		if( in_array( 'administrator', $roles ) ) {
			return 'administrator';
		} elseif( in_array( 'editor', $roles ) ) {
			return 'editor';
		} elseif( in_array( 'author', $roles ) ) {
			return 'author';
		} elseif( in_array( 'contributor', $roles ) ) {
			return 'contributor';
		} elseif( in_array( 'subscriber', $roles ) ) {
			return 'subscriber';
		}
	}

	return false;
}

// Get the Syntric user? This may no longer be needed..........................
function syntric_syntric_user() {
	$syntric_user = get_user_by( 'login', 'syntric' );
	if( ! $syntric_user instanceof WP_User ) {
		$syntric_user = get_user_by( 'email', 'ken@syntric.com' );
	}

	return $syntric_user;
}

//******************* User setup and maintenance ******************************//

/**
 * Reset user meta boxes but not really sure...
 *
 * @param int $user_id
 */
function syntric_reset_user_meta_boxes( $user_id = 0 ) {
	if( ! $user_id ) {
		$user_id = get_current_user_id();
	}
	$user_options = get_user_meta( $user_id );
	foreach( $user_options as $key => $value ) {
		$cuo_array = explode( '-', $key );
		if( 'meta' == $cuo_array[ 0 ] && 'box' == $cuo_array[ 1 ] ) {
			if( isset( $cuo_array[ 2 ] ) ) {
				$cuo_array_2 = explode( '_', $cuo_array[ 2 ] );
				if( 'order' == $cuo_array_2[ 0 ] ) {
					$res = delete_user_meta( $user_id, $key );
				}
			}
		}
	}
}

//******************* Boneyard ******************************//

/****************************************************************************************
 * if( ! in_array( $user_role, [ 'author', 'editor', 'administrator' ] ) ) {
 * $user -> set_role( 'author' );
 * }
 *
 * if( 'author' == $user_role ) {
 * $user -> set_role( 'subscriber' );
 * }
 * if( $include_page ) {
 * $teachers_page = syntric_get_teachers_page();
 *
 * if( count( $teacher_pages ) && $teacher_pages[ 0 ] instanceof WP_Post ) {
 * $teacher_page_id = wp_update_post( [
 * 'ID'          => $teacher_pages[ 0 ] -> ID,
 * 'post_title'  => $user -> first_name . ' ' . $user -> last_name,
 * 'post_author' => $user -> ID,
 * 'post_parent' => $teachers_page -> ID,
 * ] );
 * } else {
 * $teacher_page_id = wp_insert_post( [
 * 'post_status' => 'publish',
 * 'post_type'   => 'page',
 * 'post_title'  => $user -> first_name . ' ' . $user -> last_name,
 * 'post_name'   => syntric_sluggify( $user -> first_name . ' ' . $user -> last_name ),
 * 'post_author' => $user -> ID,
 * 'post_parent' => $teachers_page -> ID,
 * ] );
 * }
 * update_post_meta( $teacher_page_id, '_wp_page_template', 'teacher.php' );
 * update_field( 'syntric_teacher_page_teacher', $user -> ID, $teacher_page_id );
 * } else {
 * if( count( $teacher_pages ) ) {
 * $children = syntric_get_page_children( $teacher_pages[ 0 ] -> ID );
 * if( $children ) {
 * foreach( $children as $child ) {
 * wp_trash_post( $child -> ID );
 * }
 * }
 * wp_trash_post( $teacher_pages[ 0 ] -> ID );
 * }
 * }
 ****************************************************************************************/

/**
 * Get a teacher page or pages as defined by a page using the Teacher template and custom field teacher page -> teacher
 *
 * @param      $user_id
 * @param bool $include_trash
 *
 * @return WP_Post/int
 */
function __syntric_get_teacher_page( $user_id ) {
	//slog( 'syntric_get_teacher_page' );
	//slog( $user_id );
	$posts = get_posts( [
			'numberposts' => - 1,
			'post_type'   => 'page',
			'post_status' => [ 'publish', 'draft', 'future', 'pending', 'private', ],
			'meta_query'  => [
				'relationship' => 'AND',
				[ 'key'     => '_wp_page_template',
				  'value'   => 'teacher.php',
				  'compare' => '=', ],
				[ 'key'     => 'syntric_teacher_page_teacher',
				  'value'   => $user_id,
				  'compare' => '=', ],
			],
		]
	);

	return $posts;
}

function __syntric_trash_teacher_class_page( $class_id, $user_id ) {
	$class_page = syntric_get_teacher_class_page( $user_id ); // returns WP_Post object
	if( $class_page instanceof WP_Post ) {
		// Delete all children of the teacher page to be trashed, not just class pages
		/*$teacher_page_children = get_posts( [ 'numberposts' => - 1,
		                                      'post_status' => [ 'publish', 'draft', 'future', 'pending', 'private' ],
		                                      'post_parent' => $teacher_page -> ID,
		                                      'fields'      => 'ID', ] );
		if( $teacher_page_children ) {
			foreach( $teacher_page_children as $teacher_page_child ) {
				wp_trash_post( $teacher_page_child -> ID );
			}
		}*/
		$res = syntric_trash_descendant_pages( $class_page -> ID );
		if( $res ) {
			wp_delete_post( $class_page -> ID, false );
			//update_field( 'syntric_user_teacher_page', null, 'user_' . $user_id );
		}

		return 1;
	}

	return 0;
}

function __syntric_get_teacher_class_page( $user_id, $class_id ) {
	$classes         = get_field( 'syntric_classes', 'options' );
	$teacher_classes = [];
	foreach( $classes as $class ) {
		if( $user_id == $class[ 'teacher' ][ 'value' ] ) {
			$teacher_classes[] = $class;
		}
	}

	return $teacher_classes;
}










