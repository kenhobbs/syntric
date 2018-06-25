<?php
	// can this be moved to the acf/save_post action hook
	function syn_save_page_widgets( $post_id ) {
		$post_id = syn_resolve_post_id( $post_id );
		// save contact page widget
		// save roster page widget
		// save attachments page widget
		// save Google map page widget
		// save video page widget
		// etc...
		// save microblog page widget
		syn_save_page_microblog( $post_id );
		// save calendar page widget
		syn_save_page_calendar( $post_id );
		// clean up fields (orphans of widgets made inactive, if any)
		syn_cleanup_page_widgets( $post_id );
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