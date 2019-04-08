<?php
	
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
		slog( 'syntric_create_class_page' );
		slog( $class );
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
	
	add_filter( 'acf/load_value/name=syntric_classes', 'syntric_load_classes_repeater', 10, 3 );
	function syntric_load_classes_repeater( $value, $post_id, $field ) {
		//$classes = get_field( 'syntric_classes', 'option' );
		//slog($syntric_classes);
		//global $pagenow;
		//slog($pagenow);
		//slog(get_current_screen());
		//slog($value);
		//slog($post_id);
		//slog($field);
		slog( $value );
		
		return $value;
	}
	
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
	
	function __syntric_get_period( $period_id ) {
		$periods = get_field( 'syn_periods', 'option' );
		foreach( $periods as $period ) {
			if( $period[ 'period_id' ] == $period_id ) {
				return $period;
			}
		}
		
		return null;
	}
	
	function __syntric_get_department( $department_id ) {
		$school      = syntric_get_school();
		$departments = $school[ 'departments' ];
		foreach( $departments as $department ) {
			if( $department[ 'department_id' ] == $department_id ) {
				return $department;
			}
		}
		
		return null;
	}
	
	function __syntric_get_room( $room_id ) {
		$rooms = get_field( 'syn_rooms', 'option' );
		foreach( $rooms as $room ) {
			if( $room[ 'room_id' ] == $room_id ) {
				return $room;
			}
		}
		
		return null;
	}
	
	function __syntric_get_building( $building_id ) {
		$buildings = get_field( 'syn_buildings', 'option' );
		foreach( $buildings as $building ) {
			if( $building[ 'building_id' ] == $building_id ) {
				return $building;
			}
		}
		
		return null;
	}
	
	function __syntric_get_class_page_teacher( $post_id ) {
		$teacher_id = get_field( 'syn_page_class_teacher', $post_id );
		$teacher    = syntric_get_teacher( $teacher_id );
		
		return $teacher;
	}
	
	/*function syntric_get_class_page_class( $post_id ) {
		$class_id = get_field(  'syntric_page_class', $post_id );

	}*/
	
	function syn____list_people_example() {
		$people = get_users( [ 'role__not_in' => [ 'administrator' ] ] );
		if( $people ) :
			$organization_is_school = syn_organization_is_school();
			usort( $people, function( $a, $b ) {
				return strnatcmp( $a -> user_lastname . ', ' . $a -> user_firstname, $b -> user_lastname . ', ' . $b -> user_firstname );
			} );
			echo '<table class="admin-list">';
			echo '<thead>';
			echo '<tr align="left">';
			echo '<th>First Name</th>';
			echo '<th>Last Name</th>';
			echo '<th>Title</th>';
			echo '<th>Email</th>';
			echo '<th>Phone</th>';
			echo '<th>Extension</th>';
			if( $organization_is_school ) {
				echo '<th colspan="2">';
				echo 'Teacher?';
				echo '</th>';
			}
			echo '</tr>';
			echo '</thead>';
			echo '<tbody>';
			$user_ids = [];
			foreach( $people as $person ) :
				$user_ids[]         = $person -> ID;
				$title              = get_field( 'syn_user_title', 'user_' . $person -> ID );
				$phone              = get_field( 'syn_user_phone', 'user_' . $person -> ID );
				$extension          = get_field( 'syn_user_extension', 'user_' . $person -> ID );
				$is_teacher         = get_field( 'syn_user_is_teacher', 'user_' . $person -> ID );
				$is_teacher_checked = ( $is_teacher ) ? 'checked="checked"' : '';
				$teacher_page       = syn_get_teacher_page( $person -> ID );
				echo '<tr>';
				echo '<td>' . '<input type="hidden" name="user_' . $person -> ID . '-username" value="' . $person -> user_login . '"><input type="text" name="user_' . $person -> ID . '-first_name" value="' . $person -> user_firstname . '">' . '</td>';
				echo '<td>' . '<input type="text" name="user_' . $person -> ID . '-last_name" value="' . $person -> user_lastname . '">' . '</td>';
				echo '<td>' . '<input type="text" name="user_' . $person -> ID . '-title" value="' . $title . '">' . '</td>';
				echo '<td>' . '<input type="text" name="user_' . $person -> ID . '-email" value="' . $person -> user_email . '">' . '</td>';
				echo '<td>' . '<input type="text" name="user_' . $person -> ID . '-phone" value="' . $phone . '">' . '</td>';
				echo '<td width="5%">' . '<input type="text" name="user_' . $person -> ID . '-extension" value="' . $extension . '">' . '</td>';
				if( $organization_is_school ) {
					echo '<td nowrap>';
					echo '<input type="checkbox" name="user_' . $person -> ID . '-is_teacher" value="' . $is_teacher . '" ' . $is_teacher_checked . '">';
					if( $teacher_page ) {
						$status = ( 'publish' != $teacher_page -> post_status ) ? ' - ' . $teacher_page -> post_status : '';
						echo '<span style="margin-left: 6px;">';
						echo $teacher_page -> post_title . ucfirst( $status );
						echo '</span>';
						if( 'trash' != $teacher_page -> post_status ) {
							echo '<span style="float: right;">';
							echo '<a href="' . get_the_permalink( $teacher_page -> ID ) . '" target="_blank">View</a>';
							echo ' / <a href="/wp-admin/post.php?action=edit&post=' . $teacher_page -> ID . '" target="_blank">Edit</a>';
							echo '</span>';
						}
					}
					echo '</td>';
				}
				echo '</tr>';
			endforeach;
			echo '</tbody>';
			echo '</table>';
			echo '<input type="hidden" name="user_ids" value="' . implode( ',', $user_ids ) . '">';
		endif;
	}
	
	/*************************************** Teacher Class *****************************************/
	function __syntric_get_teacher_class( $teacher_id, $class_id ) {
		if( $teacher_id && $class_id ) {
			$teacher_page    = syntric_get_teacher_page( $teacher_id );
			$teacher_classes = syntric_get_teacher_classes( $teacher_page -> ID );
			if( $teacher_classes ) {
				foreach( $teacher_classes as $teacher_class ) {
					if( $class_id == $teacher_class[ 'class_id' ] ) {
						return $teacher_class;
					}
				}
			}
		}
		
		return false;
	}
	
	/*************************************** Teacher Classes *****************************************/
	function __syntric_get_teacher_classes( $post_id ) {
		$post_id = syntric_resolve_post_id( $post_id );
		$classes = get_field( 'syn_classes', $post_id );
		
		return $classes;
	}
	
	/**
	 * Get a teacher's class page
	 *
	 * @param $teacher_id (int) required - the user ID for the teacher
	 * @param $class_id   (int) required - the ID for the class (not the post ID of the class page)
	 *
	 * @return array - array of class pages for teacher with $teacher_id and class with $class_id (expect only 1)
	 */
	function syntric_get_teacher_class_page( $teacher_id, $class_id, $include_trash = false ) {
		$post_statuses = [ 'publish',
		                   'draft',
		                   'future',
		                   'pending',
		                   'private', ];
		if( $include_trash ) {
			$post_statuses[] = 'trash';
		}
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
	
	function __syntric_save_teacher_classes( $teacher_id ) {
		$teacher_page = syntric_get_teacher_page( $teacher_id );
		if( $teacher_page instanceof WP_Post ) {
			// Get existing teacher class pages
			$teacher_class_pages = syntric_get_teacher_class_pages( $teacher_id, true );
			// Array to hold existing teacher class ids
			$existing_teacher_class_ids = [];
			// Array to hold existing teacher class pages, indexed by class_id (full WP_Post objects)
			$existing_teacher_class_pages = [];
			// If teacher already had class pages, index them for reference later in the function
			if( $teacher_class_pages ) {
				// Iterate over existing teacher class pages adding class ids and WP_Post objects to arrays
				foreach( $teacher_class_pages as $teacher_class_page ) {
					$class_id                                  = get_field( 'syn_page_class', $teacher_class_page -> ID );
					$existing_teacher_class_ids[]              = $class_id;
					$existing_teacher_class_pages[ $class_id ] = $teacher_class_page;
				}
			}
			// Get classes registered on teacher page
			$teacher_classes = syntric_get_teacher_classes( $teacher_page -> ID );
			$tc_ids          = [];
			$tc_args         = [];
			if( $teacher_classes ) {
				$departments_active = get_field( 'syn_departments_active', 'option' );
				$courses            = get_field( 'syn_courses', 'option' );
				$courses            = array_column( $courses, 'course', 'course_id' );
				foreach( $teacher_classes as $teacher_class ) {
					$include_page = $teacher_class[ 'include_page' ];
					if( $include_page ) {
						$tc_ids[] = $teacher_class[ 'class_id' ];
						$cp_title = ( $departments_active ) ? $courses[ $teacher_class[ 'course' ] ] : $teacher_class[ 'course' ];
						//$cp_title                                = $courses[ $teacher_class[ 'course' ] ];
						$tc_args[ $teacher_class[ 'class_id' ] ] = [ 'post_type'   => 'page',
						                                             'post_title'  => $cp_title,
						                                             'post_name'   => syntric_sluggify( $cp_title ),
						                                             'post_author' => $teacher_id,
						                                             'post_parent' => $teacher_page -> ID, ];
					}
				}
				// does - classes that have a page
				$does = array_intersect( $tc_ids, $existing_teacher_class_ids );
				if( count( $does ) ) {
					foreach( $does as $class_id ) {
						$args                   = $tc_args[ $class_id ];
						$tcp                    = $existing_teacher_class_pages[ $class_id ];
						$args[ 'ID' ]           = $tcp -> ID;
						$args[ 'post_status' ]  = ( 'trash' == $tcp -> post_status ) ? 'draft' : $tcp -> post_status;
						$args[ 'post_content' ] = $tcp -> post_content;
						$tcp_id                 = wp_update_post( $args );
						update_post_meta( $tcp_id, '_wp_page_template', 'class.php' );
						update_field( 'syn_page_class_teacher', $teacher_id, $tcp_id );
						update_field( 'syn_page_class', $class_id, $tcp_id );
						update_field( 'syn_contact_active', 1, $tcp_id );
						update_field( 'syn_contact_title', 'Contact Teacher', $tcp_id );
						update_field( 'syn_contact_contact_type', 'person', $tcp_id );
						update_field( 'syn_contact_person', $teacher_id, $tcp_id );
						update_field( 'syn_contact_include_person_fields', [ 'prefix',
						                                                     'first_name',
						                                                     'title',
						                                                     'email',
						                                                     'phone', ], $tcp_id );
					}
				}
				// should - classes that should have a page, but don't
				$should = array_diff( $tc_ids, $existing_teacher_class_ids );
				if( count( $should ) ) {
					foreach( $should as $class_id ) {
						$args                   = $tc_args[ $class_id ];
						$args[ 'post_status' ]  = 'draft';
						$args[ 'post_content' ] = syntric_get_default_class_page_content();
						$tcp_id                 = wp_insert_post( $args );
						update_post_meta( $tcp_id, '_wp_page_template', 'class.php' );
						update_field( 'syn_page_class_teacher', $teacher_id, $tcp_id );
						update_field( 'syn_page_class', $class_id, $tcp_id );
						update_field( 'syn_contact_active', 1, $tcp_id );
						update_field( 'syn_contact_title', 'Contact Teacher', $tcp_id );
						update_field( 'syn_contact_contact_type', 'person', $tcp_id );
						update_field( 'syn_contact_person', $teacher_id, $tcp_id );
						update_field( 'syn_contact_include_person_fields', [ 'prefix',
						                                                     'first_name',
						                                                     'title',
						                                                     'email',
						                                                     'phone', ], $tcp_id );
					}
				}
				// should_not - classes that have a page but shouldn't
				$should_not = array_diff( $existing_teacher_class_ids, $tc_ids );
				if( count( $should_not ) ) {
					foreach( $should_not as $class_id ) {
						$del_res = syntric_trash_teacher_class_page( $teacher_id, $class_id );
					}
				}
			}
		}
		
		return;
	}
	
	/**
	 * Trash a teacher's class page
	 *
	 * @param $teacher_id (int) required - the user ID for the teacher
	 * @param $class_id   (int) required - the ID for the class (not the post ID of the class page)
	 */
	function syntric_trash_teacher_class_page( $teacher_id, $class_id ) {
		$class_page = syntric_get_teacher_class_page( $teacher_id, $class_id );
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