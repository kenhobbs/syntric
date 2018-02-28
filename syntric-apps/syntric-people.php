<?php
// Add extra capabilities to any/each role
	add_action( 'admin_init', 'syn_add_caps' );
	function syn_add_caps() {
		// Author
		$author_role = get_role( 'author' );
		$author_role->add_cap( 'edit_pages' );
		$author_role->add_cap( 'edit_published_pages' );
		//Editor
		$editor_role = get_role( 'editor' );
		$editor_role->add_cap( 'list_users' );
		$editor_role->add_cap( 'create_users' );
		$editor_role->add_cap( 'edit_users' );
		$editor_role->add_cap( 'delete_users' );
		$editor_role->remove_cap( 'remove_users' );
		$editor_role->remove_cap( 'promote_users' );
		$editor_role->remove_cap( 'manage_categories' );
	}

// actions
	add_action( 'acf/save_post', 'syn_save_people', 20 );
	function syn_save_people( $post_id ) {
		global $pagenow;
		// don't save for autosave
		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
			return;
		}
		$page = ( isset( $_REQUEST[ 'page' ] ) ) ? $_REQUEST[ 'page' ] : null;
		// should be People, New User, Edit User or Profile page
		if ( is_admin() && ( ( 'syntric-people' == $page ) || ( 'user-edit.php' == $pagenow ) || ( 'user-new.php' == $pagenow ) || ( 'profile.php' == $pagenow ) ) ) {
			// coming from user-edit, user-new or profile so $post_id is user_###
			$post_id_arr = explode( '_', $post_id );
			if ( 'user' == $post_id_arr[ 0 ] ) {
				$user_id    = $post_id_arr[ 1 ];
				$is_teacher = get_field( 'syn_user_is_teacher', $post_id );
				$user       = get_user_by( 'ID', $user_id );
				$roles      = $user->roles;
				$role       = $roles[ 0 ];
				if ( $is_teacher ) {
					if ( ! in_array( $role, [
						'author',
						'editor',
						'administrator',
					] ) ) {
						$user_id = wp_update_user( [
							'ID'   => $user_id,
							'role' => 'author',
						] );
					}
					// make sure role is at least author
					$teacher_page_id = syn_save_teacher_page( $user_id );
					update_field( 'syn_user_page', $teacher_page_id, 'user_' . $user_id );
				} else {
					syn_trash_teacher_page( $user_id );
					update_field( 'syn_user_page', null, 'user_' . $user_id );
				}
			} elseif ( 'syntric-people' == $page ) {
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
			}
		}
	}

// filters
	add_filter( 'acf/update_value/name=syn_user_phone', 'syn_update_phone', 20 );
// prepare_field
	add_filter( 'acf/prepare_field/name=syn_user_page', 'syn_prepare_user_fields' );
	function syn_prepare_user_fields( $field ) {
		if ( 'syn_user_page' == $field[ '_name' ] ) {
			$field[ 'wrapper' ][ 'hidden' ] = true;
		}

		return $field;
	}

// functions
	// todo: move to syntric-users.php
	function syn_list_people() {
		$people = get_users( [ 'login__not_in' => [ 'syntric' ] ] );
		if ( $people ) :
			$organization_is_school = syn_organization_is_school();
			$prefix_field           = get_field_object( 'field_5a6570eff3d03' );
			$prefix_options         = '<option>' . implode( '</option><option>', array_values( $prefix_field[ 'choices' ] ) ) . '</option>';
			usort( $people, function( $a, $b ) {
				return strnatcmp( $a->user_lastname . ', ' . $a->user_firstname, $b->user_lastname . ', ' . $b->user_firstname );
			} );
			echo '<table class="admin-list">';
			echo '<thead>';
			echo '<tr align="left">';
			echo '<th scope="col">Prefix</th>';
			echo '<th scope="col">First Name</th>';
			echo '<th scope="col">Last Name</th>';
			echo '<th scope="col">Title</th>';
			echo '<th scope="col">Email</th>';
			echo '<th scope="col">Phone</th>';
			echo '<th scope="col">Extension</th>';
			if ( $organization_is_school ) {
				echo '<th scope="col" colspan="2">';
				echo 'Teacher?';
				echo '</th>';
			}
			echo '</tr>';
			echo '</thead>';
			echo '<tbody>';
			$user_ids = [];
			foreach ( $people as $person ) :
				$user_ids[]         = $person->ID;
				$prefix             = get_field( 'syn_user_prefix', 'user_' . $person->ID );
				$user_prefix_option = '<option selected>' . $prefix . '</option>';
				$title              = get_field( 'syn_user_title', 'user_' . $person->ID );
				$phone              = get_field( 'syn_user_phone', 'user_' . $person->ID );
				$extension          = get_field( 'syn_user_extension', 'user_' . $person->ID );
				$is_teacher         = get_field( 'syn_user_is_teacher', 'user_' . $person->ID );
				$is_teacher_checked = ( $is_teacher ) ? 'checked="checked"' : '';
				$teacher_page       = syn_get_teacher_page( $person->ID );
				echo '<tr>'; //
				echo '<td><select name="user_' . $person->ID . '-prefix">' . $user_prefix_option . $prefix_options . '</select></td>';
				echo '<td><input type="hidden" name="user_' . $person->ID . '-username" value="' . $person->user_login . '"><input type="text" name="user_' . $person->ID . '-first_name" value="' . $person->user_firstname . '"></td>';
				echo '<td><input type="text" name="user_' . $person->ID . '-last_name" value="' . $person->user_lastname . '"></td>';
				echo '<td><input type="text" name="user_' . $person->ID . '-title" value="' . $title . '"></td>';
				echo '<td><input type="text" name="user_' . $person->ID . '-email" value="' . $person->user_email . '"></td>';
				echo '<td><input type="text" name="user_' . $person->ID . '-phone" value="' . $phone . '">' . '</td>';
				echo '<td width="5%"><input type="text" name="user_' . $person->ID . '-extension" value="' . $extension . '"></td>';
				if ( $organization_is_school ) {
					echo '<td nowrap>';
					echo '<input type="checkbox" name="user_' . $person->ID . '-is_teacher" value="' . $is_teacher . '" ' . $is_teacher_checked . '">';
					if ( $teacher_page ) {
						$status = ( 'publish' != $teacher_page->post_status ) ? ' - ' . $teacher_page->post_status : '';
						echo '<span style="margin-left: 6px;">';
						echo $teacher_page->post_title . ucfirst( $status );
						echo '</span>';
						if ( 'trash' != $teacher_page->post_status ) {
							echo '<span style="float: right;">';
							echo '<a href="' . get_the_permalink( $teacher_page->ID ) . '" target="_blank">View</a>';
							echo ' / <a href="/wp-admin/post.php?action=edit&post=' . $teacher_page->ID . '" target="_blank">Edit</a>';
							echo '</span>';
						}
					}
					echo '</td>';
				}
				echo '</tr>';
			endforeach;
			echo '</tbody>';
			echo '</table>';
			echo '<input type="hidden" name="user_ids" value="' . implode( ',', $user_ids ) . '">';
		endif;
	}