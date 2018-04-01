<?php
	/**
	 * Syntric apps settings and configuration
	 */
// load_field
	//add_filter( 'acf/load_field/name=syn_organization_person', 'syn_load_administrators' );
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
// With posts, a teacher can only post to their own microblog(s)
// With pages, a teacher can only create pages underneath their own teaacher page (like class pages)
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
	add_action( 'acf/save_post', 'syn_teacher_save_post', 20 );
	function syn_teacher_save_post( $post_id ) {
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