<?php
	/**
	 * Advanced Custom Fields
	 */
	/**
	 * Field filters
	 */
	/**
	 * Set ID field values to permanent id if not set
	 */
	add_filter( 'acf/update_value/name=syn_organization_id', 'syn_set_id' ); // Primary organization
	add_filter( 'acf/update_value/name=organization_id', 'syn_set_id' ); // Organizations
	add_filter( 'acf/update_value/name=department_id', 'syn_set_id' ); // Departments
	add_filter( 'acf/update_value/name=building_id', 'syn_set_id' ); // Buildings
	add_filter( 'acf/update_value/name=room_id', 'syn_set_id' ); // Rooms
	add_filter( 'acf/update_value/name=period_id', 'syn_set_id' ); // Periods
	add_filter( 'acf/update_value/name=course_id', 'syn_set_id' ); // Courses
	add_filter( 'acf/update_value/name=google_map_id', 'syn_set_id' ); // Google maps
	add_filter( 'acf/update_value/name=jumbotron_id', 'syn_set_id' ); // Jumbotrons
	add_filter( 'acf/update_value/name=facebook_page_id', 'syn_set_id' ); // Social media
	add_filter( 'acf/update_value/name=class_id', 'syn_set_id' ); // Teacher classes (in page)
	function syn_set_id( $value ) {
		if ( empty( $value ) ) {
			return syn_generate_permanent_id();
		}

		return $value;
	}

	/**
	 * Format phone fields
	 */
	add_filter( 'acf/update_value/name=syn_user_phone', 'syn_format_phone' );
	add_filter( 'acf/update_value/name=syn_organization_phone', 'syn_format_phone' );
	add_filter( 'acf/update_value/name=syn_organization_fax', 'syn_format_phone' );
	add_filter( 'acf/update_value/name=phone', 'syn_format_phone' );
	add_filter( 'acf/update_value/name=fax', 'syn_format_phone' );
	function syn_format_phone( $value ) {
		// is $phone set and at least 7 characters
		if ( strlen( $value ) < 7 ) {
			return '';
		}
		// strip out all non-numeric characters
		$phone  = preg_replace( "/[^0-9]/", "", $value );
		$length = strlen( $phone );
		// after stripping out non-numerics, check again if $phone at least 7 digits
		if ( strlen( $phone ) < 7 ) {
			return '';
		}
		switch ( $length ) {
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
	 * Set select options ($field['choices']) to null - will be populated with acf/prepare_field below
	 */
	// ------------------------------------------------------Check these
	add_filter( 'acf/load_field/name=syn_post_category', 'syn_nullify_field_options' );
	add_filter( 'acf/load_field/name=syn_post_microblog', 'syn_nullify_field_options' );
	// pages
	add_filter( 'acf/load_field/name=syn_page_class', 'syn_nullify_field_options' );
	add_filter( 'acf/load_field/name=syn_page_class_teacher', 'syn_nullify_field_options' );
	add_filter( 'acf/load_field/name=syn_page_teacher', 'syn_nullify_field_options' );
	add_filter( 'acf/load_field/name=syn_page_department', 'syn_nullify_field_options' );
	add_filter( 'acf/load_field/name=syn_page_course', 'syn_nullify_field_options' );
	//  teacher classes
	add_filter( 'acf/load_field/name=term', 'syn_nullify_field_options' );
	add_filter( 'acf/load_field/name=course', 'syn_nullify_field_options' );
	add_filter( 'acf/load_field/name=period', 'syn_nullify_field_options' );
	add_filter( 'acf/load_field/name=room', 'syn_nullify_field_options' );
	// page widgets
	add_filter( 'acf/load_field/name=syn_google_map_id', 'syn_nullify_field_options' );
	add_filter( 'acf/load_field/name=syn_contact_person', 'syn_nullify_field_options' );
	add_filter( 'acf/load_field/name=syn_contact_organization', 'syn_nullify_field_options' );
	add_filter( 'acf/load_field/key=field_59b118daf73d0', 'syn_nullify_field_options' ); // Google Maps > markers > organization select
	add_filter( 'acf/load_field/key=field_59af852be10d5', 'syn_nullify_field_options' ); // Page Widgets > roster > person
	// users
	// widgets
	add_filter( 'acf/load_field/name=syn_contact_widget_organization', 'syn_nullify_field_options' );
	add_filter( 'acf/load_field/name=syn_contact_widget_person', 'syn_nullify_field_options' );
	add_filter( 'acf/load_field/name=syn_upcoming_events_widget_calendar_id', 'syn_nullify_field_options' );
	add_filter( 'acf/load_field/name=syn_nav_menu_widget_menu', 'syn_nullify_field_options' );
	add_filter( 'acf/load_field/name=syn_facebook_page_widget_page', 'syn_nullify_field_options' );
	add_filter( 'acf/load_field/name=syn_google_map_widget_map_id', 'syn_nullify_field_options' );
	// options
	add_filter( 'acf/load_field/name=syn_widgets_wordpress', 'syn_load_wordpress_widgets' );
	// syn_calendar
	add_filter( 'acf/load_field/name=syn_calendar_id', 'syn_nullify_field_options' );
	function syn_nullify_field_options( $field ) {
		$field[ 'choices' ] = null;

		return $field;
	}

	add_filter( 'acf/prepare_field/name=google_map_id', 'syn_hide_field' );
	add_filter( 'acf/prepare_field/name=facebook_page_id', 'syn_hide_field' );
	add_filter( 'acf/prepare_field/name=department_id', 'syn_hide_field' );
	add_filter( 'acf/prepare_field/name=course_id', 'syn_hide_field' );
	add_filter( 'acf/prepare_field/name=period_id', 'syn_hide_field' );
	add_filter( 'acf/prepare_field/name=building_id', 'syn_hide_field' );
	add_filter( 'acf/prepare_field/name=room_id', 'syn_hide_field' );
	add_filter( 'acf/prepare_field/name=sidebar_id', 'syn_hide_field' );
	add_filter( 'acf/prepare_field/name=organization_id', 'syn_hide_field' );
	add_filter( 'acf/prepare_field/name=syn_organization_id', 'syn_hide_field' );
	add_filter( 'acf/prepare_field/name=jumbotron_id', 'syn_hide_field' );
	function syn_hide_field( $field ) {
		$field[ 'wrapper' ][ 'hidden' ] = 1;
	}

	/****************** Posts ******************/
	// ------------------------------------------------------Check these
	add_filter( 'acf/prepare_field/name=syn_post_category', 'syn_acf_prepare_fields' );
	add_filter( 'acf/prepare_field/name=syn_post_microblog', 'syn_acf_prepare_fields' );
	/****************** Pages ******************/
	add_filter( 'acf/prepare_field/name=syn_page_class', 'syn_acf_prepare_fields' );
	add_filter( 'acf/prepare_field/name=syn_page_class_teacher', 'syn_acf_prepare_fields' );
	add_filter( 'acf/prepare_field/name=syn_page_teacher', 'syn_acf_prepare_fields' );
	add_filter( 'acf/prepare_field/name=syn_page_department', 'syn_acf_prepare_fields' );
	add_filter( 'acf/prepare_field/name=syn_page_course', 'syn_acf_prepare_fields' );
	// teacher classes ------------------------------------------------------Check these too, should the use keys?
	add_filter( 'acf/prepare_field/name=term', 'syn_acf_prepare_fields' );
	add_filter( 'acf/prepare_field/name=period', 'syn_acf_prepare_fields' );
	add_filter( 'acf/prepare_field/name=course', 'syn_acf_prepare_fields' );
	add_filter( 'acf/prepare_field/name=room', 'syn_acf_prepare_fields' );
	// page widgets
	add_filter( 'acf/prepare_field/name=syn_contact_title', 'syn_acf_prepare_fields' );
	add_filter( 'acf/prepare_field/name=syn_contact_default', 'syn_acf_prepare_fields' );
	add_filter( 'acf/prepare_field/name=syn_contact_person', 'syn_acf_prepare_fields' );
	//add_filter( 'acf/prepare_field/name=syn_contact_person_include_fields', 'syn_acf_prepare_fields' );
	add_filter( 'acf/prepare_field/name=syn_contact_organization', 'syn_acf_prepare_fields' );
	//add_filter( 'acf/prepare_field/name=syn_contact_organization_include_fields', 'syn_acf_prepare_fields' );
	add_filter( 'acf/prepare_field/name=syn_roster_title', 'syn_acf_prepare_fields' );
	//add_filter( 'acf/prepare_field/name=syn_roster_include_fields', 'syn_acf_prepare_fields' );
	add_filter( 'acf/prepare_field/name=syn_calendar_title', 'syn_acf_prepare_fields' );
	add_filter( 'acf/prepare_field/name=syn_calendar_id', 'syn_acf_prepare_fields' );
	add_filter( 'acf/prepare_field/name=syn_microblog_title', 'syn_acf_prepare_fields' );
	add_filter( 'acf/prepare_field/name=syn_microblog_category', 'syn_acf_prepare_fields' );
	add_filter( 'acf/prepare_field/name=syn_microblog_term', 'syn_acf_prepare_fields' );
	// ------------------------------------------------------Check these...delete?
	add_filter( 'acf/prepare_field/name=syn_microblog_category_select', 'syn_acf_prepare_fields' );
	add_filter( 'acf/prepare_field/name=syn_microblog_term_select', 'syn_acf_prepare_fields' );
	add_filter( 'acf/prepare_field/name=syn_new_microblog_post', 'syn_acf_prepare_fields' );
	//add_filter( 'acf/prepare_field/name=syn_attachments_active', 'syn_acf_prepare_fields' );
	add_filter( 'acf/prepare_field/name=syn_attachments_title', 'syn_acf_prepare_fields' );
	add_filter( 'acf/prepare_field/name=syn_attachments', 'syn_acf_prepare_fields' );
	//add_filter( 'acf/prepare_field/name=syn_video_active', 'syn_acf_prepare_fields' );
	add_filter( 'acf/prepare_field/name=syn_video_title', 'syn_acf_prepare_fields' );
	add_filter( 'acf/prepare_field/name=syn_video_host', 'syn_acf_prepare_fields' );
	add_filter( 'acf/prepare_field/name=syn_video_youtube_id', 'syn_acf_prepare_fields' );
	add_filter( 'acf/prepare_field/name=syn_video_vimeo_id', 'syn_acf_prepare_fields' );
	add_filter( 'acf/prepare_field/name=syn_video_caption', 'syn_acf_prepare_fields' );
	//add_filter( 'acf/prepare_field/name=syn_google_map_active', 'syn_acf_prepare_fields' );
	add_filter( 'acf/prepare_field/name=syn_google_map_title', 'syn_acf_prepare_fields' );
	add_filter( 'acf/prepare_field/name=syn_google_map_id', 'syn_acf_prepare_fields' );
	/****************** Users ******************/
	add_filter( 'acf/prepare_field/name=syn_user_page', 'syn_acf_prepare_fields' );
	add_filter( 'acf/prepare_field/name=syn_user_is_teacher', 'syn_acf_prepare_fields' );
	/****************** Widgets ******************/
	add_filter( 'acf/prepare_field/name=syn_contact_widget_default', 'syn_acf_prepare_fields' );
	add_filter( 'acf/prepare_field/name=syn_contact_widget_organization', 'syn_acf_prepare_fields' );
	add_filter( 'acf/prepare_field/name=syn_contact_widget_person', 'syn_acf_prepare_fields' );
	add_filter( 'acf/prepare_field/name=syn_upcoming_events_widget_calendar_id', 'syn_acf_prepare_fields' );
	add_filter( 'acf/prepare_field/name=syn_nav_menu_widget_menu', 'syn_acf_prepare_fields' );
	add_filter( 'acf/prepare_field/name=syn_facebook_page_widget_page', 'syn_acf_prepare_fields' );
	add_filter( 'acf/prepare_field/name=syn_google_map_widget_map_id', 'syn_acf_prepare_fields' );
	// Retired????  ------------------------------------------------------Check these
	add_filter( 'acf/prepare_field/name=default_organization', 'syn_acf_prepare_fields' );
	//add_filter( 'acf/prepare_field/name=class_id', 'syn_acf_prepare_fields' );
	//add_filter( 'acf/prepare_field/name=include_page', 'syn_acf_prepare_fields' );
	//add_filter( 'acf/prepare_field/name=page', 'syn_acf_prepare_fields' );
	/****************** Calendar CPT ******************/
	add_filter( 'acf/prepare_field/name=syn_calendar_last_sync', 'syn_acf_prepare_fields' );
	add_filter( 'acf/prepare_field/name=syn_calendar_last_sync_result', 'syn_acf_prepare_fields' );
	//// by key
	add_filter( 'acf/prepare_field/key=field_59b118daf73d0', 'syn_acf_prepare_fields' ); // Google Maps > markers > organization select field
	add_filter( 'acf/prepare_field/key=field_59d127e512d9a', 'syn_acf_prepare_fields', 20 ); // Teacher classes > class page message field
	add_filter( 'acf/prepare_field/key=field_59e7f2f7049e2', 'syn_acf_prepare_fields' ); // Post category message field
	add_filter( 'acf/prepare_field/key=field_59e7f370049e3', 'syn_acf_prepare_fields' ); // Post term (microblog) message field
	add_filter( 'acf/prepare_field/key=field_59d4b242abf31', 'syn_acf_prepare_fields' ); // Page widgets microblog category message field
	add_filter( 'acf/prepare_field/key=field_59d4b278abf32', 'syn_acf_prepare_fields' ); // Page widgets microblog term message field
	add_filter( 'acf/prepare_field/key=field_598a9db813a14', 'syn_acf_prepare_fields' ); // Page widgets Attachments tab
	//add_filter( 'acf/prepare_field/key=field_5a41bf632a648', 'syn_acf_prepare_fields' ); // Page widgets Google Map tab
	//add_filter( 'acf/prepare_field/key=field_5a41c15b2a64c', 'syn_acf_prepare_fields' ); // Page widgets video tab
	add_filter( 'acf/prepare_field/key=field_59bb8bf493b01', 'syn_acf_prepare_fields' ); // Courses > department select field
	add_filter( 'acf/prepare_field/key=field_59bb90c45ec6d', 'syn_acf_prepare_fields' ); // Rooms > building select field
	function syn_acf_prepare_fields( $field ) {
		global $post;
		switch ( $field[ '_name' ] ) :
			case 'syn_contact_widget_organization' :
				$field = syn_load_organizations( $field );
				break;
			case 'syn_contact_widget_person' :
				$field = syn_load_people( $field );
				break;
			case 'syn_upcoming_events_widget_calendar_id' :
				$field = syn_load_google_calendars( $field );
				break;
			case 'syn_nav_menu_widget_menu' :
				$field = syn_load_nav_menu( $field );
				break;
			case 'syn_contact_widget_default' :
				$field[ 'label' ]   = '';
				$field[ 'message' ] = 'Use ' . get_field( 'syn_organization', 'option' );
				break;
			case 'syn_contact_default' :
				$field[ 'label' ]   = get_field( 'syn_organization', 'option' );
				$field[ 'message' ] = 'Use ' . get_field( 'syn_organization', 'option' );
				break;
			case 'syn_facebook_page_widget_page' :
				$field = syn_load_facebook_pages( $field );
				break;
			case 'syn_google_map_widget_map_id' :
			case 'syn_google_map_id' :
				$field = syn_load_google_maps( $field );
				break;
			case 'default_organization' : // field_59efde970195f is in Google Map > markers
				$field[ 'label' ]   = '';
				$field[ 'message' ] = 'Use ' . get_field( 'syn_organization', 'option' );
				break;
			case 'syn_user_page' :
				global $pagenow;
				switch ( $pagenow ) {
					case 'user-new.php' :
						$field[ 'wrapper' ][ 'hidden' ] = 1;
						break;
					case 'profile.php' :
					case 'user-edit.php':
						$user_is_teacher = get_field( 'syn_user_is_teacher', 'user_' . get_current_user_id() );
						if ( ! $user_is_teacher || ! syn_current_user_can( 'editor' ) ) {
							$field[ 'wrapper' ][ 'hidden' ] = 1;
						} elseif ( $user_is_teacher ) {
							$field[ 'disabled' ] = 1;
						}
						break;
				}
				/*if ( $user_is_teacher && syn_current_user_can( 'editor') ) {
					$field[ 'wrapper' ][ 'hidden' ] = 0;
				}*/
				$field = syn_load_teacher_pages( $field );
				break;
			case 'syn_user_is_teacher' :
				if ( ! syn_current_user_can( 'editor' ) ) {
					$field[ 'wrapper' ][ 'hidden' ] = 1;
				}
				break;
			case 'syn_post_category' :
				//if ( $field[ 'value' ] ) {
				//$field[ 'wrapper' ][ 'hidden' ] = 1;
				//} else  {
				$choices    = [];
				$categories = get_terms( [ 'taxonomy' => 'category' ] );
				/*$microblogs_cat = get_category_by_slug( 'microblogs' );
				if ( $microblogs_cat instanceof WP_Term ) {
					$choices[ $microblogs_cat->term_id ] = $microblogs_cat->name;
				}*/
				if ( $categories ) {
					foreach ( $categories as $category ) {
						$choices[ $category->term_id ] = $category->name;
					}
				}
				$field[ 'choices' ] = $choices;
				//}
				break;
			case 'syn_post_microblog' :
				if ( $field[ 'value' ] ) {
					$field[ 'wrapper' ][ 'hidden' ] = 1;
				}
				/*if ( ! syn_current_user_can( 'editor' ) ) {
					$choices = [];
					$user_microblogs = syn_get_user_microblogs( get_current_user_id() ); // returns get_posts
					if ( count( $user_microblogs ) ) {

					}
				}*/
				break;
			case 'period' :
				$periods_active = get_field( 'syn_periods_active', 'option' );
				if ( ! $periods_active ) {
					return false;
				} elseif ( $field[ 'value' ] ) {
					$field[ 'disabled' ] = 1;
				}
				$field = syn_load_periods( $field );
				break;
			case 'room' :
				$rooms_active = get_field( 'syn_rooms_active', 'option' );
				if ( ! $rooms_active ) {
					return false;
				} elseif ( $field[ 'value' ] ) {
					$field[ 'disabled' ] = 1;
				}
				$field = syn_load_rooms( $field );
				break;
			case 'term' :
				if ( $field[ 'value' ] ) {
					$field[ 'disabled' ] = 1;
				}
				$field = syn_load_terms( $field );
				break;
			case 'course' :
				if ( $field[ 'value' ] ) {
					$field[ 'disabled' ] = 1;
				}
				$field = syn_load_courses( $field );
				break;
			case 'syn_page_class' :
				$field = syn_load_classes( $field );
				if ( $field[ 'value' ] ) {
					$field[ 'disabled' ] = 1;
				}
				break;
			case 'syn_page_class_teacher' :
				$field = syn_load_teachers( $field );
				if ( $field[ 'value' ] ) {
					$field[ 'disabled' ] = 1;
				}
				break;
			case 'syn_page_teacher' :
				$field = syn_load_teachers( $field );
				if ( $field[ 'value' ] ) {
					$field[ 'disabled' ] = 1;
				}
				break;
			case 'syn_page_department' :
				$field = syn_load_departments( $field );
				if ( $field[ 'value' ] ) {
					$field[ 'disabled' ] = 1;
				}
				break;
			case 'syn_page_course' :
				$field = syn_load_courses( $field );
				if ( $field[ 'value' ] ) {
					$field[ 'disabled' ] = 1;
				}
				break;
			case 'syn_contact_person' :
				$field = syn_load_people( $field );
				break;
			case 'syn_contact_organization' :
				$field = syn_load_organizations( $field );
				break;
			case 'syn_calendar_id' :
				$field = syn_load_google_calendars( $field );
				break;
			case 'syn_microblog_category' :
				//if ( ! syn_current_user_can( 'editor' ) ) {
				$field = syn_load_categories( $field );
				if ( $field[ 'value' ] ) {
					$field[ 'disabled' ] = 1;
				}
				//}
				break;
			case 'syn_microblog_term' :
				//if ( ! syn_current_user_can( 'editor' ) ) {
				$field = syn_load_microblogs( $field );
				if ( $field[ 'value' ] ) {
					$field[ 'disabled' ] = 1;
				}
				//}
				break;
			case 'syn_microblog_category_select' :
				//$categories = get_categories( [ 'taxonomy' => 'category', 'hide_empty' => false ]);
				//if ( ! syn_current_user_can( 'editor' ) ) {
				$field               = syn_load_categories( $field );
				$field[ 'disabled' ] = 1;
				//}
				break;
			case 'syn_microblog_term_select' :
				//if ( ! syn_current_user_can( 'editor' ) ) {
				$field               = syn_load_microblogs( $field );
				$field[ 'disabled' ] = 1;
				//}
				break;
			case 'syn_new_microblog_post' :
				$microblog_active = get_field( 'syn_microblog_active', $post->ID );
				if ( ! $microblog_active ) {
					$field[ 'wrapper' ][ 'hidden' ] = 1;
				} else {
					$field[ 'wrapper' ][ 'hidden' ] = 0;
				}
				break;
			//case 'syn_attachments_active':
			//case 'syn_video_active':
			//case 'syn_google_map_active':
			//return false;
			//break;
			case 'syn_calendar_last_sync' :
				$field[ 'disabled' ] = 1;
				break;
			case 'syn_calendar_last_sync_result' :
				$field[ 'disabled' ] = 1;
				break;
		endswitch;
		// Google Maps > markers > organization select field
		if ( 'field_59b118daf73d0' == $field[ 'key' ] ) {
			$field = syn_load_organizations( $field );
		}
		// Teacher classes > class page message field
		if ( 'field_59d127e512d9a' == $field[ 'key' ] ) { // syn_classes message field
			$classes = get_field( 'syn_classes', $post->ID );
			if ( ! count( $classes ) ) {
				$field[ 'wrapper' ][ 'hidden' ] = 1;
				$field[ 'wrapper' ][ 'width' ]  = 1;
			}
			$field_prefix_array = explode( '[', str_replace( ']', '', $field[ 'prefix' ] ) );
			$row_index          = $field_prefix_array[ count( $field_prefix_array ) - 1 ];
			if ( is_numeric( $row_index ) ) {
				$class      = $classes[ $row_index ];
				$class_id   = $class[ 'class_id' ];
				$teacher_id = get_field( 'syn_page_teacher', $post->ID );
				$class_page = syn_get_teacher_class_page( $teacher_id, $class_id );
				if ( $class_page instanceof WP_Post ) {
					$field[ 'wrapper' ][ 'hidden' ] = 0;
					$field[ 'wrapper' ][ 'width' ]  = '';
					$status                         = ( 'publish' != $class_page->post_status ) ? ' - ' . ucwords( $class_page->post_status ) : '';
					$field[ 'message' ]             = $class_page->post_title . $status . '<span style="float: right;"><a href="' . get_the_permalink( $class_page->ID ) . '">View</a> / <a href="/wp-admin/post.php?action=edit&post=' . $class_page->ID . '">Edit</a></span>';
				}
			}
		}
		// Attachments page widget tab
		if ( 'field_598a9db813a14' == $field[ 'key' ] ) {
			return false;
		}
		// Microblog page widget category message field
		if ( 'field_59d4b242abf31' == $field[ 'key' ] ) {
			$field[ 'wrapper' ][ 'hidden' ] = 1;
			/*$microblog_active   = get_field( 'syn_microblog_active', $post->ID );
			$microblog_category = get_field( 'syn_microblog_category', $post->ID );
			if ( $microblog_active ) {
				if ( $microblog_category ) {
					$field[ 'message' ] = $microblog_category->name;
				}
			}*/
		}
		// Microblog page widget term (microblogs) message field
		if ( 'field_59d4b278abf32' == $field[ 'key' ] ) {
			$field[ 'wrapper' ][ 'hidden' ] = 1;
			/*if ( syn_current_user_can( 'editor' ) ) {
				return false;
			} else {
				$microblog_active = get_field( 'syn_microblog_active', $post->ID );
				$microblog_term   = get_field( 'syn_microblog_term', $post->ID );
				if ( $microblog_active ) {
					if ( $microblog_term ) {
						$field[ 'message' ] = $microblog_term->name;
					}
				}
			}*/
		}
		// Post category message field
		if ( 'field_59e7f2f7049e2' == $field[ 'key' ] ) { // Category display
			$category_id = get_field( 'syn_post_category', $post->ID );
			if ( $category_id ) {
				$category = get_category( $category_id );
				if ( $category ) {
					//$field[ 'wrapper' ][ 'class' ] = '';
					$field[ 'message' ] = $category->name;
				}
			}
		}
		// Post term (microblog) message field
		if ( 'field_59e7f370049e3' == $field[ 'key' ] ) { // Microblog display
			$microblog_id = get_field( 'syn_post_microblog', $post->ID );
			if ( $microblog_id ) {
				$microblog = get_term( $microblog_id );
				if ( $microblog ) {
					//$field[ 'wrapper' ][ 'class' ] = '';
					$field[ 'message' ] = $microblog->name;
				}
			}
		}
		// Courses > department select field
		if ( 'field_59bb8bf493b01' == $field[ 'key' ] ) {
			$departments_active = get_field( 'syn_departments_active', 'option' );
			if ( ! $departments_active ) {
				return false;
			}
			$field = syn_load_departments( $field );
		}
		// Rooms > building select field
		if ( 'field_59bb90c45ec6d' == $field[ 'key' ] ) { // syn_rooms > building
			$buildings_active = get_field( 'syn_buildings_active', 'option' );
			if ( ! $buildings_active ) {
				return false;
			}
			$field = syn_load_buildings( $field );
		}

		return $field;
	}

	// Field loaders
// Attention!  todo: adding an is_admin() condition for loading these fields causes the id to display on the front end.
	function syn_load_categories( $field ) {
		if ( 'radio' == $field[ 'type' ] || 'select' == $field[ 'type' ] ) {
			$categories = get_categories( [ 'hide_empty' => false ] );
			$choices    = [];
			if ( $categories ) {
				foreach ( $categories as $category ) {
					//if ( syn_current_user_can( 'editor' ) || ( ! syn_current_user_can( 'editor' ) && 'microblogs' == $category->slug ) ) {
					$choices[ $category->cat_ID ] = $category->name . ' (' . $category->count . ')';
					//}
				}
				//$choices[ 0 ]       = '+ New category';
				$field[ 'choices' ] = $choices;
			}

			return $field;
		}
	}

	function syn_load_microblogs( $field ) {
		if ( 'radio' == $field[ 'type' ] || 'select' == $field[ 'type' ] ) {
			$microblogs = get_terms( [ 'taxonomy' => 'microblog', 'hide_empty' => false ] );
			slog( $microblogs );
			$choices = [];
			if ( $microblogs ) {
				foreach ( $microblogs as $microblog ) {
					$choices[ $microblog->term_id ] = $microblog->name . ' (' . $microblog->count . ')';
				}
			}
			/*$args    = [
				'numberposts' => - 1,
				'post_type'   => 'page',
				'meta_key'    => 'syn_microblog_active',
				'meta_value'  => 1,
				'orderby'     => [ 'post_title' => 'ASC' ],
			];
			if ( ! syn_current_user_can( 'editor' ) ) {
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

	function syn_load_departments( $field ) {
		$active = get_field( 'syn_departments_active', 'option' );
		if ( $active && 'select' == $field[ 'type' ] ) {
			$choices     = [];
			$departments = get_field( 'syn_departments', 'option' );
			if ( $departments && is_array( $departments ) ) {
				foreach ( $departments as $department ) {
					$choices[ $department[ 'department_id' ] ] = $department[ 'department' ];
				}
			}
			$field[ 'choices' ] = $choices;
		}

		return $field;
	}

	function syn_load_buildings( $field ) {
		$active = get_field( 'syn_buildings_active', 'option' );
		if ( $active && 'select' == $field[ 'type' ] ) {
			$choices   = [];
			$buildings = get_field( 'syn_buildings', 'option' );
			if ( $buildings ) {
				foreach ( $buildings as $building ) {
					$choices[ $building[ 'building_id' ] ] = $building[ 'building' ];
				}
			}
			$field[ 'choices' ] = $choices;
		}

		return $field;
	}

	function syn_load_rooms( $field ) {
		$active = get_field( 'syn_rooms_active', 'option' );
		if ( $active && 'select' == $field[ 'type' ] ) {
			$choices = [];
			$rooms   = get_field( 'syn_rooms', 'option' );
			if ( $rooms ) {
				foreach ( $rooms as $room ) {
					$choices[ $room[ 'room_id' ] ] = $room[ 'room' ];
				}
			}
			$field[ 'choices' ] = $choices;
		}

		return $field;
	}

// academic terms, not WordPress terms
	function syn_load_terms( $field ) {
		if ( 'select' == $field[ 'type' ] ) {
			$terms = syn_get_terms();
			if ( $terms ) {
				$choices = [];
				foreach ( $terms as $term ) {
					$choices[ $term[ 'term_id' ] ] = $term[ 'term' ];
				}
				$field[ 'choices' ] = $choices;
			}
		}

		return $field;
	}

	function syn_load_periods( $field ) {
		$active = get_field( 'syn_periods_active', 'option' );
		if ( $active && 'select' == $field[ 'type' ] ) {
			$choices = [];
			$periods = get_field( 'syn_periods', 'option' );
			if ( $periods && is_array( $periods ) ) {
				foreach ( $periods as $period ) {
					$choices[ $period[ 'period_id' ] ] = $period[ 'period' ];
				}
			}
			$field[ 'choices' ] = $choices;
		}

		return $field;
	}

	function syn_load_courses( $field ) {
		if ( 'select' == $field[ 'type' ] ) {
			$choices = [];
			$courses = get_field( 'syn_courses', 'option' );
			if ( $courses && is_array( $courses ) ) {
				foreach ( $courses as $course ) {
					$choices[ $course[ 'course_id' ] ] = $course[ 'course' ];
				}
			}
			$field[ 'choices' ] = $choices;
		}

		return $field;
	}

	function syn_load_course_lengths( $field ) {
		if ( 'select' == $field[ 'type' ] ) {
			$choices   = [];
			$term_type = get_field( 'syn_term_type', 'option' );
			/*if ( $term_type ) {
				foreach ( $courses as $course ) {
					$choices[ $course[ 'course_id' ] ] = $course[ 'course' ];
				}
			}*/
			$field[ 'choices' ] = $choices;
		}

		return $field;
	}

	function syn_load_organizations( $field ) {
		if ( 'select' == $field[ 'type' ] ) {
			$choices       = [];
			$organizations = get_field( 'syn_organizations', 'option' );
			if ( $organizations && is_array( $organizations ) ) {
				foreach ( $organizations as $organization ) {
					$choices[ $organization[ 'organization_id' ] ] = $organization[ 'organization' ];
				}
			}
			$field[ 'choices' ] = $choices;
		}

		return $field;
	}

	function syn_load_people( $field ) {
		if ( 'select' == $field[ 'type' ] ) {
			$choices = [];
			$people  = get_users( [
				'meta_key' => 'last_name',
				'orderby'  => 'meta_value',
			] );
			if ( $people ) {
				foreach ( $people as $person ) {
					$choices[ $person->ID ] = $person->display_name . ' / ' . get_field( 'syn_user_title', 'user_' . $person->ID );
				}
			}
			$field[ 'choices' ] = $choices;
		}

		return $field;
	}

	function syn_load_administrators( $field ) {
		if ( 'select' == $field[ 'type' ] ) {
			$choices = [];
			$people  = get_users( 'role=administrator' );
			if ( $people ) {
				foreach ( $people as $person ) {
					$choices[ $person->ID ] = $person->display_name . ' (administrator)';
				}
			}
			$field[ 'choices' ] = $choices;
		}

		return $field;
	}

	function syn_load_pageless_teachers( $field ) {
		if ( 'select' == $field[ 'type' ] ) {
			$choices  = [];
			$teachers = syn_get_teachers(); // array of WP_User
			if ( $teachers ) {
				$teacher_pages     = syn_get_teacher_pages(); // array of WP_Post
				$paged_teacher_ids = [];
				if ( $teacher_pages ) {
					foreach ( $teacher_pages as $teacher_page ) {
						$paged_teacher_ids[] = get_field( 'syn_page_teacher', $teacher_page->ID );
					}
				}
				foreach ( $teachers as $teacher ) {
					if ( 0 == count( $paged_teacher_ids ) || ( count( $paged_teacher_ids ) && ! in_array( $teacher->ID, $paged_teacher_ids ) ) ) {
						$choices[ $teacher->ID ] = $teacher->display_name;
					}
				}
			}
			$field[ 'choices' ] = $choices;
		}

		return $field;
	}

	function syn_load_teachers( $field ) {
		if ( 'select' == $field[ 'type' ] ) {
			$choices  = [];
			$teachers = syn_get_teachers();
			if ( $teachers ) {
				foreach ( $teachers as $teacher ) {
					$choices[ $teacher->ID ] = $teacher->display_name;
				}
			}
			$field[ 'choices' ] = $choices;
		}

		return $field;
	}

	function syn_load_teacher_pages( $field ) {
		if ( 'select' == $field[ 'type' ] ) {
			$choices       = [];
			$teacher_pages = syn_get_teacher_pages();
			if ( $teacher_pages ) {
				foreach ( $teacher_pages as $teacher_page ) {
					$choices[ $teacher_page->ID ] = $teacher_page->post_title;
				}
			}
			$field[ 'choices' ] = $choices;
		}

		return $field;
	}

	function syn_load_users( $field ) {
		if ( 'select' == $field[ 'type' ] ) {
			return syn_load_people( $field );
		}

		return $field;
	}

	function syn_load_classes( $field ) {
		if ( 'select' == $field[ 'type' ] ) {
			$teacher_pages = get_posts( [
				'numberposts'  => - 1,
				'post_type'    => 'page',
				'post_status'  => [
					'publish',
					'draft',
					'future',
					'pending',
					'private',
					'trash',
				],
				'meta_key'     => '_wp_page_template',
				'meta_value'   => 'page-templates/teacher.php',
				'meta_compare' => '=',
				'fields'       => 'ids',
			] );
			$choices       = [];
			if ( $teacher_pages ) {
				$courses = get_field( 'syn_courses', 'option' );
				$courses = array_column( $courses, 'course', 'course_id' );
				foreach ( $teacher_pages as $teacher_page ) { // teacher_page is post->ID
					$teacher_id   = get_field( 'syn_page_teacher', $teacher_page );
					$teacher      = get_user_by( 'ID', $teacher_id );
					$teacher_name = ( $teacher ) ? ' (' . $teacher->display_name . ')' : ' (Unknown)';
					$classes      = get_field( 'syn_classes', $teacher_page );
					if ( $classes ) {
						foreach ( $classes as $class ) {
							if ( $class[ 'class_id' ] ) {
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

	function syn_load_facebook_pages( $field ) {
		if ( 'select' == $field[ 'type' ] ) {
			$choices        = [];
			$facebook_pages = get_field( 'syn_facebook_pages', 'option' );
			if ( $facebook_pages ) {
				foreach ( $facebook_pages as $facebook_page ) {
					$choices[ $facebook_page[ 'facebook_page_id' ] ] = $facebook_page[ 'name' ];
				}
			}
			$field[ 'choices' ] = $choices;
		}

		return $field;
	}

	function syn_load_google_calendars( $field ) {
		if ( 'select' == $field[ 'type' ] ) {
			$choices               = [];
			$calendar_widget_pages = syn_get_posts_with_calendar_widget();
			$calendar_widgets      = [];
			if ( count( $calendar_widget_pages ) ) {
				foreach ( $calendar_widget_pages as $calendar_widget_page ) {
					$page_template = syn_get_page_template( $calendar_widget_page->ID );
					$calendar_id   = get_post_meta( $calendar_widget_page->ID, 'syn_calendar_id', 1 );
					//$calendar_id                      = get_field( 'syn_calendar_id', $calendar_widget_page->ID );
					$calendar_widgets[ $calendar_id ] = [
						'post_id'       => $calendar_widget_page->ID,
						'post_title'    => $calendar_widget_page->post_title,
						'post_status'   => $calendar_widget_page->post_status,
						'post_author'   => $calendar_widget_page->post_author,
						'page_template' => $page_template,
					];
				}
			}
			$calendars = syn_get_calendars();
			if ( $calendars ) {
				if ( 'syn_calendar_id' == $field[ '_name' ] ) {
					$choices[ 0 ] = '+ New calendar';
				}
				foreach ( $calendars as $calendar ) {
					// todo: currently "Author > Calendar" but should be
					// get_the_author_meta( 'first_name', $calendar->post_author ) . ' ' . get_the_author_meta( 'last_name', $calendar->post_author ) . ' > ' .
					$detail                   = ( isset( $calendar_widgets[ $calendar->ID ] ) ) ? ' ' . $calendar_widgets[ $calendar->ID ][ 'page_template' ] . ' ' . get_the_author_meta( 'first_name', $calendar_widgets[ $calendar->ID ][ 'post_author' ] ) . ' ' . get_the_author_meta( 'last_name', $calendar_widgets[ $calendar->ID ][ 'post_author' ] ) : '';
					$choices[ $calendar->ID ] = get_the_title( $calendar->ID ) . $detail;
				}
			}
			$field[ 'choices' ] = $choices;
		}

		return $field;
	}

	function syn_load_google_maps( $field ) {
		if ( 'select' == $field[ 'type' ] ) {
			$choices     = [];
			$google_maps = get_field( 'syn_google_maps', 'option' );
			if ( $google_maps ) {
				foreach ( $google_maps as $google_map ) {
					$choices[ $google_map[ 'google_map_id' ] ] = $google_map[ 'name' ];
				}
			}
			$field[ 'choices' ] = $choices;
		}

		return $field;
	}

	function syn_load_nav_menu( $field ) {
		if ( 'select' == $field[ 'type' ] ) {
			$choices = [];
			$menus   = get_terms( 'nav_menu', [ 'hide_empty' => true ] );
			foreach ( $menus as $menu ) {
				$choices[ $menu->term_id ] = $menu->name;
			}
			$field[ 'choices' ] = $choices;
		}

		return $field;
	}

	function syn_load_wordpress_widgets( $field ) {
		$choices           = [];
		$wp_widget_factory = $GLOBALS[ 'wp_widget_factory' ];
		$wp_widgets        = $wp_widget_factory->widgets;
		if ( $wp_widgets ) {
			foreach ( $wp_widgets as $key => $value ) {
				$choices[ $key ] = $value->name;
			}
			$field[ 'choices' ] = $choices;
		}

		return $field;
	}

	/**
	 * Set default order on ACF Field Groups list (cause it's annoying)
	 */
	add_filter( 'pre_get_posts', 'syn_acf_pre_get_posts', 1, 1 );
	function syn_acf_pre_get_posts( $query ) {
		global $pagenow;
		if ( is_admin() && $query->is_main_query() && 'edit.php' == $pagenow && 'acf-field-group' == $query->get( 'post_type' ) ) {
			if ( ! isset( $_GET[ 'orderby' ] ) ) {
				$query->set( 'orderby', 'post_title' );
				$query->set( 'order', 'ASC' );
			}
			if ( ! isset( $_GET[ 'post_status' ] ) ) {
				$query->set( 'post_status', 'publish' );
			}
		}

		return $query;
	}

	/**
	 * Get the key for a field
	 *
	 * @param $field_name
	 * @param $post_id - can be 'user_', 'widget_' or 'option' too
	 */
	function syn_get_field_key( $field_name, $post_id, $type = 'field' ) {
		if ( ! isset( $post_id ) || empty( $post_id ) ) {
			return;
		}
		$field_obj = get_field_object( $field_name, $post_id );

		return $field_obj[ 'key' ];
	}

	/**
	 * Change the path where ACF will save the local JSON file
	 */
	add_filter( 'acf/settings/save_json', 'syn_acf_json_save_point' );
	function syn_acf_json_save_point() {
		$path = get_stylesheet_directory() . '/assets/json';

		return $path;
	}

	/**
	 * Specify path where ACF should look for local JSON files
	 */
	add_filter( 'acf/settings/load_json', 'syn_acf_json_load_point' );
	function syn_acf_json_load_point( $paths ) {
		// remove original path (optional)
		unset( $paths[ 0 ] );
		// append new path
		$paths[] = get_stylesheet_directory() . '/assets/json';

		return $paths;
	}

	/**
	 * Save post actions for all saves of posts, pages, options, users, CPTs, etc - fires every time a post save has ACF fields included
	 */
	// Priority 20 fires after ACF has saved the custom field data
	add_action( 'acf/save_post', 'syn_acf_save_post', 20 );
	function syn_acf_save_post( $post_id ) {
		global $pagenow;
		// Exit if not in admin
		if ( ! is_admin() ) {
			return;
		}
		// Exit if revision or AUTOSAVE
		if ( wp_is_post_revision( $post_id ) || ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) ) {
			return;
		}
		// Exit if no ACF fields or fields are empty
		if ( ! isset( $_POST[ 'acf' ] ) || empty( $_POST[ 'acf' ] ) ) {
			return;
		}
		// Process ACF $post_id into [ post_type, post_id ] because of user_ID, widget_ID and option
		$acf_post_type_array = syn_get_acf_post_type( $post_id );
		if ( 1 == count( $acf_post_type_array ) ) {
			$post_id   = $acf_post_type_array[ 0 ];
			$post_type = get_post_type( $post_id );
		} elseif ( 2 == count( $acf_post_type_array ) ) {
			$post_type = $acf_post_type_array[ 0 ];
			$post_id   = $acf_post_type_array[ 1 ];
		} else {
			return;
		}
		// Switch on post type
		switch ( $post_type ) :
			case 'user' :
				// saved logic for people view
				/*if ( 'syntric-people' == $pagenow ) {
					// coming from options page or somewhere besides user forms
					$post_fields = $_POST;
					$user_ids    = $post_fields[ 'user_ids' ];
					$user_ids    = explode( ',', $user_ids );
					if ( $user_ids ) {
						foreach ( $user_ids as $user_id ) {
							$user       = get_user_by( 'ID', $user_id );
							$first_name = $post_fields[ 'user_' . $user_id . '-first_name' ];
							$last_name  = $post_fields[ 'user_' . $user_id . '-last_name' ];
							$email      = $post_fields[ 'user_' . $user_id . '-email' ];
							$roles      = $user->roles;
							$role       = ( $roles ) ? $roles[ 0 ] : 'subscriber';
							$is_teacher = ( isset( $post_fields[ 'user_' . $user_id . '-is_teacher' ] ) ) ? 1 : 0;
							$role       = ( $is_teacher && 'subscriber' == $role ) ? 'author' : $role;
							$username   = $post_fields[ 'user_' . $user_id . '-username' ]; // this is a hidden form field
							$userdata   = [
								'ID'            => $user_id,
								'user_login'    => $username,
								'user_nicename' => $first_name . ' ' . $last_name,
								'user_email'    => $email,
								'display_name'  => $first_name . ' ' . $last_name,
								'nickname'      => $first_name,
								'first_name'    => $first_name,
								'last_name'     => $last_name,
								'role'          => $role,
							];
							$user_id    = wp_insert_user( $userdata );
							$prefix     = $post_fields[ 'user_' . $user_id . '-prefix' ];
							$title      = $post_fields[ 'user_' . $user_id . '-title' ];
							$phone      = $post_fields[ 'user_' . $user_id . '-phone' ];
							$extension  = $post_fields[ 'user_' . $user_id . '-extension' ];
							update_field( 'syn_user_prefix', $prefix, 'user_' . $user_id );
							update_field( 'syn_user_title', $title, 'user_' . $user_id );
							update_field( 'syn_user_phone', $phone, 'user_' . $user_id );
							update_field( 'syn_user_extension', $extension, 'user_' . $user_id );
							update_field( 'syn_user_is_teacher', $is_teacher, 'user_' . $user_id );
							if ( $is_teacher ) {
								$teacher_page_id = syn_save_teacher_page( $user_id );
								update_field( 'syn_user_page', $teacher_page_id, 'user_' . $user_id );
							} else {
								syn_trash_teacher_page( $user_id );
								update_field( 'syn_user_page', null, 'user_' . $user_id );
							}
						}
					}
				}*/
				// straighten out variables
				$user_id = $post_id;
				$post_id = null;
				$user    = get_user_by( 'ID', $user_id );
				if ( $user instanceof WP_User ) {
					// get user roles
					$roles = $user->roles;
					// if there are more than one role, remove all but the last in the roles array then set the user role variable
					if ( 1 < count( $roles ) ) {
						$last_role = $roles[ count( $roles ) - 1 ];
						foreach ( $roles as $role ) {
							$user->remove_role( $role );
						}
						$user->set_role( $last_role );
						$role = $last_role;
					} else {
						$role = $roles[ 0 ];
					}
					$is_teacher = get_field( 'syn_user_is_teacher', 'user_' . $user->ID );
					if ( $is_teacher ) {
						if ( ! in_array( $role, [ 'author', 'editor', 'administrator', ] ) ) {
							syn_save_teacher_page( $user_id );
							wp_update_user( [ 'ID' => $user->ID, 'role' => 'author', ] );
						}
						syn_save_teacher_page( $user->ID );
					} elseif ( ! $is_teacher ) {
						if ( 'author' == $role ) {
							// todo: also look for class and custom pages under teacher page - maybe another function syn_trash_teacher_pages
							syn_trash_teacher_page( $user->ID );
							wp_update_user( [ 'ID' => $user_id, 'role' => 'subscriber' ] );
						}
					}
				}
				break;
			case 'widget' :
				// todo: this was taken from the former syn_save_calendar acf/save_post action
				//$all_calendars = get_field( 'syn_calendars_menu_widget_all_calendars', $post_id );
				//if ( $all_calendars ) {
				//update_field( 'syn_calendars_menu_widget_calendars', null, $post_id );
				//}
				break;
			case 'option' :
				/*elseif ( 'syntric-people' == $page ) {
	// coming from options page or somewhere besides user forms
	$post_fields = $_POST;
	$user_ids    = $post_fields[ 'user_ids' ];
	$user_ids    = explode( ',', $user_ids );
	if ( $user_ids ) {
		foreach ( $user_ids as $user_id ) {
			$user       = get_user_by( 'ID', $user_id );
			$first_name = $post_fields[ 'user_' . $user_id . '-first_name' ];
			$last_name  = $post_fields[ 'user_' . $user_id . '-last_name' ];
			$email      = $post_fields[ 'user_' . $user_id . '-email' ];
			$roles      = $user->roles;
			$role       = ( $roles ) ? $roles[ 0 ] : 'subscriber';
			$is_teacher = ( isset( $post_fields[ 'user_' . $user_id . '-is_teacher' ] ) ) ? 1 : 0;
			$role       = ( $is_teacher && 'subscriber' == $role ) ? 'author' : $role;
			$username   = $post_fields[ 'user_' . $user_id . '-username' ]; // this is a hidden form field
			$userdata   = [
				'ID'            => $user_id,
				'user_login'    => $username,
				'user_nicename' => $first_name . ' ' . $last_name,
				'user_email'    => $email,
				'display_name'  => $first_name . ' ' . $last_name,
				'nickname'      => $first_name,
				'first_name'    => $first_name,
				'last_name'     => $last_name,
				'role'          => $role,
			];
			$user_id    = wp_insert_user( $userdata );
			$prefix     = $post_fields[ 'user_' . $user_id . '-prefix' ];
			$title      = $post_fields[ 'user_' . $user_id . '-title' ];
			$phone      = $post_fields[ 'user_' . $user_id . '-phone' ];
			$extension  = $post_fields[ 'user_' . $user_id . '-extension' ];
			update_field( 'syn_user_prefix', $prefix, 'user_' . $user_id );
			update_field( 'syn_user_title', $title, 'user_' . $user_id );
			update_field( 'syn_user_phone', $phone, 'user_' . $user_id );
			update_field( 'syn_user_extension', $extension, 'user_' . $user_id );
			update_field( 'syn_user_is_teacher', $is_teacher, 'user_' . $user_id );
			if ( $is_teacher ) {
				$teacher_page_id = syn_save_teacher_page( $user_id );
				update_field( 'syn_user_page', $teacher_page_id, 'user_' . $user_id );
			} else {
				syn_trash_teacher_page( $user_id );
				update_field( 'syn_user_page', null, 'user_' . $user_id );
			}
		}
	}
}*/
				break;
			case 'post' :
				// todo: needs logic check
				/*$category_id = get_field( 'syn_post_category', $post_id );
				if ( 0 == $category_id ) {
					$new_category = get_field( 'syn_post_new_category', $post_id );
					$category_id  = wp_insert_category( [ 'cat_name' => $new_category ] );
				}
				if ( $category_id ) {
					wp_set_post_categories( $post_id, [ (int) $category_id ], false );
					$microblog_category = get_category_by_slug( 'microblogs' );
					if ( $category_id == $microblog_category->cat_ID ) {
						$microblog_id = get_field( 'syn_post_microblog', $post_id );
						if ( $microblog_id ) {
							$microblog_term    = get_term_by( 'id', $microblog_id, 'microblog' );
							$microblog_term_id = $microblog_term->term_id;
						} else {
							$new_microblog_term = get_field( 'syn_post_new_microblog', $post_id );
							$microblog_term     = get_term_by( 'name', $new_microblog_term, 'microblog' );
							if ( ! $microblog_term ) {
								$microblog_term    = wp_insert_term( $new_microblog_term, 'microblog', [ 'description' => 'Microblog term created manually' ] );
								$microblog_term_id = $microblog_term[ 'term_id' ];
							} else {
								$microblog_term_id = $microblog_term->term_id;
							}
						}
						if ( $microblog_term_id ) {
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
				}*/
				break;
			case 'page' :
				///////////////// todo: page generic
				$page_template = strtolower( syn_get_page_template( $post_id ) );
				switch ( $page_template ) :
					case 'page' :
						break;
					case 'department' :
						break;
					case 'teachers' :
						break;
					case 'teacher' :
						$teacher_id = get_field( 'syn_page_teacher', $post_id );
						syn_save_teacher_page( $teacher_id );
						syn_save_teacher_classes( $teacher_id );
						break;
					case 'class' :
						break;
				endswitch;
				syn_save_page_widgets( $post_id );
				///////////////// todo: page microblog
				// todo: reduce all the "security and validity" conditional wrappers, like the next line, into a function call eg. syn_continue_if('admin','page','teacher_template').
				// set term in taxonomy "microblog"
				$microblog_active = get_field( 'syn_microblog_active', $post_id );
				if ( $microblog_active ) {
					if ( category_exists( 'Microblogs' ) ) {
						$cat    = get_category_by_slug( 'microblogs' );
						$cat_id = $cat->cat_ID;
					} else {
						$cat_id = wp_insert_category( [ 'cat_name' => 'Microblogs' ] );
					}
					$ancestor_ids = array_reverse( get_post_ancestors( $post_id ) );
					$titles       = '';
					$slugs        = '';
					foreach ( $ancestor_ids as $ancestor_id ) {
						$titles .= get_the_title( $ancestor_id ) . ' > ';
						$slugs  .= get_post_field( 'post_name', $ancestor_id ) . '-';
					}
					//$titles .= $post->post_title;
					$titles .= get_the_title( $post_id );
					//$slugs  .= $post->post_name;
					$slugs .= get_post_field( 'post_name', $post_id );
					if ( term_exists( $slugs, 'microblog' ) ) {
						$term    = get_term_by( 'slug', $slugs, 'microblog' );
						$term_id = $term->term_id;
					} else {
						$tax_term_ids = wp_insert_term( $titles, 'microblog', [
							'slug'        => $slugs,
							'description' => 'Microblog associated with <a href="/wp-admin/post.php?post=' . $post_id . '&action=edit">' . $titles . '</a> page',
						] );
						$term_id      = $tax_term_ids[ 'term_id' ];
					}
					if ( is_int( $cat_id ) && is_int( $term_id ) ) {
						//wp_set_post_categories( $post_id, [ (int) $cat_id ], false );
						//wp_set_post_terms( $post_id, [ (int) $term_id ], 'microblog', false );
						//update_field( 'syn_microblog_page', $post->ID, 'microblog_' . $term_id );
						update_field( 'syn_microblog_category', $cat_id, $post_id );
						update_field( 'syn_microblog_term', $term_id, $post_id );
						update_field( 'syn_microblog_category_select', $cat_id, $post_id );
						update_field( 'syn_microblog_term_select', $term_id, $post_id );
					}
				}
				//////////////// todo: migration field group
				$run_migration_task = get_field( 'syn_migration_run_next', $post_id );
				if ( isset( $run_migration_task ) && $run_migration_task ) {
					syn_migration_run_next( $post_id );
				}
				/**
				 * Import (local) links in an HTML list into a page's Attachments
				 */
				$run_import_attachments_html = get_field( 'syn_migration_run_import_attachments_html', $post_id );
				if ( isset( $run_import_attachments_html ) && $run_import_attachments_html ) {
					$attachments_html = get_field( 'syn_migration_attachments_html', $post_id );
					if ( isset( $attachments_html ) && ! empty( $attachments_html ) ) {
						syn_parse_attachments_html( $attachments_html, $post_id );
					}
				}
				/////////////// todo: teacher page??? if current user is teacher...
				$user_id    = get_current_user_id();
				$is_teacher = get_field( 'syn_user_is_teacher', 'user_' . $user_id );
				// if current user is a teacher
				if ( $is_teacher ) {
					// need to accommodate teacher, class and default pages
					$page_template = syn_get_page_template( $post_id );
					switch ( $page_template ) {
						case 'teacher' :
							$post_teacher = get_field( 'syn_page_teacher', $post_id );
							// if current user is page's teacher
							if ( $post_teacher == $user_id ) {
								$teachers_page = syn_get_teachers_page();
								if ( $teachers_page instanceof WP_Post ) {
									wp_update_post( [
										'ID'          => $post_id,
										'post_parent' => $teachers_page->ID,
									] );
								}
							}
							break;
						case 'class' :
							$class_teacher = get_field( 'syn_page_class_teacher', $post_id );
							if ( $class_teacher == $user_id ) {
								$teacher_page = syn_get_teacher_page( $user_id, 0 );
								if ( $teacher_page instanceof WP_Post ) {
									wp_update_post( [
										'ID'          => $post_id,
										'post_parent' => $teacher_page->ID,
									] );
								}
							}
							break;
						case 'default' :
						case 'page' :
							//slog('triggered default/page template version');
							$teacher_page = syn_get_teacher_page( $user_id, 0 );
							if ( $teacher_page instanceof WP_Post ) {
								//slog('inside checkpoint');
								$res = wp_update_post( [
									'ID'          => $post_id,
									'post_parent' => $teacher_page->ID,
								] );
								//slog( $res );
							}
							break;
					}
				}
				break;
			case 'attachment' :
				break;
			case 'syn_calendar' :
				$sync     = get_field( 'syn_calendar_sync', $post_id );
				$sync_now = get_field( 'syn_calendar_sync_now', $post_id );
				if ( $sync ) {
					syn_schedule_calendar_sync( $post_id );
				}
				if ( $sync_now ) {
					syn_sync_calendar( [
						'post_id'    => $post_id,
						'post_type'  => 'syn_calendar',
						'force_sync' => true,
					] );
					update_field( 'syn_calendar_sync_now', 0, $post_id );
				}
				break;
			case 'syn_event' :
				break;
		endswitch;
		if ( isset( $_REQUEST[ 'page' ] ) ) {
			if ( 'syntric-sidebars-widgets' == $_REQUEST[ 'page' ] ) {
				if ( have_rows( 'syn_sidebars', 'option' ) ) {
					while( have_rows( 'syn_sidebars', 'option' ) ) : the_row();
						$sidebar_section   = get_sub_field( 'section' );
						$sidebar_location  = get_sub_field( 'location' );
						$name              = $sidebar_section[ 'label' ];
						$name              .= ( 'main' == $sidebar_section[ 'value' ] ) ? ' ' . $sidebar_location[ 'label' ] : '';
						$name_array        = [];
						$description_array = [];
						// run through filters
						if ( have_rows( 'filters' ) ) {
							while( have_rows( 'filters' ) ) : the_row();
								$filter_parameter     = get_sub_field( 'parameter' );
								$filter_operator      = get_sub_field( 'operator' );
								$filter_value         = get_sub_field( 'value' );
								$filter_operator_sign = ( 'is' == $filter_operator[ 'value' ] ) ? '+' : '-';
								switch ( $filter_parameter[ 'value' ] ) :
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
						if ( count( $name_array ) ) {
							$name .= ' (' . implode( ' ', $name_array ) . ')';
						}
						$_name = get_sub_field( 'name' );
						if ( ! empty( $_name ) ) {
							update_sub_field( 'name', $name );
						}
						$sidebar_layout = get_sub_field( 'layout' );
						if ( 'main' != $sidebar_section[ 'value' ] ) {
							$description_array[] = $sidebar_layout[ 'label' ] . ' layout';
						}
						if ( count( $description_array ) ) {
							update_sub_field( 'description', implode( ' / ', $description_array ) );
						}
					endwhile;
				}
			}
			if ( 'syntric-data-functions' == $_REQUEST[ 'page' ] ) {
				// do stuff
				$run_orphan_scan              = get_field( 'syn_data_run_orphan_scan', 'option' );
				$run_users_import             = get_field( 'syn_data_run_users_import', 'option' );
				$run_users_export             = get_field( 'syn_data_run_users_export', 'option' );
				$run_users_phone_update       = get_field( 'syn_data_run_users_phone_update', 'option' );
				$run_users_password_update    = get_field( 'syn_data_run_users_password_update', 'option' );
				$run_activate_contact_widgets = get_field( 'syn_data_run_activate_contact_widgets', 'option' );
				$run_reset_user_capabilities  = get_field( 'syn_data_run_reset_user_capabilities', 'option' );
				$run_dedupe_events            = get_field( 'syn_data_run_dedupe_events', 'option' );
				if ( $run_orphan_scan ) {
					$delete_orphans = get_field( 'syn_data_delete_orphans', 'option' );
					syn_scan_orphans( $delete_orphans );
				}
				if ( $run_users_import ) {
					syn_import_users();
				}
				if ( $run_users_export ) {
					syn_export_users();
				}
				if ( $run_users_phone_update ) {
					$phone = get_field( 'syn_data_users_phone', 'option' );
					syn_update_users_phone( $phone );
				}
				if ( $run_users_password_update ) {
					syn_update_users_password();
				}
				if ( $run_activate_contact_widgets ) {
					syn_activate_contact_widgets();
				}
				if ( $run_reset_user_capabilities ) {
					syn_reset_user_capabilities();
				}
				if ( $run_dedupe_events ) {
					syn_dedupe_events();
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
	}

	function syn_get_acf_post_type( $post_id ) {
		if ( ! is_numeric( $post_id ) ) {
			// if $post_id is not numeric, this must be a user, widget or option
			$post_id_array = explode( '_', $post_id );

			// return the id array with post type and ID
			return $post_id_array;
		} else {
			// if $post_id is numeric, return post type in an array (to match above)
			return [ get_post_type( $post_id ), $post_id ];
		}
	}

	//add_action( 'acf/save_post', 'syn_save_post', 20 );
	function __syn_save_post( $post_id ) {
		global $pagenow;
		$post_id = syn_resolve_post_id( $post_id );
		// don't save for autosave
		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
			return;
		}
		if ( is_admin() && is_numeric( $post_id ) && 'post' == get_post_type( $post_id ) && isset( $_REQUEST[ 'acf' ] ) && ! wp_is_post_revision( $post_id ) ) {
			$category_id = get_field( 'syn_post_category', $post_id );
			if ( 0 == $category_id ) {
				$new_category = get_field( 'syn_post_new_category', $post_id );
				$category_id  = wp_insert_category( [ 'cat_name' => $new_category ] );
			}
			if ( $category_id ) {
				wp_set_post_categories( $post_id, [ (int) $category_id ], false );
				$microblog_category = get_category_by_slug( 'microblogs' );
				if ( $category_id == $microblog_category->cat_ID ) {
					$microblog_id = get_field( 'syn_post_microblog', $post_id );
					if ( $microblog_id ) {
						$microblog_term    = get_term_by( 'id', $microblog_id, 'microblog' );
						$microblog_term_id = $microblog_term->term_id;
					} else {
						$new_microblog_term = get_field( 'syn_post_new_microblog', $post_id );
						$microblog_term     = get_term_by( 'name', $new_microblog_term, 'microblog' );
						if ( ! $microblog_term ) {
							$microblog_term    = wp_insert_term( $new_microblog_term, 'microblog', [ 'description' => 'Microblog term created manually' ] );
							$microblog_term_id = $microblog_term[ 'term_id' ];
						} else {
							$microblog_term_id = $microblog_term->term_id;
						}
					}
					if ( $microblog_term_id ) {
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
	//add_action( 'acf/save_post', 'syn_save_calendar', 20 );
	function __syn_save_calendar( $post_id ) {
		global $pagenow;
		$post_id = syn_resolve_post_id( $post_id );
		// don't save for autosave
		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE || ! is_admin() ) {
			return;
		}
		// widgets, options, users, etc..
		if ( ! is_int( $post_id ) ) {
			$post_id_array = explode( '_', $post_id );
			$post_type     = $post_id_array[ 0 ];
			if ( $post_type == 'widget' ) {
				$all_calendars = get_field( 'syn_calendars_menu_widget_all_calendars', $post_id );
				if ( $all_calendars ) {
					update_field( 'syn_calendars_menu_widget_calendars', null, $post_id );
				}
			}
		} else {
			$post = get_post( $post_id );
			if ( 'syn_calendar' == $post->post_type && 'post.php' == $pagenow && ! wp_is_post_revision( $post_id ) ) {
				$sync     = get_field( 'syn_calendar_sync', $post_id );
				$sync_now = get_field( 'syn_calendar_sync_now', $post_id );
				if ( $sync ) {
					syn_schedule_calendar_sync( $post_id );
				}
				if ( $sync_now ) {
					syn_sync_calendar( [
						'post_id'    => $post_id,
						'post_type'  => 'syn_calendar',
						'force_sync' => true,
					] );
					update_field( 'syn_calendar_sync_now', 0, $post_id );
				}
			}
		}
	}

	//add_action( 'acf/save_post', 'syn_migration_save_post', 20 );
	function __syn_migration_save_post( $post_id ) {
		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
			return;
		}
		$post_id = syn_resolve_post_id( $post_id );
		$post    = get_post( $post_id );
		if ( is_admin() && is_numeric( $post_id ) && 'page' == $post->post_type && isset( $_REQUEST[ 'acf' ] ) && ! wp_is_post_revision( $post_id ) ) {
			$run_migration_task = get_field( 'syn_migration_run_next', $post_id );
			if ( isset( $run_migration_task ) && $run_migration_task ) {
				syn_migration_run_next( $post_id );
			}
			/**
			 * Import (local) links in an HTML list into a page's Attachments
			 */
			$run_import_attachments_html = get_field( 'syn_migration_run_import_attachments_html', $post_id );
			if ( isset( $run_import_attachments_html ) && $run_import_attachments_html ) {
				$attachments_html = get_field( 'syn_migration_attachments_html', $post_id );
				if ( isset( $attachments_html ) && ! empty( $attachments_html ) ) {
					syn_parse_attachments_html( $attachments_html, $post_id );
				}
			}
		}
	}

	/**
	 * syn_teacher_save_post controls and enforces the rules of affecting teacher pages
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
	//add_action( 'acf/save_post', 'syn_teacher_save_post', 20 );
	function __syn_teacher_save_post( $post_id ) {
		$post_id = syn_resolve_post_id( $post_id );
		$post    = get_post( $post_id );
		// if post is set and is a page
		if ( $post instanceof WP_Post && 'page' == $post->post_type ) {
			//$user       = get_user_by( 'ID', get_current_user_id() );
			$user_id    = get_current_user_id();
			$is_teacher = get_field( 'syn_user_is_teacher', 'user_' . $user_id );
			// if current user is a teacher
			if ( $is_teacher ) {
				// need to accomodate teacher, class and default pages
				$page_template = syn_get_page_template( $post_id );
				switch ( $page_template ) {
					case 'teacher' :
						$post_teacher = get_field( 'syn_page_teacher', $post_id );
						// if current user is page's teacher
						if ( $post_teacher == $user_id ) {
							$teachers_page = syn_get_teachers_page();
							if ( $teachers_page instanceof WP_Post ) {
								wp_update_post( [
									'ID'          => $post_id,
									'post_parent' => $teachers_page->ID,
								] );
							}
						}
						break;
					case 'class' :
						$class_teacher = get_field( 'syn_page_class_teacher', $post_id );
						if ( $class_teacher == $user_id ) {
							$teacher_page = syn_get_teacher_page( $user_id, 0 );
							if ( $teacher_page instanceof WP_Post ) {
								wp_update_post( [
									'ID'          => $post_id,
									'post_parent' => $teacher_page->ID,
								] );
							}
						}
						break;
					case 'default' :
					case 'page' :
						//slog('triggered default/page template version');
						$teacher_page = syn_get_teacher_page( $user_id, 0 );
						if ( $teacher_page instanceof WP_Post ) {
							//slog('inside checkpoint');
							$res = wp_update_post( [
								'ID'          => $post_id,
								'post_parent' => $teacher_page->ID,
							] );
							//slog( $res );
						}
						break;
				}
			}
		}
	}

	//add_action( 'acf/save_post', 'syn_save_page', 20 );
	function __syn_save_page( $post_id ) {
		//global $pagenow;
		// don't save for autosave
		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
			return;
		}
		$post_id = syn_resolve_post_id( $post_id );
		$post    = get_post( $post_id );
		if ( is_admin() && is_numeric( $post_id ) && 'page' == $post->post_type && isset( $_REQUEST[ 'acf' ] ) && ! wp_is_post_revision( $post_id ) ) {
			$page_template = strtolower( syn_get_page_template( $post_id ) );
			switch ( $page_template ) :
				case 'page' :
					break;
				case 'department' :
					break;
				case 'teachers' :
					break;
				case 'teacher' :
					$teacher_id = get_field( 'syn_page_teacher', $post_id );
					syn_save_teacher_page( $teacher_id );
					syn_save_teacher_classes( $teacher_id );
					break;
				case 'class' :
					break;
			endswitch;
			syn_save_page_widgets( $post_id );
		}
	}

	/**
	 * Save sidebar
	 */
	//add_action( 'acf/save_post', 'syn_save_sidebars', 20 );
	function __syn_save_sidebars() {
		// don't save for autosave
		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
			return;
		}
		if ( is_admin() && isset( $_REQUEST[ 'page' ] ) && 'syntric-sidebars-widgets' == $_REQUEST[ 'page' ] ) {
			if ( have_rows( 'syn_sidebars', 'option' ) ) {
				while( have_rows( 'syn_sidebars', 'option' ) ) : the_row();
					$sidebar_section   = get_sub_field( 'section' );
					$sidebar_location  = get_sub_field( 'location' );
					$name              = $sidebar_section[ 'label' ];
					$name              .= ( 'main' == $sidebar_section[ 'value' ] ) ? ' ' . $sidebar_location[ 'label' ] : '';
					$name_array        = [];
					$description_array = [];
					// run through filters
					if ( have_rows( 'filters' ) ) {
						while( have_rows( 'filters' ) ) : the_row();
							$filter_parameter     = get_sub_field( 'parameter' );
							$filter_operator      = get_sub_field( 'operator' );
							$filter_value         = get_sub_field( 'value' );
							$filter_operator_sign = ( 'is' == $filter_operator[ 'value' ] ) ? '+' : '-';
							switch ( $filter_parameter[ 'value' ] ) :
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
					if ( count( $name_array ) ) {
						$name .= ' (' . implode( ' ', $name_array ) . ')';
					}
					$_name = get_sub_field( 'name' );
					if ( ! empty( $_name ) ) {
						update_sub_field( 'name', $name );
					}
					$sidebar_layout = get_sub_field( 'layout' );
					if ( 'main' != $sidebar_section[ 'value' ] ) {
						$description_array[] = $sidebar_layout[ 'label' ] . ' layout';
					}
					if ( count( $description_array ) ) {
						update_sub_field( 'description', implode( ' / ', $description_array ) );
					}
				endwhile;
			}
		}
	}

	/**
	 * Get microblog tax term (or create it) and assign to microblog on save post (page)
	 */
	//add_action( 'acf/save_post', 'syn_save_microblog', 20 );
	function __syn_save_microblog( $post_id ) {
		global $pagenow;
		// don't save for autosave
		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
			return;
		}
		$post_id = syn_resolve_post_id( $post_id );
		$post    = get_post( $post_id );
		// todo: reduce all the "security and validity" conditional wrappers, like the next line, into a function call eg. syn_continue_if('admin','page','teacher_template')...
		if ( is_admin() && $post instanceof WP_Post && 'page' == $post->post_type && isset( $_REQUEST[ 'acf' ] ) && ! wp_is_post_revision( $post->ID ) ) {
			// set term in taxonomy "microblog"
			$microblog_active = get_field( 'syn_microblog_active', $post->ID );
			if ( $microblog_active ) {
				if ( category_exists( 'Microblogs' ) ) {
					$cat    = get_category_by_slug( 'microblogs' );
					$cat_id = $cat->cat_ID;
				} else {
					$cat_id = wp_insert_category( [ 'cat_name' => 'Microblogs' ] );
				}
				$ancestor_ids = array_reverse( get_post_ancestors( $post->ID ) );
				$titles       = '';
				$slugs        = '';
				foreach ( $ancestor_ids as $ancestor_id ) {
					$titles .= get_the_title( $ancestor_id ) . ' > ';
					$slugs  .= get_post_field( 'post_name', $ancestor_id ) . '-';
				}
				$titles .= $post->post_title;
				$slugs  .= $post->post_name;
				if ( term_exists( $slugs, 'microblog' ) ) {
					$term    = get_term_by( 'slug', $slugs, 'microblog' );
					$term_id = $term->term_id;
				} else {
					$tax_term_ids = wp_insert_term( $titles, 'microblog', [
						'slug'        => $slugs,
						'description' => 'Microblog associated with <a href="/wp-admin/post.php?post=' . $post->ID . '&action=edit">' . $titles . '</a> page',
					] );
					$term_id      = $tax_term_ids[ 'term_id' ];
				}
				if ( is_int( $cat_id ) && is_int( $term_id ) ) {
					wp_set_post_categories( $post->ID, [ (int) $cat_id ], false );
					wp_set_post_terms( $post->ID, [ (int) $term_id ], 'microblog', false );
					//update_field( 'syn_microblog_page', $post->ID, 'microblog_' . $term_id );
				}
			}
		}
	}

	//add_action( 'acf/save_post', 'syn_save_data_functions', 20 );
	function __syn_save_data_functions() {
		if ( is_admin() && isset( $_REQUEST[ 'page' ] ) && 'syntric-data-functions' == $_REQUEST[ 'page' ] ) {
			// do stuff
			$run_orphan_scan              = get_field( 'syn_data_run_orphan_scan', 'option' );
			$run_users_import             = get_field( 'syn_data_run_users_import', 'option' );
			$run_users_export             = get_field( 'syn_data_run_users_export', 'option' );
			$run_users_phone_update       = get_field( 'syn_data_run_users_phone_update', 'option' );
			$run_users_password_update    = get_field( 'syn_data_run_users_password_update', 'option' );
			$run_activate_contact_widgets = get_field( 'syn_data_run_activate_contact_widgets', 'option' );
			$run_reset_user_capabilities  = get_field( 'syn_data_run_reset_user_capabilities', 'option' );
			$run_dedupe_events            = get_field( 'syn_data_run_dedupe_events', 'option' );
			if ( $run_orphan_scan ) {
				$delete_orphans = get_field( 'syn_data_delete_orphans', 'option' );
				syn_scan_orphans( $delete_orphans );
			}
			if ( $run_users_import ) {
				syn_import_users();
			}
			if ( $run_users_export ) {
				syn_export_users();
			}
			if ( $run_users_phone_update ) {
				$phone = get_field( 'syn_data_users_phone', 'option' );
				syn_update_users_phone( $phone );
			}
			if ( $run_users_password_update ) {
				syn_update_users_password();
			}
			if ( $run_activate_contact_widgets ) {
				syn_activate_contact_widgets();
			}
			if ( $run_reset_user_capabilities ) {
				syn_reset_user_capabilities();
			}
			if ( $run_dedupe_events ) {
				syn_dedupe_events();
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