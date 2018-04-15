<?php
	//add_action( 'acf/save_post', 'syn_save_page', 20 );
	function ___syn_save_page( $post_id ) {
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

	function syn_save_page_widgets( $post_id ) {
		$post_id = syn_resolve_post_id( $post_id );
		// save new microblog post if present
		syn_save_microblog_page_widget_post( $post_id );
		// save new calendar if present
		syn_save_calendar_page_widget( $post_id );
		// if widget is not active, delete orphaned fields
		syn_cleanup_page_widgets( $post_id );
	}

	function syn_save_calendar_page_widget( $post_id ) {
		$post_id         = syn_resolve_post_id( $post_id );
		$calendar_active = get_field( 'syn_calendar_active', $post_id );
		if ( $calendar_active ) {
			$_calendar_id = get_field( 'syn_calendar_id', $post_id );
			// if calendar_id is 0, insert a new calendar
			if ( 0 == $_calendar_id ) {
				$post         = get_post( $post_id );
				$ancestor_ids = array_reverse( get_post_ancestors( $post_id ) );
				$post_title   = '';
				foreach ( $ancestor_ids as $ancestor_id ) {
					$post_title .= get_the_title( $ancestor_id ) . ' > ';
				}
				$post_title    .= $post->post_title;
				$calendar_args = [
					'ID'          => $_calendar_id,
					'post_type'   => 'syn_calendar',
					'post_title'  => $post_title,
					'post_name'   => $post->post_name . '-calendar',
					'post_author' => $post->post_author,
					'post_status' => 'publish',
				];
				$calendar_id   = wp_insert_post( $calendar_args );
				if ( $calendar_id ) {
					// set the page's calendar widget to the newly created calendar
					update_field( 'syn_calendar_id', $calendar_id, $post_id );
					// get new calendar related values from this page to set values for new calendar meta
					$id             = get_field( 'syn_new_calendar_id', $post_id );
					$sync_now       = get_field( 'syn_new_calendar_sync_now', $post_id );
					$sync           = get_field( 'syn_new_calendar_sync', $post_id );
					$sync_frequency = get_field( 'syn_new_calendar_sync_frequency', $post_id );
					$sync_range     = get_field( 'syn_new_calendar_sync_range', $post_id );
					$delete_events  = get_field( 'syn_new_calendar_delete_events', $post_id );
					$delete_after   = get_field( 'syn_new_calendar_delete_after', $post_id );
					// set meta fields belonging to the newly saved calendar
					update_field( 'syn_calendar_id', $id, $calendar_id );
					update_field( 'syn_calendar_sync', $sync, $calendar_id );
					update_field( 'syn_calendar_sync_frequency', $sync_frequency, $calendar_id );
					update_field( 'syn_calendar_sync_range', $sync_range, $calendar_id );
					update_field( 'syn_calendar_delete_events', $delete_events, $calendar_id );
					update_field( 'syn_calendar_delete_after', $delete_after, $calendar_id );
					// if sync is set...schedule sync
					if ( $sync ) {
						syn_schedule_calendar_sync( $calendar_id );
					}
					// if new calendar "sync now" is set...sync now
					if ( $sync_now ) {
						syn_sync_calendar( [
							'post_id'   => $calendar_id,
							'post_type' => 'syn_calendar',
						] );
					}
					// clear out all the widget values
					update_field( 'syn_new_calendar_id', '', $post_id );
					update_field( 'syn_new_calendar_sync_now', 0, $post_id );
					update_field( 'syn_new_calendar_sync', 0, $post_id );
					update_field( 'syn_new_calendar_sync_frequency', null, $post_id );
					update_field( 'syn_new_calendar_sync_range', null, $post_id );
					update_field( 'syn_new_calendar_delete_events', null, $post_id );
					update_field( 'syn_new_calendar_delete_after', null, $post_id );
				}
			}
		}
	}

	function syn_save_microblog_page_widget_post( $post_id ) {
		$post_id          = syn_resolve_post_id( $post_id );
		$microblog_active = get_field( 'syn_microblog_active', $post_id );
		if ( $microblog_active ) {
			$new_microblog_post = get_field( 'syn_new_microblog_post', $post_id );
			if ( $new_microblog_post ) {
				$post_title   = get_field( 'syn_new_microblog_post_title', $post_id );
				$post_name    = syn_sluggify( $post_title );
				$post_content = get_field( 'syn_new_microblog_post_content', $post_id );
				$category     = get_field( 'syn_microblog_category', $post_id );
				$term         = get_field( 'syn_microblog_term', $post_id );
				slog( $term );
				// todo: post_author should be set to teacher if is a teacher or class page microblog post, not current user id
				$author        = get_current_user_id();
				$page_template = strtolower( syn_get_page_template( $post_id ) );
				if ( 'teacher' == $page_template ) {
					$author = get_field( 'syn_page_teacher', $post_id );
				} elseif ( 'class' == $page_template ) {
					$author = get_field( 'syn_page_class_teacher', $post_id );
				}
				$post_args         = [
					'post_type'     => 'post',
					'post_title'    => $post_title,
					'post_name'     => $post_name,
					// slug
					'post_status'   => 'publish',
					'post_author'   => $author,
					'post_content'  => $post_content,
					'post_category' => [ $category->term_id ],
					'tax_input'     => [ 'microblog' => [ $term->term_id ] ],
				];
				$microblog_post_id = wp_insert_post( $post_args );
				update_field( 'syn_post_category', $category->term_id, $microblog_post_id );
				update_field( 'syn_post_microblog', $term->term_id, $microblog_post_id );
			}
			update_field( 'syn_new_microblog_post', '', $post_id );
			update_field( 'syn_new_microblog_post_title', '', $post_id );
			update_field( 'syn_new_microblog_post_content', '', $post_id );
		}
	}

	function syn_cleanup_page_widgets( $post_id ) {
		$post_id            = syn_resolve_post_id( $post_id );
		$attachments_active = get_field( 'syn_attachments_active', $post_id );
		if ( ! $attachments_active ) {
			delete_field( 'syn_attachments_title', $post_id );
			delete_field( 'syn_attachments', $post_id );
		}
		$calendar_active = get_field( 'syn_calendar_active', $post_id );
		if ( ! $calendar_active ) {
			// check if there was a calendar and trash
			$calendar_id = get_field( 'syn_calendar_id', $post_id );
			if ( $calendar_id ) {
				wp_delete_post( $calendar_id );
			}
			delete_field( 'syn_calendar_title', $post_id );
			delete_field( 'syn_calendar_id', $post_id );
			delete_field( 'syn_calendar_events', $post_id );
			delete_field( 'syn_calendar_include_date', $post_id );
			delete_field( 'syn_new_calendar_id', $post_id );
			delete_field( 'syn_new_calendar_sync_now', $post_id );
			delete_field( 'syn_new_calendar_sync', $post_id );
			delete_field( 'syn_new_calendar_sync_frequency', $post_id );
			delete_field( 'syn_new_calendar_sync_range', $post_id );
			delete_field( 'syn_new_calendar_delete_events', $post_id );
			delete_field( 'syn_new_calendar_delete_after', $post_id );
		}
		$contact_active = get_field( 'syn_contact_active', $post_id );
		if ( ! $contact_active ) {
			delete_field( 'syn_contact_title', $post_id );
			delete_field( 'syn_contact_contact_type', $post_id );
			delete_field( 'syn_contact_include_person_fields', $post_id );
			delete_field( 'syn_contact_include_organization_fields', $post_id );
			delete_field( 'syn_contact_person', $post_id );
			delete_field( 'syn_contact_organization', $post_id );
		}
		$microblog_active = get_field( 'syn_microblog_active', $post_id );
		if ( ! $microblog_active ) {
			$microblog_category = get_field( 'syn_microblog_category', $post_id );
			$microblog_term     = get_field( 'syn_microblog_term', $post_id );
			if ( $microblog_category ) {
				wp_remove_object_terms( $post_id, $microblog_category->term_id, 'category' );
			}
			if ( $microblog_term ) {
				wp_remove_object_terms( $post_id, $microblog_term->term_id, 'microblog' );
			}
			delete_field( 'syn_microblog_title', $post_id );
			delete_field( 'syn_microblog_category', $post_id );
			delete_field( 'syn_microblog_term', $post_id );
			delete_field( 'syn_microblog_posts', $post_id );
			delete_field( 'syn_microblog_include_date', $post_id );
			delete_field( 'syn_new_microblog_post', $post_id );
			delete_field( 'syn_new_microblog_post_title', $post_id );
			delete_field( 'syn_new_microblog_post_content', $post_id );
			if ( $microblog_term ) {
				wp_delete_term( $microblog_term->term_id, 'microblog' );
			}
		}
		$roster_active = get_field( 'syn_roster_active', $post_id );
		if ( ! $roster_active ) {
			delete_field( 'syn_roster_title', $post_id );
			delete_field( 'syn_roster_include_fields', $post_id );
			delete_field( 'syn_roster_people', $post_id );
		}
		$google_map_active = get_field( 'syn_google_map_active', $post_id );
		if ( ! $google_map_active ) {
			delete_field( 'syn_google_map_title', $post_id );
			delete_field( 'syn_google_map_id', $post_id );
		}
		$video_active = get_field( 'syn_video_active', $post_id );
		if ( ! $video_active ) {
			delete_field( 'syn_video_title', $post_id );
			delete_field( 'syn_video_host', $post_id );
			delete_field( 'syn_video_youtube_id', $post_id );
			delete_field( 'syn_video_vimeo_id', $post_id );
			delete_field( 'syn_video_caption', $post_id );
		}
	}