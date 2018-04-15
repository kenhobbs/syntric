<?php
	/**
	 * Syntric apps settings and configuration
	 */
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
	//add_action( 'acf/save_post', 'syn_teacher_save_post', 20 );
	function ___syn_teacher_save_post( $post_id ) {
		$post_id = syn_resolve_post_id( $post_id );
		$post    = get_post( $post_id );
		// if post is set and is a page
		if ( $post instanceof WP_Post && 'page' == $post->post_type ) {
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
						$teacher_page = syn_get_teacher_page( $user_id, 0 );
						if ( $teacher_page instanceof WP_Post ) {
							$res = wp_update_post( [
								'ID'          => $post_id,
								'post_parent' => $teacher_page->ID,
							] );
						}
						break;
				}
			}
		}
	}