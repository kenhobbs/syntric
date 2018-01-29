<?php
	add_action( 'acf/save_post', 'syn_save_data_functions', 20 );
	function syn_save_data_functions( $post_id ) {
		if ( is_admin() && isset( $_REQUEST[ 'page' ] ) && 'syntric-data-functions' == $_REQUEST[ 'page' ] ) {
			// do stuff
			$run_orphan_scan           = get_field( 'syn_data_run_orphan_scan', 'option' );
			$run_users_import          = get_field( 'syn_data_run_users_import', 'option' );
			$run_users_export          = get_field( 'syn_data_run_users_export', 'option' );
			$run_users_phone_update    = get_field( 'syn_data_run_users_phone_update', 'option' );
			$run_users_password_update = get_field( 'syn_data_run_users_password_update', 'option' );
			if ( $run_orphan_scan ) {
				$delete_orphans = get_field( 'syn_data_delete_orphans', 'option' );
				syn_scan_orphans( $delete_orphans );
			}
			if ( $run_users_import ) {
				syn_import_users();
			}
			if ( $run_users_export ) {
				syn_export_users();
			}
			if ( $run_users_phone_update ) {
				$phone = get_field( 'syn_data_users_phone', 'option' );
				syn_update_users_phone( $phone );
			}
			if ( $run_users_password_update ) {
				syn_update_users_password();
			}
		}
		// clear/reset all fields, except orphan scan console
		update_field( 'syn_data_run_orphan_scan', null, 'option' );
		update_field( 'syn_data_delete_orphans', null, 'option' );
		update_field( 'syn_data_run_users_import', null, 'option' );
		update_field( 'syn_data_users_file', null, 'option' );
		update_field( 'syn_data_users_file_has_header_row', null, 'option' );
		update_field( 'syn_data_run_users_phone_update', null, 'option' );
		update_field( 'syn_data_users_phone', null, 'option' );
		update_field( 'syn_data_run_users_password_update', null, 'option' );
	}

	function syn_stringify_array( $array ) {
		$ret = '';
		foreach ( $array as $item ) {
			if ( is_array( $item ) ) {
				foreach ( $item as $key => $value ) {
					$ret .= $key . '=' . $value . '    ';
				}
			}
			$ret .= "\n";
		}

		return $ret;
	}

// find acf orphans in posts, post meta, user meta and options
// todo: this doesn't handle widgets which are stored as options with a widget_ prefix
	function syn_scan_orphans( $delete_orphans = false ) {
		global $wpdb;
		$console_output = [ date( 'c' ) ];
		/**
		 * Parentless field scan and delete - sanity check this...is it necessary/better way of doing this??
		 *
		 * $sql               = 'SELECT f.post_name, f.post_title FROM `wp_posts` as f left outer join wp_posts as g on f.post_parent = g.ID where f.post_type = "acf-field" and g.post_name is null';
		 * $parentless_fields = $wpdb->get_results( $sql, ARRAY_A );
		 * $wpdb->flush();
		 * // parentless field keys
		 * $parentless_fields_keys = array_column( $parentless_fields, 'post_name' );
		 * // parentless field titles
		 * $parentless_fields_titles = array_column( $parentless_fields, 'post_title' );
		 * array_push( $console_output, '*****************************Parentless fields' );
		 * if ( $parentless_fields_titles ) {
		 * array_push( $console_output, syn_stringify_array( $parentless_fields ) );
		 * } else {
		 * array_push( $console_output, 'No parentless fields' );
		 * }
		 * //
		 * // delete parentless fields
		 * if ( $delete_orphans ) {
		 * if ( $parentless_fields_keys ) {
		 * $sql                      = 'DELETE FROM wp_posts where post_name in ("' . implode( '","', $parentless_fields_keys ) . '")';
		 * $delete_parentless_fields = $wpdb->get_results( $sql, ARRAY_A );
		 * $wpdb->flush();
		 * array_push( $console_output, '*****************************DELETED parentless fields' );
		 * }
		 * }
		 * /
		 * //
		 * //
		 * //
		 * //
		 * /**
		 * Get all post field keys
		 */
		/**
		 * Get all field groups
		 */
		$sql       = 'SELECT DISTINCT ID, post_title, post_excerpt, post_parent from `wp_posts` where post_type = "acf-field-group" and post_status = "publish"';
		$acfg_rows = $wpdb->get_results( $sql, ARRAY_A );
		$wpdb->flush();
		/**
		 * Get all fields
		 */
		$sql      = 'SELECT DISTINCT ID, post_name as field_key, post_title, post_excerpt, post_parent from `wp_posts` where post_type = "acf-field" and post_status = "publish"';
		$acf_rows = $wpdb->get_results( $sql, ARRAY_A );
		$wpdb->flush();
		$acf_keys = [];
		// separate multi-key strings (eg field_abc123_field_xyz789)
		foreach ( $acf_rows as $acf_row ) {
			$paka = explode( '_', $acf_row[ 'field_key' ] );
			foreach ( $paka as $item ) {
				if ( 'field' != $item ) {
					$acf_keys[] = 'field_' . $item;
				}
			}
		}
		/**
		 * Get all post meta field keys
		 */
		$sql           = 'SELECT DISTINCT meta_key, meta_value as field_key from `wp_postmeta` where meta_value like "field_%"';
		$postmeta_rows = $wpdb->get_results( $sql, ARRAY_A );
		$wpdb->flush();
		$postmeta_keys = [];
		// separate multi-key strings
		foreach ( $postmeta_rows as $postmeta_row ) {
			$pmka = explode( '_', $postmeta_row[ 'field_key' ] );
			foreach ( $pmka as $item ) {
				if ( 'field' != $item ) {
					$postmeta_keys[] = 'field_' . $item;
				}
			}
		}
		/**
		 * Get all option field keys
		 */
		$sql         = 'SELECT DISTINCT option_name, option_value as field_key FROM `wp_options` where option_value like "field_%"';
		$option_rows = $wpdb->get_results( $sql, ARRAY_A );
		$wpdb->flush();
		$option_keys = [];
		// separate multi-key strings
		foreach ( $option_rows as $option_row ) {
			$poka = explode( '_', $option_row[ 'field_key' ] );
			foreach ( $poka as $item ) {
				if ( 'field' != $item ) {
					$option_keys[] = 'field_' . $item;
				}
			}
		}
		/**
		 * Get all user meta field keys
		 */
		$sql           = 'SELECT DISTINCT meta_key, meta_value as field_key FROM `wp_usermeta` where meta_value like "field_%"';
		$usermeta_rows = $wpdb->get_results( $sql, ARRAY_A );
		$wpdb->flush();
		$usermeta_keys = [];
		// separate multi-key strings
		foreach ( $usermeta_rows as $usermeta_row ) {
			$puka = explode( '_', $usermeta_row[ 'field_key' ] );
			foreach ( $puka as $item ) {
				if ( 'field' != $item ) {
					$usermeta_keys[] = 'field_' . $item;
				}
			}
		}
		/**
		 * At this point we have all field keys for posts, post meta, user meta and options
		 */
		$post_option_user_keys = array_merge( $postmeta_keys, $option_keys, $usermeta_keys );
		$pre_orphan_keys       = array_diff( $post_option_user_keys, $acf_keys );
		foreach ( $postmeta_rows as $postmeta_row ) {
			if ( array_search( $postmeta_row[ 'field_key' ], $pre_orphan_keys, false ) ) {
			}
		}
		foreach ( $option_rows as $option_row ) {
			if ( array_search( $option_row[ 'field_key' ], $pre_orphan_keys, false ) ) {
			}
		}
		foreach ( $usermeta_rows as $usermeta_row ) {
			if ( array_search( $usermeta_row[ 'field_key' ], $pre_orphan_keys, false ) ) {
			}
		}

		return;
		//
		// make orphan keys distinct
		$orphan_keys = [];
		foreach ( $pre_orphan_keys as $pre_orphan_key ) {
			if ( ! in_array( $pre_orphan_key, $orphan_keys ) ) {
				$orphan_keys[] = $pre_orphan_key;
			}
		}
		// BUT WHY???
		// double check against acf keys in wp_posts
		$sql                 = 'SELECT * from `wp_posts` where post_name in ("' . implode( '","', $orphan_keys ) . '")';
		$orphan_keys_recheck = $wpdb->get_results( $sql, ARRAY_A );
		$wpdb->flush();
		//
		// construct compound like statement
		$compound_like    = '';
		$orphan_key_count = count( $orphan_keys );
		$i                = 1;
		foreach ( $orphan_keys as $orphan_key ) {
			$compound_like .= 'field_key like "' . $orphan_key . '%"';
			$compound_like .= ( $i != $orphan_key_count ) ? ' OR ' : ' ';
			$i ++;
		}
		//``````````````````````````````````````````` Post meta ``````````````````````````````````````````
		// get orphaned post meta records
		$postmeta_compound_like = str_replace( 'field_key', 'meta_value', $compound_like );
		$sql                    = 'SELECT * from `wp_postmeta` where ' . $postmeta_compound_like;
		$postmeta_orphans       = $wpdb->get_results( $sql, ARRAY_A );
		$wpdb->flush();
		array_push( $console_output, '*****************************Post meta orphans' );
		if ( $postmeta_orphans ) {
			array_push( $console_output, syn_stringify_array( $postmeta_orphans ) );
		} else {
			array_push( $console_output, 'No post meta orphans' );
		}
		// delete postmeta orphans
		if ( $delete_orphans ) {
			// get value row for each postmeta orphan - the row immediate before the orphan
			$postmeta_orphan_ids  = array_column( $postmeta_orphans, 'meta_id' );
			$postmeta_orphan_rows = [];
			foreach ( $postmeta_orphan_ids as $postmeta_orphan_id ) {
				$postmeta_orphan_rows[] = $postmeta_orphan_id;
				$postmeta_orphan_rows[] = $postmeta_orphan_id - 1;
			}
			if ( 0 != count( $postmeta_orphan_rows ) ) {
				$sql                              = 'DELETE from `wp_postmeta` where meta_id in (' . implode( ',', $postmeta_orphan_rows ) . ')';
				$delete_postmeta_orphans_all_rows = $wpdb->get_results( $sql, ARRAY_A );
				$wpdb->flush();
				array_push( $console_output, '*****************************DELETED post meta orphans' );
			}
		}
		//``````````````````````````````````````````` Options ``````````````````````````````````````````
		// get orphaned option records
		$option_compound_like = str_replace( 'field_key', 'option_value', $compound_like );
		$sql                  = 'SELECT * from `wp_options` where ' . $option_compound_like;
		$options_orphans      = $wpdb->get_results( $sql, ARRAY_A );
		$wpdb->flush();
		array_push( $console_output, '*****************************Options orphans' );
		if ( $options_orphans ) {
			array_push( $console_output, syn_stringify_array( $options_orphans ) );
		} else {
			array_push( $console_output, 'No option orphans' );
		}
		// delete options orphans
		if ( $delete_orphans ) {
			// get value row for each option orphan - the row immediately before the orphan
			$options_orphan_ids  = array_column( $options_orphans, 'option_id' );
			$options_orphan_rows = [];
			foreach ( $options_orphan_ids as $options_orphan_id ) {
				$options_orphan_rows[] = $options_orphan_id;
				$options_orphan_rows[] = $options_orphan_id - 1;
			}
			if ( 0 != count( $options_orphan_rows ) ) {
				$sql                             = 'DELETE from `wp_options` where option_id in (' . implode( ',', $options_orphan_rows ) . ')';
				$delete_options_orphans_all_rows = $wpdb->get_results( $sql, ARRAY_A );
				$wpdb->flush();
				array_push( $console_output, '*****************************DELETED option orphans' );
			}
		}
		// no need to create a compound like for usermeta, it is the same as postmeta
		$sql              = 'SELECT * from `wp_usermeta` where ' . $postmeta_compound_like;
		$usermeta_orphans = $wpdb->get_results( $sql, ARRAY_A );
		$wpdb->flush();
		array_push( $console_output, '*****************************User meta orphans' );
		if ( $usermeta_orphans ) {
			array_push( $console_output, syn_stringify_array( $usermeta_orphans ) );
		} else {
			array_push( $console_output, 'No user meta orphans' );
		}
		update_field( 'syn_data_run_orphan_scan', '', 'option' );
		update_field( 'syn_data_delete_orphans', '', 'option' );
		update_field( 'syn_data_orphan_scan_console', implode( "\n", $console_output ), 'option' );

		return;
	}

	function syn_export_users() {
		$users            = get_users();
		$console_output   = [];
		$console_output[] = 'ID,Email,Role,First Name,Last Name,Title,Phone,Extension,Is Teacher';
		foreach ( $users as $user ) {
			$usermeta         = get_user_meta( $user->ID );
			$user_title       = get_field( 'syn_user_title', 'user_' . $user->ID );
			$user_phone       = get_field( 'syn_user_phone', 'user_' . $user->ID );
			$user_ext         = get_field( 'syn_user_extension', 'user_' . $user->ID );
			$user_is_teacher  = get_field( 'syn_user_is_teacher', 'user_' . $user->ID );
			$console_output[] = $user->ID . ',' . $user->data->user_email . ',' . $user->roles[ 0 ] . ',' . $usermeta[ 'first_name' ][ 0 ] . ',' . $usermeta[ 'last_name' ][ 0 ] . ',' . $user_title . ',' . $user_phone . ',' . $user_ext . ',' . $user_is_teacher;
		}
		update_field( 'syn_data_users_import_console', implode( "\n", $console_output ), 'option' );
		update_field( 'syn_data_run_users_export', 0, 'option' );
	}

// import users from a CSV upload
	function syn_import_users() {
		$run_users_import = get_field( 'syn_data_run_users_import', 'option' );
		if ( $run_users_import ) {
			$console_output  = [ date( 'c' ) ];
			$users_file      = get_field( 'syn_data_users_file', 'option' );
			$upload_dir      = wp_get_upload_dir();
			$users_file_path = str_replace( $upload_dir[ 'url' ], $upload_dir[ 'path' ], $users_file[ 'url' ] );
			$file            = fopen( $users_file_path, 'r' );
			if ( $file ) {
				$header_row  = get_field( 'syn_data_users_file_has_header_row', 'option' );
				$row_counter = 0;
				while( ! feof( $file ) ) {
					$user_row = fgetcsv( $file );
					if ( 0 == $row_counter && $header_row ) {
						$console_output[] = 'Skipped header row';
						$row_counter ++;
						continue;
					}
					// ID is required
					if ( ! isset( $user_row[ 0 ] ) ) {
						$console_output[] = 'ID is missing in row ' . $row_counter . ', skipping row';
						$row_counter ++;
						continue;
					}
					// Email is required
					if ( ! isset( $user_row[ 1 ] ) || empty( $user_row[ 1 ] ) ) {
						$console_output[] = 'Email is missing in row ' . $row_counter . ', skipping row';
						$row_counter ++;
						continue;
					}
					// Role is required
					if ( ! isset( $user_row[ 2 ] ) || empty( $user_row[ 2 ] ) ) {
						$console_output[] = 'Role is missing in row ' . $row_counter . ', skipping row';
						$row_counter ++;
						continue;
					}
					// First Name is required
					if ( ! isset( $user_row[ 4 ] ) || empty( $user_row[ 4 ] ) ) {
						$console_output[] = 'First Name is missing in row ' . $row_counter . ', skipping row';
						$row_counter ++;
						continue;
					}
					// Last Name is required
					if ( ! isset( $user_row[ 5 ] ) || empty( $user_row[ 5 ] ) ) {
						$console_output[] = 'Last Name is missing in row ' . $row_counter . ', skipping row';
						$row_counter ++;
						continue;
					}
					$user_id         = $user_row[ 0 ];
					$user_email      = $user_row[ 1 ];
					$user_role       = $user_row[ 2 ];
					$user_prefix     = $user_row[ 3 ];
					//$user_prefix     = str_replace( '.', '', $user_prefix );
					$user_firstname  = $user_row[ 4 ];
					$user_lastname   = $user_row[ 5 ];
					$user_title      = ( isset( $user_row[ 6 ] ) && ! empty( $user_row[ 6 ] ) ) ? $user_row[ 5 ] : false;
					$user_phone      = ( isset( $user_row[ 7 ] ) && ! empty( $user_row[ 7 ] ) ) ? $user_row[ 6 ] : false;
					$user_extension  = ( isset( $user_row[ 8 ] ) && ! empty( $user_row[ 8 ] ) ) ? $user_row[ 7 ] : false;
					$user_is_teacher = ( isset( $user_row[ 9 ] ) && ! empty( $user_row[ 9 ] ) ) ? $user_row[ 8 ] : false;
					$user_role       = ( $user_is_teacher && 'subscriber' == $user_role ) ? 'author' : $user_role;
					$userdata        = [ 'user_nicename' => $user_firstname . ' ' . $user_lastname, 'user_email' => $user_email, 'display_name' => $user_firstname . ' ' . $user_lastname,
					                     'nickname'      => $user_firstname, 'first_name' => $user_firstname, 'last_name' => $user_lastname, 'role' => $user_role, ];
					if ( 0 < $user_id ) {
						$userdata[ 'ID' ] = $user_id;
						$user_id          = wp_update_user( $userdata );
					} else {
						$userdata[ 'user_login' ] = $user_email;
						$userdata[ 'user_pass' ]  = syn_generate_password();
						$user_id                  = wp_insert_user( $userdata );
					}
					if ( is_int( $user_id ) ) {
						update_field( 'syn_user_prefix', $user_prefix, 'user_' . $user_id );
						update_field( 'syn_user_title', $user_title, 'user_' . $user_id );
						update_field( 'syn_user_phone', $user_phone, 'user_' . $user_id );
						update_field( 'syn_user_extension', $user_extension, 'user_' . $user_id );
						update_field( 'syn_user_is_teacher', $user_is_teacher, 'user_' . $user_id );
						if ( $user_is_teacher ) {
							$teacher_page_id = syn_save_teacher_page( $user_id );
							update_field( 'syn_user_page', $teacher_page_id, 'user_' . $user_id );
						} else {
							syn_trash_teacher_page( $user_id );
							update_field( 'syn_user_page', null, 'user_' . $user_id );
						}
					}
					$row_counter ++;
				}
			}
			fclose( $file );
			update_field( 'syn_data_users_import_console', implode( "\n", $console_output ), 'option' );
		}
		update_field( 'syn_data_run_users_import', 0, 'option' );

		return;
	}

// update all users phone number in user meta
	function syn_update_users_phone( $phone ) {
		$users = get_users( [ 'login__not_in' => [ 'syntric', 'trinette' ], 'fields' => 'ID', ] );
		if ( $users ) :
			foreach ( $users as $key => $value ) {
				update_field( 'syn_user_phone', $phone, 'user_' . $value );
			}
		endif;
	}

// Set user password to email address...don't use this unless urgent
	function syn_update_users_password() {
		//$users = get_users();
		//$_users = get_field( 'syn_data_update_password_users', 'option' );
		$users = get_users( [ 'login__not_in' => [ 'syntric' ], 'fields' => [ 'ID', 'user_email' ], ] );
		foreach ( $users as $user ) {
			$userdata = [ 'ID' => $user->ID, 'user_pass' => $user->user_email, ];
			// email and password change emails controlled via send_email_change_email filter in setup.php
			wp_update_user( $userdata );
		}
	}