<?php

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

/**
 * Prepare field
 *add_filter( 'acf/prepare_field/name=google_map_id', 'syntric_hide_field' );
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
 * }
 * // School field group
 * add_filter( 'acf/prepare_field/name=teacher', 'syntric_acf_prepare_fields' ); // this is also on teacher page
 * add_filter( 'acf/prepare_field/name=term', 'syntric_acf_prepare_fields' );
 * add_filter( 'acf/prepare_field/name=schedules', 'syntric_acf_prepare_fields' );
 * add_filter( 'acf/prepare_field/name=period', 'syntric_acf_prepare_fields' );
 * add_filter( 'acf/prepare_field/name=course', 'syntric_acf_prepare_fields' );
 * add_filter( 'acf/prepare_field/name=building', 'syntric_acf_prepare_fields' );
 * add_filter( 'acf/prepare_field/name=room', 'syntric_acf_prepare_fields' );
 * add_filter( 'acf/prepare_field/name=class', 'syntric_acf_prepare_fields' );
 *
 * // Filters field group - is cloned frequently
 * //add_filter( 'acf/prepare_field/name=page_template_value', 'syntric_acf_prepare_fields' );
 * //add_filter( 'acf/prepare_field/name=post_type_value', 'syntric_acf_prepare_fields' );
 *
 * // User field group
 * //add_filter( 'acf/prepare_field/name=teacher_page', 'syntric_acf_prepare_fields' );
 *
 * // Upcoming Events Widget field group
 * //add_filter( 'acf/prepare_field/name=calendar', 'syntric_acf_prepare_fields' );
 *
 * // Contact Widget & Roster Widget
 * //add_filter( 'acf/prepare_field/name=person', 'syntric_acf_prepare_fields' );
 * //add_filter( 'acf/prepare_field/name=organization', 'syntric_acf_prepare_fields' );
 *
 * // Facebook Widget
 * //add_filter( 'acf/prepare_field/name=facebook_page', 'syntric_acf_prepare_fields' );
 *
 * // blocks
 * //add_filter( 'acf/prepare_field/name=wordpress_blocks', 'syntric_acf_prepare_fields' );
 * //add_filter( 'acf/prepare_field/name=syn_post_category', 'syntric_acf_prepare_fields' );
 * //add_filter( 'acf/prepare_field/name=syn_post_microblog', 'syntric_acf_prepare_fields' );
 * //add_filter( 'acf/prepare_field/name=syn_page_class', 'syntric_acf_prepare_fields' );
 * //add_filter( 'acf/prepare_field/name=syn_page_class_teacher', 'syntric_acf_prepare_fields' );
 * //add_filter( 'acf/prepare_field/name=syn_page_teacher', 'syntric_acf_prepare_fields' );
 * //add_filter( 'acf/prepare_field/name=syn_page_department', 'syntric_acf_prepare_fields' );
 * //add_filter( 'acf/prepare_field/name=syn_page_course', 'syntric_acf_prepare_fields' );
 * //add_filter( 'acf/prepare_field/name=include_page', 'syntric_acf_prepare_fields' );
 * // Page widgets
 * //add_filter( 'acf/prepare_field/name=syn_contact_title', 'syntric_acf_prepare_fields' );
 * //add_filter( 'acf/prepare_field/name=syn_contact_default', 'syntric_acf_prepare_fields' );
 * //add_filter( 'acf/prepare_field/name=syn_contact_person', 'syntric_acf_prepare_fields' );
 * //add_filter( 'acf/prepare_field/name=person', 'syntric_acf_prepare_fields' );
 * //add_filter( 'acf/prepare_field/name=syn_contact_person_include_fields', 'syntric_acf_prepare_fields' );
 * //add_filter( 'acf/prepare_field/name=syn_contact_organization', 'syntric_acf_prepare_fields' );
 * //add_filter( 'acf/prepare_field/name=syn_contact_organization_include_fields', 'syntric_acf_prepare_fields' );
 * //add_filter( 'acf/prepare_field/name=syn_roster_title', 'syntric_acf_prepare_fields' );
 * //add_filter( 'acf/prepare_field/name=syn_roster_include_fields', 'syntric_acf_prepare_fields' );
 * //add_filter( 'acf/prepare_field/name=syn_calendar_title', 'syntric_acf_prepare_fields' );
 * //add_filter( 'acf/prepare_field/name=syn_calendar_id', 'syntric_acf_prepare_fields' );
 * //add_filter( 'acf/prepare_field/name=syn_microblog_title', 'syntric_acf_prepare_fields' );
 * //add_filter( 'acf/prepare_field/name=syn_microblog_category', 'syntric_acf_prepare_fields' );
 * //add_filter( 'acf/prepare_field/name=syn_microblog_term', 'syntric_acf_prepare_fields' );
 * //add_filter( 'acf/prepare_field/name=syn_microblog_category_select', 'syntric_acf_prepare_fields' );
 * //add_filter( 'acf/prepare_field/name=syn_microblog_term_select', 'syntric_acf_prepare_fields' );
 * //add_filter( 'acf/prepare_field/name=syn_new_microblog_post', 'syntric_acf_prepare_fields' );
 * //add_filter( 'acf/prepare_field/name=syn_attachments_active', 'syntric_acf_prepare_fields' );
 * //add_filter( 'acf/prepare_field/name=syn_attachments_title', 'syntric_acf_prepare_fields' );
 * //add_filter( 'acf/prepare_field/name=syn_attachments', 'syntric_acf_prepare_fields' );
 * //add_filter( 'acf/prepare_field/name=syn_video_active', 'syntric_acf_prepare_fields' );
 * //add_filter( 'acf/prepare_field/name=syn_video_title', 'syntric_acf_prepare_fields' );
 * //add_filter( 'acf/prepare_field/name=syn_video_host', 'syntric_acf_prepare_fields' );
 * //add_filter( 'acf/prepare_field/name=syn_video_youtube_id', 'syntric_acf_prepare_fields' );
 * //add_filter( 'acf/prepare_field/name=syn_video_vimeo_id', 'syntric_acf_prepare_fields' );
 * //add_filter( 'acf/prepare_field/name=syn_video_caption', 'syntric_acf_prepare_fields' );
 * //add_filter( 'acf/prepare_field/name=syn_google_map_active', 'syntric_acf_prepare_fields' );
 * //add_filter( 'acf/prepare_field/name=syn_google_map_title', 'syntric_acf_prepare_fields' );
 * //add_filter( 'acf/prepare_field/name=syn_google_map_id', 'syntric_acf_prepare_fields' );
 * //Users
 * //add_filter( 'acf/prepare_field/name=syn_user_page', 'syntric_acf_prepare_fields' );
 * //add_filter( 'acf/prepare_field/name=syn_user_is_teacher', 'syntric_acf_prepare_fields' );
 * //add_filter( 'acf/prepare_field/key=field_5ad3b76d1ea8b', 'syntric_acf_prepare_fields' );
 * // Widgets
 * //add_filter( 'acf/prepare_field/name=syn_contact_widget_default', 'syntric_acf_prepare_fields' );
 * //add_filter( 'acf/prepare_field/name=syn_contact_widget_organization', 'syntric_acf_prepare_fields' );
 * //add_filter( 'acf/prepare_field/name=syn_contact_widget_person', 'syntric_acf_prepare_fields' );
 * //add_filter( 'acf/prepare_field/name=syn_upcoming_events_widget_calendar_id', 'syntric_acf_prepare_fields' );
 * //add_filter( 'acf/prepare_field/name=syn_nav_menu_widget_menu', 'syntric_acf_prepare_fields' );
 * //add_filter( 'acf/prepare_field/name=syn_facebook_page_widget_page', 'syntric_acf_prepare_fields' );
 * //add_filter( 'acf/prepare_field/name=syn_google_map_widget_map_id', 'syntric_acf_prepare_fields' );
 * // Retired????  Check these
 * //add_filter( 'acf/prepare_field/name=default_organization', 'syntric_acf_prepare_fields' );
 * //add_filter( 'acf/prepare_field/name=class_id', 'syntric_acf_prepare_fields' );
 * //add_filter( 'acf/prepare_field/name=include_page', 'syntric_acf_prepare_fields' );
 * //add_filter( 'acf/prepare_field/name=page', 'syntric_acf_prepare_fields' );
 * // Calendar CPT
 * //add_filter( 'acf/prepare_field/name=syn_calendar_last_sync', 'syntric_acf_prepare_fields' );
 * //add_filter( 'acf/prepare_field/name=syn_calendar_last_sync_result', 'syntric_acf_prepare_fields' );
 * // Taxonomy
 * //add_filter( 'acf/prepare_field/name=syn_category_page', 'syntric_acf_prepare_fields' );
 * // by key
 * //add_filter( 'acf/prepare_field/key=field_59b118daf73d0', 'syntric_acf_prepare_fields' ); // Google Maps > markers > organization select field
 * //add_filter( 'acf/prepare_field/key=field_59d127e512d9a', 'syntric_acf_prepare_fields', 20 ); // syntric_classes Class Page message field
 * //add_filter( 'acf/prepare_field/key=field_59e7f2f7049e2', 'syntric_acf_prepare_fields' ); // Post category message field
 * //add_filter( 'acf/prepare_field/key=field_59e7f370049e3', 'syntric_acf_prepare_fields' ); // Post term (microblog) message field
 * //add_filter( 'acf/prepare_field/key=field_59d4b242abf31', 'syntric_acf_prepare_fields' ); // Page widgets microblog category message field
 * //add_filter( 'acf/prepare_field/key=field_59d4b278abf32', 'syntric_acf_prepare_fields' ); // Page widgets microblog term message field
 * //add_filter( 'acf/prepare_field/key=field_598a9db813a14', 'syntric_acf_prepare_fields' ); // Page widgets Attachments tab
 * //add_filter( 'acf/prepare_field/key=field_5a41bf632a648', 'syntric_acf_prepare_fields' ); // Page widgets Google Map tab
 * //add_filter( 'acf/prepare_field/key=field_5a41c15b2a64c', 'syntric_acf_prepare_fields' ); // Page widgets video tab
 * //add_filter( 'acf/prepare_field/key=field_59bb8bf493b01', 'syntric_acf_prepare_fields' ); // Courses > department select field
 * //add_filter( 'acf/prepare_field/key=field_59bb90c45ec6d', 'syntric_acf_prepare_fields' ); // Rooms > building select field*/
add_filter( 'acf/prepare_field/key=field_5c80dc4a53446', 'syntric_acf_prepare_fields' ); // syntric_user_photo
add_filter( 'acf/prepare_field/name=page_template_value', 'syntric_acf_prepare_fields' );
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
			if( 'select' == $field[ 'type' ] ) {
				$departments = get_field( 'syntric_departments', 'option' );
				if( $departments ) {
					$choices = [];
					foreach( $departments as $department ) {
						$choices[ $department[ 'id' ] ] = $department[ 'department' ];
					}
				}
				$field[ 'choices' ] = $choices;
			}
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
			if( 'select' == $field[ 'type' ] ) {
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
			}
		break;
		case 'course' :
			$courses = get_field( 'syntric_courses', 'option' );
			//slog( $courses );
			if( 'select' == $field[ 'type' ] ) {
				$courses = get_field( 'syntric_courses', 'option' );
//slog($courses);
				if( $courses ) {
					$choices = [];
//slog($courses);
					foreach( $courses as $course ) {
//slog($course);
						$choices[ $course[ 'id' ] ] = $course[ 'course' ];
					}
					$field[ 'choices' ] = $choices;
				}
			}

		break;
		case 'building' :
			if( 'select' == $field[ 'type' ] ) {
				$buildings = get_field( 'syntric_buildings', 'option' );
				if( $buildings ) {
					$choices = [];
					foreach( $buildings as $building ) {
						$choices[ $building[ 'id' ] ] = $building[ 'building' ];
					}
					$field[ 'choices' ] = $choices;
				}
			}
		break;
		case 'room' :
			if( 'select' == $field[ 'type' ] ) {
				$rooms = get_field( 'syntric_rooms', 'option' );
				if( $rooms ) {
					$choices = [];
					foreach( $rooms as $room ) {
//$bld = ( !  empty( $room['building'] ) ) ? $room['building']['label'] . ' ' : '';
						$choices[ $room[ 'id' ] ] = $room[ 'room' ];
					}
					$field[ 'choices' ] = $choices;
				}
			}
		break;
		case 'class' :
			if( 'select' == $field[ 'type' ] ) {
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
			}
		break;
// filters
		case 'post_type_value' :
			if( 'select' == $field[ 'type' ] ) {
				$post_types = get_post_types( [ 'public' => true ], 'objects' );
				$choices    = [];
				foreach( $post_types as $post_type ) {
					$choices[ $post_type -> name ] = $post_type -> labels -> singular_name;
				}
				$field[ 'choices' ] = $choices;
			}
		break;
		case 'page_template_value' :
			if( 'select' == $field[ 'type' ] ) {
				$page_templates = get_page_templates();
				$choices        = [];
				foreach( $page_templates as $key => $value ) {
					$choices[ $value ] = $key;
				}
				$field[ 'choices' ] = $choices;
			}
		break;
// user
		case 'teacher_page' :
			if( 'select' == $field[ 'type' ] ) {
//if( 'profile.php' == $pagenow || 'user-new.php' == $pagenow ) {
				$teacher_pages = syntric_get_teacher_pages();
				if( $teacher_pages ) {
					$choices = [];
					foreach( $teacher_pages as $teacher_page ) {
						$choices[ $teacher_page -> ID ] = $teacher_page -> post_title;
					}
					$field[ 'choices' ] = $choices;
				}
			}
//}
		break;
// upcoming events widget
		case 'calendar' :
			if( 'select' == $field[ 'type' ] ) {
				$calendars = syntric_get_calendars();
				if( $calendars ) {
					$choices = [];
					foreach( $calendars as $calendar ) {
						$choices[ $calendar -> ID ] = $calendar -> post_title;
					}
					$field[ 'choices' ] = $choices;
				}
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
			break;
		case 'syntric_contact_widget_organization' :
			$field = syntric_load_organizations( $field );
			break;
		case 'syntric_contact_widget_person' :
			$field = syntric_load_people( $field );
			break;
		case 'person' :
			$field = syntric_load_people( $field );
			break;
		case 'syntric_upcoming_events_widget_calendar_id' :
			$field = syntric_load_google_calendars( $field );
			break;
		case 'syntric_nav_menu_widget_menu' :
			if( 'select' == $field[ 'type' ] ) {
				$choices = [];
				$menus   = get_terms( 'nav_menu', [ 'hide_empty' => true ] );
				foreach( $menus as $menu ) {
					$choices[ $menu -> term_id ] = $menu -> name;
				}
				$field[ 'choices' ] = $choices;
			}
			break;
		case 'syntric_contact_widget_default' :
			$field[ 'label' ]   = '';
			$field[ 'message' ] = 'Use ' . get_field( 'syn_organization', 'option' );
			break;
		case 'syntric_contact_default' :
			$field[ 'label' ]   = get_field( 'syn_organization', 'option' );
			$field[ 'message' ] = 'Use ' . get_field( 'syn_organization', 'option' );
			break;
		case 'syntric_facebook_page_widget_page' :
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
			break;
		case 'syntric_google_map_widget_map_id' :
		case 'syntric_google_map_id' :
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
			break;
		case 'default_organization' : // field_59efde970195f is in Google Map > markers
			$field[ 'label' ]   = '';
			$field[ 'message' ] = 'Use ' . get_field( 'syn_organization', 'option' );
			break;
		case 'syntric_user_page' :
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
			break;
		case 'syntric_user_is_teacher' :
			if( ! syntric_current_user_can( 'editor' ) ) {
				$field[ 'wrapper' ][ 'hidden' ] = 1;
			}
			break;
		case 'syntric_post_category' :
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
			break;
		case 'syntric_post_microblog' :
			if( $field[ 'value' ] ) {
				$field[ 'wrapper' ][ 'hidden' ] = 1;
			}
			break;
		case 'syntric_page_class' :
			$field = syntric_load_classes( $field );
			if( $field[ 'value' ] ) {
				$field[ 'disabled' ] = 1;
			}
			break;
		case 'syn_page_class_teacher' :
			$field = syntric_load_teachers( $field );
			if( $field[ 'value' ] ) {
				$field[ 'disabled' ] = 1;
			}
			break;
		case 'syn_page_teacher' :
			$field = syntric_load_teachers( $field );
			if( $field[ 'value' ] ) {
				$field[ 'disabled' ] = 1;
			}
			break;
		case 'syn_page_department' :
			$field = syntric_load_departments( $field );
			if( $field[ 'value' ] ) {
				$field[ 'disabled' ] = 1;
			}
			break;
		case 'syn_page_course' :
			$field = syntric_load_courses( $field );
			if( $field[ 'value' ] ) {
				$field[ 'disabled' ] = 1;
			}
			break;
		case 'syn_contact_person' :
			$field = syntric_load_people( $field );
			break;
		case 'syn_contact_organization' :
			$field = syntric_load_organizations( $field );
			if( ! $field[ 'value' ] ) {
				$organization_id  = get_field( 'syn_organization_id', 'option' );
				$field[ 'value' ] = $organization_id;
			}
			break;
		case 'calendar_id' :
			$field = syntric_load_google_calendars( $field );
			break;
		case 'syn_microblog_category' :
//if ( ! syntric_current_user_can( 'editor' ) ) {
			$field = syntric_load_categories( $field );
			if( $field[ 'value' ] ) {
				$field[ 'disabled' ] = 1;
			}
//}
			break;
		case 'syn_microblog_term' :
//if ( ! syntric_current_user_can( 'editor' ) ) {
			$field = syntric_load_microblogs( $field );
			if( $field[ 'value' ] ) {
				$field[ 'disabled' ] = 1;
			}
//}
			break;
		case 'syn_microblog_category_select' :
//$categories = get_categories( [ 'taxonomy' => 'category', 'hide_empty' => false ]);
//if ( ! syntric_current_user_can( 'editor' ) ) {
			$field               = syntric_load_categories( $field );
			$field[ 'disabled' ] = 1;
//}
			break;
		case 'syn_microblog_term_select' :
//if ( ! syntric_current_user_can( 'editor' ) ) {
			$field               = syntric_load_microblogs( $field );
			$field[ 'disabled' ] = 1;
//}
			break;
		case 'syn_new_microblog_post' :
			$microblog_active = get_field( 'syn_microblog_active', $post -> ID );
			if( ! $microblog_active ) {
				$field[ 'wrapper' ][ 'hidden' ] = 1;
			} else {
				$field[ 'wrapper' ][ 'hidden' ] = 0;
			}
			break;
		case 'syn_category_page' :
			if( 'edit-tags.php' == $pagenow || 'term.php' == $pagenow ) {
				$field[ 'wrapper' ][ 'hidden' ] = 1;
			}
			break;
		case 'syntric_attachments_active':
		case 'syntric_video_active':
		case 'syntric_google_map_active':
		return false;
		break;*/

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
	endswitch;
// User > Teacher Page message field
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

add_filter( 'acf/load_field/name=building', 'syntric_acf_load_field' ); // syntric_rooms_building
add_filter( 'acf/load_field/name=department', 'syntric_acf_load_field' ); // syntric_courses_department & syntric_department_page_department
add_filter( 'acf/load_field/name=teacher', 'syntric_acf_load_field' ); // syntric_classes_teacher & syntric_teacher_page_teacher
add_filter( 'acf/load_field/name=course', 'syntric_acf_load_field' ); // syntric_classes_course & syntric_course_page_course
add_filter( 'acf/load_field/name=period', 'syntric_acf_load_field' ); // syntric_classes_period
add_filter( 'acf/load_field/name=room', 'syntric_acf_load_field' ); // syntric_classes_room
//add_filter( 'acf/load_field/name=class_page', 'syntric_acf_load_field' ); // syntric_classes_class_page
add_filter( 'acf/load_field/name=class', 'syntric_acf_load_field' ); // syntric_class_page_class
add_filter( 'acf/load_field/name=schedules', 'syntric_acf_load_field' ); // syntric_schedules_page_schedules
add_filter( 'acf/load_field/name=teacher_page', 'syntric_acf_load_field' ); // syntric_user_teacher_page
//add_filter( 'acf/load_field/name=page_template_value', 'syntric_acf_load_field' ); // filters_value_page_template_value
add_filter( 'acf/load_field/name=post_type_value', 'syntric_acf_load_field' ); // filters_value_post_type_value
add_filter( 'acf/load_field/name=page_type_value', 'syntric_acf_load_field' ); // filters_value_page_type_value
add_filter( 'acf/load_field/name=person', 'syntric_acf_load_field' ); // syntric_contact_widget_person
add_filter( 'acf/load_field/name=organization', 'syntric_acf_load_field' ); // syntric_contact_widget_organization

add_filter( 'acf/load_field/key=field_5cb76e6d4e431', 'syntric_acf_load_field' ); // syntric_classes class page message field
/*// syntric_rooms ////////////////////
add_filter( 'acf/load_field/key=field_5c6fada5bbd68', 'syntric_acf_load_field' ); // syntric_rooms_building
// syntric_courses ////////////////////
add_filter( 'acf/load_field/key=field_5c6fae31bbd6d', 'syntric_acf_load_field' ); // syntric_courses_department
// syntric_classes ////////////////////
add_filter( 'acf/load_field/key=field_5cb5b9fcc5f22', 'syntric_acf_load_field' ); // syntric_classes_teacher
add_filter( 'acf/load_field/key=field_5cb5ba20c5f23', 'syntric_acf_load_field' ); // syntric_classes_course
add_filter( 'acf/load_field/key=field_5cb5ba2fc5f24', 'syntric_acf_load_field' ); // syntric_classes_period
add_filter( 'acf/load_field/key=field_5cb5ba41c5f25', 'syntric_acf_load_field' ); // syntric_classes_room
add_filter( 'acf/load_field/key=field_5cb5ba89c5f27', 'syntric_acf_load_field' ); // syntric_classes_class_page
// syntric_class_page ////////////////////
add_filter( 'acf/load_field/key=field_5cb5bd08723f3', 'syntric_acf_load_field' ); // syntric_class_page_class
// syntric_course_page ////////////////////
add_filter( 'acf/load_field/key=field_5c97d03566669', 'syntric_acf_load_field' ); // syntric_course_page_course
// syntric_department_page ////////////////////
add_filter( 'acf/load_field/key=field_5c97d08f2b7d3', 'syntric_acf_load_field' ); // syntric_department_page_department
// syntric_schedules_page ////////////////////
add_filter( 'acf/load_field/key=field_5c97d1fb103b3', 'syntric_acf_load_field' ); // syntric_schedules_page_schedules
// syntric_teacher_page ////////////////////
add_filter( 'acf/load_field/key=field_5cadb2ec3de2d', 'syntric_acf_load_field' ); // syntric_teacher_page_teacher
// syntric_user ////////////////////
add_filter( 'acf/load_field/key=field_5cadbcc82bd79', 'syntric_acf_load_field' ); // syntric_user_teacher_page
// filters ////////////////////
add_filter( 'acf/load_field/key=field_59a1f43953657', 'syntric_acf_load_field' ); // filters_value_post_type_value
//add_filter( 'acf/load_field/key=field_59a1f76e5365c', 'syntric_acf_load_field' ); // filters_value_page_template_value
add_filter( 'acf/load_field/key=field_5c9947af26a7c', 'syntric_acf_load_field' ); // filters_value_page_type_value
// syntric_contact_widget
add_filter( 'acf/load_field/key=field_5c80da686ae5e', 'syntric_acf_load_field' ); // syntric_contact_widget_person
add_filter( 'acf/load_field/key=field_5ca1538c6091b', 'syntric_acf_load_field' ); // syntric_contact_widget_organization*/
//field_5c80da686ae5e person
//field_5ca1538c6091b organization
//field_5cb76e6d4e431 class page message field
function syntric_acf_load_field( $field ) {
	global $post, $pagenow;
	slog( 'syntric_acf_load_field...pagenow and _REQUEST' );
	slog( $pagenow );
	slog( $_REQUEST );
	if( isset( $_REQUEST[ 'action' ] ) && 'heartbeat' == $_REQUEST[ 'action' ] ) {
		return;
	}
	if( ! is_admin() ) {
		return;
	}
	//field_5cb76e6d4e431

	switch( $field[ 'key' ] ) :
		case 'field_5cb76e6d4e431' :
			slog( $field );
		break;
		/******************************************************
		 * // syntric_rooms_building
		 * case 'field_5c6fada5bbd68' :
		 * $choices   = [];
		 * $buildings = get_field( 'syntric_buildings', 'option' );
		 * if( $buildings ) {
		 * foreach( $buildings as $building ) {
		 * $choices[ $building[ 'id' ] ] = $building[ 'building' ];
		 * }
		 * }
		 * $field[ 'choices' ] = $choices;
		 * break;
		 * // syntric_department_page_department
		 * case 'field_5c97d08f2b7d3' :
		 * // syntric_courses_department
		 * case 'field_5c6fae31bbd6d' :
		 * $choices     = [];
		 * $departments = get_field( 'syntric_departments', 'option' );
		 * if( $departments ) {
		 * foreach( $departments as $department ) {
		 * $choices[ $department[ 'id' ] ] = $department[ 'department' ];
		 * }
		 * }
		 * $field[ 'choices' ] = $choices;
		 * break;
		 * // syntric_teacher_page_teacher
		 * case 'field_5cadb2ec3de2d' :
		 * // syntric_classes_teacher
		 * case 'field_5cb5b9fcc5f22' :
		 * $choices  = [];
		 * $teachers = syntric_get_teachers();
		 * if( $teachers ) {
		 * foreach( $teachers as $teacher ) {
		 * $choices[ $teacher -> ID ] = $teacher -> display_name;
		 * }
		 * }
		 * $field[ 'choices' ] = $choices;
		 * break;
		 * // syntric_course_page_course
		 * case 'field_5c97d03566669' :
		 * // syntric_classes_course
		 * case 'field_5cb5ba20c5f23' :
		 * $choices = [];
		 * $courses = get_field( 'syntric_courses', 'option' );
		 * if( $courses ) {
		 * foreach( $courses as $course ) {
		 * $choices[ $course[ 'id' ] ] = $course[ 'course' ];
		 * }
		 * }
		 * $field[ 'choices' ] = $choices;
		 * break;
		 * // syntric_classes_period
		 * case 'field_5cb5ba2fc5f24' :
		 * $choices   = [];
		 * $schedules = get_field( 'syntric_schedules', 'option' );
		 * if( $schedules ) {
		 * $regular_schedules = [];
		 * foreach( $schedules as $schedule ) {
		 * if( 'regular' == $schedule[ 'schedule_type' ] ) {
		 * $regular_schedules[] = $schedule;
		 * }
		 * }
		 * // now with regular schedules
		 * foreach( $regular_schedules as $regular_schedule ) {
		 * $_schedule = $regular_schedule[ 'schedule' ];
		 * foreach( $_schedule as $__schedule ) {
		 * if( $__schedule[ 'instructional_period' ] ) {
		 * $choices[ $__schedule[ 'period' ] ] = $__schedule[ 'period' ];
		 * }
		 * }
		 * }
		 * }
		 * $field[ 'choices' ] = $choices;
		 * break;
		 * //syntric_classes_room
		 * case 'field_5cb5ba41c5f25' :
		 * $choices = [];
		 * $rooms   = get_field( 'syntric_rooms', 'option' );
		 * if( $rooms ) {
		 * foreach( $rooms as $room ) {
		 * //$bld = ( !  empty( $room['building'] ) ) ? $room['building']['label'] . ' ' : '';
		 * $choices[ $room[ 'id' ] ] = $room[ 'room' ];
		 * }
		 * }
		 * $field[ 'choices' ] = $choices;
		 * break;
		 * // syntric_classes_class_page
		 * case 'field_5cb5ba89c5f27' :
		 * $choices     = [];
		 * $class_pages = syntric_get_class_pages();
		 * if( $class_pages ) {
		 * foreach( $class_pages as $class_page ) {
		 * $choices[ $class_page -> ID ] = $class_page -> post_title;
		 * }
		 * }
		 * $field[ 'choices' ] = $choices;
		 * break;
		 * // syntric_schedules_page_schedules
		 * case 'field_5c97d1fb103b3' :
		 * $choices   = [];
		 * $schedules = get_field( 'syntric_schedules', 'option' );
		 * if( $schedules ) {
		 * foreach( $schedules as $schedule ) {
		 * $choices[ $schedule[ 'name' ] ] = $schedule[ 'name' ];
		 * }
		 * }
		 * $field[ 'choices' ] = $choices;
		 * break;
		 * // filters
		 * case 'field_5cadbcc82bd79' :
		 * $choices       = [];
		 * $teacher_pages = syntric_get_teacher_pages();
		 * if( $teacher_pages ) {
		 * foreach( $teacher_pages as $teacher_page ) {
		 * $choices[ $teacher_page -> ID ] = $teacher_page -> post_title;
		 * }
		 * }
		 * $field[ 'choices' ] = $choices;
		 * break;
		 * case 'field_59a1f43953657' :
		 * $choices    = [];
		 * $post_types = get_post_types( [ 'public' => true ], 'objects' );
		 * if( $post_types ) {
		 * foreach( $post_types as $post_type ) {
		 * $choices[ $post_type -> name ] = $post_type -> labels -> singular_name;
		 * }
		 * }
		 * $field[ 'choices' ] = $choices;
		 * break;
		 * case 'field_59a1f76e5365c' :
		 * $choices        = [];
		 * $page_templates = get_page_templates( null, 'page' );
		 * if( $page_templates ) {
		 * foreach( $page_templates as $key => $value ) {
		 * $choices[ $value ] = $key;
		 * }
		 * }
		 * $field[ 'choices' ] = $choices;
		 * break;
		 * // syntric_contact_widget_person
		 * case 'field_5c80da686ae5e' :
		 * $choices = [];
		 * $users   = get_users( [
		 * 'meta_key' => 'last_name',
		 * 'order_by' => 'meta_value',
		 * ] );
		 * if( $users ) {
		 * foreach( $users as $user ) {
		 * $user_custom_fields     = get_field( 'syntric_user', 'user_' . $user -> ID );
		 * $label                  = ( ! empty( $prefix ) ) ? $prefix . ' ' . $user -> display_name : $user -> display_name;
		 * $label                  .= ( ! empty( $user_custom_fields[ 'title' ] ) ) ? ' - ' . str_replace( '|', ' / ', $user_custom_fields[ 'title' ] ) : ' - (no title)';
		 * $label                  .= ( ! empty( $user -> user_email ) ) ? ' - ' . $user -> user_email : ' - (no email)';
		 * $label                  .= ( ! empty( $user_custom_fields[ 'phone' ] ) ) ? ' - ' . $user_custom_fields[ 'phone' ] : ' - (no phone)';
		 * $label                  .= ( ! empty( $user_custom_fields[ 'ext' ] ) ) ? ' x' . $user_custom_fields[ 'ext' ] : '';
		 * $choices[ $user -> ID ] = $label;
		 * }
		 * }
		 * $field[ 'choices' ] = $choices;
		 * break;
		 * // syntric_contact_widget_organization
		 * case 'field_5ca1538c6091b' :
		 * $choices      = [];
		 * $organization = get_field( 'syntric_organization', 'option' );
		 * if( $organization ) {
		 * $choices[ $organization[ 'name' ] ] = $organization[ 'name' ];
		 * }
		 * $organizations = get_field( 'syntric_organizations', 'option' );
		 * if( $organizations ) {
		 * foreach( $organizations as $_organization ) {
		 * $choices[ $_organization[ 'name' ] ] = $_organization[ 'name' ];
		 * }
		 * }
		 * $field[ 'choices' ] = $choices;
		 * break;
		 ******************************************************/
	endswitch;

	if( is_admin() && 'select' == $field[ 'type' ] ) :
		switch( $field[ '_name' ] ) :
// syntric_rooms_building
			case 'building' :
				if( 'admin.php' == $pagenow && 'syntric-rooms' == $_REQUEST[ 'page' ] ) {
					//slog( '_name=building' );
					$choices   = [];
					$buildings = get_field( 'syntric_buildings', 'option' );
					if( $buildings ) {
						foreach( $buildings as $building ) {
							$choices[ $building[ 'id' ] ] = $building[ 'building' ];
						}
					}
					$field[ 'choices' ] = $choices;
				}
			break;
// syntric_department_page_department
			case 'department' :
				if( ( 'admin.php' == $pagenow && 'syntric-courses' == $_REQUEST[ 'page' ] ) ||
				    ( isset( $_REQUEST[ 'page_template' ] ) && 'department.php' == $_REQUEST[ 'page_template' ] ) ) {
					//slog( '_name=department' );
					$choices     = [];
					$departments = get_field( 'syntric_departments', 'option' );
					if( $departments ) {
						foreach( $departments as $department ) {
							$choices[ $department[ 'id' ] ] = $department[ 'department' ];
						}
					}
					$field[ 'choices' ] = $choices;
				}
			break;
// syntric_teacher_page_teacher & syntric_classes_teacher
			case 'teacher' :
				if( ( 'admin.php' == $pagenow && 'syntric-classes' == $_REQUEST[ 'page' ] ) ||
				    ( isset( $_REQUEST[ 'page_template' ] ) && 'teacher.php' == $_REQUEST[ 'page_template' ] ) ) {
					//slog( '_name=teacher' );
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
// syntric_course_page_course & syntric_classes_course
			case 'course' :
				if( ( 'admin.php' == $pagenow && 'syntric-classes' == $_REQUEST[ 'page' ] ) ||
				    //( isset( $_REQUEST[ 'page_template' ] ) && 'course.php' == $_REQUEST[ 'page_template' ] ) ) {
				    'course' == syntric_get_page_template( $post -> ID ) ) {
					slog( '_name=course' );
					$choices = [];
					if( have_rows( 'syntric_courses', 'option' ) ) {
						while( have_rows( 'syntric_courses', 'option' ) ) {
							the_row();
							$choices[ get_sub_field( 'id' ) ] = get_sub_field( 'course' );
						}
					}
					slog( $field );
					$field[ 'choices' ] = $choices;
				}
			break;
// syntric_classes_period
			case 'period' :
				//slog( '_name=period' );
				$choices   = [];
				$schedules = get_field( 'syntric_schedules', 'option' );
				if( $schedules ) {
					$regular_schedules = [];
					foreach( $schedules as $schedule ) {
						if( 'regular' == $schedule[ 'schedule_type' ] ) {
							$regular_schedules[] = $schedule;
						}
					}
// now with regular schedules
					foreach( $regular_schedules as $regular_schedule ) {
						$_schedule = $regular_schedule[ 'schedule' ];
						foreach( $_schedule as $__schedule ) {
							if( $__schedule[ 'instructional_period' ] ) {
								$choices[ $__schedule[ 'period' ] ] = $__schedule[ 'period' ];
							}
						}
					}
				}
				$field[ 'choices' ] = $choices;
			break;
//syntric_classes_room
			case 'room' :
				if( 'admin.php' == $pagenow && 'syntric-classes' == $_REQUEST[ 'page' ] ) {
					//	slog( '_name=room' );
					$choices = [];
					$rooms   = get_field( 'syntric_rooms', 'option' );
					if( $rooms ) {
						foreach( $rooms as $room ) {
//$bld = ( !  empty( $room['building'] ) ) ? $room['building']['label'] . ' ' : '';
							$choices[ $room[ 'id' ] ] = $room[ 'room' ];
						}
					}
					$field[ 'choices' ] = $choices;
				}
			break;
// syntric_classes_class_page
			/*case 'class_page' :
			//	slog( '_name=class_page' );
				$choices     = [];
				$class_pages = syntric_get_class_pages();
				if( $class_pages ) {
					foreach( $class_pages as $class_page ) {
						$choices[ $class_page -> ID ] = $class_page -> post_title;
					}
				}
				$field[ 'choices' ] = $choices;
			break;*/
// syntric_schedules_page_schedules
			case 'schedules' :
				if( ( 'admin.php' == $pagenow && 'syntric-rooms' == $_REQUEST[ 'page' ] ) ||
				    ( isset( $_REQUEST[ 'page_template' ] ) && 'schedules.php' == $_REQUEST[ 'page_template' ] ) ) {
					//slog( '_name=schedules' );
					$choices   = [];
					$schedules = get_field( 'syntric_schedules', 'option' );
					if( $schedules ) {
						foreach( $schedules as $schedule ) {
							$choices[ $schedule[ 'name' ] ] = $schedule[ 'name' ];
						}
					}
					$field[ 'choices' ] = $choices;
				}
			break;
// syntric_class_page_class
			case 'class' :
				if( ( 'post.php' == $pagenow && 'edit' == $_REQUEST[ 'action' ] ) ||
				    ( 'post-new.php' == $pagenow ) ||
				    ( isset( $_REQUEST[ 'page_template' ] ) && 'class.php' == $_REQUEST[ 'page_template' ] ) ) {
					$choices = [];
					$classes = get_field( 'syntric_classes', 'option' );
					if( $classes ) {
						slog( $post );
						$post_parent_template = syntric_get_page_template( $post -> post_parent );
						$teacher_id           = get_field( 'syntric_teacher_page_teacher', $post -> post_parent );
						$author_id            = $post -> post_author;
						slog( $post_parent_template );
						slog( 'teacher_id: ' . $teacher_id );
						slog( 'author_id: ' . $author_id );
						foreach( $classes as $class ) {
							$teacher = get_user_by( 'ID', $class[ 'teacher' ][ 'value' ] );
							slog( 'class-teacher-value: ' . $class[ 'teacher' ][ 'value' ] );
							slog( 'teacher->ID: ' . $teacher -> ID );
							if( $teacher -> ID == $teacher_id && $teacher -> ID == $author_id && $teacher -> ID == $class[ 'teacher' ][ 'value' ] ) {
								$choices[ $class[ 'id' ] ] = substr( $teacher -> first_name, 0, 1 ) . '. ' . $teacher -> last_name . ' / ' . $class[ 'course' ][ 'label' ] . ' / Period ' . $class[ 'period' ][ 'label' ];
							}
						}
					}
					$field[ 'choices' ] = $choices;
				}
			break;
// filters
			/*case 'field_5cadbcc82bd79' :
				$choices       = [];
				$teacher_pages = syntric_get_teacher_pages();
				if( $teacher_pages ) {
					foreach( $teacher_pages as $teacher_page ) {
						$choices[ $teacher_page -> ID ] = $teacher_page -> post_title;
					}
				}
				$field[ 'choices' ] = $choices;
			break;
			case 'field_59a1f43953657' :
				$choices    = [];
				$post_types = get_post_types( [ 'public' => true ], 'objects' );
				if( $post_types ) {
					foreach( $post_types as $post_type ) {
						$choices[ $post_type -> name ] = $post_type -> labels -> singular_name;
					}
				}
				$field[ 'choices' ] = $choices;
			break;
			case 'field_59a1f76e5365c' :
				$choices        = [];
				$page_templates = get_page_templates( null, 'page' );
				if( $page_templates ) {
					foreach( $page_templates as $key => $value ) {
						$choices[ $value ] = $key;
					}
				}
				$field[ 'choices' ] = $choices;
			break;*/
// syntric_contact_widget_person
			case 'person' :
				if( 'widgets.php' == $pagenow ) {
					//slog( '_name=person' );
					$choices = [];
					$users   = get_users( [
						'meta_key' => 'last_name',
						'order_by' => 'meta_value',
					] );
					if( $users ) {
						foreach( $users as $user ) {
							$user_custom_fields     = get_field( 'syntric_user', 'user_' . $user -> ID );
							$label                  = ( ! empty( $prefix ) ) ? $prefix . ' ' . $user -> display_name : $user -> display_name;
							$label                  .= ( ! empty( $user_custom_fields[ 'title' ] ) ) ? ' - ' . str_replace( '|', ' / ', $user_custom_fields[ 'title' ] ) : ' - (no title)';
							$label                  .= ( ! empty( $user -> user_email ) ) ? ' - ' . $user -> user_email : ' - (no email)';
							$label                  .= ( ! empty( $user_custom_fields[ 'phone' ] ) ) ? ' - ' . $user_custom_fields[ 'phone' ] : ' - (no phone)';
							$label                  .= ( ! empty( $user_custom_fields[ 'ext' ] ) ) ? ' x' . $user_custom_fields[ 'ext' ] : '';
							$choices[ $user -> ID ] = $label;
						}
					}
					$field[ 'choices' ] = $choices;
				}
			break;
// syntric_contact_widget_organization
			case 'organization' :
				if( 'widgets.php' == $pagenow ) {
					//	slog( '_name=organization' );
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
		endswitch;
	endif;

	/*switch( $field[ '_name' ] ) :
		case 'department' :
			if( 'select' == $field[ 'type' ] ) {
				$departments = get_field( 'syntric_departments', 'option' );
				if( $departments ) {
					$choices = [];
					foreach( $departments as $department ) {
						$choices[ $department[ 'id' ] ] = $department[ 'department' ];
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
		case 'teacher' :
			if( 'select' == $field[ 'type' ] ) {
				$choices  = [];
				$teachers = syntric_get_teachers();
				if( $teachers ) {
					foreach( $teachers as $teacher ) {
						$choices[ $teacher -> ID ] = $teacher -> display_name . ' (Teacher) ';
					}
				}
				$field[ 'choices' ] = $choices;
			}
			break;
		 case 'period' :
			if( 'select' == $field[ 'type' ] ) {
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
						if( $__schedule[ 'instructional_period' ] ) {
							$choices[ $__schedule[ 'period' ] ] = $__schedule[ 'period' ];
						}
					}
				}
				$field[ 'choices' ] = $choices;
			}
			break;
		case 'course' :
			if( 'select' == $field[ 'type' ] ) {
				$courses = get_field( 'syntric_courses', 'option' );
				if( $courses ) {
					$choices = [];
					foreach( $courses as $course ) {
						$choices[ $course[ 'id' ] ] = $course[ 'course' ];
					}
					$field[ 'choices' ] = $choices;
				}
			}

			break;
		case 'building' :
			if( 'select' == $field[ 'type' ] ) {
				$buildings = get_field( 'syntric_buildings', 'option' );
				if( $buildings ) {
					$choices = [];
					foreach( $buildings as $building ) {
						$choices[ $building[ 'id' ] ] = $building[ 'building' ];
					}
					$field[ 'choices' ] = $choices;
				}
			}
			break;
		case 'room' :
			if( 'select' == $field[ 'type' ] ) {
				$rooms = get_field( 'syntric_rooms', 'option' );
				if( $rooms ) {
					$choices = [];
					foreach( $rooms as $room ) {
//$bld = ( !  empty( $room['building'] ) ) ? $room['building']['label'] . ' ' : '';
						$choices[ $room[ 'id' ] ] = $room[ 'room' ];
					}
					$field[ 'choices' ] = $choices;
				}
			}
			break;
		case 'class' :
			if( 'select' == $field[ 'type' ] ) {
//	$page_author
				$classes = get_field( 'syntric_classes', 'option' );
				if( $classes ) {
					$choices = [];
					asort( $classes );
					foreach( $classes as $class ) {
						$teacher_id                = $class[ 'teacher' ][ 'value' ];
						$teacher                   = get_user_by( 'ID', $teacher_id );
						$course                    = $class[ 'course' ][ 'label' ];
						$period                    = $class[ 'period' ][ 'label' ];
						$room                      = $class[ 'room' ][ 'label' ];
						$choices[ $class[ 'id' ] ] = $course . ' / ' . $teacher -> display_name . ' / ' . $period . ' / ' . $room;
					}
					$field[ 'choices' ] = $choices;
				}
			}
			break;
		case 'class_page' :
			if( 'select' == $field[ 'type' ] ) {
				$class_pages = syntric_get_class_pages();
				if( $class_pages ) {
					$choices = [];
					foreach( $class_pages as $class_page ) {
//$class_page_cfs               = get_field( 'syntric_class_page', $class_page -> ID );
//$class                        = $class_page_cfs['class'];

						$choices[ $class_page -> ID ] = $class_page -> post_title . ' / ';
					}
					$field[ 'choices' ] = $choices;
				}
			}
			break;
	endswitch;*/

	return $field;
}

//add_filter( 'acf/fields/user/query/name=teacher', 'syntric_filter_users_to_teachers', 10, 3 ); // syntric_classes_teacher
function syntric_filter_users_to_teachers( $args, $field, $options ) {
	slog( 'syntric_filter_users_to_teachers' );
	slog( $args );
	slog( $field );
	slog( $options );

	return $args;
}

//add_filter( 'acf/fields/user/result/name=teacher', 'syntric_user_field_title', 10, 4 );
function syntric_user_field_title( $result, $user, $field, $post_id ) {
	$user_cf    = get_field( 'syntric_user', 'user_' . $user -> ID );
	$is_teacher = $user_cf[ 'is_teacher' ];
	if( ! $is_teacher ) {
		return false;
	} else {
		return $user -> first_name . ' ' . $user -> last_name . ' Teacher: ' . $user_cf[ 'is_teacher' ];
	}
	//$suffix             = ( $user_cf[ 'is_teacher' ] ) ? ' (Teacher)' : '';

	//return false;

}

//add_filter('acf/load_value/name=teacher', 'syntric_load_teacher_value', 10, 3);
function syntric_load_teacher_value( $value, $post_id, $field ) {
	slog( $value );
	// run the_content filter on all textarea values
	//$value = apply_filters('the_content',$value);

	return $value;
}

// acf/load_value - filter for every value load

/*
* Set default order on ACF Field Groups list (cause it's annoying)
*/
add_filter( 'pre_get_posts', 'syntric_acf_pre_get_posts', 1, 1 );
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

/*
* Save post actions for all saves of posts, pages, options, users, CPTs, etc - fires every time a post save has ACF fields included
*
* Priority 20 fires after ACF has saved the custom field data, well after the post itself has been saved
*/
add_action( 'acf/save_post', 'syntric_acf_save_post', 20 );
function syntric_acf_save_post( $post_id ) {
	global $pagenow, $post, $post_type;
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
	if( is_numeric( $post_id ) ) {
		$post_type = get_post_type( $post_id );
	} elseif( 'option' == $post_id || 'options' == $post_id ) {
		$post_type = 'options';
	} else {
// user, widget, etc...
		$post_id_arr = explode( '_', $post_id );
		$post_type   = $post_id_arr[ 0 ];
	}
// Switch on post type
	switch( $post_type ) :
		case 'user' :
			if( is_admin() && in_array( $pagenow, [ 'user-new.php', 'user-edit.php', 'profile.php' ] ) ) {
				$post_id_arr   = explode( '_', $post_id );
				$user_id       = $post_id_arr[ 1 ];
				$user          = get_user_by( 'ID', $user_id );
				$user_cf       = get_field( 'syntric_user', 'user_' . $user_id );
				$user_role     = syntric_user_role( $user -> ID );
				$is_teacher    = $user_cf[ 'is_teacher' ];
				$teacher_page  = $user_cf[ 'teacher_page' ];
				$teachers_page = syntric_get_teachers_page();
				//slog( $user_cf );
				if( $is_teacher ) {
					//slog( 'user is teacher' );
					if( ! in_array( $user_role, [ 'author', 'editor', 'administrator' ] ) ) {
						$user -> set_role( 'author' );
					}
					if( $teacher_page instanceof WP_Post ) {
						//slog( 'syntric_user_teacher_page is WP_Post' );
						$args            = [
							'ID'          => $teacher_page -> ID,
							'post_title'  => $user -> first_name . ' ' . $user -> last_name,
							'post_author' => $user -> ID,
							'post_parent' => $teachers_page -> ID,
						];
						$teacher_page_id = wp_update_post( $args );
						update_post_meta( $teacher_page_id, '_wp_page_template', 'teacher.php' );
						update_field( 'syntric_teacher_page_teacher', $user -> ID, $teacher_page_id );
					} else {
						//slog( 'teacher_page is NOT WP_Post' );
						$args            = [ 'post_status' => 'publish',
						                     'post_type'   => 'page',
						                     'post_title'  => $user -> first_name . ' ' . $user -> last_name,
						                     'post_name'   => syntric_sluggify( $user -> first_name . ' ' . $user -> last_name ),
						                     'post_author' => $user -> ID,
						                     'post_parent' => $teachers_page -> ID, ];
						$teacher_page_id = wp_insert_post( $args );
						update_post_meta( $teacher_page_id, '_wp_page_template', 'teacher.php' );
						update_field( 'syntric_teacher_page_teacher', $user -> ID, $teacher_page_id );
						//slog( 'so we created one ad it has ID ' . $teacher_page_id );
					}
					update_field( 'syntric_user_teacher_page', $teacher_page_id, 'user_' . $user -> ID );
				} elseif( ! $is_teacher ) {
					//slog( 'user is NOT teacher' );
					if( $teacher_page instanceof WP_Post ) {
						$children = syntric_get_page_children( $teacher_page -> ID );
						if( $children ) {
							foreach( $children as $child ) {
								wp_trash_post( $child -> ID );
							}
						}
						wp_trash_post( $teacher_page -> ID );
						update_field( 'syntric_user_teacher_page', null, 'user_' . $user -> ID );
					}
				}
			}
		break;
		case 'widget' :
			//slog( 'syntric_acf_save_post for widget' );
// todo: this was taken from the former syntric_save_calendar acf/save_post action
		break;
		case 'options' :
			slog( 'syntric_acf_save_post for options' );
			// Classes
			if( 'admin.php' == $pagenow && 'syntric-classes' == $_REQUEST[ 'page' ] ) {
				if( have_rows( 'syntric_classes', 'option' ) ) {
					//slog( 'syntric_classes has rows...' );
					while( have_rows( 'syntric_classes', 'option' ) ) {
						the_row();
						$include_page = get_sub_field( 'include_page' );
						$class_page   = get_sub_field( 'class_page' );
						slog( $include_page );
						slog( $class_page );
						if( $include_page ) {
							$class_id = get_sub_field( 'id' );
							$teacher  = get_sub_field( 'teacher' );
							$course   = get_sub_field( 'course' );
							$room     = get_sub_field( 'room' );
							$period   = get_sub_field( 'period' );
							$user     = get_user_by( 'ID', $teacher[ 'value' ] );
							$user_cf  = get_field( 'syntric_user', 'user_' . $teacher[ 'value' ] );
							slog( $user_cf );
							$title = $course[ 'label' ] . ' / ' . substr( $user -> first_name, 0, 1 ) . '. ' . $user -> last_name . ' / ' . 'Period ' . $period[ 'label' ] . ' / Room' . $room[ 'label' ];
							if( ! $class_page ) {
								$page_id = wp_insert_post( [ 'post_status' => 'publish',
								                             'post_type'   => 'page',
								                             'post_title'  => $title,
								                             'post_name'   => syntric_sluggify( $title ),
								                             'post_author' => $teacher[ 'value' ],
								                             'post_parent' => $user_cf[ 'teacher_page' ] -> ID, ] );
							} else {
								$page_id = wp_update_post( [ 'ID'          => $class_page -> ID,
								                             'post_status' => 'publish',
								                             'post_type'   => 'page',
								                             'post_title'  => $title,
								                             'post_name'   => syntric_sluggify( $title ),
								                             'post_author' => $teacher[ 'value' ],
								                             'post_parent' => $user_cf[ 'teacher_page' ] -> ID, ] );
							}
							slog( 'teacher_class_page_id=' . $page_id );
							update_post_meta( $page_id, '_wp_page_template', 'class.php' );
							update_sub_field( 'class_page', $page_id );
							update_field( 'syntric_class_page_class', $class_id, $page_id );
						} elseif( ! $include_page ) {
							if( ! $class_page ) {
								// do nothing?  maybe search for out-of-place class page and zap it if it exists
							} else {
								$children = syntric_get_page_children( $class_page -> ID );
								if( $children ) {
									foreach( $children as $child ) {
										wp_trash_post( $child -> ID );
									}
								}
								wp_trash_post( $class_page -> ID );
								update_sub_field( 'class_page', null );
							}
						}
					}
				}
			}
			// Departments
			if( 'admin.php' == $pagenow && 'syntric-departments' == $_REQUEST[ 'page' ] ) {
				slog( 'syntric-departments arrived.' );
				if( have_rows( 'syntric_departments', 'option' ) ) {
					$academics_page = syntric_get_academics_page();
					while( have_rows( 'syntric_departments', 'option' ) ) {
						the_row();
						$include_page    = get_sub_field( 'include_page' );
						$department_page = get_sub_field( 'department_page' );
						slog( $include_page );
						slog( $department_page );
						if( $include_page ) {
							$department_id = get_sub_field( 'id' );
							$department    = get_sub_field( 'department' );
							if( ! $department_page ) {
								$page_id = wp_insert_post( [ 'post_status' => 'publish',
								                             'post_type'   => 'page',
								                             'post_title'  => $department,
								                             'post_name'   => syntric_sluggify( $department ),
								                             'post_parent' => $academics_page -> ID, ] );
							} else {
								$page_id = wp_update_post( [ 'ID'          => $department_page -> ID,
								                             'post_status' => 'publish',
								                             'post_type'   => 'page',
								                             'post_title'  => $department,
								                             'post_name'   => syntric_sluggify( $department ),
								                             'post_parent' => $academics_page -> ID, ] );
							}
							slog( 'department_page_id=' . $page_id );
							update_post_meta( $page_id, '_wp_page_template', 'department.php' );
							update_sub_field( 'department_page', $page_id );
							update_field( 'syntric_department_page_department', $department_id, $page_id );
						} elseif( ! $include_page ) {
							if( $department_page ) {
								$children = syntric_get_page_children( $department_page -> ID );
								if( $children ) {
									foreach( $children as $child ) {
										wp_trash_post( $child -> ID );
									}
								}
								wp_trash_post( $department_page -> ID );
								update_sub_field( 'department_page', null );
							} else {
								// do nothing?
							}
						}
					}
				} else {
					// do nothing?
				}
			}
			// Courses
			if( 'admin.php' == $pagenow && 'syntric-courses' == $_REQUEST[ 'page' ] ) {
				slog( 'syntric-courses arrived' );
				if( have_rows( 'syntric_courses', 'option' ) ) {
					while( have_rows( 'syntric_courses', 'option' ) ) {
						the_row();
						$course_id       = get_sub_field( 'id' );
						$course          = get_sub_field( 'course' );
						$department      = get_sub_field( 'department' );
						$description     = get_sub_field( 'description' );
						$meta            = get_sub_field( 'meta' );
						$include_page    = get_sub_field( 'include_page' );
						$course_page     = get_sub_field( 'course_page' );
						$department_page = syntric_get_department_page( $department[ 'value' ] );
						slog( $include_page );
						slog( $course_page );
						if( $include_page ) {
							$args = [ 'post_status' => 'publish',
							          'post_type'   => 'page',
							          'post_title'  => $course,
							          'post_name'   => syntric_sluggify( $course ),
							          'post_parent' => $department_page -> ID, ];
							if( $course_page ) {
								$args[ 'ID' ] = $course_page -> ID;
							}
							$page_id = wp_insert_post( $args );
							update_post_meta( $page_id, '_wp_page_template', 'course.php' );
							update_sub_field( 'course_page', $page_id );
							update_field( 'syntric_course_page_course', $course_id, $page_id );
						} elseif( ! $include_page ) {
							if( $course_page ) {
								$children = syntric_get_page_children( $course_page -> ID );
								if( $children ) {
									foreach( $children as $child ) {
										wp_trash_post( $child -> ID );
									}
								}
								wp_trash_post( $course_page -> ID );
								update_sub_field( 'course_page', null );
							} else {
								// do nothing?
							}
						}
					}
				} else {
					// do nothing?
				}
			}
			/*$classes = get_field( 'syntric_classes', 'option' );
							if( $classes ) {
								$class_counter = 0;
								foreach( $classes as $class ) {
									$user_cf = get_field( 'syntric_user', 'user_' . $class['teacher']->ID );
									if ( $class['include_page'] && ! $class['class_page'] ) {
										$teacher_class_page_id             = wp_insert_post( [ 'post_status' => 'publish',
																				 'post_type'   => 'page',
																				 'post_title'  => $class['course']['label'],
																				 'post_name'   => syntric_sluggify( $class['course']['label'] ),
																				 'post_author' => $class['teacher']->ID,
																				 'post_parent' => $user_cf['teacher_page'], ] );
										update_post_meta( $teacher_class_page_id, '_wp_page_template', 'class.php' );
										//$class_page_id = syntric_save_teacher_class_page( $class['id'], $class['teacher']->ID);
										$class['class_page'] = $teacher_class_page_id;
										update_sub_field( 'syntric_classes', $teacher_class_page_id, '' $value)
									} elseif ( ! $class['include_page' ] && $class['class_page'] ) {
										$res = wp_trash_post( $class['class_page'] );
										$class['class_page'] = null;
										//$res = syntric_trash_teacher_class_page( $class['id'], $class['teacher']->ID );
									}

									update_row( 'syntric_classes', $class_counter, $class );
									$class_counter++;
			$teacher_id   = $class[ 'teacher' ] -> ID;
			$class_id     = $class[ 'id' ];
			$teacher_page = syntric_get_teacher_page( $teacher_id ); // WP_Post
			slog('teacher_page in acf/save_post/options');
			slog( $teacher_page);
			if( ! $teacher_page instanceof WP_Post ) {
				slog( 'saving teaxher page b.c. user doenst have one');
				$teacher_page_id = syntric_save_teacher_page( $teacher_id );
			}
			if( $class[ 'include_page' ] ) {
				slog( 'save class page for ' . $class_id );
				$class_page_id = syntric_save_teacher_class_page( $teacher_id, $class_id );
			} else {
				syntric_trash_teacher_class_page( $class_id );
			}*/
		break;

		case 'post' :
// todo: needs logic check
		break;
		case 'page' :
			//slog( 'syntric_acf_save_post for page' );
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
			//slog( 'syntric_acf_save_post for syntric_calendar' );
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

function syntric_get_page_children( $post_id ) {
	$wp_query  = new WP_Query();
	$all_pages = $wp_query -> query( [ 'post_type' => 'page' ] );

	return get_page_children( $post_id, $all_pages );
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

	