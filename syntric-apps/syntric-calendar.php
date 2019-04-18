<?php
/**
 * syntric_calendar and syntric_event - calendar and event app with Google Calendar sync features
 */

/**
 * Force local time zone for all syntric_calendar and syntric_event operations
 */
date_default_timezone_set( 'America/Los_Angeles' );

add_action( 'init', 'syntric_register_calendar' );
function syntric_register_calendar() {
	$labels = [
		'name'                  => _x( 'Calendars', 'syntric' ),
		'singular_name'         => _x( 'Calendar', 'syntric' ),
		'menu_name'             => _x( 'Calendars', 'syntric' ),
		'name_admin_bar'        => _x( 'Calendar', 'syntric' ),
		'add_new'               => __( 'Add New Calendar', 'syntric' ),
		'add_new_item'          => __( 'Add New Calendar', 'syntric' ),
		'new_item'              => __( 'New Calendar', 'syntric' ),
		'edit_item'             => __( 'Edit Calendar', 'syntric' ),
		'view_item'             => __( 'View Calendar', 'syntric' ),
		'all_items'             => __( 'All Calendars', 'syntric' ),
		'search_items'          => __( 'Search Calendars', 'syntric' ),
		'parent_item_colon'     => __( 'Parent Calendars:', 'syntric' ),
		'not_found'             => __( 'No calendars found.', 'syntric' ),
		'not_found_in_trash'    => __( 'No calendars found in trash.', 'syntric' ),
		'archives'              => _x( 'Calendar archives', 'syntric' ),
		'insert_into_item'      => _x( 'Insert into calendar', 'syntric' ),
		'uploaded_to_this_item' => _x( 'Uploaded to this calendar', 'syntric' ),
		'filter_items_list'     => _x( 'Filter calendars list', 'syntric' ),
		'items_list_navigation' => _x( 'Calendars list navigation', 'syntric' ),
		'items_list'            => _x( 'Calendars list', 'syntric' ),
	];
	$args   = [
		'labels'            => $labels,
		'public'            => true,
		//'publicly_queryable' => true,
		//'capability_type'    => 'post',
		'has_archive'       => true,
		'map_meta_cap'      => true,
		'menu_position'     => 30,
		'hierarchical'      => true,
		'delete_with_user'  => false,
		'supports'          => [ 'title' ],
		'show_in_rest'      => true,
		'rest_base'         => 'calendars',
		'can_export'        => true,
		'show_ui'           => true,
		'show_in_menu'      => true,
		'show_in_nav_menus' => true,
		'show_in_admin_bar' => false,
		'menu_icon'         => 'dashicons-calendar-alt',
		'query_var'         => true,
		'rewrite'           => [
			'slug'       => 'calendars',
			'with_front' => true,
		],
		'redirect'          => true,
	];
	register_post_type( 'syntric_calendar', $args );
}

/**
 * Force complete deletion of syntric_calendar and syntric_event post types
 */
//add_action( 'wp_trash_post', 'syntric_force_delete_calcpts' );
function syntric_force_delete_calcpts( $post_id ) {
	if( get_post_type( $post_id ) == 'syntric_calendar' || 'syntric_event' == get_post_type( $post_id ) ) {
		wp_delete_post( $post_id, true );
	}
}

/**
 * Sync Google Calendar as WP posts
 */
add_action( 'syntric_calendar_sync', 'syntric_sync_calendar' );
function syntric_sync_calendar( $calendar_post_id, $sync_type = 'scheduled', $sync_past = 0, $sync_past_period = 0 ) {
	$post = get_post( $calendar_post_id );
	//$syntric_calendar = get_field( 'syntric_calendar', $calendar_post_id );
	//$syntric_settings = get_field( 'syntric_settings', 'option' );
	$log = 'Sync started at ' . date( 'n/j/Y g:i A' ) . ' for calendar ' . $post -> post_title . "\n";
	//$cal_meta         = get_field( 'syntric_calendar', $calendar_post_id );
	if( 'now' == $sync_type ) {
		$log .= 'Sync now' . "\n";
		if( $sync_past && $sync_past_period ) {
			$log .= 'Syncing past ' . $sync_past_period . ' months' . "\n";
		}
	} elseif( 'scheduled' == $sync_type ) {
		$log .= 'Scheduled sync' . "\n";
	}
	update_field( 'syntric_calendar_last_sync_result', $log, $calendar_post_id );

	// Set up timeMin and timeMax which define date range of events to sync
	$time_min = date_create();
	if( $sync_past && $sync_past_period ) {
		date_sub( $time_min, date_interval_create_from_date_string( $sync_past_period . ' months' ) );
	}
	$time_min = date_format( $time_min, 'c' );
	$time_max = date_create();
	date_add( $time_max, date_interval_create_from_date_string( '1 year' ) );
	$time_max = date_format( $time_max, 'c' );

	// Construct URL for API call
	$google_calendar_id = get_field( 'syntric_calendar_google_calendar_id', $calendar_post_id );
	$api_key            = get_field( 'syntric_settings_google_api_key', 'option' );
	$url                = trailingslashit( 'https://www.googleapis.com/calendar/v3/calendars/' ) . $google_calendar_id . '/events?key=' . $api_key . '&singleEvents=true&orderBy=startTime&maxResults=200' . '&timeMin=' . $time_min . '&timeMax=' . $time_max;
	$log                .= 'URL for API call constructed as ' . $url . "\n";
	update_field( 'syntric_calendar_last_sync_result', $log, $calendar_post_id );

	// Call the calendar API
	$response = wp_remote_get( $url );

	if( $response ) {
		// received response
		$log .= 'Response received from remote host' . "\n";
		update_field( 'syntric_calendar_last_sync_result', $log, $calendar_post_id );

		// JSON decode the response
		$body = wp_remote_retrieve_body( $response );
		$body = json_decode( $body );

		// if response is an error, include it in the log
		if( property_exists( $body, 'error' ) ) {
			$error  = $body -> error;
			$errors = $error -> errors;
			foreach( $errors as $_error ) {
				$reason = $_error -> reason;
			}
			$log .= "\n" . 'Response includes the following error: ' . $reason . "\n";
			$log .= 'Exiting sync' . "\n";
			update_field( 'syntric_calendar_last_sync_result', $log, $calendar_post_id );
			update_field( 'syntric_calendar_last_sync', date( 'F j, Y g:i A' ), $calendar_post_id );

			return;
		}
	} else {
		// did not receive response
		$log .= 'No response received from remote host' . "\n";
		$log .= 'Exiting sync' . "\n";
		update_field( 'syntric_calendar_last_sync_result', $log, $calendar_post_id );
		update_field( 'syntric_calendar_last_sync', date( 'F j, Y g:i A' ), $calendar_post_id );

		return;
	}

	// Check to see if calendar has been updated since last sync, if not exit
	$last_sync = get_field( 'syntric_calendar_last_sync', $calendar_post_id );
	if( $last_sync && ! 'now' == $sync_type ) {
		$local_last_updated     = date_create( $last_sync );
		$remote_last_updated    = date_create( $body -> updated );
		$remote_timezone_offset = timezone_offset_get( timezone_open( date_default_timezone_get() ), $remote_last_updated );
		$remote_last_updated    = date_add( $remote_last_updated, date_interval_create_from_date_string( $remote_timezone_offset . ' seconds' ) );
		$refresh                = date_format( $local_last_updated, 'YmdHi' ) < date_format( $remote_last_updated, 'YmdHi' );
		if( ! $refresh ) {
			$log .= 'Remote calendar has not be updated since last sync' . "\n";
			$log .= 'Exiting sync' . "\n";
			update_field( 'syntric_calendar_last_sync_result', $log, $calendar_post_id );
			update_field( 'syntric_calendar_last_sync', date( 'F j, Y g:i A' ), $calendar_post_id );

			return;
		}
	}

	$log .= 'Syncing response to local database' . "\n";
	update_field( 'syntric_calendar_last_sync_result', $log, $calendar_post_id );

	// purge events that start on or after $time_min
	/*$time_min_array = explode( 'T', $time_min );
	$time_min_date  = $time_min_array[ 0 ];
	$date_min       = date_create( $time_min_date );
	$start_date_min = date_format( $date_min, 'Ymd' );
	// todo: this addresses the duplication of multi-day events on sync...changed key on first meta_query condition to end_date...was start_date
	$events = get_posts(
		[
			'numberposts' => - 1,
			'post_type'   => 'syntric_event',
			'post_status' => 'publish',
			'meta_query'  => [
				'relation' => 'AND',
				[
					'key'     => 'start_date',
					'value'   => $start_date_min,
					'compare' => '>=',
				],
				[
					'key'     => 'calendar_id',
					'value'   => $calendar_post_id,
					'compare' => '=',
				],
			],
		] );
	if( $events ) {
		$log .= 'Deleting events' . "\n";
		update_field( 'syntric_calendar_last_sync_result', $log, $calendar_post_id );
		foreach( $events as $event ) {
			$del_result = wp_delete_post( $event -> ID, true );
			if( ! $del_result ) {
				$log .= 'Failed to delete event ID ' . $event -> ID . "\n";
				update_field( 'syntric_calendar_last_sync_result', $log, $calendar_post_id );
			} else {
				$log .= 'Deleted event ID ' . $event -> ID . "\n";
				update_field( 'syntric_calendar_last_sync_result', $log, $calendar_post_id );
			}
		}
	}*/
	//wp_reset_postdata(); // for good measure

	// Programmatically assemble all post meta fields that need to be saved for each event
	/*$_event_field_groups = acf_get_field_groups();
	$event_field_groups  = [];
	// loop through field groups
	foreach( $_event_field_groups as $_event_field_group ) {
		// loop over location groups
		if( ! empty( $_event_field_group[ 'location' ] ) ) {
			foreach( $_event_field_group[ 'location' ] as $location_group_id => $location_group ) {
				// loop over group rules
				if( ! empty( $location_group ) ) {
					foreach( $location_group as $rule_id => $rule ) {
						if( $rule[ 'param' ] == 'post_type' && $rule[ 'operator' ] == '==' && $rule[ 'value' ] == 'syntric_event' ) {
							$event_field_groups[] = $_event_field_group;
						} else {
							break;
						}
					}
				}
			}
		}
	}
	$_event_fields = [];
	foreach( $event_field_groups as $event_field_group ) {
		$event_field_group_fields = acf_get_fields( $event_field_group[ 'key' ] );
		$_event_fields            = array_merge( $_event_fields, $event_field_group_fields );
	}
	$event_fields = [];
	foreach( $_event_fields as $_event_field ) {
		if( ! empty( $_event_field[ 'name' ] ) && $_event_field[ 'type' ] != 'row' && $_event_field[ 'type' ] != 'enhanced_message' ) {
			$event_fields[] = [
				'key'   => $_event_field[ 'key' ],
				'name'  => $_event_field[ 'name' ],
				'label' => $_event_field[ 'label' ],
				'type'  => $_event_field[ 'type' ],
			];
		}
	}
	$log .= 'Collected event meta data to update' . "\n";
	update_field( 'syntric_calendar_last_sync_result', $log, $calendar_post_id );*/

	$events = $body -> items;

	// Delete dupe events (same event id but no recurring id)
	$event_count = 0;
	foreach( $events as $event ) {
		// Set up to insert event
		$args          = [
			'post_content' => ( isset( $event -> description ) ) ? $event -> description : '',
			'post_title'   => ( isset( $event -> summary ) ) ? $event -> summary : '',
			'post_status'  => 'publish',
			'post_type'    => 'syntric_event',
		];
		$event_post_id = wp_insert_post( $args );

		// if insertion was successful
		if( $event_post_id ) {
			$log .= 'Inserted event ID ' . $event_post_id . "\n";
			update_field( 'syntric_calendar_last_sync_result', $log, $calendar_post_id );

			$eventId     = $event -> id;
			$startDate   = ( isset( $event -> start -> dateTime ) ) ? $event -> start -> dateTime : $event -> start -> date;
			$startDate   = syntric_convert_date_string_to_meta( $startDate );
			$startTime   = ( isset( $event -> start -> dateTime ) ) ? syntric_convert_time_string_to_meta( $event -> start -> dateTime ) : '';
			$endDate     = ( isset( $event -> end -> dateTime ) ) ? $event -> end -> dateTime : $event -> end -> date;
			$endDate     = syntric_convert_date_string_to_meta( $endDate );
			$endTime     = ( isset( $event -> end -> dateTime ) ) ? syntric_convert_time_string_to_meta( $event -> end -> dateTime ) : '';
			$location    = ( isset( $event -> location ) ) ? $event -> location : '';
			$recurringId = ( isset( $event -> recurringEventId ) ) ? $event -> recurringEventId : '';
			// dupe check
			$dupe_events = get_posts( [
				'post_type'    => 'syntric_event',
				'post_status'  => 'publish',
				'post__not_in' => [ $event_post_id ],
				'meta_query'   => [
					[
						'key'     => 'syntric_event_event_id',
						'value'   => $eventId,
						'compare' => '=',
					],
				],
			] );
			if( $dupe_events ) {
				$log .= 'Deleting dupe events' . "\n";
				update_field( 'syntric_calendar_last_sync_result', $log, $calendar_post_id );
				foreach( $dupe_events as $dupe_event ) {
					wp_delete_post( $dupe_event -> ID, true );
					$log .= 'Dupe event ' . $event -> summary . ' deleted' . "\n";
					update_field( 'syntric_calendar_last_sync_result', $log, $calendar_post_id );
				}
			}

			//$syntric_event = get_field( 'syntric_event', $event_post_id );
			update_field( 'syntric_event_calendar_id', $calendar_post_id, $event_post_id );
			update_field( 'syntric_event_start_date', $startDate, $event_post_id );
			update_field( 'syntric_event_end_date', $endDate, $event_post_id );
			update_field( 'syntric_event_start_time', $startTime, $event_post_id );
			update_field( 'syntric_event_end_time', $endTime, $event_post_id );
			update_field( 'syntric_event_location', $location, $event_post_id );
			update_field( 'syntric_event_event_id', $eventId, $event_post_id );
			update_field( 'syntric_event_recurring_id', $recurringId, $event_post_id );
			update_field( 'syntric_event_last_sync', date( 'F j, Y g:i A' ), $event_post_id );
			$log .= 'Event ID ' . $event_post_id . ' meta data updated' . "\n";
			update_field( 'syntric_calendar_last_sync_result', $log, $calendar_post_id );
			/*foreach( $event_fields as $event_field ) {
				$value         = '';
				$syntric_event = get_field( 'syntric_event', $event_id );
				switch( $event_field[ 'name' ] ) {
					case 'calendar_id':
						update_field( 'syntric_event_calendar_id', $calendar_post_id, $event_id );
						break;
					case 'start_date':
						$value = ( isset( $event -> start -> dateTime ) ) ? $event -> start -> dateTime : $event -> start -> date;
						$value = syntric_convert_date_string_to_meta( $value );
						update_field( 'syntric_event_start_date', $value, $event_id );
						break;
					case 'end_date':
						$value = ( isset( $event -> end -> dateTime ) ) ? $event -> end -> dateTime : $event -> end -> date;
						$value = syntric_convert_date_string_to_meta( $value );
						update_field( 'syntric_event_end_date', $value, $event_id );
						break;
					case 'start_time':
						$value = ( isset( $event -> start -> dateTime ) ) ? syntric_convert_time_string_to_meta( $event -> start -> dateTime ) : '';
						update_field( 'syntric_event_start_time', $value, $event_id );
						break;
					case 'end_time':
						$value = ( isset( $event -> end -> dateTime ) ) ? syntric_convert_time_string_to_meta( $event -> end -> dateTime ) : '';
						update_field( 'syntric_event_end_time', $value, $event_id );
						break;
					case 'location':
						$value = ( isset( $event -> location ) ) ? $event -> location : '';
						update_field( 'syntric_event_location', $value, $event_id );
						break;
					case 'event_id':
						$value = $eventId;
						update_field( 'syntric_event_event_id', $value, $event_id );
						break;
					case 'recurring_id':
						$value = ( isset( $event -> recurringEventId ) ) ? $event -> recurringEventId : '';
						update_field( 'syntric_event_recurring_id', $value, $event_id );
						break;
					case 'last_sync':
						$value = date( 'F j, Y g:i A' );
						update_field( 'syntric_event_last_sync', $value, $event_id );
						break;
				}*/
			//update_field( $event_field[ 'key' ], $value, $event_id );
		}

		$event_count = $event_count + 1;
	}

	update_field( 'syntric_calendar_last_sync', date( 'F j, Y g:i A' ), $calendar_post_id );
	$log .= 'Sync completed with ' . $event_count . ' events' . "\n";
	update_field( 'syntric_calendar_last_sync_result', $log, $calendar_post_id );

	return true;
}

/**
 * Delete calendar - remove sync schedules (cron) and delete events
 * todo: is this working?  not sure cron schedule is getting deleted properly...
 */
add_action( 'before_delete_post', 'syntric_before_delete_calendar', 10 );
function syntric_before_delete_calendar( $post_id ) {
	if( 'syntric_calendar' == get_post_type( $post_id ) ) {
		wp_clear_scheduled_hook( 'syntric_calendar_sync', [ 'post_id' => $post_id ] );
		$args            = [
			'post_type'     => 'syntric_event',
			'post_status'   => 'publish',
			'meta_key'      => 'syntric_event_calendar_id',
			'meta_value'    => $post_id,
			'meta_compare'  => '=',
			'no_found_rows' => true,
		];
		$calendar_events = get_posts( $args );
		if( $calendar_events ) {
			foreach( $calendar_events as $calendar_event ) {
				wp_delete_post( $calendar_event -> ID, true );
			}
		}
	}
}

/**
 * AJAX handler fetching public and private and calendars
 */
add_action( 'wp_ajax_nopriv_syntric_fetch_calendar_events', 'syntric_fetch_calendar_events' );
add_action( 'wp_ajax_syntric_fetch_calendar_events', 'syntric_fetch_calendar_events' );
function syntric_fetch_calendar_events() {
	check_ajax_referer( 'syntric_fetch_calendar_events' );
	$post_id = $_REQUEST[ 'post_id' ];
	$args    = [
		'numberposts'      => - 1,
		'post_type'        => 'syntric_event',
		'post_status'      => 'publish',
		'suppress_filters' => false,
		'no_found_rows'    => true,
		'meta_key'         => 'syntric_event_calendar_id',
		'meta_value'       => $post_id,
		'meta_compare'     => '=',
	];
	$events  = get_posts( $args );
	if( $events ) {
		$event_details = [];
		foreach( $events as $event ) {
			$start_date      = get_field( 'syntric_event_start_date', $event -> ID );
			$start_time      = get_field( 'syntric_event_start_time', $event -> ID );
			$end_date        = get_field( 'syntric_event_end_date', $event -> ID );
			$end_time        = get_field( 'syntric_event_end_time', $event -> ID );
			$start_datetime  = date_create( $start_date . ' ' . $start_time );
			$end_datetime    = date_create( $end_date . ' ' . $end_time );
			$location        = get_field( 'syntric_event_location', $event -> ID );
			$event_id        = get_field( 'syntric_event_event_id', $event -> ID );
			$recurring_id    = get_field( 'syntric_event_recurring_id', $event -> ID );
			$event_id        = ( $recurring_id ) ? explode( '_', $event_id ) : $event_id;
			$event_id        = ( is_array( $event_id ) ) ? $event_id[ 0 ] : $event_id;
			$event_details[] = [
				'title'        => $event -> post_title,
				'allDay'       => ( empty( $start_time ) ) ? true : false,
				'start'        => date_format( $start_datetime, 'c' ),
				'end'          => date_format( $end_datetime, 'c' ),
				'url'          => ( syntric_has_content( $event -> post_content ) ) ? get_the_permalink( $event -> ID ) : null,
				'description'  => $event -> post_content,
				'location'     => $location,
				'event_id'     => $event_id,
				'recurring_id' => $recurring_id,
			];
		}
		wp_send_json( $event_details );
	}
	wp_send_json( [] );
	wp_die();
}

add_filter( 'months_dropdown_results', 'syntric_calendar_months_dropdown_results', 10, 2 );
function syntric_calendar_months_dropdown_results( $months, $post_type ) {
	if( $post_type == 'syntric_calendar' || $post_type = 'syntric_event' ) {
		return [];
	}

	return $months;
}

function syntric_schedule_calendar_sync( $post_id ) {
	$args = [
		'post_id' => $post_id,
	];
	wp_clear_scheduled_hook( 'syntric_calendar_sync', $args );
	wp_schedule_event( time() + 60, 'hourly', 'syntric_calendar_sync', $args );
	update_field( 'syntric_calendar_last_sync', 'Scheduled', $post_id );
	update_field( 'syntric_calendar_last_sync_result', 'Calendar scheduled to sync hourly', $post_id );
}

function syntric_get_calendar_next_scheduled( $post_id ) {
	$next_scheduled = wp_next_scheduled( 'syntric_calendar_sync', [
		[
			'post_id' => $post_id,
		],
	] );

	return $next_scheduled;
}

function syntric_get_calendar_sync_schedule( $post_id ) {
	$schedule = wp_get_schedule( 'syntric_calendar_sync', [
		[
			'post_id' => $post_id,
		],
	] );

	return $schedule;
}

function syntric_get_calendar_last_sync( $post_id ) {
	//$syntric_calendar = get_field( 'syntric_calendar', $post_id );
	$schedule_sync = get_field( 'syntric_calendar_schedule_sync', $post_id );
	$last_sync     = get_field( 'syntric_calendar_last_sync', $post_id );
	if( ! $schedule_sync ) {
		return false;
	} else {
		$minutes_ago = syntric_calculate_time_difference( date_create(), $last_sync, 'minutes' );

		return (int) $minutes_ago;
	}
}

function syntric_get_calendar_next_sync( $post_id ) {
	$schedule_sync = get_field( 'syntric_calendar_schedule_sync', $post_id );
	$next_sync     = syntric_get_calendar_next_scheduled( $post_id );
	if( ! $schedule_sync ) {
		return false;
	} else {
		$in_minutes = syntric_calculate_time_difference( date_create(), date( 'c', $next_sync ), 'minutes' );

		return $in_minutes;
	}
}

function syntric_get_calendar_ids( $post_status = 'publish' ) {
	$args = [
		'numberposts'      => - 1,
		'post_type'        => 'syntric_calendar',
		'post_status'      => $post_status,
		'orderby'          => [ 'post_title' => 'ASC' ],
		'suppress_filters' => false,
		'no_found_rows'    => true,
		'fields'           => 'ids',
	];
	add_filter( 'posts_distinct', 'syntric_posts_distinct' );
	$post_ids = get_posts( $args );
	remove_filter( 'posts_distinct', 'syntric_posts_distinct' );

	return $post_ids;
}

function syntric_purge_calendar( $post_id ) {
	$events      = syntric_get_calendar_events( $post_id, null, 'all' );
	$log         = count( $events ) . ' events to be purged' . "\n";
	$event_count = 0;
	foreach( $events as $event ) {
		wp_delete_post( $event -> ID, true );
		$log .= $event -> post_title . ' purged' . "\n";
		$event_count ++;
	}
	$log .= $event_count . ' events purged in total' . "\n";
	update_field( 'syntric_calendar_last_sync_result', $log, $post_id );

	return true;
}

/**
 * Get events for a calendar.
 *
 * Retrieves $calendar_id's $numberposts (number or -1) quantity of events for $ref_date (date) $range (month/year/next day).
 * If $range is 'all', gets all events for $calendar_id and ignores $ref_date and $numberposts.
 *
 * * todo: maybe refactor this.  rearrange the args so that base get_posts args are first and custom are last.  also add post_type arg
 * * and any others that might be helpful.  for example, post_type would help when doing an event count for a particular
 * * calendar
 *
 * @param $calendar_id (optional) default is current post->ID
 * @param $ref_date    (optional) default is today
 * @param $range       (optional) default is month
 * @param $numberposts (optional) default is -1
 *
 * @return array of events
 */
function syntric_get_calendar_events( $post_id, $ref_date = null, $range = 'month', $numberposts = - 1, $fields = '*' ) {
	$post = get_post( $post_id );
	if( ! $post instanceof WP_Post || 'syntric_calendar' != $post -> post_type ) {
		return;
	}
	$ref_date    = ( isset( $ref_date ) ) ? $ref_date : date( 'Ymd' );
	$numberposts = ( isset( $numberposts ) ) ? (int) $numberposts : - 1;
	// args common to all queries
	$args = [
		'post_type'        => 'syntric_event',
		'post_status'      => 'publish',
		'meta_query'       => [
			[
				'key'     => 'syntric_event_calendar_id',
				'value'   => $post_id,
				'compare' => '=',
			],
		],
		'meta_key'         => 'syntric_event_start_date',
		'orderby'          => 'meta_value_num',
		'order'            => 'ASC',
		'suppress_filters' => false,
		'no_found_rows'    => true,
	];
	if( '*' != $fields ) {
		$args[ 'fields' ] = $fields;
	}
	if( 'all' == $range ) {
		$args[ 'numberposts' ] = '-1';
	}
	if( 'all' != $range ) {
		$args[ 'numberposts' ] = $numberposts;
		$calendar_date         = date_create( $ref_date );
		switch( $range ) {
			case 'next':
				$start_date = date_format( $calendar_date, 'Ymd' );
			break;
			case 'month':
				$start_date = date_format( $calendar_date, 'Ym01' );
				$end_date   = date_format( $calendar_date, 'Ymt' );
			break;
			case 'year':
				$start_date = date_format( $calendar_date, 'Y0101' );
				$end_date   = date_format( $calendar_date, 'Y1231' );
			break;
			default: // get just one day
				$start_date = date_format( $calendar_date, 'Ymd' );
				$end_date   = date_format( $calendar_date, 'Ymd' );
		}
		if( isset( $end_date ) ) {
			$meta_query[] = [
				'key'     => 'syntric_event_end_date',
				'value'   => $end_date,
				'compare' => '<=',
			];
		}
		$args[ 'meta_value' ]   = $start_date;
		$args[ 'meta_compare' ] = '>=';
	}
	add_filter( 'posts_distinct', 'syntric_posts_distinct' );
	$events = get_posts( $args );
	remove_filter( 'posts_distinct', 'syntric_posts_distinct' );

	return $events;
}

function syntric_calculate_time_difference( $date_time_1 = null, $date_time_2 = null, $time_unit = 'minutes' ) {
	if( ! isset( $date_time_1 ) || ! isset( $date_time_2 ) ) {
		return;
	}
	if( ! is_a( $date_time_1, 'DateTime' ) ) {
		$date_time_1 = date_create( $date_time_1 );
	}
	if( ! is_a( $date_time_2, 'DateTime' ) ) {
		$date_time_2 = date_create( $date_time_2 );
	}
	$interval = date_diff( $date_time_1, $date_time_2 );
	switch( $time_unit ) {
		case 'seconds':
			$interval_format = '%s';
		break;
		case 'hours':
			$interval_format = '%h';
		break;
		case 'days':
			$interval_format = '%d';
		break;
		default: // default is minutes
			$interval_format = '%i';
		break;
	}

	return date_interval_format( $interval, $interval_format );
}

function syntric_convert_date_string_to_meta( $date_str ) {
	// date string to YYYYMMDD
	$date_obj  = date_create( $date_str );
	$date_meta = date_format( $date_obj, 'Ymd' );

	return $date_meta;
}

function syntric_convert_time_string_to_meta( $time_str ) {
	// date/time string to YYYYMMDD
	$date_obj  = date_create( $time_str );
	$time_meta = date_format( $date_obj, 'g:i A' );

	return $time_meta;
}

function syntric_get_event_dates( $post_id ) {
	$syntric_event = get_field( 'syntric_event', $post_id );
	$start_date    = get_field( 'syntric_event_start_date', $post_id );
	$end_date      = get_field( 'syntric_event_end_date', $post_id );
	$start_time    = get_field( 'syntric_event_start_time', $post_id );
	$end_time      = get_field( 'syntric_event_end_time', $post_id );
	$start_date    = date_create( $start_date );
	$end_date      = date_create( $end_date );
	$dates         = date_format( $start_date, 'F j, Y' );
	if( $start_date != $end_date ) {
		if( empty( $start_time ) || empty( $end_time ) ) {
			$days_diff = syntric_calculate_time_difference( $start_date, $end_date, 'days' );
			if( $days_diff > 1 ) {
				date_sub( $end_date, date_interval_create_from_date_string( '1 day' ) );
				$dates .= ' - ' . date_format( $end_date, 'F j, Y' );
			}
		} else {
			$dates .= ' - ' . date_format( $end_date, 'F j, Y' );
		}
	}
	if( ! empty( $start_time ) ) {
		$dates .= ' ' . $start_time;
	}
	if( ! empty( $end_time ) && $start_time != $end_time ) {
		$dates .= '-' . $end_time;
	}

	return $dates;
}

function syntric_event_date_time_display( $event_id, $date_type, $date_format = '' ) {
	if( ! isset( $event_id ) || ! isset( $date_type ) ) {
		return false;
	}
	$date_format = ( ! empty( $date_format ) ) ? $date_format : 'F j, Y';
	$date_field  = '';
	$time_field  = '';
	$ret         = '';
	if( 'start' == $date_type || 'start_date' == $date_type ) {
		$date_field = 'syntric_event_start_date';
		$time_field = 'syntric_event_start_time';
	} elseif( 'end' == $date_type || 'end_date' == $date_type ) {
		$date_field = 'syntric_event_end_date';
		$time_field = 'syntric_event_end_time';
	}
	if( empty( $date_field ) || empty( $time_field ) ) {
		return false;
	}
	$event_date = get_field( $date_field, $event_id, false );
	$event_date = date_create_from_format( 'Ymd', $event_date );
	if( ! is_a( $event_date, 'DateTime' ) ) {
		return false;
	}
	$ret        = date_format( $event_date, $date_format );
	$event_time = get_field( $time_field, $event_id, true );
	if( $event_time && ! empty( $event_time ) ) {
		$ret .= ' @ ' . $event_time;
	}

	return $ret;
}

function syntric_get_calendars() {
	$args      = [
		'numberposts'      => - 1,
		'post_type'        => 'syntric_calendar',
		'post_status'      => 'publish',
		'orderby'          => [ 'post_title' => 'ASC' ],
		'suppress_filters' => false,
		'no_found_rows'    => true,
	];
	$calendars = get_posts( $args );

	return $calendars;
}