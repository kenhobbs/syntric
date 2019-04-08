<?php
	/**
	 * Advanced Custom Fields
	 */
	
	/**
	 * This catches all ID fields
	 */
	add_filter( 'acf/update_value/name=id', 'syntric_set_id', 10, 3 );
	function syntric_set_id( $value, $post_id, $field ) {
		if( is_admin() && 'id' == $field[ '_name' ] && empty( $value ) ) {
			return syntric_unique_id();
		}
		
		return $value;
	}
	
	/**
	 * Format phone fields
	 */
	add_filter( 'acf/update_value/name=phone', 'syntric_format_phone' );
	add_filter( 'acf/update_value/name=fax', 'syntric_format_phone' );
	function syntric_format_phone( $value ) {
		// is $phone set and at least 7 characters
		if( strlen( $value ) < 7 ) {
			return '';
		}
		// strip out all non-numeric characters
		$phone  = preg_replace( "/[^0-9]/", "", $value );
		$length = strlen( $phone );
		// after stripping out non-numerics, check again if $phone at least 7 digits
		if( strlen( $phone ) < 7 ) {
			return '';
		}
		switch( $length ) {
			case 7:
				$value = preg_replace( "/([0-9]{3})([0-9]{4})/", "$1-$2", $phone );
				break;
			case 10:
				$value = preg_replace( "/([0-9]{3})([0-9]{3})([0-9]{4})/", "($1) $2-$3", $phone );
				break;
			case 11: // if 11 characters, assume 1 prefix, strip it out
				$value = preg_replace( "/([0-9]{1})([0-9]{3})([0-9]{3})([0-9]{4})/", "($2) $3-$4", $phone );
				break;
			default:
				$value = $phone;
				break;
		}
		
		return $value;
	}
	
	/**
	 * Nullify field
	 * Set select options ($field['choices']) to null - will be populated with acf/prepare_field below
	 * add_filter( 'acf/load_field/name=term', 'syntric_nullify_field_options' );
	 * add_filter( 'acf/load_field/name=department', 'syntric_nullify_field_options' );
	 * add_filter( 'acf/load_field/name=course', 'syntric_nullify_field_options' );
	 * add_filter( 'acf/load_field/name=period', 'syntric_nullify_field_options' );
	 * add_filter( 'acf/load_field/name=room', 'syntric_nullify_field_options' );
	 * // filters
	 * add_filter( 'acf/load_field/name=post_type_value', 'syntric_nullify_field_options' );
	 * add_filter( 'acf/load_field/name=page_template_value', 'syntric_nullify_field_options' );
	 * add_filter( 'acf/load_field/name=syn_post_category', 'syntric_nullify_field_options' );
	 * add_filter( 'acf/load_field/name=syn_post_microblog', 'syntric_nullify_field_options' );
	 * // pages
	 * add_filter( 'acf/load_field/name=syn_page_class', 'syntric_nullify_field_options' );
	 * add_filter( 'acf/load_field/name=syn_page_class_teacher', 'syntric_nullify_field_options' );
	 * add_filter( 'acf/load_field/name=syn_page_teacher', 'syntric_nullify_field_options' );
	 * add_filter( 'acf/load_field/name=syn_page_department', 'syntric_nullify_field_options' );
	 * add_filter( 'acf/load_field/name=syn_page_course', 'syntric_nullify_field_options' );
	 * // page widgets
	 * add_filter( 'acf/load_field/name=syn_google_map_id', 'syntric_nullify_field_options' );
	 * add_filter( 'acf/load_field/name=syn_contact_person', 'syntric_nullify_field_options' );
	 * add_filter( 'acf/load_field/name=syn_contact_organization', 'syntric_nullify_field_options' );
	 * add_filter( 'acf/load_field/key=field_59b118daf73d0', 'syntric_nullify_field_options' ); // Google Maps > markers > organization select
	 * add_filter( 'acf/load_field/key=field_59af852be10d5', 'syntric_nullify_field_options' ); // Page Widgets > roster > person
	 * // users
	 * // widgets
	 * add_filter( 'acf/load_field/name=syn_contact_widget_organization', 'syntric_nullify_field_options' );
	 * add_filter( 'acf/load_field/name=syn_contact_widget_person', 'syntric_nullify_field_options' );
	 * add_filter( 'acf/load_field/name=syn_upcoming_events_widget_calendar_id', 'syntric_nullify_field_options' );
	 * add_filter( 'acf/load_field/name=syn_nav_menu_widget_menu', 'syntric_nullify_field_options' );
	 * add_filter( 'acf/load_field/name=syn_facebook_page_widget_page', 'syntric_nullify_field_options' );
	 * add_filter( 'acf/load_field/name=syn_google_map_widget_map_id', 'syntric_nullify_field_options' );
	 * // options
	 *
	 * // syn_calendar
	 * add_filter( 'acf/load_field/name=display_calendar_id', 'syntric_nullify_field_options' );
	 * function syntric_nullify_field_options( $field ) {
	 * $field[ 'choices' ] = null;
	 *
	 * return $field;
	 * }
	 */
	
	/**add_filter( 'acf/prepare_field/name=google_map_id', 'syntric_hide_field' );
	 * //add_filter( 'acf/prepare_field/name=facebook_page_id', 'syntric_hide_field' );
	 * //add_filter( 'acf/prepare_field/name=department_id', 'syntric_hide_field' );
	 * //add_filter( 'acf/prepare_field/name=course_id', 'syntric_hide_field' );
	 * //add_filter( 'acf/prepare_field/name=period_id', 'syntric_hide_field' );
	 * //add_filter( 'acf/prepare_field/name=building_id', 'syntric_hide_field' );
	 * //add_filter( 'acf/prepare_field/name=room_id', 'syntric_hide_field' );
	 * //add_filter( 'acf/prepare_field/name=sidebar_id', 'syntric_hide_field' );
	 * //add_filter( 'acf/prepare_field/name=organization_id', 'syntric_hide_field' );
	 * //add_filter( 'acf/prepare_field/name=syn_organization_id', 'syntric_hide_field' );
	 * //add_filter( 'acf/prepare_field/name=jumbotron_id', 'syntric_hide_field' );
	 * //add_filter( 'acf/prepare_field/key=field_59a5b62d88d98', 'syntric_hide_field' ); // Page widgets Contact tab
	 * //add_filter( 'acf/prepare_field/name=syn_contact_active', 'syntric_hide_field' );
	 * //add_filter( 'acf/prepare_field/name=syn_contact_title', 'syntric_hide_field' );
	 * //add_filter( 'acf/prepare_field/name=syn_contact_contact_type', 'syntric_hide_field' );
	 * //add_filter( 'acf/prepare_field/name=syn_contact_person', 'syntric_hide_field' );
	 * //add_filter( 'acf/prepare_field/name=syn_contact_organization', 'syntric_hide_field' );
	 * //add_filter( 'acf/prepare_field/name=syn_contact_default', 'syntric_hide_field' );
	 * //add_filter( 'acf/prepare_field/name=syn_contact_include_person_fields', 'syntric_hide_field' );
	 * //add_filter( 'acf/prepare_field/name=syn_contact_include_organization_fields', 'syntric_hide_field' );
	 * //add_filter( 'acf/prepare_field/key=field_5ac25dab1f08d', 'syntric_hide_field' ); // Page widgets Attachments tab
	 * //add_filter( 'acf/prepare_field/name=syn_attachments_active', 'syntric_hide_field' );
	 * //add_filter( 'acf/prepare_field/name=syn_attachments_title', 'syntric_hide_field' );
	 * //add_filter( 'acf/prepare_field/name=syn_attachments', 'syntric_hide_field' );
	 *
	 *
	 * function syntric_hide_field( $field ) {
	 * global $post;
	 * if( $post instanceof WP_Post ) {
	 * $page_template = syntric_get_page_template( $post -> ID );
	 * if( 'page' != $post -> post_type || ( 'page' == $post -> post_type && in_array( $page_template, [ 'teachers' ] ) && ( in_array( $field[ '_name' ],
	 * [ 'syn_contact_active',
	 * 'syn_contact_title',
	 * 'syn_contact_contact_type',
	 * 'syn_contact_person',
	 * 'syn_contact_organization',
	 * 'syn_contact_default',
	 * 'syn_contact_include_person_fields',
	 * 'syn_contact_include_organization_fields',
	 * ] ) || 'field_59a5b62d88d98' == $field[ 'key' ] ) ) ) {
	 * $field[ 'wrapper' ][ 'hidden' ] = 1;
	 * }
	 * }
	 * }*/
	
	/**********************************************************
	 * acf/prepare_field filters
	 */
	// School field group
	add_filter( 'acf/prepare_field/name=teacher', 'syntric_acf_prepare_fields' ); // this is also on teacher page
	add_filter( 'acf/prepare_field/name=term', 'syntric_acf_prepare_fields' );
	add_filter( 'acf/prepare_field/name=schedules', 'syntric_acf_prepare_fields' );
	add_filter( 'acf/prepare_field/name=period', 'syntric_acf_prepare_fields' );
	add_filter( 'acf/prepare_field/name=course', 'syntric_acf_prepare_fields' );
	add_filter( 'acf/prepare_field/name=prerequisite_course', 'syntric_acf_prepare_fields' );
	add_filter( 'acf/prepare_field/name=department', 'syntric_acf_prepare_fields' );
	add_filter( 'acf/prepare_field/name=building', 'syntric_acf_prepare_fields' );
	add_filter( 'acf/prepare_field/name=room', 'syntric_acf_prepare_fields' );
	add_filter( 'acf/prepare_field/name=class', 'syntric_acf_prepare_fields' );
	//add_filter( 'acf/prepare_field/name=class_page', 'syntric_acf_prepare_fields' );
	
	// Filters field group - is cloned frequently
	add_filter( 'acf/prepare_field/name=page_template_value', 'syntric_acf_prepare_fields' );
	add_filter( 'acf/prepare_field/name=post_type_value', 'syntric_acf_prepare_fields' );
	
	// User field group
	add_filter( 'acf/prepare_field/name=teacher_page', 'syntric_acf_prepare_fields' );
	
	// Upcoming Events Widget field group
	add_filter( 'acf/prepare_field/name=calendar', 'syntric_acf_prepare_fields' );
	
	// Contact Widget & Roster Widget
	add_filter( 'acf/prepare_field/name=person', 'syntric_acf_prepare_fields' );
	add_filter( 'acf/prepare_field/name=organization', 'syntric_acf_prepare_fields' );
	
	// Facebook Widget
	add_filter( 'acf/prepare_field/name=facebook_page', 'syntric_acf_prepare_fields' );
	
	// blocks
	//add_filter( 'acf/prepare_field/name=wordpress_blocks', 'syntric_acf_prepare_fields' );
	//add_filter( 'acf/prepare_field/name=syn_post_category', 'syntric_acf_prepare_fields' );
	//add_filter( 'acf/prepare_field/name=syn_post_microblog', 'syntric_acf_prepare_fields' );
	//add_filter( 'acf/prepare_field/name=syn_page_class', 'syntric_acf_prepare_fields' );
	//add_filter( 'acf/prepare_field/name=syn_page_class_teacher', 'syntric_acf_prepare_fields' );
	//add_filter( 'acf/prepare_field/name=syn_page_teacher', 'syntric_acf_prepare_fields' );
	//add_filter( 'acf/prepare_field/name=syn_page_department', 'syntric_acf_prepare_fields' );
	//add_filter( 'acf/prepare_field/name=syn_page_course', 'syntric_acf_prepare_fields' );
	//add_filter( 'acf/prepare_field/name=include_page', 'syntric_acf_prepare_fields' );
	/****************** Page widgets ******************/
	//add_filter( 'acf/prepare_field/name=syn_contact_title', 'syntric_acf_prepare_fields' );
	//add_filter( 'acf/prepare_field/name=syn_contact_default', 'syntric_acf_prepare_fields' );
	//add_filter( 'acf/prepare_field/name=syn_contact_person', 'syntric_acf_prepare_fields' );
	//add_filter( 'acf/prepare_field/name=person', 'syntric_acf_prepare_fields' );
	//add_filter( 'acf/prepare_field/name=syn_contact_person_include_fields', 'syntric_acf_prepare_fields' );
	//add_filter( 'acf/prepare_field/name=syn_contact_organization', 'syntric_acf_prepare_fields' );
	//add_filter( 'acf/prepare_field/name=syn_contact_organization_include_fields', 'syntric_acf_prepare_fields' );
	//add_filter( 'acf/prepare_field/name=syn_roster_title', 'syntric_acf_prepare_fields' );
	//add_filter( 'acf/prepare_field/name=syn_roster_include_fields', 'syntric_acf_prepare_fields' );
	//add_filter( 'acf/prepare_field/name=syn_calendar_title', 'syntric_acf_prepare_fields' );
	//add_filter( 'acf/prepare_field/name=syn_calendar_id', 'syntric_acf_prepare_fields' );
	//add_filter( 'acf/prepare_field/name=syn_microblog_title', 'syntric_acf_prepare_fields' );
	//add_filter( 'acf/prepare_field/name=syn_microblog_category', 'syntric_acf_prepare_fields' );
	//add_filter( 'acf/prepare_field/name=syn_microblog_term', 'syntric_acf_prepare_fields' );
	//add_filter( 'acf/prepare_field/name=syn_microblog_category_select', 'syntric_acf_prepare_fields' );
	//add_filter( 'acf/prepare_field/name=syn_microblog_term_select', 'syntric_acf_prepare_fields' );
	//add_filter( 'acf/prepare_field/name=syn_new_microblog_post', 'syntric_acf_prepare_fields' );
	//add_filter( 'acf/prepare_field/name=syn_attachments_active', 'syntric_acf_prepare_fields' );
	//add_filter( 'acf/prepare_field/name=syn_attachments_title', 'syntric_acf_prepare_fields' );
	//add_filter( 'acf/prepare_field/name=syn_attachments', 'syntric_acf_prepare_fields' );
	//add_filter( 'acf/prepare_field/name=syn_video_active', 'syntric_acf_prepare_fields' );
	//add_filter( 'acf/prepare_field/name=syn_video_title', 'syntric_acf_prepare_fields' );
	//add_filter( 'acf/prepare_field/name=syn_video_host', 'syntric_acf_prepare_fields' );
	//add_filter( 'acf/prepare_field/name=syn_video_youtube_id', 'syntric_acf_prepare_fields' );
	//add_filter( 'acf/prepare_field/name=syn_video_vimeo_id', 'syntric_acf_prepare_fields' );
	//add_filter( 'acf/prepare_field/name=syn_video_caption', 'syntric_acf_prepare_fields' );
	//add_filter( 'acf/prepare_field/name=syn_google_map_active', 'syntric_acf_prepare_fields' );
	//add_filter( 'acf/prepare_field/name=syn_google_map_title', 'syntric_acf_prepare_fields' );
	//add_filter( 'acf/prepare_field/name=syn_google_map_id', 'syntric_acf_prepare_fields' );
	/****************** Users ******************/
	//add_filter( 'acf/prepare_field/name=syn_user_page', 'syntric_acf_prepare_fields' );
	//add_filter( 'acf/prepare_field/name=syn_user_is_teacher', 'syntric_acf_prepare_fields' );
	//add_filter( 'acf/prepare_field/key=field_5ad3b76d1ea8b', 'syntric_acf_prepare_fields' );
	/****************** Widgets ******************/
	//add_filter( 'acf/prepare_field/name=syn_contact_widget_default', 'syntric_acf_prepare_fields' );
	//add_filter( 'acf/prepare_field/name=syn_contact_widget_organization', 'syntric_acf_prepare_fields' );
	//add_filter( 'acf/prepare_field/name=syn_contact_widget_person', 'syntric_acf_prepare_fields' );
	//add_filter( 'acf/prepare_field/name=syn_upcoming_events_widget_calendar_id', 'syntric_acf_prepare_fields' );
	//add_filter( 'acf/prepare_field/name=syn_nav_menu_widget_menu', 'syntric_acf_prepare_fields' );
	//add_filter( 'acf/prepare_field/name=syn_facebook_page_widget_page', 'syntric_acf_prepare_fields' );
	//add_filter( 'acf/prepare_field/name=syn_google_map_widget_map_id', 'syntric_acf_prepare_fields' );
	// Retired????  Check these
	//add_filter( 'acf/prepare_field/name=default_organization', 'syntric_acf_prepare_fields' );
	//add_filter( 'acf/prepare_field/name=class_id', 'syntric_acf_prepare_fields' );
	//add_filter( 'acf/prepare_field/name=include_page', 'syntric_acf_prepare_fields' );
	//add_filter( 'acf/prepare_field/name=page', 'syntric_acf_prepare_fields' );
	/****************** Calendar CPT ******************/
	//add_filter( 'acf/prepare_field/name=syn_calendar_last_sync', 'syntric_acf_prepare_fields' );
	//add_filter( 'acf/prepare_field/name=syn_calendar_last_sync_result', 'syntric_acf_prepare_fields' );
	/****************** Taxonomy ******************/
	//add_filter( 'acf/prepare_field/name=syn_category_page', 'syntric_acf_prepare_fields' );
	// by key
	//add_filter( 'acf/prepare_field/key=field_59b118daf73d0', 'syntric_acf_prepare_fields' ); // Google Maps > markers > organization select field
	//add_filter( 'acf/prepare_field/key=field_59d127e512d9a', 'syntric_acf_prepare_fields', 20 ); // syntric_classes Class Page message field
	//add_filter( 'acf/prepare_field/key=field_59e7f2f7049e2', 'syntric_acf_prepare_fields' ); // Post category message field
	//add_filter( 'acf/prepare_field/key=field_59e7f370049e3', 'syntric_acf_prepare_fields' ); // Post term (microblog) message field
	//add_filter( 'acf/prepare_field/key=field_59d4b242abf31', 'syntric_acf_prepare_fields' ); // Page widgets microblog category message field
	//add_filter( 'acf/prepare_field/key=field_59d4b278abf32', 'syntric_acf_prepare_fields' ); // Page widgets microblog term message field
	//add_filter( 'acf/prepare_field/key=field_598a9db813a14', 'syntric_acf_prepare_fields' ); // Page widgets Attachments tab
	//add_filter( 'acf/prepare_field/key=field_5a41bf632a648', 'syntric_acf_prepare_fields' ); // Page widgets Google Map tab
	//add_filter( 'acf/prepare_field/key=field_5a41c15b2a64c', 'syntric_acf_prepare_fields' ); // Page widgets video tab
	//add_filter( 'acf/prepare_field/key=field_59bb8bf493b01', 'syntric_acf_prepare_fields' ); // Courses > department select field
	//add_filter( 'acf/prepare_field/key=field_59bb90c45ec6d', 'syntric_acf_prepare_fields' ); // Rooms > building select field
	
	function syntric_acf_prepare_fields( $field ) {
		global $pagenow;
		switch( $field[ '_name' ] ) :
			// school
			case 'teacher' :
				if( 'select' == $field[ 'type' ] ) {
					$choices  = [];
					$teachers = syntric_get_teachers();
					if( $teachers ) {
						foreach( $teachers as $teacher ) {
							$choices[ $teacher -> ID ] = $teacher -> display_name;
						}
					}
					$field[ 'choices' ] = $choices;
				}
				break;
			case 'term' :
				if( 'select' == $field[ 'type' ] ) {
					$terms = syntric_get_terms();
					if( $terms ) {
						$choices = [];
						foreach( $terms as $term ) {
							$choices[ $term[ 'term' ] ] = $term[ 'term' ];
						}
						$field[ 'choices' ] = $choices;
					}
				}
				break;
			case 'department' :
				$departments = get_field( 'syntric_departments', 'option' );
				if( $departments ) {
					$choices = [];
					foreach( $departments as $department ) {
						$choices[ $department[ 'id' ] ] = $department[ 'department' ];
					}
				}
				
				$field[ 'choices' ] = $choices;
				break;
			case 'schedules' :
				$schedules = get_field( 'syntric_schedules', 'option' );
				if( $schedules ) {
					$choices = [];
					foreach( $schedules as $schedule ) {
						$choices[ $schedule[ 'name' ] ] = $schedule[ 'name' ];
					}
				}
				
				$field[ 'choices' ] = $choices;
				break;
			case 'period' :
				$schedules         = get_field( 'syntric_schedules', 'option' );
				$regular_schedules = [];
				foreach( $schedules as $schedule ) {
					if( 'regular' == $schedule[ 'schedule_type' ] ) {
						$regular_schedules[] = $schedule;
					}
				}
				// now with regular schedules
				$choices = [];
				foreach( $regular_schedules as $regular_schedule ) {
					$_schedule = $regular_schedule[ 'schedule' ];
					foreach( $_schedule as $__schedule ) {
						$choices[ $__schedule[ 'period' ] ] = $__schedule[ 'period' ];
					}
				}
				$field[ 'choices' ] = $choices;
				break;
			case 'course' :
				$courses = get_field( 'syntric_courses', 'option' );
				if( $courses ) {
					$choices = [];
					//slog($courses);
					foreach( $courses as $course ) {
						//slog($course);
						$choices[ $course[ 'id' ] ] = $course[ 'course' ];
					}
					$field[ 'choices' ] = $choices;
				}
				break;
			case 'building' :
				$buildings = get_field( 'syntric_buildings', 'option' );
				if( $buildings ) {
					$choices = [];
					foreach( $buildings as $building ) {
						$choices[ $building[ 'id' ] ] = $building[ 'building' ];
					}
					$field[ 'choices' ] = $choices;
				}
				break;
			case 'room' :
				$rooms = get_field( 'syntric_rooms', 'option' );
				if( $rooms ) {
					$choices = [];
					foreach( $rooms as $room ) {
						//$bld = ( !  empty( $room['building'] ) ) ? $room['building']['label'] . ' ' : '';
						$choices[ $room[ 'id' ] ] = $room[ 'room' ];
					}
					$field[ 'choices' ] = $choices;
				}
				break;
			case 'class' :
				$classes = get_field( 'syntric_classes', 'option' );
				if( $classes ) {
					$choices = [];
					asort( $classes, '' );
					foreach( $classes as $class ) {
						$teacher                   = $class[ 'teacher' ];
						$user                      = get_user_by( 'ID', $teacher );
						$course                    = $class[ 'course' ][ 'label' ];
						$period                    = $class[ 'period' ][ 'label' ];
						$room                      = $class[ 'room' ][ 'label' ];
						$class_id                  = $user -> display_name . '.' . $course . '.' . $period;
						$choices[ $class[ 'id' ] ] = $course . ' / ' . $user -> first_name . ' ' . $user -> last_name . ' / ' . $period;
					}
					$field[ 'choices' ] = $choices;
				}
				break;
			// filters
			case 'post_type_value' :
				$post_types = get_post_types( [ 'public' => true ], 'objects' );
				$choices    = [];
				foreach( $post_types as $post_type ) {
					$choices[ $post_type -> name ] = $post_type -> labels -> singular_name;
				}
				$field[ 'choices' ] = $choices;
				break;
			case 'page_template_value' :
				$page_templates = get_page_templates();
				$choices        = [];
				foreach( $page_templates as $key => $value ) {
					$choices[ $value ] = $key;
				}
				$field[ 'choices' ] = $choices;
				break;
			// user
			case 'teacher_page' :
				//if( 'profile.php' == $pagenow || 'user-new.php' == $pagenow ) {
				$teacher_pages = syntric_get_teacher_pages();
				if( $teacher_pages ) {
					$choices = [];
					foreach( $teacher_pages as $teacher_page ) {
						$choices[ $teacher_page -> ID ] = $teacher_page -> post_title;
					}
					$field[ 'choices' ] = $choices;
				}
				//}
				break;
			// upcoming events widget
			case 'calendar' :
				$calendars = syntric_get_calendars();
				if( $calendars ) {
					$choices = [];
					foreach( $calendars as $calendar ) {
						$choices[ $calendar -> ID ] = $calendar -> post_title;
					}
					$field[ 'choices' ] = $choices;
				}
				break;
			// contact widget
			case 'person' :
				if( 'select' == $field[ 'type' ] ) {
					$users = get_users( [
						'meta_key' => 'last_name',
						'order_by' => 'meta_value',
					] );
					if( $users ) {
						$choices = [];
						foreach( $users as $user ) {
							$user_custom_fields     = get_field( 'syntric_user', 'user_' . $user -> ID );
							$label                  = ( ! empty( $prefix ) ) ? $prefix . ' ' . $user -> display_name : $user -> display_name;
							$label                  .= ( ! empty( $user_custom_fields[ 'title' ] ) ) ? ' - ' . str_replace( '|', ' / ', $user_custom_fields[ 'title' ] ) : ' - (no title)';
							$label                  .= ( ! empty( $user -> user_email ) ) ? ' - ' . $user -> user_email : ' - (no email)';
							$label                  .= ( ! empty( $user_custom_fields[ 'phone' ] ) ) ? ' - ' . $user_custom_fields[ 'phone' ] : ' - (no phone)';
							$label                  .= ( ! empty( $user_custom_fields[ 'ext' ] ) ) ? ' x' . $user_custom_fields[ 'ext' ] : '';
							$choices[ $user -> ID ] = $label;
						}
						$field[ 'choices' ] = $choices;
					}
				}
				break;
			case 'organization' :
				if( 'select' == $field[ 'type' ] ) {
					$choices      = [];
					$organization = get_field( 'syntric_organization', 'option' );
					if( $organization ) {
						$choices[ $organization[ 'name' ] ] = $organization[ 'name' ];
					}
					$organizations = get_field( 'syntric_organizations', 'option' );
					if( $organizations ) {
						foreach( $organizations as $_organization ) {
							$choices[ $_organization[ 'name' ] ] = $_organization[ 'name' ];
						}
					}
					$field[ 'choices' ] = $choices;
				}
				break;
			// Facebook widget
			case 'facebook_page' :
				if( 'select' == $field[ 'type' ] ) {
					$choices      = [];
					$organization = get_field( 'syntric_organization', 'option' );
					if( $organization ) {
						$choices[ $organization[ 'facebook_page' ] ] = $organization[ 'name' ];
					}
					$organizations = get_field( 'syntric_organizations', 'option' );
					if( $organizations ) {
						foreach( $organizations as $_organization ) {
							$choices[ $_organization[ 'facebook_page' ] ] = $_organization[ 'name' ];
						}
					}
					$field[ 'choices' ] = $choices;
				}
				break;
			// blocks
			/*case 'wordpress_blocks' :
				$field = syntric_load_blocks( $field );
				break;*/
			//
			//
			//
			//
			//
			//
			/**
			 * Maybe in use
			 */
			/*case 'syntric_contact_widget_organization' :
				$field = syntric_load_organizations( $field );
				break;*/
			/*case 'syntric_contact_widget_person' :
				$field = syntric_load_people( $field );
				break;*/
			/*case 'person' :
				$field = syntric_load_people( $field );
				break;*/
			/*case 'syntric_upcoming_events_widget_calendar_id' :
				$field = syntric_load_google_calendars( $field );
				break;*/
			/*case 'syntric_nav_menu_widget_menu' :
				if( 'select' == $field[ 'type' ] ) {
					$choices = [];
					$menus   = get_terms( 'nav_menu', [ 'hide_empty' => true ] );
					foreach( $menus as $menu ) {
						$choices[ $menu -> term_id ] = $menu -> name;
					}
					$field[ 'choices' ] = $choices;
				}
				break;*/
			/*case 'syntric_contact_widget_default' :
				$field[ 'label' ]   = '';
				$field[ 'message' ] = 'Use ' . get_field( 'syn_organization', 'option' );
				break;*/
			/*case 'syntric_contact_default' :
				$field[ 'label' ]   = get_field( 'syn_organization', 'option' );
				$field[ 'message' ] = 'Use ' . get_field( 'syn_organization', 'option' );
				break;*/
			/*case 'syntric_facebook_page_widget_page' :
				if( 'select' == $field[ 'type' ] ) {
					$settings = get_field( 'syntric_settings', 'option' );
					$facebook_pages = $settings['facebook_pages'];
					if( $facebook_pages ) {
						$choices        = [];
						foreach( $facebook_pages as $facebook_page ) {
							$choices[ $facebook_page[ 'facebook_page_id' ] ] = $facebook_page[ 'name' ];
						}
					}
					$field[ 'choices' ] = $choices;
				}
				break;*/
			/*case 'syntric_google_map_widget_map_id' :*/
			/*case 'syntric_google_map_id' :
				if( 'select' == $field[ 'type' ] ) {
					$choices     = [];
					$google_maps = get_field( 'syn_google_maps', 'option' );
					if( $google_maps ) {
						foreach( $google_maps as $google_map ) {
							$choices[ $google_map[ 'google_map_id' ] ] = $google_map[ 'name' ];
						}
					}
					$field[ 'choices' ] = $choices;
				}
				break;*/
			/*case 'default_organization' : // field_59efde970195f is in Google Map > markers
				$field[ 'label' ]   = '';
				$field[ 'message' ] = 'Use ' . get_field( 'syn_organization', 'option' );
				break;*/
			/*case 'syntric_user_page' :
				$field[ 'wrapper' ][ 'hidden' ] = 1;
				switch( $pagenow ) {
					case 'profile.php' :
						$user_id = get_current_user_id();
						break;
					case 'user-edit.php':
						if( isset( $_REQUEST[ 'user_id' ] ) ) {
							$user_id = $_REQUEST[ 'user_id' ];
						}
						break;
				}
				if( isset( $user_id ) ) {
					$user_is_teacher = get_field( 'syn_user_is_teacher', 'user_' . $user_id );
					if( $user_is_teacher && syntric_current_user_can( 'administrator' ) ) {
						$teacher_page                   = syntric_get_teacher_page( $user_id );
						$field[ 'wrapper' ][ 'hidden' ] = 0;
						$field[ 'instructions' ]        = 'Go to <a href="/wp-admin/post.php?post=' . $teacher_page -> ID . '&action=edit">' . $teacher_page -> post_title . '</a> page';
					}
				}
				$field = syntric_load_teacher_pages( $field );
				break;*/
			/*case 'syntric_user_is_teacher' :
				if( ! syntric_current_user_can( 'editor' ) ) {
					$field[ 'wrapper' ][ 'hidden' ] = 1;
				}
				break;*/
			/*case 'syntric_post_category' :
				//if ( $field[ 'value' ] ) {
				//$field[ 'wrapper' ][ 'hidden' ] = 1;
				//} else  {
				$choices    = [];
				$categories = get_terms( [ 'taxonomy' => 'category' ] );
				if( $categories ) {
					foreach( $categories as $category ) {
						$choices[ $category -> term_id ] = $category -> name;
					}
				}
				$field[ 'choices' ] = $choices;
				//}
				break;*/
			/*case 'syntric_post_microblog' :
				if( $field[ 'value' ] ) {
					$field[ 'wrapper' ][ 'hidden' ] = 1;
				}
				break;*/
			/*case 'syntric_page_class' :
				$field = syntric_load_classes( $field );
				if( $field[ 'value' ] ) {
					$field[ 'disabled' ] = 1;
				}
				break;*/
			/*case 'syn_page_class_teacher' :
				$field = syntric_load_teachers( $field );
				if( $field[ 'value' ] ) {
					$field[ 'disabled' ] = 1;
				}
				break;*/
			/*case 'syn_page_teacher' :
				$field = syntric_load_teachers( $field );
				if( $field[ 'value' ] ) {
					$field[ 'disabled' ] = 1;
				}
				break;*/
			/*case 'syn_page_department' :
				$field = syntric_load_departments( $field );
				if( $field[ 'value' ] ) {
					$field[ 'disabled' ] = 1;
				}
				break;*/
			/*case 'syn_page_course' :
				$field = syntric_load_courses( $field );
				if( $field[ 'value' ] ) {
					$field[ 'disabled' ] = 1;
				}
				break;*/
			/*case 'syn_contact_person' :
				$field = syntric_load_people( $field );
				break;*/
			/*case 'syn_contact_organization' :
				$field = syntric_load_organizations( $field );
				if( ! $field[ 'value' ] ) {
					$organization_id  = get_field( 'syn_organization_id', 'option' );
					$field[ 'value' ] = $organization_id;
				}
				break;*/
			/*case 'calendar_id' :
				$field = syntric_load_google_calendars( $field );
				break;*/
			/*case 'syn_microblog_category' :
				//if ( ! syntric_current_user_can( 'editor' ) ) {
				$field = syntric_load_categories( $field );
				if( $field[ 'value' ] ) {
					$field[ 'disabled' ] = 1;
				}
				//}
				break;*/
			/*case 'syn_microblog_term' :
				//if ( ! syntric_current_user_can( 'editor' ) ) {
				$field = syntric_load_microblogs( $field );
				if( $field[ 'value' ] ) {
					$field[ 'disabled' ] = 1;
				}
				//}
				break;*/
			/*case 'syn_microblog_category_select' :
				//$categories = get_categories( [ 'taxonomy' => 'category', 'hide_empty' => false ]);
				//if ( ! syntric_current_user_can( 'editor' ) ) {
				$field               = syntric_load_categories( $field );
				$field[ 'disabled' ] = 1;
				//}
				break;*/
			/*case 'syn_microblog_term_select' :
				//if ( ! syntric_current_user_can( 'editor' ) ) {
				$field               = syntric_load_microblogs( $field );
				$field[ 'disabled' ] = 1;
				//}
				break;*/
			/*case 'syn_new_microblog_post' :
				$microblog_active = get_field( 'syn_microblog_active', $post -> ID );
				if( ! $microblog_active ) {
					$field[ 'wrapper' ][ 'hidden' ] = 1;
				} else {
					$field[ 'wrapper' ][ 'hidden' ] = 0;
				}
				break;*/
			/*case 'syn_category_page' :
				if( 'edit-tags.php' == $pagenow || 'term.php' == $pagenow ) {
					$field[ 'wrapper' ][ 'hidden' ] = 1;
				}
				break;*/
			//case 'syntric_attachments_active':
			//case 'syntric_video_active':
			//case 'syntric_google_map_active':
			//return false;
			//break;
		
		endswitch;
		
		/*switch( $field[ 'key' ] ) :
			case 'field_5c80dc4a54a41' :
				if( 'profile.php' == $pagenow || 'user-new.php' == $pagenow ) {
					$teacher_pages = syntric_get_teacher_pages();
					if( $teacher_pages ) {
						$choices = [];
						foreach( $teacher_pages as $teacher_page ) {
							$choices[ $teacher_page -> ID ] = $teacher_page -> post_title;
						}
						$field[ 'choices' ] = $choices;
					}
				}
				break;
		endswitch;*/
		
		/*// User > Teacher Page message field
		if( 'field_5ad3b76d1ea8b' == $field[ 'key' ] ) {
			$field[ 'wrapper' ][ 'hidden' ] = 1;
			if( 'user-new.php' == $pagenow ) {
				$field[ 'wrapper' ][ 'hidden' ] = 1;
			} elseif( 'profile.php' == $pagenow ) {
				$user_id = get_current_user_id();
			} elseif( isset( $_REQUEST[ 'user_id' ] ) ) {
				$user_id = $_REQUEST[ 'user_id' ];
			}
			if( isset( $user_id ) && ! syntric_current_user_can( 'administrator' ) ) {
				$user_is_teacher = get_field( 'syn_user_is_teacher', 'user_' . $user_id );
				$teacher_page    = syntric_get_teacher_page( $user_id );
				if( $user_is_teacher && $teacher_page instanceof WP_Post ) {
					$field[ 'message' ]             = '<a href="/wp-admin/post.php?post=' . $teacher_page -> ID . '&action=edit">' . $teacher_page -> post_title . '</a>';
					$field[ 'wrapper' ][ 'hidden' ] = 0;
				}
			}
			
			return $field;
		}
		//$field = syntric_load_organizations( $field );
		// Google Maps > markers > organization select field
		if( 'field_59b118daf73d0' == $field[ 'key' ] ) {
			$field = syntric_load_organizations( $field );
		}
		// syntric_classes > Class Page message field
		if( 'field_59d127e512d9a' == $field[ 'key' ] ) {
			$field[ 'wrapper' ][ 'hidden' ] = 1;
			$field[ 'wrapper' ][ 'width' ]  = 1;
			$classes                        = get_field( 'syn_classes', $post -> ID );
			if( $classes ) {
				$field[ 'wrapper' ][ 'hidden' ] = 0;
				$field[ 'wrapper' ][ 'width' ]  = '';
				$field_prefix_array             = explode( '[', str_replace( ']', '', $field[ 'prefix' ] ) );
				$row_index                      = $field_prefix_array[ count( $field_prefix_array ) - 1 ];
				if( is_numeric( $row_index ) ) {
					$class      = $classes[ $row_index ];
					$class_id   = $class[ 'class_id' ];
					$teacher_id = get_field( 'syn_page_teacher', $post -> ID );
					$class_page = syntric_get_teacher_class_page( $teacher_id, $class_id );
					if( $class_page instanceof WP_Post ) {
						$status             = ( 'publish' != $class_page -> post_status ) ? ' - ' . ucwords( $class_page -> post_status ) : '';
						$field[ 'message' ] = $class_page -> post_title . $status . '<span style="float: right;"><a href="' . get_the_permalink( $class_page -> ID ) . '">View</a> / <a href="/wp-admin/post.php?action=edit&post=' . $class_page -> ID . '">Edit</a></span>';
					}
				}
			}
		}
		// Attachments page widget tab
		if( 'field_598a9db813a14' == $field[ 'key' ] ) {
			return false;
		}
		// Microblog page widget category message field
		if( 'field_59d4b242abf31' == $field[ 'key' ] ) {
			$field[ 'wrapper' ][ 'hidden' ] = 1;
		}
		// Microblog page widget term (microblogs) message field
		if( 'field_59d4b278abf32' == $field[ 'key' ] ) {
			$field[ 'wrapper' ][ 'hidden' ] = 1;
		}
		// Post category message field
		if( 'field_59e7f2f7049e2' == $field[ 'key' ] ) { // Category display
			$category_id = get_field( 'syn_post_category', $post -> ID );
			if( $category_id ) {
				$category = get_category( $category_id );
				if( $category ) {
					//$field[ 'wrapper' ][ 'class' ] = '';
					$field[ 'message' ] = $category -> name;
				}
			}
		}
		// Post term (microblog) message field
		if( 'field_59e7f370049e3' == $field[ 'key' ] ) { // Microblog display
			$microblog_id = get_field( 'syn_post_microblog', $post -> ID );
			if( $microblog_id ) {
				$microblog = get_term( $microblog_id );
				if( $microblog ) {
					//$field[ 'wrapper' ][ 'class' ] = '';
					$field[ 'message' ] = $microblog -> name;
				}
			}
		}
		// Courses > department select field
		if( 'field_5c6fae31bbd6d' == $field[ 'key' ] ) {
			$field = syntric_load_departments( $field );
		}
		// Rooms > building select field
		if( 'field_59bb90c45ec6d' == $field[ 'key' ] ) { // syntric_rooms > building
			$buildings_active = get_field( 'syn_buildings_active', 'option' );
			if( ! $buildings_active ) {
				return false;
			}
			$field = syntric_load_buildings( $field );
		}*/
		
		return $field;
	}
	
	add_filter( 'acf/fields/user/result/name=teacher', 'syntric_user_field_title', 10, 4 );
	function syntric_user_field_title( $result, $user, $field, $post_id ) {
		$user_custom_fields = get_field( 'syntric_user', 'user_' . $user -> ID );
		$suffix             = ( $user_custom_fields[ 'is_teacher' ] ) ? ' (Teacher)' : '';
		
		return $user -> first_name . ' ' . $user -> last_name . $suffix;
	}
	
	/**
	 * Set default order on ACF Field Groups list (cause it's annoying)
	 */
	//add_filter( 'pre_get_posts', 'syntric_acf_pre_get_posts', 1, 1 );
	function syntric_acf_pre_get_posts( $query ) {
		global $pagenow;
		if( is_admin() && $query -> is_main_query() && 'edit.php' == $pagenow && 'acf-field-group' == $query -> get( 'post_type' ) ) {
			if( ! isset( $_GET[ 'orderby' ] ) ) {
				$query -> set( 'orderby', 'post_title' );
				$query -> set( 'order', 'ASC' );
			}
			if( ! isset( $_GET[ 'post_status' ] ) ) {
				$query -> set( 'post_status', 'publish' );
			}
		}
		
		return $query;
	}
	
	// Change the path where ACF will save the local JSON file
	add_filter( 'acf/settings/save_json', 'syntric_acf_json_save_point' );
	function syntric_acf_json_save_point() {
		$path = get_stylesheet_directory() . '/assets/json';
		
		return $path;
	}
	
	// Specify path where ACF should look for local JSON files
	add_filter( 'acf/settings/load_json', 'syntric_acf_json_load_point' );
	function syntric_acf_json_load_point( $paths ) {
		// remove original path (optional)
		unset( $paths[ 0 ] );
		// append new path
		$paths[] = get_stylesheet_directory() . '/assets/json';
		
		return $paths;
	}
	
	/**
	 * Save post actions for all saves of posts, pages, options, users, CPTs, etc - fires every time a post save has ACF fields included
	 *
	 * Priority 20 fires after ACF has saved the custom field data, well after the post itself has been saved
	 */
	add_action( 'acf/save_post', 'syntric_acf_save_post', 20 );
	function syntric_acf_save_post( $post_id ) {
		global $pagenow;
		// Exit if not in admin
		if( ! is_admin() ) {
			return;
		}
		// Exit if revision or AUTOSAVE
		if( wp_is_post_revision( $post_id ) || ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) ) {
			return;
		}
		// Exit if no ACF fields or fields are empty
		if( ! isset( $_POST[ 'acf' ] ) || empty( $_POST[ 'acf' ] ) ) {
			return;
		}
		if( 'option' == $post_id || 'options' == $post_id ) {
			$post_type = 'option';
		} elseif( is_numeric( $post_id ) ) {
			$post_type = get_post_type( $post_id );
		} else {
			$post_id_arr = explode( '_', $post_id );
			$post_type   = $post_id_arr[ 0 ];
			$post_id     = $post_id_arr[ 1 ];
		}
		// Switch on post type
		switch( $post_type ) :
			case 'user' :
				// Create or delete teacher page according to if the user is a teacher
				syntric_manage_teacher_page( $post_id );
				break;
			case 'widget' :
				// todo: this was taken from the former syntric_save_calendar acf/save_post action
				break;
			case 'option' :
				if( 'admin.php?page=syntric-classes' == $pagenow ) {
					$classes = get_field( 'syntric_classes', 'option' );
					if( is_array( $classes ) && count( $classes ) ) {
						for( $i = 0; $i < count( $classes ); $i ++ ) {
							$teacher      = $classes[ $i ][ 'teacher' ];
							$teacher_id   = $teacher[ 'id' ];
							$class_id     = $classes[ $i ][ 'id' ];
							$teacher_page = syntric_get_teacher_page( $teacher_id, false ); // WP_Post
							if( ! $teacher_page instanceof WP_Post ) {
								$teacher_page_id = syntric_create_teacher_page( $teacher_id, true );
							}
						}
					}
				}
				break;
			case 'post' :
				// todo: needs logic check
				break;
			case 'page' :
				///////////////// todo: page generic
				$page_template = strtolower( syntric_get_page_template( $post_id ) );
				switch( $page_template ) :
					case 'default' :
						break;
					case 'page' :
						break;
					case 'department' :
						// ensure the department page/name/permalink is unique, under Academics and in alpha order
						break;
					case 'teachers' :
						// ensure there is only 1 teachers page and that it's under Academics
						break;
					case 'teacher' :
						$classes = get_field( 'syntric_classes', $post_id );
						foreach( $classes as $class ) {
							if( $class[ 'include_page' ] ) {
								if( empty( $class[ 'class_page' ] ) ) {
									syntric_create_class_page( $class );
								}
							}
						}
						break;
					case '_teacher' : // teacher template
						//$teacher_page    = get_field( 'syntric_teacher_page', $post_id );
						//$teacher         = $teacher_page[ 'teacher' ]; // WP_User
						$teacher_page    = get_post( $post_id );
						$teacher_id      = $teacher_page -> post_author;
						$teacher_classes = get_field( 'syntric_teacher_classes', $post_id );
						$classes         = get_field( 'syntric_classes', 'option' );
						
						if( is_array( $teacher_classes ) && count( $teacher_classes ) ) {
							// teacher has classes
							for( $tci = 0; $tci < count( $teacher_classes ); $tci ++ ) {
								$include_page = $teacher_classes[ $tci ][ 'include_page' ]; // boolean
								$class_page   = $teacher_classes[ $tci ][ 'class_page' ]; // WP_Post
								$class_id     = $teacher_classes[ $tci ][ 'id' ];
								if( $include_page && ! $class_page instanceof WP_Post ) {
									$teacher_page_id = syntric_create_class_page( $class_id );
								} elseif( ! $include_page && $class_page instanceof WP_Post ) {
									$delete_result = syntric_trash_teacher_class_page( $teacher_id, $class_id );
								}
								//Y%N 5ca7a48d373286.09456176
								//Y%N 5ca7a48d373286.09456176
								/*$class = $teacher_classes[$tci];
								$course              = $teacher_classes[ $tci ][ 'course' ];
								$period              = $teacher_classes[ $tci ][ 'period' ];
								$room                = $teacher_classes[ $tci ][ 'room' ];
								$include_page        = $teacher_classes[ $tci ][ 'include_page' ];
								$class_page          = $teacher_classes[ $tci ][ 'class_page' ];
								$google_classroom_id = $teacher_classes[ $tci ][ 'google_classroom_id' ];
								// create or trash class pages
								foreach( $teacher_classes as $teacher_class ) {
									if( $include_page && empty( $class_page ) ) {
										// wants page but none exists
										$class_page = syntric_manage_class_page( $teacher_class );
									} elseif( ! $include_page && $class_page ) {
										// doesn't want page but exists
										
									}
								}*/
								
								// maintain bidirectional relationship between option syntric_classes and post meta syntric_page_teacher_classes, both repeaters
								$found_tc = false;
								for( $ci = 0; $ci < count( $classes ); $ci ++ ) {
									if( $teacher_classes[ $tci ][ 'id' ] == $classes[ $ci ][ 'id' ] ) {
										$found_tc = true;
										// update the class in syntric_classes
										update_row( 'syntric_classes', $ci, $teacher_classes[ $tci ], 'option' );
										break;
									}
								}
								if( ! $found_tc ) {
									// no matching record was found in syntric_classes, so add it
									add_row( 'syntric_classes', $teacher_classes[ $tci ], 'option' );
								} else {
									$found_tc = false;
								}
							}
						} else {
							// teacher has no classes, delete any records in syntric_classes
							for( $tci = 0; $tci < count( $teacher_classes ); $tci ++ ) {
								$course = $teacher_classes[ $tci ][ 'course' ];
								$period = $teacher_classes[ $tci ][ 'period' ];
								for( $ci = 0; $ci < count( $classes ); $ci ++ ) {
									if( $teacher_id == $classes[ $ci ][ 'teacher' ] && $course == $classes[ $ci ][ 'course' ] && $period == $classes[ $ci ][ 'period' ] ) {
										delete_row( 'syntric_classes', $ci, 'option' );
										break;
									}
								}
							}
						}
						
						/*$teacher_classes_index = 0;
							$classes_index         = 0;
							foreach( $teacher_classes as $teacher_class ) {
								// a class is unique when teacher, course & period are unique (might need to consider section too)
								$course      = $teacher_class[ 'course' ];
								$period      = $teacher_class[ 'period' ];
								$found_class = false;
								
								foreach( $classes as $class ) {
									if( $teacher == $class[ 'teacher' ] && $course == $class[ 'course' ] && $period == $class[ 'period' ] ) {
										$found_existing_class = true;
										// update the class in syntric_classes
										update_field( 'syntric_classes', $classes_index, $teacher_class, 'option' );
										
										break;
									}
									$classes_index ++;
								}
								$teacher_classes_index ++;
							}*/
						
						// create and destroy class pages
						// force page under teachers page
						break;
					case 'class' :
						break;
					case 'course' :
						break;
					case 'schedules' :
						break;
				endswitch;
				//syntric_save_page_widgets( $post_id );
				///////////////// todo: page microblog
				// todo: reduce all the "security and validity" conditional wrappers,
				// like the next line, into a function call eg. syntric_continue_if('admin','page','teacher_template').
				/*$microblog_active = get_field( 'syn_microblog_active', $post_id );
				if( $microblog_active ) {
					$page_microblog_category = syntric_get_page_microblog_category( $post_id );
				}
				//////////////// todo: migration field group
				$run_migration_task = get_field( 'syn_migration_run_next', $post_id );
				if( isset( $run_migration_task ) && $run_migration_task ) {
					syntric_migration_run_next( $post_id );
				}
				// Import (local) links in an HTML list into a page's Attachments
				$run_import_attachments_html = get_field( 'syn_migration_run_import_attachments_html', $post_id );
				if( isset( $run_import_attachments_html ) && $run_import_attachments_html ) {
					$attachments_html = get_field( 'syn_migration_attachments_html', $post_id );
					if( isset( $attachments_html ) && ! empty( $attachments_html ) ) {
						syntric_parse_attachments_html( $attachments_html, $post_id );
					}
				}
				/////////////// todo: teacher page??? if current user is teacher...
				$user_id    = get_current_user_id();
				$is_teacher = get_field( 'syn_user_is_teacher', 'user_' . $user_id );
				// if current user is a teacher
				if( $is_teacher ) {
					// need to accommodate teacher, class and default pages
					$page_template = syntric_get_page_template( $post_id );
					switch( $page_template ) {
						case 'teacher' :
							$post_teacher = get_field( 'syn_page_teacher', $post_id );
							// if current user is page's teacher
							if( $post_teacher == $user_id ) {
								$teachers_page = syntric_get_teachers_page();
								if( $teachers_page instanceof WP_Post ) {
									wp_update_post( [ 'ID'          => $post_id,
									                  'post_parent' => $teachers_page -> ID, ] );
								}
							}
							break;
						case 'class' :
							$class_teacher = get_field( 'syn_page_class_teacher', $post_id );
							if( $class_teacher == $user_id ) {
								$teacher_page = syntric_get_teacher_page( $user_id, 0 );
								if( $teacher_page instanceof WP_Post ) {
									wp_update_post( [ 'ID'          => $post_id,
									                  'post_parent' => $teacher_page -> ID, ] );
								}
							}
							break;
						case 'default' :
						case 'page' :
							$teacher_page = syntric_get_teacher_page( $user_id, 0 );
							if( $teacher_page instanceof WP_Post ) {
								$res = wp_update_post( [ 'ID'          => $post_id,
								                         'post_parent' => $teacher_page -> ID, ] );
							}
							break;
					}
				}*/
				break;
			case 'attachment' :
				break;
			case 'syntric_calendar':
				$calendar         = get_field( 'syntric_calendar', $post_id );
				$schedule_sync    = $calendar[ 'schedule_sync' ];
				$purge            = $calendar[ 'purge' ];
				$sync_now         = $calendar[ 'sync_now' ];
				$sync_past        = $calendar[ 'sync_past' ];
				$sync_past_period = $calendar[ 'sync_past_period' ];
				if( $schedule_sync ) {
					syntric_schedule_calendar_sync( $post_id );
				} else {
					wp_clear_scheduled_hook( 'syntric_calendar_sync', [ 'post_id' => $post_id ] );
				}
				if( $purge ) {
					syntric_purge_calendar( $post_id );
					update_field( 'syntric_calendar_purge', 0, $post_id );
				}
				if( $sync_now ) {
					syntric_sync_calendar( $post_id, 'now', $sync_past, $sync_past_period );
					update_field( 'syntric_calendar_sync_now', 0, $post_id );
					update_field( 'syntric_calendar_sync_past', 0, $post_id );
					update_field( 'syntric_calendar_sync_past_period', 1, $post_id );
				}
				break;
			case 'syntric_event' :
				break;
		endswitch;
		/*if( 'syntric-data-functions' == $_REQUEST[ 'page' ] ) {
			// do stuff
			$run_orphan_scan              = get_field( 'syn_data_run_orphan_scan', 'option' );
			$run_users_import             = get_field( 'syn_data_run_users_import', 'option' );
			$run_users_export             = get_field( 'syn_data_run_users_export', 'option' );
			$run_users_phone_update       = get_field( 'syn_data_run_users_phone_update', 'option' );
			$run_users_password_update    = get_field( 'syn_data_run_users_password_update', 'option' );
			$run_activate_contact_widgets = get_field( 'syn_data_run_activate_contact_widgets', 'option' );
			$run_reset_user_capabilities  = get_field( 'syn_data_run_reset_user_capabilities', 'option' );
			$run_dedupe_events            = get_field( 'syn_data_run_dedupe_events', 'option' );
			if( $run_orphan_scan ) {
				$delete_orphans = get_field( 'syn_data_delete_orphans', 'option' );
				syntric_scan_orphans( $delete_orphans );
			}
			if( $run_users_import ) {
				syntric_import_users();
			}
			if( $run_users_export ) {
				syntric_export_users();
			}
			if( $run_users_phone_update ) {
				$phone = get_field( 'syn_data_users_phone', 'option' );
				syntric_update_users_phone( $phone );
			}
			if( $run_users_password_update ) {
				syntric_update_users_password();
			}
			if( $run_activate_contact_widgets ) {
				syntric_activate_contact_widgets();
			}
			if( $run_reset_user_capabilities ) {
				syntric_reset_user_capabilities();
			}
			if( $run_dedupe_events ) {
				syntric_dedupe_events();
			}
			// clear/reset all fields, except orphan scan console
			update_field( 'syn_data_run_orphan_scan', 0, 'option' );
			update_field( 'syn_data_delete_orphans', 0, 'option' );
			update_field( 'syn_data_run_users_import', 0, 'option' );
			update_field( 'syn_data_users_file', null, 'option' );
			update_field( 'syn_data_users_file_has_header_row', 0, 'option' );
			update_field( 'syn_data_run_users_phone_update', 0, 'option' );
			update_field( 'syn_data_users_phone', null, 'option' );
			update_field( 'syn_data_run_users_password_update', 0, 'option' );
			update_field( 'syn_data_run_activate_contact_widgets', 0, 'option' );
			update_field( 'syn_data_run_reset_user_capabilities', 0, 'option' );
			update_field( 'syn_data_run_dedupe_events', 0, 'option' );
		}*/
	}
	
	add_action( 'before_delete_post', 'syntric_before_delete_post', 20 );
	function syntric_before_delete_post( $post_id ) {
		$post_type = get_post_type( $post_id );
		switch( $post_type ) :
			case 'syntric_calendar' :
				syntric_purge_calendar( $post_id );
				break;
		
		endswitch;
	}
	
	add_filter( 'acf/fields/google_map/api', 'syntric_google_map_api' );
	function syntric_google_map_api( $api ) {
		$syntric_settings = get_field( 'syntric_settings', 'option' );
		$api[ 'key' ]     = $syntric_settings[ 'google' ][ 'api_key' ];
		
		return $api;
	}
	
	/***********************************
	 *           Boneyard              *
	 ***********************************/
	
	/**
	 * Get ACF post type (post, page, widget, option, user, etc.) and return the post type + post_id in an array.
	 *
	 * For example user_123 will return as ['user', 123]. A page with $post_id 123 will return as ['page', 123]
	 *
	 * @param $post_id - an ACF post_id, not WordPress
	 *
	 * @return array - an array with two values, post type and post id. Note that user, option, widget
	 */
	function __syntric_get_acf_post_type( $post_id ) {
		if( ! is_numeric( $post_id ) ) {
			// if $post_id is not numeric, this must be a user, widget or option
			$post_id_array = explode( '_', $post_id );
			
			// return the id array with post type and ID
			return $post_id_array;
		} else {
			// if $post_id is numeric, return post type in an array (to match above)
			return [ get_post_type( $post_id ), $post_id ];
		}
	}
	
	function __syntric_load_teacher_pages( $field ) {
		if( 'select' == $field[ 'type' ] ) {
			$choices       = [];
			$teacher_pages = syntric_get_teacher_pages();
			if( $teacher_pages ) {
				foreach( $teacher_pages as $teacher_page ) {
					$choices[ $teacher_page -> ID ] = $teacher_page -> post_title;
				}
			}
			$field[ 'choices' ] = $choices;
		}
		
		return $field;
	}
	
	function __syntric_load_organizations( $field ) {
		if( 'select' == $field[ 'type' ] ) {
			$choices       = [];
			$organizations = get_field( 'syn_organizations', 'option' );
			if( $organizations && is_array( $organizations ) ) {
				foreach( $organizations as $organization ) {
					$choices[ $organization[ 'organization_id' ] ] = $organization[ 'organization' ];
				}
			}
			$field[ 'choices' ] = $choices;
		}
		
		return $field;
	}
	
	function __syntric_load_people( $field ) {
		if( 'select' == $field[ 'type' ] ) {
			$choices = [];
			$people  = get_users( [ 'meta_key' => 'last_name',
			                        'orderby'  => 'meta_value', ] );
			if( $people ) {
				foreach( $people as $person ) {
					$choices[ $person -> ID ] = $person -> display_name . ' / ' . get_field( 'syn_user_title', 'user_' . $person -> ID );
				}
			}
			$field[ 'choices' ] = $choices;
		}
		
		return $field;
	}
	
	function __syntric_load_administrators( $field ) {
		if( 'select' == $field[ 'type' ] ) {
			$choices = [];
			$people  = get_users( 'role=administrator' );
			if( $people ) {
				foreach( $people as $person ) {
					$choices[ $person -> ID ] = $person -> display_name . ' (administrator)';
				}
			}
			$field[ 'choices' ] = $choices;
		}
		
		return $field;
	}
	
	function __syntric_load_pageless_teachers( $field ) {
		if( 'select' == $field[ 'type' ] ) {
			$choices  = [];
			$teachers = syntric_get_teachers(); // array of WP_User
			if( $teachers ) {
				$teacher_pages     = syntric_get_teacher_pages(); // array of WP_Post
				$paged_teacher_ids = [];
				if( $teacher_pages ) {
					foreach( $teacher_pages as $teacher_page ) {
						$paged_teacher_ids[] = get_field( 'syn_page_teacher', $teacher_page -> ID );
					}
				}
				foreach( $teachers as $teacher ) {
					if( 0 == count( $paged_teacher_ids ) || ( count( $paged_teacher_ids ) && ! in_array( $teacher -> ID, $paged_teacher_ids ) ) ) {
						$choices[ $teacher -> ID ] = $teacher -> display_name;
					}
				}
			}
			$field[ 'choices' ] = $choices;
		}
		
		return $field;
	}
	
	function __syntric_load_teachers( $field ) {
		if( 'select' == $field[ 'type' ] ) {
			$choices  = [];
			$teachers = syntric_get_teachers();
			if( $teachers ) {
				foreach( $teachers as $teacher ) {
					$choices[ $teacher -> ID ] = $teacher -> display_name;
				}
			}
			$field[ 'choices' ] = $choices;
		}
		
		return $field;
	}
	
	function ___syntric_load_teacher_pages( $field ) {
		if( 'select' == $field[ 'type' ] ) {
			$choices       = [];
			$teacher_pages = syntric_get_teacher_pages();
			if( $teacher_pages ) {
				foreach( $teacher_pages as $teacher_page ) {
					$choices[ $teacher_page -> ID ] = $teacher_page -> post_title;
				}
			}
			$field[ 'choices' ] = $choices;
		}
		
		return $field;
	}
	
	function __syntric_load_users( $field ) {
		if( 'select' == $field[ 'type' ] ) {
			return syntric_load_people( $field );
		}
		
		return $field;
	}
	
	function __syntric_load_classes( $field ) {
		if( 'select' == $field[ 'type' ] ) {
			$teacher_pages = get_posts( [ 'numberposts'  => - 1,
			                              'post_type'    => 'page',
			                              'post_status'  => [ 'publish',
			                                                  'draft',
			                                                  'future',
			                                                  'pending',
			                                                  'private',
			                                                  'trash', ],
			                              'meta_key'     => '_wp_page_template',
			                              'meta_value'   => 'page-templates/teacher.php',
			                              'meta_compare' => '=',
			                              'fields'       => 'ids', ] );
			$choices       = [];
			if( $teacher_pages ) {
				$courses = get_field( 'syn_courses', 'option' );
				$courses = array_column( $courses, 'course', 'course_id' );
				foreach( $teacher_pages as $teacher_page ) { // teacher_page is post->ID
					$teacher_id   = get_field( 'syn_page_teacher', $teacher_page );
					$teacher      = get_user_by( 'ID', $teacher_id );
					$teacher_name = ( $teacher ) ? ' (' . $teacher -> display_name . ')' : ' (Unknown)';
					$classes      = get_field( 'syn_classes', $teacher_page );
					if( $classes ) {
						foreach( $classes as $class ) {
							if( $class[ 'class_id' ] ) {
								$choices[ $class[ 'class_id' ] ] = $courses[ $class[ 'course' ] ] . ' ' . $teacher_name;
							}
						}
					}
				}
				asort( $choices );
			}
			$field[ 'choices' ] = $choices;
		}
		
		return $field;
	}
	
	function __syntric_load_facebook_pages( $field ) {
		if( 'select' == $field[ 'type' ] ) {
			$choices        = [];
			$facebook_pages = get_field( 'syn_facebook_pages', 'option' );
			if( $facebook_pages ) {
				foreach( $facebook_pages as $facebook_page ) {
					$choices[ $facebook_page[ 'facebook_page_id' ] ] = $facebook_page[ 'name' ];
				}
			}
			$field[ 'choices' ] = $choices;
		}
		
		return $field;
	}
	
	////// current ? this is used in 2 filters above //////
	function __syntric_load_google_calendars( $field ) {
		if( 'select' == $field[ 'type' ] ) {
			$choices               = [];
			$calendar_widget_pages = syntric_get_posts_with_calendar_widget();
			$calendar_widgets      = [];
			if( count( $calendar_widget_pages ) ) {
				foreach( $calendar_widget_pages as $calendar_widget_page ) {
					$page_template = syntric_get_page_template( $calendar_widget_page -> ID );
					$calendar_id   = get_post_meta( $calendar_widget_page -> ID, 'syn_calendar_id', 1 );
					//$calendar_id                      = get_field( 'syn_calendar_id', $calendar_widget_page->ID );
					$calendar_widgets[ $calendar_id ] = [ 'post_id'       => $calendar_widget_page -> ID,
					                                      'post_title'    => $calendar_widget_page -> post_title,
					                                      'post_status'   => $calendar_widget_page -> post_status,
					                                      'post_author'   => $calendar_widget_page -> post_author,
					                                      'page_template' => $page_template, ];
				}
			}
			$calendars = syntric_get_calendars();
			if( $calendars ) {
				if( 'syn_calendar_id' == $field[ '_name' ] ) {
					$choices[ 0 ] = '+ New calendar';
				}
				foreach( $calendars as $calendar ) {
					// todo: currently "Author > Calendar" but should be
					// get_the_author_meta( 'first_name', $calendar->post_author ) . ' ' . get_the_author_meta( 'last_name', $calendar->post_author ) . ' > ' .
					$detail                     = ( isset( $calendar_widgets[ $calendar -> ID ] ) ) ? ' ' . $calendar_widgets[ $calendar -> ID ][ 'page_template' ] . ' ' . get_the_author_meta( 'first_name', $calendar_widgets[ $calendar -> ID ][ 'post_author' ] ) . ' ' . get_the_author_meta( 'last_name', $calendar_widgets[ $calendar -> ID ][ 'post_author' ] ) : '';
					$choices[ $calendar -> ID ] = get_the_title( $calendar -> ID ) . $detail;
				}
			}
			$field[ 'choices' ] = $choices;
		}
		
		return $field;
	}
	
	// not in use
	function __syntric_load_departments( $field ) {
		$school      = syntric_get_school();
		$departments = $school[ 'departments' ];
		if( $departments ) {
			$choices = [];
			foreach( $departments as $department ) {
				$item      = $department[ 'department' ];
				$choices[] = $item;
			}
			$field[ 'choices' ] = $choices;
		}
		
		return $field;
	}
	
	// not in use
	function __syntric_load_blocks( $field ) {
		$blocks  = syntric_get_wp_blocks();
		$choices = [];
		foreach( $blocks as $block ) {
			$key_val                          = explode( ':', $block );
			$choices[ trim( $key_val[ 0 ] ) ] = trim( $key_val[ 1 ] );
		}
		$field[ 'choices' ] = $choices;
		
		return $field;
	}
	
	function __syntric_load_microblogs( $field ) {
		if( 'radio' == $field[ 'type' ] || 'select' == $field[ 'type' ] ) {
			$microblogs = get_terms( [ 'taxonomy'   => 'microblog',
			                           'hide_empty' => false ] );
			$choices    = [];
			if( $microblogs ) {
				foreach( $microblogs as $microblog ) {
					if( property_exists( $microblog, 'term_id' ) ) {
						$choices[ $microblog -> term_id ] = $microblog -> name . ' (' . $microblog -> count . ')';
					}
				}
			}
			/*$args    = [
				'numberposts' => - 1,
				'post_type'   => 'page',
				'meta_key'    => 'syntric_microblog_active',
				'meta_value'  => 1,
				'orderby'     => [ 'post_title' => 'ASC' ],
			];
			if ( ! syntric_current_user_can( 'editor' ) ) {
				$args[ 'author' ] = get_current_user_id();
			}
			$mb_pages = get_posts( $args );
			if ( count( $mb_pages ) ) {
				foreach ( $mb_pages as $mb_page ) {
					$mb_active = get_field( 'syn_microblog_active', $mb_page->ID );
					if ( $mb_active ) {
						$mb_term                      = get_field( 'syn_microblog_term', $mb_page->ID );
						$choices[ $mb_term->term_id ] = $mb_term->name . ' (' . $mb_term->count . ')';
					}
				}
			}*/
			//$choices[ 0 ]       = '+ New microblog';
			$field[ 'choices' ] = $choices;
		}
		
		return $field;
	}
	
	//add_action( 'acf/save_post', 'syntric_save_post', 20 );
	function __syntric_save_post( $post_id ) {
		global $pagenow;
		$post_id = syntric_resolve_post_id( $post_id );
		// don't save for autosave
		if( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
			return;
		}
		if( is_admin() && is_numeric( $post_id ) && 'post' == get_post_type( $post_id ) && isset( $_REQUEST[ 'acf' ] ) && ! wp_is_post_revision( $post_id ) ) {
			$category_id = get_field( 'syn_post_category', $post_id );
			if( 0 == $category_id ) {
				$new_category = get_field( 'syn_post_new_category', $post_id );
				$category_id  = wp_insert_category( [ 'cat_name' => $new_category ] );
			}
			if( $category_id ) {
				wp_set_post_categories( $post_id, [ (int) $category_id ], false );
				$microblog_category = get_category_by_slug( 'microblogs' );
				if( $category_id == $microblog_category -> cat_ID ) {
					$microblog_id = get_field( 'syn_post_microblog', $post_id );
					if( $microblog_id ) {
						$microblog_term    = get_term_by( 'id', $microblog_id, 'microblog' );
						$microblog_term_id = $microblog_term -> term_id;
					} else {
						$new_microblog_term = get_field( 'syn_post_new_microblog', $post_id );
						$microblog_term     = get_term_by( 'name', $new_microblog_term, 'microblog' );
						if( ! $microblog_term ) {
							$microblog_term    = wp_insert_term( $new_microblog_term, 'microblog', [ 'description' => 'Microblog term created manually' ] );
							$microblog_term_id = $microblog_term[ 'term_id' ];
						} else {
							$microblog_term_id = $microblog_term -> term_id;
						}
					}
					if( $microblog_term_id ) {
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
	}
	
	/**
	 * On save calendar sync from Google and schedule future syncs
	 */
	//add_action( 'acf/save_post', 'syntric_save_calendar', 20 );
	function __syntric_save_calendar( $post_id ) {
		global $pagenow;
		$post_id = syntric_resolve_post_id( $post_id );
		// don't save for autosave
		if( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE || ! is_admin() ) {
			return;
		}
		// widgets, options, users, etc..
		if( ! is_int( $post_id ) ) {
			$post_id_array = explode( '_', $post_id );
			$post_type     = $post_id_array[ 0 ];
			if( $post_type == 'widget' ) {
				$all_calendars = get_field( 'syn_calendars_menu_widget_all_calendars', $post_id );
				if( $all_calendars ) {
					update_field( 'syn_calendars_menu_widget_calendars', null, $post_id );
				}
			}
		} else {
			$post = get_post( $post_id );
			if( 'syn_calendar' == $post -> post_type && 'post.php' == $pagenow && ! wp_is_post_revision( $post_id ) ) {
				$sync     = get_field( 'syn_calendar_sync', $post_id );
				$sync_now = get_field( 'syn_calendar_sync_now', $post_id );
				if( $sync ) {
					syntric_schedule_calendar_sync( $post_id );
				}
				if( $sync_now ) {
					syntric_sync_calendar( [ 'post_id'    => $post_id,
					                         'post_type'  => 'syn_calendar',
					                         'force_sync' => true, ] );
					update_field( 'syn_calendar_sync_now', 0, $post_id );
				}
			}
		}
	}
	
	//add_action( 'acf/save_post', 'syntric_migration_save_post', 20 );
	function __syntric_migration_save_post( $post_id ) {
		if( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
			return;
		}
		$post_id = syntric_resolve_post_id( $post_id );
		$post    = get_post( $post_id );
		if( is_admin() && is_numeric( $post_id ) && 'page' == $post -> post_type && isset( $_REQUEST[ 'acf' ] ) && ! wp_is_post_revision( $post_id ) ) {
			$run_migration_task = get_field( 'syn_migration_run_next', $post_id );
			if( isset( $run_migration_task ) && $run_migration_task ) {
				syntric_migration_run_next( $post_id );
			}
			/**
			 * Import (local) links in an HTML list into a page's Attachments
			 */
			$run_import_attachments_html = get_field( 'syn_migration_run_import_attachments_html', $post_id );
			if( isset( $run_import_attachments_html ) && $run_import_attachments_html ) {
				$attachments_html = get_field( 'syn_migration_attachments_html', $post_id );
				if( isset( $attachments_html ) && ! empty( $attachments_html ) ) {
					syntric_parse_attachments_html( $attachments_html, $post_id );
				}
			}
		}
	}
	
	/**
	 * syntric_teacher_save_post controls and enforces the rules of affecting teacher pages
	 *
	 * function runs if post is a teacher page and user is a teacher
	 *
	 * fidelity checks:
	 * user has role of author or better
	 * can only be teacher, class or default template (not teachers, course or department)
	 * if teacher page, parent is teachers
	 * if class page, parent is teacher for user
	 * if default page, parent is teacher for user after any class pages
	 *
	 * teacher can only save a page they own
	 *
	 *
	 */
	//add_action( 'acf/save_post', 'syntric_teacher_save_post', 20 );
	function __syntric_teacher_save_post( $post_id ) {
		$post_id = syntric_resolve_post_id( $post_id );
		$post    = get_post( $post_id );
		// if post is set and is a page
		if( $post instanceof WP_Post && 'page' == $post -> post_type ) {
			//$user       = get_user_by( 'ID', get_current_user_id() );
			$user_id    = get_current_user_id();
			$is_teacher = get_field( 'syn_user_is_teacher', 'user_' . $user_id );
			// if current user is a teacher
			if( $is_teacher ) {
				// need to accomodate teacher, class and default pages
				$page_template = syntric_get_page_template( $post_id );
				switch( $page_template ) {
					case 'teacher' :
						$post_teacher = get_field( 'syn_page_teacher', $post_id );
						// if current user is page's teacher
						if( $post_teacher == $user_id ) {
							$teachers_page = syntric_get_teachers_page();
							if( $teachers_page instanceof WP_Post ) {
								wp_update_post( [ 'ID'          => $post_id,
								                  'post_parent' => $teachers_page -> ID, ] );
							}
						}
						break;
					case 'class' :
						$class_teacher = get_field( 'syn_page_class_teacher', $post_id );
						if( $class_teacher == $user_id ) {
							$teacher_page = syntric_get_teacher_page( $user_id, 0 );
							if( $teacher_page instanceof WP_Post ) {
								wp_update_post( [ 'ID'          => $post_id,
								                  'post_parent' => $teacher_page -> ID, ] );
							}
						}
						break;
					case 'default' :
					case 'page' :
						$teacher_page = syntric_get_teacher_page( $user_id, 0 );
						if( $teacher_page instanceof WP_Post ) {
							$res = wp_update_post( [ 'ID'          => $post_id,
							                         'post_parent' => $teacher_page -> ID, ] );
						}
						break;
				}
			}
		}
	}
	
	//add_action( 'acf/save_post', 'syntric_save_page', 20 );
	function __syntric_save_page( $post_id ) {
		//global $pagenow;
		// don't save for autosave
		if( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
			return;
		}
		$post_id = syntric_resolve_post_id( $post_id );
		$post    = get_post( $post_id );
		if( is_admin() && is_numeric( $post_id ) && 'page' == $post -> post_type && isset( $_REQUEST[ 'acf' ] ) && ! wp_is_post_revision( $post_id ) ) {
			$page_template = strtolower( syntric_get_page_template( $post_id ) );
			switch( $page_template ) :
				case 'page' :
					break;
				case 'department' :
					break;
				case 'teachers' :
					break;
				case 'teacher' :
					$teacher_id = get_field( 'syn_page_teacher', $post_id );
					syntric_save_teacher_page( $teacher_id );
					syntric_save_teacher_classes( $teacher_id );
					break;
				case 'class' :
					break;
			endswitch;
			syntric_save_page_widgets( $post_id );
		}
	}
	
	/**
	 * Save sidebar
	 */
	//add_action( 'acf/save_post', 'syntric_save_sidebars', 20 );
	function __syntric_save_sidebars() {
		// don't save for autosave
		if( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
			return;
		}
		if( is_admin() && isset( $_REQUEST[ 'page' ] ) && 'syntric-sidebars-widgets' == $_REQUEST[ 'page' ] ) {
			if( have_rows( 'syntric_sidebars', 'option' ) ) {
				while( have_rows( 'syntric_sidebars', 'option' ) ) : the_row();
					$sidebar_section   = get_sub_field( 'section' );
					$sidebar_location  = get_sub_field( 'location' );
					$name              = $sidebar_section[ 'label' ];
					$name              .= ( 'main' == $sidebar_section[ 'value' ] ) ? ' ' . $sidebar_location[ 'label' ] : '';
					$name_array        = [];
					$description_array = [];
					// run through filters
					if( have_rows( 'filters' ) ) {
						while( have_rows( 'filters' ) ) : the_row();
							$filter_parameter     = get_sub_field( 'parameter' );
							$filter_operator      = get_sub_field( 'operator' );
							$filter_value         = get_sub_field( 'value' );
							$filter_operator_sign = ( 'is' == $filter_operator[ 'value' ] ) ? '+' : '-';
							switch( $filter_parameter[ 'value' ] ) :
								case 'post_type' :
									$post_type_value     = $filter_value[ 'post_type_value' ];
									$name_array[]        = $filter_operator_sign . $post_type_value[ 'label' ];
									$description_array[] = 'Post type ' . $filter_operator[ 'label' ] . ' ' . $post_type_value[ 'label' ];
									break;
								case 'post_category' :
									$post_category_value = $filter_value[ 'post_category_value' ];
									$post_category       = get_the_category_by_ID( $post_category_value );
									$name_array[]        = $filter_operator_sign . $post_category;
									$description_array[] = 'Post category ' . $filter_operator[ 'label' ] . ' ' . $post_category;
									break;
								case 'page_template' :
									$page_template_value = $filter_value[ 'page_template_value' ];
									$name_array[]        = $filter_operator_sign . $page_template_value[ 'label' ];
									$description_array[] = 'Post template ' . $filter_operator[ 'label' ] . ' ' . $page_template_value[ 'label' ];
									break;
								case 'post' :
									$post_value          = $filter_value[ 'post_value' ];
									$name_array[]        = $filter_operator_sign . get_the_title( $post_value );
									$description_array[] = 'Post ' . $filter_operator[ 'label' ] . ' ' . get_the_title( $post_value );
									break;
								case 'page' :
									$page_value          = $filter_value[ 'page_value' ];
									$name_array[]        = $filter_operator_sign . get_the_title( $page_value );
									$description_array[] = 'Page ' . $filter_operator[ 'label' ] . ' ' . get_the_title( $page_value );
									break;
							endswitch;
						endwhile;
					}
					if( count( $name_array ) ) {
						$name .= ' (' . implode( ' ', $name_array ) . ')';
					}
					$_name = get_sub_field( 'name' );
					if( ! empty( $_name ) ) {
						update_sub_field( 'name', $name );
					}
					$sidebar_layout = get_sub_field( 'layout' );
					if( 'main' != $sidebar_section[ 'value' ] ) {
						$description_array[] = $sidebar_layout[ 'label' ] . ' layout';
					}
					if( count( $description_array ) ) {
						update_sub_field( 'description', implode( ' / ', $description_array ) );
					}
				endwhile;
			}
		}
	}
	
	/**
	 * Get microblog tax term (or create it) and assign to microblog on save post (page)
	 */
	//add_action( 'acf/save_post', 'syntric_save_microblog', 20 );
	function __syntric_save_microblog( $post_id ) {
		global $pagenow;
		// don't save for autosave
		if( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
			return;
		}
		$post_id = syntric_resolve_post_id( $post_id );
		$post    = get_post( $post_id );
		// todo: reduce all the "security and validity" conditional wrappers, like the next line, into a function call eg. syntric_continue_if('admin','page','teacher_template')...
		if( is_admin() && $post instanceof WP_Post && 'page' == $post -> post_type && isset( $_REQUEST[ 'acf' ] ) && ! wp_is_post_revision( $post -> ID ) ) {
			// set term in taxonomy "microblog"
			$microblog_active = get_field( 'syn_microblog_active', $post -> ID );
			if( $microblog_active ) {
				if( category_exists( 'Microblogs' ) ) {
					$cat    = get_category_by_slug( 'microblogs' );
					$cat_id = $cat -> cat_ID;
				} else {
					$cat_id = wp_insert_category( [ 'cat_name' => 'Microblogs' ] );
				}
				$ancestor_ids = array_reverse( get_post_ancestors( $post -> ID ) );
				$titles       = '';
				$slugs        = '';
				foreach( $ancestor_ids as $ancestor_id ) {
					$titles .= get_the_title( $ancestor_id ) . ' > ';
					$slugs  .= get_post_field( 'post_name', $ancestor_id ) . '-';
				}
				$titles .= $post -> post_title;
				$slugs  .= $post -> post_name;
				if( term_exists( $slugs, 'microblog' ) ) {
					$term    = get_term_by( 'slug', $slugs, 'microblog' );
					$term_id = $term -> term_id;
				} else {
					$tax_term_ids = wp_insert_term( $titles, 'microblog', [ 'slug'        => $slugs,
					                                                        'description' => 'Microblog associated with <a href="/wp-admin/post.php?post=' . $post -> ID . '&action=edit">' . $titles . '</a> page', ] );
					$term_id      = $tax_term_ids[ 'term_id' ];
				}
				if( is_int( $cat_id ) && is_int( $term_id ) ) {
					wp_set_post_categories( $post -> ID, [ (int) $cat_id ], false );
					wp_set_post_terms( $post -> ID, [ (int) $term_id ], 'microblog', false );
					//update_field( 'syn_microblog_page', $post->ID, 'microblog_' . $term_id );
				}
			}
		}
	}
	
	//add_action( 'acf/save_post', 'syntric_save_data_functions', 20 );
	function __syntric_save_data_functions() {
		if( is_admin() && isset( $_REQUEST[ 'page' ] ) && 'syntric-data-functions' == $_REQUEST[ 'page' ] ) {
			// do stuff
			$run_orphan_scan              = get_field( 'syn_data_run_orphan_scan', 'option' );
			$run_users_import             = get_field( 'syn_data_run_users_import', 'option' );
			$run_users_export             = get_field( 'syn_data_run_users_export', 'option' );
			$run_users_phone_update       = get_field( 'syn_data_run_users_phone_update', 'option' );
			$run_users_password_update    = get_field( 'syn_data_run_users_password_update', 'option' );
			$run_activate_contact_widgets = get_field( 'syn_data_run_activate_contact_widgets', 'option' );
			$run_reset_user_capabilities  = get_field( 'syn_data_run_reset_user_capabilities', 'option' );
			$run_dedupe_events            = get_field( 'syn_data_run_dedupe_events', 'option' );
			if( $run_orphan_scan ) {
				$delete_orphans = get_field( 'syn_data_delete_orphans', 'option' );
				syntric_scan_orphans( $delete_orphans );
			}
			if( $run_users_import ) {
				syntric_import_users();
			}
			if( $run_users_export ) {
				syntric_export_users();
			}
			if( $run_users_phone_update ) {
				$phone = get_field( 'syn_data_users_phone', 'option' );
				syntric_update_users_phone( $phone );
			}
			if( $run_users_password_update ) {
				syntric_update_users_password();
			}
			if( $run_activate_contact_widgets ) {
				syntric_activate_contact_widgets();
			}
			if( $run_reset_user_capabilities ) {
				syntric_reset_user_capabilities();
			}
			if( $run_dedupe_events ) {
				syntric_dedupe_events();
			}
			// clear/reset all fields, except orphan scan console
			update_field( 'syn_data_run_orphan_scan', 0, 'option' );
			update_field( 'syn_data_delete_orphans', 0, 'option' );
			update_field( 'syn_data_run_users_import', 0, 'option' );
			update_field( 'syn_data_users_file', null, 'option' );
			update_field( 'syn_data_users_file_has_header_row', 0, 'option' );
			update_field( 'syn_data_run_users_phone_update', 0, 'option' );
			update_field( 'syn_data_users_phone', null, 'option' );
			update_field( 'syn_data_run_users_password_update', 0, 'option' );
			update_field( 'syn_data_run_activate_contact_widgets', 0, 'option' );
			update_field( 'syn_data_run_reset_user_capabilities', 0, 'option' );
			update_field( 'syn_data_run_dedupe_events', 0, 'option' );
		}
	}
	
	function __syntric_load_google_maps( $field ) {
		if( 'select' == $field[ 'type' ] ) {
			$choices     = [];
			$google_maps = get_field( 'syn_google_maps', 'option' );
			if( $google_maps ) {
				foreach( $google_maps as $google_map ) {
					$choices[ $google_map[ 'google_map_id' ] ] = $google_map[ 'name' ];
				}
			}
			$field[ 'choices' ] = $choices;
		}
		
		return $field;
	}
	
	function __syntric_load_nav_menu( $field ) {
		if( 'select' == $field[ 'type' ] ) {
			$choices = [];
			$menus   = get_terms( 'nav_menu', [ 'hide_empty' => true ] );
			foreach( $menus as $menu ) {
				$choices[ $menu -> term_id ] = $menu -> name;
			}
			$field[ 'choices' ] = $choices;
		}
		
		return $field;
	}
	
	