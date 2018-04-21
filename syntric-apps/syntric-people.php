<?php
// Add extra capabilities to any/each role
	add_action( 'admin_init', 'syn_add_caps' );
	function syn_add_caps() {
		///////////////////////////////
		// Author
		///////////////////////////////
		$author_role = get_role( 'author' );
		// pages
		$author_role->add_cap( 'edit_pages' );
		$author_role->add_cap( 'publish_pages' );
		$author_role->add_cap( 'delete_pages' );
		// published
		$author_role->add_cap( 'edit_published_pages' );
		$author_role->add_cap( 'delete_published_pages' );
		// private
		$author_role->add_cap( 'edit_private_pages' );
		$author_role->add_cap( 'delete_private_pages' );
		// posts
		//$author_role->add_cap( 'edit_posts' );
		//$author_role->add_cap( 'publish_posts' );
		//$author_role->add_cap( 'delete_posts' );
		// published
		//$author_role->add_cap( 'edit_published_posts' );
		//$author_role->add_cap( 'delete_published_posts' );
		// private
		$author_role->add_cap( 'edit_private_posts' );
		$author_role->add_cap( 'delete_private_posts' );
		///////////////////////////////
		// Editor
		///////////////////////////////
		$editor_role = get_role( 'editor' );
		// users
		$editor_role->add_cap( 'list_users' );
		$editor_role->add_cap( 'create_users' );
		$editor_role->add_cap( 'edit_users' );
		$editor_role->add_cap( 'delete_users' );
		$editor_role->add_cap( 'customize' );
		// plugins
		$editor_role->add_cap( 'update_plugins' );
		$editor_role->add_cap( 'edit_plugins' );
		$editor_role->remove_cap( 'remove_users' );
		//$editor_role->remove_cap( 'promote_users' );
		//$editor_role->remove_cap( 'manage_categories' );
		$editor_role->add_cap( 'manage_categories' );
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		/// ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		/// ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		/// ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		/// ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		/// Also limit items an author can see in Media Library to their own uploads
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		/// ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		/// ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		/// ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		/// ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	}

// actions
	/**
	 * Protect ID #1 and administrator accounts from other users
	 */
	add_action( 'pre_user_query', 'syn_hide_administrators' );
	function syn_hide_administrators( $query ) {
		if ( is_admin() && is_user_logged_in() ) {
			global $wpdb;
			$current_user = wp_get_current_user();
			// hide user ID #1 from everyone else
			if ( $current_user->roles[ 0 ] == 'administrator' && $current_user->ID != 1 ) {
				$query->query_where = str_replace( 'WHERE 1=1', "WHERE 1=1 AND {$wpdb->users}.ID<>1", $query->query_where );
				// hide administrators from non-administrators
			} elseif ( $current_user->roles[ 0 ] != 'administrator' ) {
				$query->query_where = str_replace( 'WHERE 1=1', "WHERE 1=1 AND {$wpdb->users}.ID IN (SELECT {$wpdb->usermeta}.user_id FROM $wpdb->usermeta	WHERE {$wpdb->usermeta}.meta_key = '{$wpdb->prefix}capabilities' AND {$wpdb->usermeta}.meta_value NOT LIKE '%administrator%')", $query->query_where );
			}
		}
	}

	add_filter( 'editable_roles', 'syn_editable_roles', 20 );
	function syn_editable_roles( $roles ) {
		$current_user = wp_get_current_user();
		if ( $current_user->roles[ 0 ] != 'administrator' ) {
			unset( $roles[ 'administrator' ] );
		}

		return $roles;
	}

// functions
	// todo: move to syntric-users.php
	function syn_list_people() {
		$people = get_users( [ 'exclude'       => [ 1 ],
		                       'role__not_in'  => [ 'Administrator', ],
		                       'login__not_in' => [ 'syntric' ], ] );
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
						echo $teacher_page->post_title . ucwords( $status );
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