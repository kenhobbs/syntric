<?php
	/**
	 * Syntric apps settings and configuration
	 */
// load_field
	add_filter( 'acf/load_field/name=syn_organization_person', 'syn_load_administrators' );
	add_filter( 'acf/load_field/key=field_59bb8bf493b01', 'syn_load_departments' ); // syn_courses > department
	add_filter( 'acf/load_field/key=field_59bb90c45ec6d', 'syn_load_buildings' ); // syn_rooms > building
//
// update_value
	add_filter( 'acf/update_value/name=building_id', 'syn_update_id' );
	add_filter( 'acf/update_value/name=room_id', 'syn_update_id' );
	add_filter( 'acf/update_value/name=department_id', 'syn_update_id' );
	add_filter( 'acf/update_value/name=period_id', 'syn_update_id' );
	add_filter( 'acf/update_value/name=course_id', 'syn_update_id' );
//
// prepare_field
	add_filter( 'acf/prepare_field/name=department_id', 'syn_prepare_settings_fields' );
	add_filter( 'acf/prepare_field/name=course_id', 'syn_prepare_settings_fields' );
	add_filter( 'acf/prepare_field/name=period_id', 'syn_prepare_settings_fields' );
	add_filter( 'acf/prepare_field/name=building_id', 'syn_prepare_settings_fields' );
	add_filter( 'acf/prepare_field/name=room_id', 'syn_prepare_settings_fields' );
	add_filter( 'acf/prepare_field/name=sidebar_id', 'syn_prepare_settings_fields' );
	add_filter( 'acf/prepare_field/key=field_59bb8bf493b01', 'syn_prepare_settings_fields' ); // syn_courses > department
	add_filter( 'acf/prepare_field/key=field_59bb90c45ec6d', 'syn_prepare_settings_fields' ); // syn_rooms > building
//add_filter( 'acf/prepare_field/key=field_59bdc5d740d90', 'syn_prepare_settings_fields' );
	function syn_prepare_settings_fields( $field ) {
		if ( is_admin() && isset( $_REQUEST[ 'page' ] ) ) {
			if ( 'field_59bb8bf493b01' == $field[ 'key' ] ) { // syn_courses > department
				$departments_active = get_field( 'syn_departments_active', 'option' );
				if ( ! $departments_active ) {
					return false;
				}
			} elseif ( 'field_59bb90c45ec6d' == $field[ 'key' ] ) { // syn_rooms > building
				$buildings_active = get_field( 'syn_buildings_active', 'option' );
				if ( ! $buildings_active ) {
					return false;
				}
			} else { // **_id field
				$field_name       = $field[ '_name' ];
				$field_name_array = explode( '_', $field_name );
				$is_id_field      = ( 'id' == $field_name_array[ count( $field_name_array ) - 1 ] ) ? true : false;
				if ( $is_id_field ) {
					$field[ 'wrapper' ][ 'hidden' ] = true;
				}
			}
		}

		return $field;
	}

//
//
//
//
// This is all about controlling what/where an author (eg Teacher) can do creating new content
// An author can not publish pages or posts (outside of the automated processes), they can only save a draft for approval
// With posts, a teacher can only post to their own microblog(s)
// With pages, a teacher can only create pages underneath their own teaacher page (like class pages)
	add_action( 'acf/save_post', 'syn_save_author_post', 20 );
	function syn_save_author_post( $post_id ) {
		$post = get_post( $post_id );
		if ( $post instanceof WP_Post && 'page' == $post->post_type && current_user_can( 'author' ) ) {
			$user       = get_user_by( 'ID', get_current_user_id() );
			$is_teacher = get_field( 'syn_user_is_teacher', 'user_' . $user->ID );
			if ( $is_teacher ) {
				$teacher_page = syn_get_teacher_page( $user->ID );
				if ( $teacher_page instanceof WP_Post ) {
					wp_update_post( [
						'ID'          => $post_id,
						'post_parent' => $teacher_page->ID,
					] );
				}
			}
		}
	}