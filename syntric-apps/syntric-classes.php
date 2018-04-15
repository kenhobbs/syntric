<?php
// todo: on delete Teacher page via post delete, also trash Teacher Classes
	add_action( 'wp_trash_post', 'syn_trash_post' );
	function syn_trash_post( $post_id ) {
		$post_id = syn_resolve_post_id( $post_id );
		$post    = get_post( $post_id );
		if ( is_admin() && 'page' == $post->post_type && ! wp_is_post_revision( $post ) ) {
			$page_template = strtolower( syn_get_page_template( $post_id ) );
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
