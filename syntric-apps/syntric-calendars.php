<?php
	/**
	 * syn_calendar and syn_event - calendar and event app with Google Calendar sync features
	 */
	/**
	 * Force local time zone for all syn_calendar and syn_event operations
	 */
	date_default_timezone_set( 'America/Los_Angeles' );
//
//
// Actions
//
//
	/**
	 * Register syn_calendar custom post type
	 */
	add_action( 'init', 'syn_register_calendar' );
	function syn_register_calendar() {
		$labels = [
			'name'                  => _x( 'Calendars', 'syntric' ),
			'singular_name'         => _x( 'Calendar', 'syntric' ),
			'menu_name'             => _x( 'Calendars', 'syntric' ),
			'name_admin_bar'        => _x( 'Calendar', 'syntric' ),
			'add_new'               => __( 'Add New', 'syntric' ),
			'add_new_item'          => __( 'Add New Calendar', 'syntric' ),
			'new_item'              => __( 'New Calendar', 'syntric' ),
			'edit_item'             => __( 'Edit Calendar', 'syntric' ),
			'view_item'             => __( 'View Calendar', 'syntric' ),
			'all_items'             => __( 'All Calendars', 'syntric' ),
			'search_items'          => __( 'Search Calendars', 'syntric' ),
			'parent_item_colon'     => __( 'Parent Calendars:', 'syntric' ),
			'not_found'             => __( 'No calendars found.', 'syntric' ),
			'not_found_in_trash'    => __( 'No calendars found in Trash.', 'syntric' ),
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
			'hierarchical'      => false,
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
			'query_var'         => false,
			'rewrite'           => [ 'slug'       => 'calendars',
			                         'with_front' => true,
			],
			'redirect'          => true,
		];
		register_post_type( 'syn_calendar', $args );
	}

	/**
	 * Register syn_event custom post type
	 */
	add_action( 'init', 'syn_register_event' );
	function syn_register_event() {
		$labels = [
			'name'                  => _x( 'Events', 'syntric' ),
			'singular_name'         => _x( 'Event', 'syntric' ),
			'menu_name'             => _x( 'Events', 'syntric' ),
			'name_admin_bar'        => _x( 'Event', 'syntric' ),
			'add_new'               => __( 'Add New', 'syntric' ),
			'add_new_item'          => __( 'Add New Event', 'syntric' ),
			'new_item'              => __( 'New Event', 'syntric' ),
			'edit_item'             => __( 'Edit Event', 'syntric' ),
			'view_item'             => __( 'View Event', 'syntric' ),
			'all_items'             => __( 'All Events', 'syntric' ),
			'search_items'          => __( 'Search Events', 'syntric' ),
			'parent_item_colon'     => __( 'Parent Events:', 'syntric' ),
			'not_found'             => __( 'No events found.', 'syntric' ),
			'not_found_in_trash'    => __( 'No events found in Trash.', 'syntric' ),
			'archives'              => _x( 'Event archives', 'syntric' ),
			'insert_into_item'      => _x( 'Insert into event', 'syntric' ),
			'uploaded_to_this_item' => _x( 'Uploaded to this event', 'syntric' ),
			'filter_items_list'     => _x( 'Filter events list', 'syntric' ),
			'items_list_navigation' => _x( 'Events list navigation', 'syntric' ),
			'items_list'            => _x( 'Events list', 'syntric' ),
		];
		$args   = [
			'labels'              => $labels,
			'public'              => false,
			'exclude_from_search' => false,
			'publicly_queryable'  => true,
			'capability_type'     => 'post',
			'has_archive'         => true,
			'map_meta_cap'        => true,
			'menu_position'       => 7,
			'hierarchical'        => false,
			'delete_with_user'    => false,
			'supports'            => [
				'title',
				'editor',
			],
			'show_in_rest'        => true,
			'rest_base'           => 'events',
			'can_export'          => true,
			'show_ui'             => true,
			//'show_in_menu'       => true,
			'show_in_menu'        => 'edit.php?post_type=syn_calendar',
			'show_in_nav_menus'  => false,
			'show_in_admin_bar'  => false,
			'query_var'          => false,
			'rewrite'            => [ 'slug'       => 'events',
			                          'with_front' => true,
			],
			'redirect'           => true,
		];
		register_post_type( 'syn_event', $args );
	}

	/**
	 * Add posts lists dropdown for filtering
	 */
// Using filter from Admin Columns Pro
//add_action( 'restrict_manage_posts', 'syn_calendar_restrict_manage_posts', 10 );
	function syn_calendar_restrict_manage_posts() {
		global $post_type;
		if ( is_admin() && $post_type == 'syn_event' ) {
			$filter_dropdown        = '<select name="events_calendar_filter">' . "\n";
			$events_calendar_filter = 0;
			if ( isset( $_GET[ 'events_calendar_filter' ] ) ) {
				$events_calendar_filter = (int) sanitize_text_field( $_GET[ 'events_calendar_filter' ] );
			}
			$filter_dropdown .= "\t" . '<option value="0">All calendars</option>' . "\n";
			$calendar_ids    = syn_get_calendar_ids();
			foreach ( $calendar_ids as $calendar_id ) {
				$filter_dropdown .= "\t" . '<option value="' . $calendar_id . '"';
				$filter_dropdown .= ( $events_calendar_filter == $calendar_id ) ? 'selected' : '';
				$filter_dropdown .= '>' . get_the_title( $calendar_id ) . '</option>' . "\n";
			}
			$filter_dropdown .= '</select>' . "\n";
			echo $filter_dropdown;
		}
	}

	/**
	 * On save calendar sync from Google and schedule future syncs
	 */
	add_action( 'acf/save_post', 'syn_save_calendar', 20 );
	function syn_save_calendar( $post_id ) {
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
					syn_sync_calendar( [ 'post_id'   => $post_id,
					                     'post_type' => 'syn_calendar',
										 'force_sync' => true,
					] );
					update_field( 'syn_calendar_sync_now', 0, $post_id );
				}
			}
		}
	}

	/**
	 * Sync Google Calendar as WP posts
	 */
	add_action( 'syn_calendar_sync', 'syn_sync_calendar' );
	function syn_sync_calendar( $args ) {
		//slog( $args );
		$calendar_id = $args[ 'post_id' ];
		$log         = '';
		$lb          = "\n";
		$log         .= 'Sync started with values ' . $lb . print_r( $args, true );
		update_field( 'syn_calendar_last_sync_result', $log, $calendar_id );
		// bail if $sync_args doesn't contain both post_type and post_id or if post_type isn't syn_calendar
		if ( ! $args[ 'post_type' ] || ! $args[ 'post_id' ] || $args[ 'post_type' ] != 'syn_calendar' ) {
			$log .= $lb . 'Invalid args. Exited sync at ' . date( 'n/j/Y g:i A' ) . '.';
			update_field( 'syn_calendar_last_sync_result', $log, $calendar_id );

			return;
		}
		$calendar_fields = get_fields( $calendar_id );
		//slog( $calendar_fields );
		// bail if no calendar postmeta
		if ( ! $calendar_fields ) {
			$log .= $lb . 'Calendar settings not found. Exited sync at ' . date( 'n/j/Y g:i A' ) . '.';
			update_field( 'syn_calendar_last_sync_result', $log, $calendar_id );

			return;
		}
		// bail if the minimum fields aren't present (api_url, api_key, calendar_id)
		$api_key  = get_field( 'syn_google_api_key', 'option' );
		$api_url  = get_field( 'syn_google_calendar_api_url', 'option' );
		$calendar = $calendar_fields[ 'syn_calendar_id' ];
		if ( ! $api_key || ! $api_url || ! $calendar ) {
			$log .= $lb . 'One or more required values were absent. Exited sync at ' . date( 'n/j/Y g:i A' ) . '.';
			update_field( 'syn_calendar_last_sync_result', $log, $calendar_id );

			return;
		}
		//$sync_range = ( isset( $calendar_fields[ 'syn_calendar_sync_range' ] ) ) ? (string) $calendar_fields[ 'syn_calendar_sync_range' ] : '1 year';
		$sync_range = '2 years';
		$last_sync  = ( isset( $calendar_fields[ 'syn_calendar_last_sync' ] ) ) ? $calendar_fields[ 'syn_calendar_last_sync' ] : '';
		// todo: implement delete events
		//if ( isset( $calendar_fields[ 'syn_calendar_delete_events' ] ) && isset( $calendar_fields[ 'syn_calendar_delete_after' ] ) ) {
			//$delete_events = ( $calendar_fields[ 'syn_calendar_delete_events' ] ) ? $calendar_fields[ 'syn_calendar_delete_events' ] : false;
			//$delete_after = ( $calendar_fields[ 'syn_calendar_delete_after' ] ) ? $calendar_fields[ 'syn_calendar_delete_after' ] : '';
		//}
		$force_sync = ( isset( $args['force_sync'] ) && $args['force_sync'] ) ? true : false;
		$time_min = date_create();
		//date_sub( $time_min, date_interval_create_from_date_string( '1 day' ) ); // take off a day for good measure
		$time_min = date_format( $time_min, 'c' );
		$time_max = date_create();
		date_add( $time_max, date_interval_create_from_date_string( $sync_range ) );
		$time_max = date_format( $time_max, 'c' );
		$url      = trailingslashit( $api_url ) . $calendar . '/events?key=' . $api_key;
		$url      .= '&singleEvents=true&orderBy=startTime&maxResults=200';
		$url      .= '&timeMin=' . $time_min . '&timeMax=' . $time_max;
		$log      .= $lb . 'Url contructed as: ' . $url;
		update_field( 'syn_calendar_last_sync_result', $log, $calendar_id );
		$response = wp_remote_get( $url );
		if ( $response ) { // received response
			$log .= $lb . 'Response received from remote host';
			update_field( 'syn_calendar_last_sync_result', $log, $calendar_id );
			$body = wp_remote_retrieve_body( $response );
			$body = json_decode( $body );
			if ( property_exists( $body, 'error' ) ) {
				$log .= $lb . 'Response from remote host was an error';
				update_field( 'syn_calendar_last_sync_result', $log, $calendar_id );
				$error  = $body->error;
				$errors = $error->errors;
				foreach ( $errors as $_error ) {
					$reason = $_error->reason;
				}
				if ( $reason ) {
					$log .= $lb . 'Response from remote host errored with reason: ' . $reason;
					update_field( 'syn_calendar_last_sync_result', $log, $calendar_id );
				} else {
					$log .= $lb . 'Response from remote host errored but no reason could be found';
					update_field( 'syn_calendar_last_sync_result', $log, $calendar_id );
				}

				return;
			}
		} else { // did not receive response
			$log .= $lb . 'No response received from remote server. Exited sync at ' . date( 'njY g:i A' ) . '.';
			update_field( 'syn_calendar_last_sync_result', $log, $calendar_id );

			return;
		}
		if ( ! isset( $last_sync ) || empty( $last_sync ) || $force_sync ) {
			$refresh = true;
		} else {
			$local_last_updated     = date_create( $last_sync );
			$remote_last_updated    = date_create( $body->updated );
			$remote_timezone_offset = timezone_offset_get( timezone_open( date_default_timezone_get() ), $remote_last_updated );
			$remote_last_updated    = date_add( $remote_last_updated, date_interval_create_from_date_string( $remote_timezone_offset . ' seconds' ) );
			//slog( $local_last_updated);
			//slog( $remote_last_updated);
			$refresh                = date_format( $local_last_updated, 'YmdHi' ) < date_format( $remote_last_updated, 'YmdHi' );
		}
		// Google calendar hasn't been updated since the last sync
		if ( ! $refresh ) {
			//$log .= $lb . 'Local calendar last updated: ' . $local_last_updated->date . $lb . 'Remote calendar last updated: ' . $remote_last_updated->date . $lb . 'Remote calendar has not been updated since last sync. Exiting sync.';
			$log .= $lb . 'Local calendar last updated: ' . date_format( $local_last_updated, 'YmdHi' ) . $lb . 'Remote calendar last updated: ' . date_format( $remote_last_updated, 'YmdHi' ) . $lb . 'Remote calendar has not been updated since last sync. Exiting sync.';
			update_field( 'syn_calendar_last_sync', date( 'r' ), $calendar_id );
			update_field( 'syn_calendar_last_sync_result', $log, $calendar_id );

			return;
			//
		} else {
			$log .= $lb . 'Remote calendar has been updated since last sync, syncing';
			update_field( 'syn_calendar_last_sync_result', $log, $calendar_id );
		}
		// purge events that start on or before $time_min
		$time_min_array = explode( 'T', $time_min );
		$time_min_date  = $time_min_array[ 0 ];
		$date_min       = date_create( $time_min_date );
		$start_date_min = date_format( $date_min, 'Ymd' );
		// todo: this is the change that addresses the duplication of multi-day events on sync...changed key on first meta_query condition to syn_event_end_date...was start date
		$events_args    = [
			'numberposts' => - 1,
			'post_type'   => 'syn_event',
			'post_status' => 'publish',
			'meta_query'  => [
				'relation' => 'AND',
				[
					'key'     => 'syn_event_end_date',
					'value'   => $start_date_min,
					'compare' => '>=',
				],
				[
					'key'     => 'syn_event_calendar_id',
					'value'   => $calendar_id,
					'compare' => '=',
				],
			],
		];
		$events         = get_posts( $events_args );
		$log            .= $lb . 'Check for events to delete';
		update_field( 'syn_calendar_last_sync_result', $log, $calendar_id );
		if ( count( $events ) ) {
			$log .= $lb . 'Events need to be deleted';
			update_field( 'syn_calendar_last_sync_result', $log, $calendar_id );
			foreach ( $events as $event ) {
				$del_result = wp_delete_post( $event->ID, true );
				if ( ! $del_result ) {
					$log .= $lb . 'Failed to delete event: ' . $event->ID;
					update_field( 'syn_calendar_last_sync_result', $log, $calendar_id );
				} else {
					$log .= $lb . 'Deleted event: ' . $event->ID;
					update_field( 'syn_calendar_last_sync_result', $log, $calendar_id );
				}
			}
			$log .= $lb . 'Finished deleting events';
			update_field( 'syn_calendar_last_sync_result', $log, $calendar_id );
		} else {
			$log .= $lb . 'No events need to be deleted';
			update_field( 'syn_calendar_last_sync_result', $log, $calendar_id );
		}
		wp_reset_postdata(); // for good measure
		$_event_field_groups = acf_get_field_groups();
		$event_field_groups  = [];
		// loop through field groups
		foreach ( $_event_field_groups as $_event_field_group ) {
			// loop over location groups
			if ( ! empty( $_event_field_group[ 'location' ] ) ) {
				foreach ( $_event_field_group[ 'location' ] as $location_group_id => $location_group ) {
					// loop over group rules
					if ( ! empty( $location_group ) ) {
						foreach ( $location_group as $rule_id => $rule ) {
							if ( $rule[ 'param' ] == 'post_type' && $rule[ 'operator' ] == '==' && $rule[ 'value' ] == 'syn_event' ) {
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
		foreach ( $event_field_groups as $event_field_group ) {
			$event_field_group_fields = acf_get_fields( $event_field_group[ 'key' ] );
			$_event_fields            = array_merge( $_event_fields, $event_field_group_fields );
		}
		$event_fields = [];
		foreach ( $_event_fields as $_event_field ) {
			if ( ! empty( $_event_field[ 'name' ] ) && $_event_field[ 'type' ] != 'row' && $_event_field[ 'type' ] != 'enhanced_message' ) {
				$event_fields[] = [
					'key'   => $_event_field[ 'key' ],
					'name'  => $_event_field[ 'name' ],
					'label' => $_event_field[ 'label' ],
					'type'  => $_event_field[ 'type' ],
				];
			}
		}
		$log .= $lb . 'Collected event meta data to update';
		update_field( 'syn_calendar_last_sync_result', $log, $calendar_id );
		$events      = $body->items;
		$event_count = 0;
		foreach ( $events as $event ) {
			$args     = [
				'post_content' => ( isset( $event->description ) ) ? $event->description : '',
				'post_title'   => ( isset( $event->summary ) ) ? $event->summary : '',
				'post_status'  => 'publish',
				'post_type'    => 'syn_event',
			];
			$event_id = wp_insert_post( $args );
			if ( $event_id ) {
				$log .= $lb . 'Event inserted: ' . $event_id;
				update_field( 'syn_calendar_last_sync_result', $log, $calendar_id );
				foreach ( $event_fields as $event_field ) {
					$value = '';
					switch ( $event_field[ 'name' ] ) {
						case 'syn_event_start_date':
							$value = ( isset( $event->start->dateTime ) ) ? $event->start->dateTime : $event->start->date;
							$value = syn_convert_date_string_to_meta( $value );
							break;
						case 'syn_event_end_date':
							$value = ( isset( $event->end->dateTime ) ) ? $event->end->dateTime : $event->end->date;
							$value = syn_convert_date_string_to_meta( $value );
							break;
						case 'syn_event_start_time':
							$value = ( isset( $event->start->dateTime ) ) ? syn_convert_time_string_to_meta( $event->start->dateTime ) : '';
							break;
						case 'syn_event_end_time':
							$value = ( isset( $event->end->dateTime ) ) ? syn_convert_time_string_to_meta( $event->end->dateTime ) : '';
							break;
						case 'syn_event_location':
							$value = ( isset( $event->location ) ) ? $event->location : '';
							break;
						case 'syn_event_calendar_id':
							$value = $calendar_id;
							break;
						case 'syn_event_event_id':
							$value = $event->id;
							break;
						case 'syn_event_recurring_id':
							$value = ( isset( $event->recurringEventId ) ) ? $event->recurringEventId : '';
							break;
						case 'syn_event_last_sync':
							$value = date( 'F j, Y g:i A' );
							break;
					}
					update_field( $event_field[ 'key' ], $value, $event_id );
				}
				$log .= $lb . 'Event ' . $event_id . ' meta data updated';
				update_field( 'syn_calendar_last_sync_result', $log, $calendar_id );
			} else {
				$log .= $lb . 'Event ' . $event_id . ' meta data failed to update';
				update_field( 'syn_calendar_last_sync_result', $log, $calendar_id );
			}
			$event_count = $event_count + 1;
		}
		update_field( 'syn_calendar_last_sync', date( 'F j, Y g:i A' ), $calendar_id );
		$log .= $lb . 'Sync completed. ' . $event_count . ' events were synced.';
		update_field( 'syn_calendar_last_sync_result', $log, $calendar_id );
		update_field( 'syn_calendar_event_count', $event_count, $calendar_id );

		//syn_write_calendar_json_file( $calendar_id );
		return true;
	}

	/**
	 * Delete calendar - remove sync schedules (cron) and delete events
	 * todo: is this working?  not sure cron schedule is getting deleted properly...
	 */
	add_action( 'before_delete_post', 'syn_before_delete_calendar' );
	function syn_before_delete_calendar( $post_id ) {
		$post_id = syn_resolve_post_id( $post_id );
		//slog( 'syn_before_delete_calendar triggered...................................................................' );
		$post = get_post( $post_id );
		if ( 'syn_calendar' == $post->post_type ) {
			// Remove any sync schedules
			wp_clear_scheduled_hook( 'syn_calendar_sync', [ 'post_id'   => $post_id,
			                                                'post_type' => 'syn_calendar',
			] );
			$event_ids = syn_get_calendar_events( $post_id, null, 'all', - 1, 'ids' );
			if ( $event_ids ) {
				foreach ( $event_ids as $event_id ) {
					wp_delete_post( $event_id, true );
				}
			}
		}
	}

	/**
	 * AJAX handler fetching public and private and calendars
	 */
	add_action( 'wp_ajax_nopriv_syn_fetch_calendar_events', 'syn_fetch_calendar_events' );
	add_action( 'wp_ajax_syn_fetch_calendar_events', 'syn_fetch_calendar_events' );
	function syn_fetch_calendar_events() {
		check_ajax_referer( 'syn_fetch_calendar_events' );
		$args   = [
			'numberposts'      => - 1,
			'post_type'        => 'syn_event',
			'post_status'      => 'publish',
			'suppress_filters' => false,
			'no_found_rows'    => true,
			'meta_key'         => 'syn_event_calendar_id',
			'meta_value'       => $_REQUEST[ 'post_id' ],
			'meta_compare'     => '=',
		];
		$events = get_posts( $args );
		if ( $events ) {
			$event_details = [];
			foreach ( $events as $event ) {
				$start_date          = get_field( 'syn_event_start_date', $event->ID );
				$start_time          = get_field( 'syn_event_start_time', $event->ID );
				$end_date            = get_field( 'syn_event_end_date', $event->ID );
				$end_time            = get_field( 'syn_event_end_time', $event->ID );
				$start_datetime      = date_create( $start_date . ' ' . $start_time );
				$end_datetime        = date_create( $end_date . ' ' . $end_time );
				$location            = get_field( 'syn_event_location', $event->ID );
				$google_calendar_id  = get_field( 'syn_event_calendar_id', $event->ID );
				$google_event_id     = get_field( 'syn_event_event_id', $event->ID );
				$google_recurring_id = get_field( 'syn_event_recurring_id', $event->ID );
				$google_event_id     = ( $google_recurring_id ) ? explode( '_', $google_event_id ) : $google_event_id;
				$google_event_id     = ( is_array( $google_event_id ) ) ? $google_event_id[ 0 ] : $google_event_id;
				$event_details[]     = [
					'title'               => $event->post_title,
					'allDay'              => ( empty( $start_time ) ) ? true : false,
					'start'               => date_format( $start_datetime, 'c' ),
					'end'                 => date_format( $end_datetime, 'c' ),
					'url'                 => ( syn_has_content( $event->post_content ) ) ? get_the_permalink( $event->ID ) : null,
					'description'         => $event->post_content,
					'location'            => $location,
					'google_event_id'     => $google_event_id,
					'google_recurring_id' => $google_recurring_id,
				];
			}
			wp_send_json( $event_details );
		}
		wp_send_json( [] );
		wp_die();
	}

//
//
// Filters
//
//
	add_filter( 'acf/prepare_field/name=syn_calendar_last_sync', 'syn_prepare_calendar_fields' );
	add_filter( 'acf/prepare_field/name=syn_calendar_last_sync_result', 'syn_prepare_calendar_fields' );
	function syn_prepare_calendar_fields( $field ) {
		global $pagenow;
		global $post;
		if ( is_admin() && 'post.php' == $pagenow && 'syn_calendar' == $post->post_type ) {
			if ( 'syn_calendar_last_sync' == $field[ '_name' ] ) {
				$field[ 'disabled' ] = true;
			}
			if ( 'syn_calendar_last_sync_result' == $field[ '_name' ] ) {
				$field[ 'disabled' ] = true;
			}
		}

		return $field;
	}

	/**
	 * Sort and filter syn_calendar and syn_event post lists in the admin
	 */
	add_filter( 'pre_get_posts', 'syn_calendar_pre_get_posts', 1, 1 );
	function syn_calendar_pre_get_posts( $query ) {
		global $pagenow;
		// only for admin "list" screens
		if ( is_admin() && $query->is_main_query() && $pagenow == 'edit.php' ) {
			if ( $query->get( 'post_type' ) == 'syn_event' || $query->get( 'post_type' ) == 'syn_calendar' ) {
				$meta_query = $query->get( 'meta_query' );
				if ( isset( $_GET[ 'orderby' ] ) ) {
					$orderby = sanitize_text_field( $_GET[ 'orderby' ] );
					if ( $query->get( 'post_type' ) == 'syn_event' ) {
						switch ( $orderby ) {
							case 'syn_event_start_date':
								$query->set( 'meta_key', 'syn_event_start_date' );
								$query->set( 'orderby', 'meta_value_num' );
								break;
							case 'syn_event_end_date':
								$query->set( 'meta_key', 'syn_event_end_date' );
								$query->set( 'orderby', 'meta_value_num' );
								break;
							case 'syn_event_calendar_id':
								$query->set( 'meta_key', 'syn_event_calendar_id' );
								$query->set( 'orderby', 'meta_value_num' );
								break;
						}
					}
					/*
					 * Post type calendar use prefix "syn_calendar_"
					 */
					if ( $query->get( 'post_type' ) == 'syn_calendar' ) {
						switch ( $orderby ) {
							case 'syn_calendar_event_count':
								$query->set( 'meta_key', 'syn_calendar_event_count' );
								$query->set( 'orderby', 'meta_value_num' );
								break;
							/*case 'syn_calendar_source':
								$query->set( 'meta_key', 'syn_calendar_source' );
								$query->set( 'orderby', 'meta_value' );
								break;*/
							case 'syn_calendar_sync':
								$query->set( 'meta_key', 'syn_calendar_sync' );
								$query->set( 'orderby', 'meta_value_num' );
								break;
							case 'last_sync':
								$query->set( 'meta_key', 'syn_calendar_last_sync' );
								$query->set( 'orderby', 'meta_value' );
								break;
						}
					}
					$order = sanitize_text_field( $_GET[ 'order' ] );
					$query->set( 'order', $order );
				} else {
					if ( $query->get( 'post_type' ) == 'syn_event' ) {
						$query->set( 'meta_key', 'syn_event_start_date' );
						$query->set( 'orderby', [
							'meta_value_num' => 'DESC',
							'title'          => 'ASC',
						] );
						$query->unset( 'order', 'ASC' );
					}
					if ( $query->get( 'post_type' ) == 'syn_calendar' ) {
						//$query->set( 'meta_key', 'syn_calendar_primary' );
						$query->set( 'orderby', [
							//'meta_value_num' => 'DESC',
							'title' => 'ASC',
						] );
						$query->unset( 'order', 'ASC' );
					}
				}
				if ( isset( $_GET[ 'events_calendar_filter' ] ) && 0 != $_GET[ 'events_calendar_filter' ] ) {
					/*
					 * Filter list by adding a meta query to $query
					 */
					$syn_event_calendar_id = (int) $_GET[ 'events_calendar_filter' ];
					$meta_query[]          = [
						'key'     => 'syn_event_calendar_id',
						'value'   => $syn_event_calendar_id,
						'compare' => '=',
					];
					$query->set( 'meta_query', $meta_query );
				}
			}
		}

		return $query;
	}

	add_filter( 'months_dropdown_results', 'syn_calendar_months_dropdown_results', 10, 2 );
	function syn_calendar_months_dropdown_results( $months, $post_type ) {
		if ( $post_type == 'syn_calendar' || $post_type = 'syn_event' ) {
			return [];
		}

		return $months;
	}

//
//
// Functions
//
//
	function syn_schedule_calendar_sync( $post_id ) {
		$schedule_args = [
			'post_id'                  => $post_id,
		                   'post_type' => 'syn_calendar',
		];
		$sync          = get_field( 'syn_calendar_sync', $post_id );
		if ( $sync ) {
			//$frequency = get_field( 'syn_calendar_sync_frequency', $post_id );
			$frequency = 'daily';
			wp_clear_scheduled_hook( 'syn_calendar_sync', [ $schedule_args ] );
			wp_schedule_event( time(), $frequency, 'syn_calendar_sync', [ $schedule_args ] );
			update_field( 'syn_calendar_last_sync', date( 'F j, Y g:i A' ), $post_id );
			update_field( 'syn_calendar_last_sync_result', 'Sync schedule updated', $post_id );
		} else {
			wp_clear_scheduled_hook( 'syn_calendar_sync', [ $schedule_args ] );
			update_field( 'syn_calendar_last_sync', date( 'F j, Y g:i A' ), $post_id );
			update_field( 'syn_calendar_last_sync_result', 'Not scheduled to sync', $post_id );
			delete_field( 'syn_calendar_sync_frequency', $post_id );
			delete_field( 'syn_calendar_sync_range', $post_id );
			delete_field( 'syn_calendar_delete_events', $post_id );
			delete_field( 'syn_calendar_delete_after', $post_id );
		}
	}

	function syn_get_calendar_next_scheduled( $post_id ) {
		$post_id        = syn_resolve_post_id( $post_id );
		$next_scheduled = wp_next_scheduled( 'syn_calendar_sync', [
			[
				'post_id'   => $post_id,
				'post_type' => 'syn_calendar',
			],
		] );

		return $next_scheduled;
	}

	function syn_get_calendar_sync_schedule( $post_id ) {
		$post_id  = syn_resolve_post_id( $post_id );
		$schedule = wp_get_schedule( 'syn_calendar_sync', [
			[
				'post_id'   => $post_id,
				'post_type' => 'syn_calendar',
			],
		] );

		return $schedule;
	}

	function syn_get_calendar_last_sync( $post_id ) {
		$post_id   = syn_resolve_post_id( $post_id );
		$sync      = get_field( 'syn_calendar_sync', $post_id );
		$last_sync = get_field( 'syn_calendar_last_sync', $post_id );
		if ( ! $sync || empty( $last_sync ) ) {
			return false;
		} else {
			$minutes_ago = syn_calculate_time_difference( date_create(), $last_sync, 'minutes' );

			return (int) $minutes_ago;
		}
	}

	function syn_get_calendar_next_sync( $post_id ) {
		$post_id   = syn_resolve_post_id( $post_id );
		$sync      = get_field( 'syn_calendar_sync', $post_id );
		$next_sync = syn_get_calendar_next_scheduled( $post_id );
		if ( ! $sync || empty( $next_sync ) ) {
			return false;
		} else {
			$in_minutes = syn_calculate_time_difference( date_create(), date( 'c', $next_sync ), 'minutes' );

			return $in_minutes;
		}
	}

	function syn_get_calendar_ids() {
		$args = [
			'numberposts'      => - 1,
			'post_type'        => 'syn_calendar',
			'post_status'      => 'publish',
			'orderby'          => [ 'post_title' => 'ASC' ],
			'suppress_filters' => false,
			'no_found_rows'    => true,
			'fields'           => 'ids',
		];
		add_filter( 'posts_distinct', 'syn_posts_distinct' );
		$post_ids = get_posts( $args );
		remove_filter( 'posts_distinct', 'syn_posts_distinct' );
		//wp_reset_postdata();
		//wp_reset_query();
		return $post_ids;
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
	function syn_get_calendar_events( $post_id, $ref_date = null, $range = 'month', $numberposts = - 1, $fields = '*' ) {
		$post_id  = syn_resolve_post_id( $post_id );
		$calendar = get_post( $post_id );
		if ( ! $calendar instanceof WP_Post || 'syn_calendar' != $calendar->post_type ) {
			return;
		}
		$ref_date    = ( isset( $ref_date ) ) ? $ref_date : date( 'Ymd' );
		$range       = ( isset( $range ) ) ? $range : 'month';
		$numberposts = ( isset( $numberposts ) ) ? (int) $numberposts : - 1;
		// Get all events, ignore $ref_date and $numberposts
		if ( 'all' == $range ) {
			$args = [
				'numberposts'      => - 1,
				'post_type'        => 'syn_event',
				'post_status'      => 'publish',
				'meta_query'       => [
					[
						'key'     => 'syn_event_calendar_id',
						'value'   => $post_id,
						'compare' => '=',
					],
				],
				'meta_key'         => 'syn_event_start_date',
				'orderby'          => 'meta_value_num',
				'order'            => 'ASC',
				'suppress_filters' => false,
				'no_found_rows'    => true,
			];
			if ( '*' != $fields ) {
				$args[ 'fields' ] = $fields;
			}
		} else {
			$calendar_date = date_create( $ref_date );
			$meta_query    = [];
			$meta_query[]  = [
				[
					'key'     => 'syn_event_calendar_id',
					'value'   => $post_id,
					'compare' => '=',
				],
			];
			switch ( $range ) {
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
			if ( isset( $end_date ) ) {
				$meta_query[] = [
					'key'     => 'syn_event_end_date',
					'value'   => $end_date,
					'compare' => '<=',
				];
			};
			$args = [
				'numberposts'      => $numberposts,
				'post_type'        => 'syn_event',
				'post_status'      => 'publish',
				'meta_query'       => $meta_query,
				'meta_key'         => 'syn_event_start_date',
				'meta_value'       => $start_date,
				'meta_compare'     => '>=',
				'orderby'          => 'meta_value_num',
				'order'            => 'ASC',
				'suppress_filters' => false,
				'no_found_rows'    => true,
			];
			if ( '*' != $fields ) {
				$args[ 'fields' ] = $fields;
			}
		}
		add_filter( 'posts_distinct', 'syn_posts_distinct' );
		$events = get_posts( $args );
		remove_filter( 'posts_distinct', 'syn_posts_distinct' );

		return $events;
	}

	function syn_calculate_time_difference( $date_time_1 = null, $date_time_2 = null, $time_unit = 'minutes' ) {
		if ( ! isset( $date_time_1 ) || ! isset( $date_time_2 ) ) {
			return;
		}
		if ( ! is_a( $date_time_1, 'DateTime' ) ) {
			$date_time_1 = date_create( $date_time_1 );
		}
		if ( ! is_a( $date_time_2, 'DateTime' ) ) {
			$date_time_2 = date_create( $date_time_2 );
		}
		$interval = date_diff( $date_time_1, $date_time_2 );
		switch ( $time_unit ) {
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

	function syn_convert_date_string_to_meta( $date_str ) {
		// date string to YYYYMMDD
		$date_obj  = date_create( $date_str );
		$date_meta = date_format( $date_obj, 'Ymd' );

		return $date_meta;
	}

	function syn_convert_time_string_to_meta( $time_str ) {
		// date/time string to YYYYMMDD
		$date_obj  = date_create( $time_str );
		$time_meta = date_format( $date_obj, 'g:i A' );

		return $time_meta;
	}

	function syn_get_event_dates( $post_id ) {
		$post_id    = syn_resolve_post_id( $post_id );
		$start_date = get_field( 'syn_event_start_date', $post_id );
		$end_date   = get_field( 'syn_event_end_date', $post_id );
		$start_time = get_field( 'syn_event_start_time', $post_id );
		$end_time   = get_field( 'syn_event_end_time', $post_id );
		$start_date = date_create( $start_date );
		$end_date   = date_create( $end_date );
		$dates      = date_format( $start_date, 'F j, Y' );
		if ( $start_date != $end_date ) {
			if ( empty( $start_time ) || empty( $end_time ) ) {
				$days_diff = syn_calculate_time_difference( $start_date, $end_date, 'days' );
				if ( $days_diff > 1 ) {
					date_sub( $end_date, date_interval_create_from_date_string( '1 day' ) );
					$dates .= ' - ' . date_format( $end_date, 'F j, Y' );
				}
			} else {
				$dates .= ' - ' . date_format( $end_date, 'F j, Y' );
			}
		}
		if ( ! empty( $start_time ) ) {
			$dates .= ' ' . $start_time;
		}
		if ( ! empty( $end_time ) && $start_time != $end_time ) {
			$dates .= '-' . $end_time;
		}

		return $dates;
	}

	function syn_event_date_time_display( $event_id, $date_type, $date_format = '' ) {
		if ( ! isset( $event_id ) || ! isset( $date_type ) ) {
			return false;
		}
		$date_format = ( ! empty( $date_format ) ) ? $date_format : 'F j, Y';
		$date_field  = '';
		$time_field  = '';
		$ret         = '';
		if ( 'start' == $date_type || 'start_date' == $date_type ) {
			$date_field = 'syn_event_start_date';
			$time_field = 'syn_event_start_time';
		} elseif ( 'end' == $date_type || 'end_date' == $date_type ) {
			$date_field = 'syn_event_end_date';
			$time_field = 'syn_event_end_time';
		}
		if ( empty( $date_field ) || empty( $time_field ) ) {
			return false;
		}
		$event_date = get_field( $date_field, $event_id, false );
		$event_date = date_create_from_format( 'Ymd', $event_date );
		if ( ! is_a( $event_date, 'DateTime' ) ) {
			return false;
		}
		$ret        = date_format( $event_date, $date_format );
		$event_time = get_field( $time_field, $event_id, true );
		if ( $event_time && ! empty( $event_time ) ) {
			$ret .= ' @ ' . $event_time;
		}

		return $ret;
	}

	function syn_posts_distinct() {
		return 'DISTINCT';
	}

	function syn_get_calendars() {
		$args      = [
			'numberposts'      => - 1,
			'post_type'        => 'syn_calendar',
			'post_status'      => 'publish',
			'orderby'          => [ 'post_title' => 'ASC' ],
			'suppress_filters' => false,
			'no_found_rows'    => true,
		];
		$calendars = get_posts( $args );

		return $calendars;
	}

//
//
// Bone yard...be careful here...
//
//
	function ________notinuse______syn_render_accessible_calendar( $post_id = null, $ref_date = null ) {
		$post_id           = syn_resolve_post_id( $post_id );
		$ref_date          = ( isset( $ref_date ) ) ? (int) $ref_date : date( 'Ymd' );
		$range             = 'month';
		$calendar_date     = date_create( (int) $ref_date );
		$calendar_title    = date_format( $calendar_date, 'F Y' );
		$previous_month    = date_sub( $calendar_date, date_interval_create_from_date_string( '1 month' ) );
		$previous_ref_date = date_format( $previous_month, 'Ym01' );
		$next_month        = date_add( $calendar_date, date_interval_create_from_date_string( '2 months' ) );
		$next_ref_date     = date_format( $next_month, 'Ym01' );
		$event_ids         = syn_get_calendar_events( $post_id, $ref_date, $range, '-1', 'ids' );
		?>
		<nav class="calendar-nav">
			<div class="row">
				<div class="col-2">
					<a id="previous-month-link" title="Previous Month" href="<?php /*echo get_the_permalink() . '?ref_date=' . $previous_ref_date; */ ?>"><i class="fa fa-angle-left" aria-hidden="true"></i><span class="sr-only">Previous Month</span></a>
				</div>
				<div class="col text-center">
					<h3 class="calendar-nav-title"><?php echo $calendar_title; ?></h3>
				</div>
				<div class="col-2 text-right">
					<a id="next-month-link" title="Next Month" href="<?php /*echo get_the_permalink() . '?ref_date=' . $next_ref_date; */ ?>"><span class="sr-only">Next Month</span><i class="fa fa-angle-right" aria-hidden="true"></i></a>
				</div>
			</div>
		</nav>
		<?php
		if ( $event_ids ) {
			foreach ( $event_ids as $event_id ) {
				setup_postdata( $event_id );
				get_template_part( 'loop-templates/content-excerpt' );
			}
			wp_reset_postdata();
		} else {
			if ( 'year' == $range || 'month' == $range ) {
				echo '<div class="no-content">No events available for this ' . $range;
			} else {
				echo '<div class="no-content">No events</div>';
			}
		}
	}

	function _________notinuse_____syn_render_calendar( $post_id ) {
		$post_id  = syn_resolve_post_id( $post_id );
		$calendar = get_post( $post_id );
		if ( $calendar instanceof WP_Post ) {
			$ref_date                   = ( isset( $_GET[ 'ref_date' ] ) ) ? (int) $_GET[ 'ref_date' ] : date( 'Ymd' );
			$range                      = ( isset( $_GET[ 'range' ] ) ) ? $_GET[ 'range' ] : 'month';
			$view                       = ( isset( $_GET[ 'view' ] ) ) ? $_GET[ 'view' ] : 'grid';
			switch ( $view ) :
				case 'grid':
					$selector_id = 'calendar-' . $post_id;
					$google_calendar_id = get_field( 'syn_calendar_id', $post_id );
					$google_api_key     = get_field( 'syn_google_api_key', 'option' );
					$calendar_title     = $calendar->post_title;
					$args               = [
						$post_id,
						$selector_id,
						$view,
						$google_calendar_id,
						$google_api_key,
						$calendar_title,
					];
					$args               = implode( "','", $args );
					?>
					<div id="<?php echo $selector_id; ?>" class="calendar-<?php echo $view; ?>">
						<script type="text/javascript">
							(function ($) {
								renderCalendar('<?php echo $args; ?>');
							})(jQuery);
						</script>
					</div>
					<?php
					break;
			endswitch;
		}

		return;
	}

	/**
	 * Set up values for syn_calendar posts list custom columns
	 */
//add_action( 'manage_syn_calendar_posts_custom_column', 'syn_calendar_manage_posts_custom_column', 10, 2 );
	function ________notinuse__________syn_calendar_manage_posts_custom_column( $column, $post_id ) {
		switch ( $column ) {
			case 'syn_calendar_event_count':
				$event_count = get_field( 'syn_calendar_event_count', $post_id );
				$event_link  = '<a href="' . admin_url() . 'edit.php?s&post_type=syn_event&action=-1&events_calendar_filter=' . $post_id . '&filter_action=Filter&action2=-1' . '">';
				$event_link  .= $event_count;
				$event_link  .= '</a>';
				echo $event_link;
				break;
			/*case 'syn_calendar_source':
				echo get_field( 'syn_calendar_source', $post_id, true );
				break;*/
			case 'syn_calendar_sync':
				$sync_schedule = syn_get_calendar_sync_schedule( $post_id );
				echo ( $sync_schedule ) ? ucwords( $sync_schedule ) : 'No';
				break;
			case 'last_sync':
				$last_sync = syn_get_calendar_last_sync( $post_id );
				if ( is_numeric( $last_sync ) ) {
					echo ( $last_sync === 1 ) ? '1 minute ago' : $last_sync . ' minutes ago';
				} else {
					echo 'Unsynced';
				}
				break;
			case 'next_sync':
				$next_sync = syn_get_calendar_next_sync( $post_id );
				if ( is_numeric( $next_sync ) ) {
					echo ( $next_sync === 1 ) ? 'In 1 minute' : 'In ' . $next_sync . ' minutes';
				} else {
					echo 'Unscheduled';
				}
				break;
		}
	}

	/**
	 * Set up values for syn_events posts list custom columns
	 */
//add_action( 'manage_syn_event_posts_custom_column', 'syn_event_manage_posts_custom_column', 10, 2 );
	function ________notinuse__________syn_event_manage_posts_custom_column( $column, $post_id ) {
		switch ( $column ) {
			case 'syn_event_start_date':
				$start_date_time = syn_event_date_time_display( $post_id, 'start', 'F j, Y' );
				echo $start_date_time;
				break;
			case 'syn_event_end_date':
				$end_date_time = syn_event_date_time_display( $post_id, 'end', 'F j, Y' );
				echo $end_date_time;
				break;
			case 'syn_event_calendar_id':
				//http://127.0.0.1:4001/wordpress/wp-admin/post.php?post=11389&action=edit
				$event_calendar_id = get_field( 'syn_event_calendar_id', $post_id );
				$calendar          = get_post( $event_calendar_id );
				if ( $calendar ) {
					$calendar_link = '<a href="' . admin_url() . 'post.php?s&post=' . $event_calendar_id . '&action=edit' . '">';
					$calendar_link .= $calendar->post_title;
					$calendar_link .= '</a>';
					echo $calendar_link;
				} else {
					echo 'Unknown';
				}
				break;
		}
	}

//add_filter( 'manage_posts_columns', 'syn_calendar_manage_posts_columns', 10, 2 );
	function ________notinuse__________syn_calendar_manage_posts_columns( $columns, $post_type ) {
		switch ( $post_type ) {
			case 'syn_event':
				$new_columns = [];
				foreach ( $columns as $key => $value ) {
					$new_columns[ $key ] = $value;
					if ( $key == 'title' ) {
						$new_columns[ 'syn_event_start_date' ]  = 'Start Date';
						$new_columns[ 'syn_event_end_date' ]    = 'End Date';
						$new_columns[ 'syn_event_calendar_id' ] = 'Calendar';
					}
				}

				return $new_columns;
			case 'syn_calendar':
				$new_columns = [];
				foreach ( $columns as $key => $value ) {
					$new_columns[ $key ] = $value;
					if ( $key == 'title' ) {
						$new_columns[ 'primary_calendar' ]         = 'Primary';
						$new_columns[ 'syn_calendar_event_count' ] = 'Events';
						//$new_columns[ 'syn_calendar_source' ]      = 'Source';
						$new_columns[ 'syn_calendar_sync' ] = 'Sync';
						$new_columns[ 'last_sync' ]         = 'Last Sync';
						$new_columns[ 'next_sync' ]         = 'Next Sync';
					}
				}

				return $new_columns;
		};

		return $columns;
	}

//add_filter( 'manage_edit-syn_calendar_sortable_columns', 'syn_calendar_manage_edit_sortable_columns', 10 );
	function ________notinuse__________syn_calendar_manage_edit_sortable_columns( $sortable_columns ) {
		$sortable_columns[ 'primary_calendar' ]         = 'primary_calendar';
		$sortable_columns[ 'syn_calendar_event_count' ] = 'syn_calendar_event_count';
		//$sortable_columns[ 'syn_calendar_source' ]      = 'syn_calendar_source';
		$sortable_columns[ 'syn_calendar_sync' ] = 'syn_calendar_sync';
		$sortable_columns[ 'last_sync' ]         = 'last_sync';
		$sortable_columns[ 'next_sync' ]         = 'next_sync';

		return $sortable_columns;
	}

//add_filter( 'manage_edit-syn_event_sortable_columns', 'syn_event_manage_sortable_columns', 10 );
	function ________notinuse__________syn_event_manage_sortable_columns( $sortable_columns ) {
		$sortable_columns[ 'syn_event_start_date' ]  = 'syn_event_start_date';
		$sortable_columns[ 'syn_event_end_date' ]    = 'syn_event_end_date';
		$sortable_columns[ 'syn_event_calendar_id' ] = 'syn_event_calendar_id';

		return $sortable_columns;
	}

	function _________notinsuse________syn_purge_calendar_events() {
		$events_args = [
			'numberposts' => - 1,
			'post_type'   => 'syn_event',
			'post_status' => 'publish',
		];
		$events      = get_posts( $events_args );
		foreach ( $events as $event ) {
			wp_delete_post( $event->ID, true );
		}
	}

	/**
	 * Delete event - de-increment syn_calendar_event_count
	 *
	 * todo: This only de-increments when an event is permanently deleted, not just trashed. Need to consider trash and restore cases.
	 *
	 * @param $post_id (optional) event ID, default is global $post->ID
	 */
//add_action( 'before_delete_post', 'syn_event_before_delete_post' );
	function __________notinuse___________syn_event_before_delete_post( $post_id ) {
		$post_id   = syn_resolve_post_id( $post_id );
		$post_type = get_post_type( $post_id );
		if ( $post_type != 'syn_event' ) {
			return;
		}
		$calendar_id = get_field( 'syn_event_calendar_id', $post_id );
		$event_count = get_field( 'syn_calendar_event_count', $calendar_id );
		$event_count = $event_count - 1;
		update_field( 'syn_calendar_event_count', $event_count, $calendar_id );

		return $event_count;
	}

	/**
	 * Get event count for calendar
	 *
	 * wp_count_posts doesn't work here because we need to query post meta for syn_event_calendar_id.
	 *
	 * Best choice is to just get all events using syn_calendar_get_events and return the count
	 *
	 * @param $post_id (optional) default is global $post->ID
	 */
	function ____________notinuse__________syn_calendar_event_count( $post_id ) {
		global $post;
		$calendar_id = ( isset( $post_id ) && is_numeric( $post_id ) ) ? $post_id : $post->ID;
		$post_type   = get_post_type( $calendar_id );
		// If post type not syn_calendar, bail
		if ( 'syn_calendar' != $post_type ) {
			return false;
		}
		$event_count = 0;
		$event_ids   = syn_get_calendar_events( $calendar_id, null, 'all', - 1, 'ids' );
		if ( $event_ids ) {
			$event_count = count( $event_ids );
		}
		// Since we have spent money on the lookup, update the value in calendar meta
		update_field( 'syn_calendar_event_count', $event_count, $calendar_id );

		return $event_count;
	}

/**
 * syn_calendar_save_post backup
 *
 * function syn_calendar_save_post( $post_id, $post, $update ) {
 * // don't save for autosave
 * if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
 * return;
 * }
 * // dont't save if post_type not set or post_type not calendar
 * if ( ! isset( $post->post_type ) || $post->post_type != 'syn_calendar' ) {
 * return;
 * }
 * $sync          = get_field( 'syn_calendar_sync', $post_id );
 * $schedule_args = array( 'post_id' => $post_id, 'post_type' => $post->post_type );
 * if ( $sync ) {
 * $frequency = get_field( 'syn_calendar_sync_frequency', $post_id );
 * wp_clear_scheduled_hook( 'syn_calendar_sync', array( $schedule_args ) );
 * wp_schedule_event( time(), $frequency, 'syn_calendar_sync', array( $schedule_args ) );
 * update_field( 'syn_calendar_last_sync_result', 'Sync schedule updated.', $post_id );
 * $sync_now = get_field( 'syn_calendar_sync_now', $post_id );
 * if ( isset( $sync_now ) && $sync_now ) {
 * syn_sync_calendar( $schedule_args );
 * update_field( 'syn_calendar_sync_now', 0, $post_id );
 * }
 * } else {
 * wp_clear_scheduled_hook( 'syn_calendar_sync', array( $schedule_args ) );
 * update_field( 'syn_calendar_last_sync_result', 'Not scheduled to sync.', $post_id );
 * delete_field( 'syn_calendar_sync_frequency', $post_id );
 * delete_field( 'syn_calendar_sync_range', $post_id );
 * delete_field( 'syn_calendar_delete_events', $post_id );
 * delete_field( 'syn_calendar_delete_after', $post_id );
 * }
 * }*/