<?php
	/**
	 * todo: there is an lack of synchronicity between the file index and attachments list.  when reruning the file index, files
	 * remain attached to the attachments list and cause the new files to be named/uploaded with -1 at the end of their filename.
	 * Need to purge both index, attachments and delete the attachment file when files are being indexed.  but must be careful to
	 * only purge/delete files from attachments that are from the index.  also consider what to do with headers and descriptions.
	 *
	 * todo: not necessarily a migration issue, but when a page is deleted, are custom fields removed too?  i think so.
	 */
	/**
	 * Register Migration Type taxonomy for media items
	 */
	/**
	 * Process pasted content from a remote site
	 *
	 * 1. Index files, both docs and images
	 * 2. Fetch remote files and upload into Wordpress media library
	 * 3. Convert links (a.href and img.src) to local file
	 * 4. Clean local content - remove unwanted tag attributes, multi-spaces, multi-line breaks, etc
	 *
	 */
	/**
	 * Action that triggers the migration to run.  Listens for save of Wix options page
	 */
	add_action( 'acf/save_post', 'syn_migration_save_post', 20 );
	function syn_migration_save_post( $post_id ) {
		if( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
			return;
		}
		$post = get_post( $post_id );
		if( is_admin() && is_numeric( $post_id ) && 'page' == $post->post_type && isset( $_REQUEST[ 'acf' ] ) && ! wp_is_post_revision( $post_id ) ) {
			$run_migration_task = get_field( 'syn_migration_run_next', $post_id );
			if( isset( $run_migration_task ) && $run_migration_task ) {
				syn_migration_run_next( $post_id );
			}
			$run_import_attachments_html = get_field( 'syn_migration_run_import_attachments_html', $post_id );
			if( isset( $run_import_attachments_html ) && $run_import_attachments_html ) {
				$attachments_html = get_field( 'syn_migration_attachments_html', $post_id );
				if( isset( $attachments_html ) && ! empty( $attachments_html ) ) {
					syn_parse_attachments_html( $attachments_html, $post_id );
				}
			}
		}
	}

	/**
	 * Process controller
	 */
	function syn_migration_run_next( $post_id ) {
		if( get_field( 'syn_migration_files_indexed', $post_id ) != 1 ) {
			syn_migration_index_files( $post_id );
		} elseif( get_field( 'syn_migration_files_synced', $post_id ) != 1 ) {
			syn_migration_sync_files( $post_id );
		} elseif( get_field( 'syn_migration_links_converted', $post_id ) != 1 ) {
			syn_migration_convert_links( $post_id );
			//} elseif ( get_field( 'syn_migration_files_attached', $post_id ) != 1 ) {
			//syn_migration_attach_files( $post_id );
		} elseif( get_field( 'syn_migration_content_cleaned', $post_id ) != 1 ) {
			syn_migration_clean_content( $post_id );
		}
	}

	/**
	 * Index files on a page
	 *
	 * @param $post_id
	 */
	function syn_migration_index_files( $post_id ) {
		// clear out prior indexed files
		update_field( 'syn_migration_files', [], $post_id );
		$page    = get_post( $post_id );
		$anchors = syn_migration_tag_attributes( $page->post_content, 'a' );
		if( $anchors ) {
			foreach( $anchors as $anchor ) {
				$remote_filename = basename( $anchor[ 'href' ] );
				//$ext             = pathinfo( $remote_filename, PATHINFO_EXTENSION );
				//if ( in_array( $ext, array( 'pdf', 'doc', 'docx', 'xls', 'xlsx', 'ppt', 'pptx', ) ) ) {
				$remote_title = $anchor[ 'tag-content' ];
				// todo: VERY Important...need to add a field for the base hostname as below.  this also appears in the image loop below
				$remote_url  = 'http://pgs-acusd-ca.schoolloop.com' . $anchor[ 'href' ];
				$local_title = syn_migration_sanitize_title( $anchor[ 'tag-content' ] );
				$local_title = ( ! empty( trim( $local_title ) ) ) ? $local_title : 'Hidden File ' . $remote_filename;
				//$local_filename = syn_migration_title_to_filename( $local_title );
				$file_row = [
					'remote_title'    => $remote_title,
					'remote_filename' => $remote_filename,
					'remote_url'      => $remote_url,
					'local_title'     => $local_title,
					//'local_filename'  => $local_filename . '.' . $ext,
					'local_filename'  => '...generated from Local Title',
					'local_file'      => '',
				];
				add_row( 'syn_migration_files', $file_row, $post_id );
				//}
			}
		}
		update_field( 'syn_migration_images', [], $post_id );
		$images = syn_migration_tag_attributes( $page->post_content, 'img' );
		if( isset( $images ) ) {
			$image_count = 1;
			foreach( $images as $image ) {
				$remote_filename = basename( $image[ 'src' ] );
				$remote_url      = $image[ 'src' ];
				$local_title     = $page->post_title . ' image ' . $image_count;
				$local_title     = syn_migration_sanitize_title( $local_title );
				//$image_details = syn_migration_image_details( $image[ 'src' ] );
				//for ( $part = 1; $part <= $image_details[ 'parts' ]; $part ++ ) {
				//$remote_filename = basename( $image_details[ 'part_' . $part ] );
				//$ext             = pathinfo( $remote_filename, PATHINFO_EXTENSION );
				//$remote_url = $image_details[ 'part_' . $part ];
				//if ( in_array( $ext, array( 'png', 'jpg', 'jpeg', 'gif', 'tif', 'tiff', ) ) ) {
				//$title          = $page->post_title . ' ' . $image_count . '-' . $part;
				//$title          = syn_migration_sanitize_title( $title );
				//$title_filename = syn_migration_title_to_filename( $title );
				$image_row = [
					'remote_filename' => $remote_filename,
					'remote_url'      => $remote_url,
					'local_title'     => $local_title,
					'local_filename'  => '...generated from Local Title',
					'local_file'      => '',
				];
				add_row( 'syn_migration_images', $image_row, $post_id );
				//}
				//}
				$image_count ++;
			}
		}
		update_field( 'syn_migration_files_indexed', 1, $post_id );
		update_field( 'syn_migration_run_next', '', $post_id );
	}

	/**
	 * Download files from remote url, upload them into Wordpress and attach to a post
	 *
	 * @param $post_id
	 */
	function syn_migration_sync_files( $post_id ) {
		if( have_rows( 'syn_migration_files', $post_id ) ) {
			while( have_rows( 'syn_migration_files', $post_id ) ) : the_row();
				$remote_file    = wp_remote_get( get_sub_field( 'remote_url' ) );
				$local_title    = get_sub_field( 'local_title' );
				$ext            = pathinfo( get_sub_field( 'remote_filename' ), PATHINFO_EXTENSION );
				$local_filename = syn_migration_title_to_filename( $local_title ) . '.' . $ext;
				if( $ext == 'pdf' ) {
					add_filter( 'upload_dir', 'syn_pdf_upload_dir' );
				} elseif( in_array( $ext, [ 'doc', 'docx', 'xls', 'xlsx', 'ppt', 'pptx' ] ) ) {
					add_filter( 'upload_dir', 'syn_msoffice_upload_dir' );
				} else {
					add_filter( 'upload_dir', 'syn_other_upload_dir' );
				}
				$uploaded_file = wp_upload_bits( $local_filename, null, wp_remote_retrieve_body( $remote_file ) );
				if( $ext == 'pdf' ) {
					remove_filter( 'upload_dir', 'syn_pdf_upload_dir' );
				} elseif( in_array( $ext, [ 'doc', 'docx', 'xls', 'xlsx', 'ppt', 'pptx' ] ) ) {
					remove_filter( 'upload_dir', 'syn_msoffice_upload_dir' );
				} else {
					remove_filter( 'upload_dir', 'syn_other_upload_dir' );
				}
				$attachment_args = [
					'post_status'    => 'inherit',
					'post_mime_type' => $uploaded_file[ 'type' ],
					'parent_id'      => $post_id,
					'post_title'     => $local_title,
				];
				$attachment_id   = wp_insert_attachment( $attachment_args, $uploaded_file[ 'file' ], $post_id );
				//wp_set_post_terms( $attachment_id, array( $ext ), 'file_type' );
				update_sub_field( 'local_file', $attachment_id );
				update_sub_field( 'local_filename', basename( $uploaded_file[ 'file' ] ) );
			endwhile;
		}
		if( have_rows( 'syn_migration_images', $post_id ) ) {
			while( have_rows( 'syn_migration_images', $post_id ) ) : the_row();
				$remote_file    = wp_remote_get( get_sub_field( 'remote_url' ) );
				$local_title    = get_sub_field( 'local_title' );
				$ext            = pathinfo( get_sub_field( 'remote_filename' ), PATHINFO_EXTENSION );
				$local_filename = syn_migration_title_to_filename( $local_title ) . '.' . $ext;
				add_filter( 'upload_dir', 'syn_image_upload_dir' );
				$uploaded_file = wp_upload_bits( $local_filename, null, wp_remote_retrieve_body( $remote_file ) );
				remove_filter( 'upload_dir', 'syn_image_upload_dir' );
				$attachment_args = [
					'post_status'    => 'inherit',
					'post_mime_type' => $uploaded_file[ 'type' ],
					'parent_id'      => $post_id,
					'post_title'     => $local_title,
				];
				$attachment_id   = wp_insert_attachment( $attachment_args, $uploaded_file[ 'file' ], $post_id );
				//wp_set_post_terms( $attachment_id, array( $ext ), 'file_type' );
				update_sub_field( 'local_file', $attachment_id );
				update_sub_field( 'local_filename', basename( $uploaded_file[ 'file' ] ) );
				/*$ext = pathinfo( get_sub_field( 'remote_filename' ), PATHINFO_EXTENSION );
				$remote_url = get_sub_field( 'remote_url' );
				add_filter( 'upload_dir', 'syn_image_upload_dir' );
				// See https://developer.wordpress.org/reference/functions/media_handle_sideload/
				$tmp        = download_url( $remote_url );
				remove_filter( 'upload_dir', 'syn_image_upload_dir' );
				$file_array = array(
					'name'     => get_sub_field( 'local_title' ),
					'tmp_name' => $tmp,
				);
				if ( is_wp_error( $tmp ) ) {
					@unlink( $file_array[ 'tmp_name' ] );
					//return $tmp;
				}
				$attachment_id = media_handle_sideload( $file_array, $post_id );
				if ( is_wp_error( $attachment_id ) ) {
					@unlink( $file_array[ 'tmp_name' ] );
					//return $attachment_id;
				}
				wp_set_post_terms( $attachment_id, strtoupper( $ext ), 'file_type' );
				update_sub_field( 'local_file', $attachment_id );*/
			endwhile;
		}
		update_field( 'syn_migration_files_synced', 1, $post_id );
		update_field( 'syn_migration_run_next', '', $post_id );
		/*if ( get_field( 'syn_migration_run_all', $post_id ) ) {
			syn_migration_convert_links( $post_id );
		}*/
	}

	/**
	 * Convert remote urls in the content of a page to local urls
	 *
	 * @param $post_id
	 */
	function syn_migration_convert_links( $post_id ) {
		$post    = get_post( $post_id );
		$content = $post->post_content;
		if( have_rows( 'syn_migration_files', $post_id ) ) {
			while( have_rows( 'syn_migration_files', $post_id ) ) : the_row();
				$remote_title = get_sub_field( 'remote_title' );
				$local_title  = get_sub_field( 'local_title' );
				$remote_url   = get_sub_field( 'remote_url' );
				$local_file   = get_sub_field( 'local_file' );
				$local_url    = wp_get_attachment_url( $local_file );
				$local_url    = $local_url . '" data-attachment-id="' . $local_file;
				//$data_content_attr = ' data-content="' . $remote_url . '"';
				$searches     = [ $remote_url, $remote_title ];
				$replacements = [ $local_url, $local_title ];
				$content      = str_replace( $searches, $replacements, $content );
			endwhile;
			wp_update_post( [ 'ID' => $post_id, 'post_content' => $content ] );
		}
		if( have_rows( 'syn_migration_images', $post_id ) ) {
			while( have_rows( 'syn_migration_images', $post_id ) ) : the_row();
				$remote_url = get_sub_field( 'remote_url' );
				$file       = get_sub_field( 'local_file' );
				$url        = wp_get_attachment_url( $file );
				$content    = str_replace( $remote_url, $url, $content );
			endwhile;
			wp_update_post( [ 'ID' => $post_id, 'post_content' => $content ] );
		}
		update_field( 'syn_migration_links_converted', 1, $post_id );
		update_field( 'syn_migration_run_next', '', $post_id );
	}

	function syn_parse_attachments_html( $attachments_html, $post_id ) {
		$attachments_index = syn_migration_parse_attachment_lists( $attachments_html );
		if( $attachments_index ) {
			update_field( 'syn_attachments_active', 1, $post_id );
			update_field( 'syn_attachments_title', 'Attachments (parsed from HTML)', $post_id );
			update_field( 'syn_migration_attachments_html', '', $post_id );
			foreach( $attachments_index as $attachment_index ) {
				$attachment_group = [
					'header'      => '',
					'description' => '',
					'attachments' => [],
				];
				foreach( $attachment_index as $attachment_id ) {
					$attachment_group[ 'attachments' ][] = [
						'attachment_type' => 'file',
						'file'            => $attachment_id,
					];
				}
				add_row( 'syn_attachments', $attachment_group, $post_id );
			}
		}
		update_field( 'syn_migration_run_import_attachments_html', 0, $post_id );
		update_field( 'syn_migration_attachments_html', '', $post_id );
	}

	/**
	 * Clean page content: remove classes, cleanup whitespace and returns, get rid of extraneous tags and attributes
	 *
	 * @param $post_id
	 */
	function syn_migration_clean_content( $post_id ) {
		$post    = get_post( $post_id );
		$content = $post->post_content;
		// remove all class attributes
		$content = preg_replace( "/ class=\"[a-zA-Z0-9\_\-\s]+\"/", '', $content );
		// remove all style attributes
		$content = preg_replace( "/ style=\"[a-zA-Z0-9\#\_\-\:\;\s]+\"/", '', $content );
		// remove all id attributes
		$content = preg_replace( "/ id=\"[a-zA-Z0-9\#\_\-\:\;\s]+\"/", '', $content );
		// remove all id attributes
		$content = preg_replace( "/ id=\"[a-zA-Z0-9\_\-]+\"/", '', $content );
		// remove all data-reactid attributes
		$content = preg_replace( "/ data-reactid=\"[a-zA-Z0-9\.\_\-\$]+\"/", '', $content );
		// remove all data-exact-height attributes
		$content = preg_replace( "/ data-exact-height=\"[0-9]+\"/", '', $content );
		// remove all data-content-padding-(horizontal|vertical) attributes
		$content = preg_replace( "/ data-content-padding-(horizontal|vertical)=\"[0-9]+\"/", '', $content );
		// remove all data-style attributes
		$content = preg_replace( "/ data-style=\"[a-zA-Z0-9\#\_\-\:\;\s]+\"/", '', $content );
		// remove all data-content attributes
		//$content = preg_replace( "/ data-content=\"[a-zA-Z0-9.#_-:;\s\/]+\"/", '', $content );
		// remove all data-auto-recognition attributes
		$content = preg_replace( "/ data-auto-recognition=\"[a-zA-Z0-9]+\"/", '', $content );
		// remove all data-mce-fragment attributes
		$content = preg_replace( "/ data-mce-fragment=\"[a-zA-Z0-9]+\"/", '', $content );
		// replace $nbsp; with proper space
		$content = preg_replace( "/&#?[a-zA-Z0-9]{2,8};/i", "", $content );
		// replace no breaking spaces with a space
		$content = str_replace( '&nbsp;', ' ', $content );
		// remove empty title attributes
		$content = str_replace( 'title=""', ' ', $content );
		// remove data-content attributes
		$content = preg_replace( "/\sdata\-content\=\"[a-zA-Z\.\/\:0-9\-\_]+\"/", '', $content );
		// remove all empty tags - fail
		$content = preg_replace( "/<(\w+)\b(?:\s+[\w\-.:]+(?:\s*=\s*(?:\"[^\"]*\"|\"[^\"]*\"|[\w\-.:]+))?)*\s*\/?>\s*<\/\1\s*>/", '', $content );
		// remove empty anchor tags
		$content = str_replace( '<a>​</a>', '', $content );
		// remove empty div tags
		$content = str_replace( '<div>​</div>', '', $content );
		// remove empty p tags
		$content = str_replace( '<p>​</p>', '', $content );
		// remove p tags with a no breaking space
		$content = str_replace( '<p>&nbsp;</p>', '', $content );
		// remove empty h6 tags
		$content = str_replace( '<h6>​</h6>', '', $content );
		// remove empty h5 tags
		$content = str_replace( '<h5>​</h5>', '', $content );
		// remove empty h4 tags
		$content = str_replace( '<h4>​</h4>', '', $content );
		// remove empty h3 tags
		$content = str_replace( '<h3>​</h3>', '', $content );
		// remove empty h2 tags
		$content = str_replace( '<h2>​</h2>', '', $content );
		// remove empty h1 tags
		$content = str_replace( '<h1>​</h1>', '', $content );
		// replace multi-spaces with single space
		$content = preg_replace( '!\s+!', ' ', $content );
		// replace multi-line breaks with single line break
		/*$content = preg_replace( "/[\r]+/", "", $content );
		$content = preg_replace( "/[\n]+/", "\n", $content );*/
		wp_update_post( [ 'ID' => $post_id, 'post_content' => $content ] );
		update_field( 'syn_migration_content_cleaned', 1, $post_id );
		update_field( 'syn_migration_run_next', '', $post_id );
		//update_field( 'syn_migration_run_all', '', $post_id );
	}

	/**
	 * Gets all tags matching $tag in string $content and returns them in an array with an array of attributes for each.
	 *
	 * @param      $content         (required) the content to scan for $tag tags
	 * @param      $tag             (required) the tag to glean out of $content
	 * @param null $attribute       (optional) if searching for a single instance tag, this attribute will be matched for $attribute_value
	 * @param null $attribute_value (optional) if searching for a single instance of tag, this is the value to match for $attribute
	 *
	 * @return array|boolean without $attribute and $attrubute_value, returns an array of attributes for each matching tag,
	 * otherwise an array of attributes for matching tag.  Returns false if params are missing or not correct.
	 */
	function syn_migration_tag_attributes( $content, $tag, $attribute = null, $attribute_value = null ) {
		if( isset( $content ) && isset( $tag ) ) {
			$dom_doc = new DomDocument();
			$dom_doc->loadHTML( $content );
			$items = [];
			foreach( $dom_doc->getElementsByTagName( $tag ) as $item ) {
				$attributes = [];
				if( $item->attributes ) {
					foreach( $item->attributes as $attr ) {
						$attributes[ $attr->nodeName ] = $attr->nodeValue;
					}
					$title                       = $item->nodeValue;
					$attributes[ 'tag-content' ] = $title;
				}
				if( isset( $attribute ) && isset( $attribute_value ) && $attribute == 'filename' ) {
					$file = $attributes[ 'href' ];
					$file = basename( $file );
					$file = reset( explode( '?', $file ) );
					if( $file == $attribute_value ) {
						return $attributes;
					}
				} elseif( isset( $attribute ) && isset( $attribute_value ) && $attributes[ $attribute ] == $attribute_value ) {
					return $attributes;
				}
				$items[] = $attributes;
			};

			return $items;
		}
	}

	/**
	 * Parse ol/ul lists into attachments
	 *
	 * @param $content
	 *
	 * @return array
	 */
	function syn_migration_parse_attachment_lists( $content ) {
		$dom_doc = new DomDocument();
		$dom_doc->loadHTML( $content );
		$lists = [];
		foreach( [ 'ul', 'ol' ] as $tag ) {
			foreach( $dom_doc->getElementsByTagName( $tag ) as $item ) {
				$items = [];
				foreach( $item->childNodes as $li ) {
					if( 'li' == $li->nodeName ) {
						foreach( $li->childNodes as $a ) {
							if( $a->nodeName == 'a' ) {
								foreach( $a->attributes as $attr ) {
									if( $attr->nodeName == 'data-attachment-id' ) {
										$items[] = $attr->nodeValue;
									}
								}
							}
						}
					}
				}
				$lists[] = $items;
			}
		}

		return $lists;
	}

	/**
	 * Clean/sanatize a title (not a filename)
	 *
	 * @param $title
	 *
	 * @return mixed|string
	 */
	function syn_migration_sanitize_title( $title ) {
		$title        = str_replace( 'Â', '', $title );
		$title        = iconv( 'UTF-8', 'ASCII//TRANSLIT//IGNORE', $title );
		$title        = filter_var( $title, FILTER_SANITIZE_STRING );
		$now          = date_create();
		$current_year = date_format( $now, 'y' );
		$cy_m5        = (int) $current_year - 5;
		$cy_p5        = (int) $current_year + 5;
		for( $yr = $cy_m5; $yr < $cy_p5; $yr ++ ) {
			$yr_p1    = $yr + 1;
			$sh_sh    = $yr . '-' . $yr_p1;
			$lg_sh    = '20' . $yr . '-' . $yr_p1;
			$lg_lg    = '20' . $yr . '-20' . $yr_p1;
			$title    = str_replace( $lg_sh, $lg_lg, $title );
			$title    = str_replace( $sh_sh, $lg_lg, $title );
			$lg_lg_sp = $lg_lg . ' ';
			$sp_lg_lg = ' ' . $lg_lg;
			$title    = str_replace( $sp_lg_lg, '', $title, $replacements );
			if( $replacements ) {
				$title = $lg_lg_sp . $title;
				break;
			}
		}

		return $title;
	}

	/**
	 * Convert a title into a filename by sanitizing for url
	 *
	 * @param $title
	 *
	 * @return mixed
	 */
	function syn_migration_title_to_filename( $title ) {
		$title = str_replace( ' ', '_', $title );
		$title = str_replace( ':', '', $title );
		$title = filter_var( $title, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_LOW | FILTER_FLAG_STRIP_HIGH );

		return $title;
	}

	function syn_migration_image_details( $image_src ) {
		if( ! $image_src ) {
			return false;
		}
		$ret                           = [];
		$ret[ 'img_src' ]              = $image_src;
		$ret[ 'parts' ]                = 1;
		$src_len                       = strlen( $image_src );
		$image_ext_starts_at           = [];
		$image_ext_starts_at[ 'jpg' ]  = strpos( $image_src, '.jpg' );
		$image_ext_starts_at[ 'jpeg' ] = strpos( $image_src, '.jpeg' );
		$image_ext_starts_at[ 'png' ]  = strpos( $image_src, '.png' );
		$image_ext_starts_at[ 'gif' ]  = strpos( $image_src, '.gif' );
		$image_ext_starts_at[ 'tiff' ] = strpos( $image_src, '.tiff' );
		$image_ext_starts_at[ 'tif' ]  = strpos( $image_src, '.tif' );
		$image_ext_starts_at[ 'bmp' ]  = strpos( $image_src, '.bmp' );
		foreach( $image_ext_starts_at as $key => $value ) {
			if( $value ) {
				$image_ext_src_len = $value + strlen( $key ) + 1; // extra 1 for the period (.)
				if( $src_len != $image_ext_src_len ) {
					// this is a multi-part src, split it up
					$image_src_array = explode( $key, $image_src );
					$ret[ 'part_1' ] = $image_src_array[ 0 ] . $key;
					$ret[ 'part_2' ] = $ret[ 'part_1' ] . $image_src_array[ 1 ];
					$ret[ 'parts' ]  = 2;
					if( count( $image_src_array ) > 2 ) {
						for( $i = 2; $i < count( $image_src_array ); $i ++ ) {
							$part           = 'part_' . ( $i + 1 );
							$prev_part      = 'part_' . $i;
							$ret[ $part ]   = $ret[ $prev_part ] . $image_src_array[ $i ];
							$ret[ 'parts' ] = $ret[ 'parts' ] + 1;
						}
					}
				}

				return $ret;
			}
		}

		return $ret;
	}

	function syn_migration_tax_term_error() {
		?>
		<div class="notice notice-error">
			<p><?php _e( 'Required taxonomy and/or terms missing - refer to Site Migration settings', 'syntric' ); ?></p>
		</div>
		<?php
	}

	function syn_migration_success_message() {
		?>
		<div class="notice notice-success">
			<p><?php _e( 'Required taxonomy and/or terms exist', 'syntric' ); ?></p>
		</div>
		<?php
	}

	/**
	 * Convert files in files index to page attachments
	 *
	 * @param $post_id
	 */
	function ___________needs_updating_________syn_migration_attach_files( $post_id ) {
		/*$attachments_list_field     = get_field_object( 'syn_attachments_list', $post_id, false, false );
		$attachments_list_field_key = $attachments_list_field[ 'key' ];*/
		$fk = syn_get_field_key( 'syn_attachments', '' );
		if( have_rows( 'syn_migration_files', $post_id ) ) {
			$file_index = get_field( 'syn_migration_files' );
			$rows       = [];
			//'syn_file_migration_import' => 1,
			foreach( $file_index as $index_file ) {
				$row    = [
					'syn_file'      => $index_file[ 'local_file' ],
					'acf_fc_layout' => 'syn_file_layout',
				];
				$rows[] = $row;
			}
			$result = update_field( $fk, $rows, $post_id );
		}
		update_field( 'syn_migration_files_attached', 1, $post_id );
		update_field( 'syn_migration_run_next', '', $post_id );
		/*if ( get_field( 'syn_migration_run_all', $post_id ) ) {
			syn_migration_clean_content( $post_id );
		}*/
	}