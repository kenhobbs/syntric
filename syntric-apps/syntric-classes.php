<?php
// update_value filters
	add_filter( 'acf/update_value/name=class_id', 'syn_update_id' );
// load_field filters
	add_filter( 'acf/load_field/name=term', 'syn_load_terms' );
	add_filter( 'acf/load_field/name=teacher', 'syn_load_teachers' );
	add_filter( 'acf/load_field/name=course', 'syn_load_courses' );
	add_filter( 'acf/load_field/name=period', 'syn_load_periods' );
	add_filter( 'acf/load_field/name=room', 'syn_load_rooms' );
// prepare field filters
	add_filter( 'acf/prepare_field/name=term', 'syn_prepare_classes_fields' );
	add_filter( 'acf/prepare_field/name=period', 'syn_prepare_classes_fields' );
	add_filter( 'acf/prepare_field/name=course', 'syn_prepare_classes_fields' );
	add_filter( 'acf/prepare_field/name=room', 'syn_prepare_classes_fields' );
	add_filter( 'acf/prepare_field/key=field_59d127e512d9a', 'syn_prepare_classes_fields' ); // syn_classes message field
	add_filter( 'acf/prepare_field/name=class_id', 'syn_prepare_classes_fields' );
//add_filter( 'acf/prepare_field/name=include_page', 'syn_prepare_classes_fields' );
	add_filter( 'acf/prepare_field/name=page', 'syn_prepare_classes_fields' );
	function syn_prepare_classes_fields( $field ) {
		global $post;
		global $pagenow;
		if ( is_admin() && ( ( 'post.php' == $pagenow && 'page' == $post->post_type ) || 'admin-ajax.php' == $pagenow ) ) {
			if ( 'period' == $field[ '_name' ] ) {
				$periods_active = get_field( 'syn_periods_active', 'option' );
				if ( ! $periods_active ) {
					return false;
				} elseif ( $field[ 'value' ] ) {
					$field[ 'disabled' ] = true;
				}
			}
			if ( 'room' == $field[ '_name' ] ) {
				$rooms_active = get_field( 'syn_rooms_active', 'option' );
				if ( ! $rooms_active ) {
					return false;
				} elseif ( $field[ 'value' ] ) {
					$field[ 'disabled' ] = true;
				}
			}
			if ( 'term' == $field[ '_name' ] ) {
				if ( $field[ 'value' ] ) {
					$field[ 'disabled' ] = true;
				}
			}
			if ( 'course' == $field[ '_name' ] ) {
				if ( $field[ 'value' ] ) {
					$field[ 'disabled' ] = true;
				}
			}
			if ( 'field_59d127e512d9a' == $field[ 'key' ] ) { // syn_classes message field
				$pre_row_number_array = explode( '[', $field[ 'prefix' ] );
				$row_number_array     = explode( ']', $pre_row_number_array[ count( $pre_row_number_array ) - 1 ] );
				$row_number           = $row_number_array[ 0 ];
				if ( is_numeric( $row_number ) ) {
					$classes    = get_field( 'syn_classes', $post->ID );
					$class      = $classes[ $row_number ];
					$class_id   = $class[ 'class_id' ];
					$teacher_id = get_field( 'syn_page_teacher', $post->ID );
					$class_page = syn_get_teacher_class_page( $teacher_id, $class_id );
					if ( $class_page instanceof WP_Post ) {
						$field[ 'message' ] = $class_page->post_title . ' (' . $class_page->post_status . ')' . '<span style="float: right;"><a href="' . get_the_permalink( $class_page->ID ) . '">View</a> / <a href="/wp-admin/post.php?action=edit&post=' . $class_page->ID . '">Edit</a></span>';
					}
				}
			}
		}

		return $field;
	}

// todo: on delete Teacher page via post delete, also trash Teacher Classes
	add_action( 'wp_trash_post', 'syn_trash_post' );
	function syn_trash_post( $post_id ) {
		$post = get_post( $post_id );
		if ( is_admin() && 'page' == $post->post_type && ! wp_is_post_revision( $post ) ) {
			$page_template = syn_get_page_template( $post_id );
			if ( 'teacher' == $page_template ) {
				$teacher_id          = get_field( 'syn_page_teacher', $post_id );
				$teacher_class_pages = syn_get_teacher_class_pages( $teacher_id );
				if ( $teacher_class_pages ) {
					foreach ( $teacher_class_pages as $teacher_class_page ) {
						wp_delete_post( $teacher_class_page->ID );
					}
				}
				update_field( 'syn_user_is_teacher', null, 'user_' . $teacher_id );
				update_field( 'syn_user_page', null, 'user_' . $teacher_id );
			}
		}
	}
