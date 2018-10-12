<?php
	/*add_filter( 'wp_handle_upload_prefilter', 'syn_handle_upload_prefilter' );
	function syn_handle_upload_prefilter( $file ) {
		add_filter( 'upload_dir', 'syn_upload_dir' );

		return $file;
	}

	add_filter( 'wp_handle_upload', 'syn_handle_upload' );
	function syn_handle_upload( $fileinfo ) {
		remove_filter( 'upload_dir', 'syn_upload_dir' );

		return $fileinfo;
	}*/
	/**
	 * Register Media Categories and File Types taxonomies
	 */
	add_action( 'init', 'syn_register_media_taxonomies', 1 );
	function syn_register_media_taxonomies() {
		// media_category taxonomy & defaults
		$tax_args = [ 'labels' => [ 'name'         => _x( 'Media Categories', 'syntric' ), 'singular_name' => _x( 'Media Category', 'syntric' ),
		                            'search_items' => __( 'Search Media Categories', 'syntric' ), 'all_items' => __( 'Media Categories', 'syntric' ),
		                            'edit_item'    => __( 'Edit Media Category', 'syntric' ), 'update_item' => __( 'Update Media Category', 'syntric' ),
		                            'add_new_item' => __( 'Add New Media Category', 'syntric' ), 'new_item_name' => __( 'New Media Category Name', 'syntric' ),
		                            'menu_name'    => __( 'Categories', 'syntric' ), ], 'description' => 'Media categories', 'public' => false, 'publicly_queryable' => false,
		              'hierarchical' => true, 'show_ui' => true, 'show_in_menu' => true, 'show_in_nav_menus' => false, 'show_in_rest' => true, 'rest_base' => 'media-categories',
		              'show_in_quick_edit' => true, 'show_admin_column' => true, 'rewrite' => false, 'query_var' => false, ];
		register_taxonomy( 'media_category', 'attachment', $tax_args );
		if ( ! term_exists( 'Images', 'media_category' ) ) {
			$media_categories = [ 'Images', 'Publications', 'Documents', 'Forms', ];
			foreach ( $media_categories as $media_category ) {
				$res            = wp_insert_term( $media_category, 'media_category' );
				$sub_categories = [];
				if ( is_array( $res ) && ! is_wp_error( $res ) ) {
					$term_id = $res[ 'term_id' ];
					if ( 'Images' == $media_category ) {
						$sub_categories[] = 'Banner';
						$sub_categories[] = 'Headshot';
					}
					if ( 'Publications' == $media_category ) {
						$sub_categories[] = 'Handbook';
						$sub_categories[] = 'Newsletter';
						$sub_categories[] = 'Calendar';
					}
					if ( 'Documents' == $media_category ) {
						$sub_categories[] = 'Report';
						$sub_categories[] = 'Legal';
						$sub_categories[] = 'Communication';
					}
					if ( 'Forms' == $media_category ) {
						$sub_categories[] = 'Enrollment';
						$sub_categories[] = 'Athletics';
						$sub_categories[] = 'Activities';
					}
					foreach ( $sub_categories as $sub_category ) {
						wp_insert_term( $sub_category, 'media_category', [ 'parent' => $term_id ] );
					}
				}
			}
		}
	}

	/**
	 * Font-end display for Syntric Attachments
	 *
	 * todo: the
	 */
	/**
	 * Filter the EML dropdowns in media list view (grid does not call this filter)
	 */
	add_filter( 'wp_dropdown_cats', 'syn_dropdown_cats', 10 );
	function syn_dropdown_cats( $dropdown ) {
		global $pagenow;
		if ( is_admin() && ( 'upload.php' == $pagenow ) ) {
			$dropdown = preg_replace( "/— /", '', $dropdown );
			$dropdown = preg_replace( "/All/", 'In', $dropdown );
			$dropdown = preg_replace( "/Not in a/", 'No', $dropdown );
			$dropdown = preg_replace( "/ —/", '', $dropdown );
			$dropdown = preg_replace( "/&nbsp;&nbsp;&nbsp;/", '- ', $dropdown );
			$dropdown = preg_replace( "/Filter by File Type/", 'All file types', $dropdown );
			$dropdown = preg_replace( "/In File Types/", 'Has file type', $dropdown );
			$dropdown = preg_replace( "/No File Type/", 'No file type', $dropdown );
			$dropdown = preg_replace( "/Filter by Media Category/", 'All media categories', $dropdown );
			$dropdown = preg_replace( "/In Media Categories/", 'Has media category', $dropdown );
			$dropdown = preg_replace( "/No Media Category/", 'No media category', $dropdown );
			$dropdown = preg_replace( "/Filter by Language/", 'All languages', $dropdown );
			$dropdown = preg_replace( "/In Languages/", 'Has language', $dropdown );
			$dropdown = preg_replace( "/No Language/", 'No language', $dropdown );
		}

		return $dropdown;
	}

	/**
	 * Handle uploads, direct to custom folders according to file mime type
	 *
	 * Default allowed mime types (from wp_get_mime_types)
	 *
	 * // Image formats
	 * 'jpg|jpeg|jpe'                 => 'image/jpeg',
	 * 'gif'                          => 'image/gif',
	 * 'png'                          => 'image/png',
	 * 'bmp'                          => 'image/bmp',
	 * 'tif|tiff'                     => 'image/tiff',
	 * 'ico'                          => 'image/x-icon',
	 *
	 * // Video formats
	 * 'asf|asx'                      => 'video/x-ms-asf',
	 * 'wmv'                          => 'video/x-ms-wmv',
	 * 'wmx'                          => 'video/x-ms-wmx',
	 * 'wm'                           => 'video/x-ms-wm',
	 *                                                      'avi'                          => 'video/avi',
	 * 'divx'                         => 'video/divx',
	 * 'flv'                          => 'video/x-flv',
	 *                                                      'mov|qt'                       => 'video/quicktime',
	 * 'mpeg|mpg|mpe'                 => 'video/mpeg',
	 *                                                      'mp4|m4v'                      => 'video/mp4',
	 * 'ogv'                          => 'video/ogg',
	 *                                                      'webm'                         => 'video/webm',
	 *                                                      'mkv'                          => 'video/x-matroska',
	 *
	 * // Text formats
	 * 'txt|asc|c|cc|h'               => 'text/plain',
	 * 'csv'                          => 'text/csv',
	 * 'tsv'                          => 'text/tab-separated-values',
	 * 'ics'                          => 'text/calendar',
	 * 'rtx'                          => 'text/richtext',
	 * 'css'                          => 'text/css',                                        SPECIAL CASE
	 * 'htm|html'                     => 'text/html',                                       SPECIAL CASE
	 *
	 * // Audio formats
	 *                                                      'mp3|m4a|m4b'                  => 'audio/mpeg',
	 * 'ra|ram'                       => 'audio/x-realaudio',
	 *                                                      'wav'                          => 'audio/wav',
	 *                                                      'ogg|oga'                      => 'audio/ogg',
	 *                                                      'mid|midi'                     => 'audio/midi',
	 * 'wma'                          => 'audio/x-ms-wma',
	 * 'wax'                          => 'audio/x-ms-wax',
	 *                                                      'mka'                          => 'audio/x-matroska',
	 *
	 * // Misc application formats
	 * 'rtf'                          => 'application/rtf',
	 * 'js'                           => 'application/javascript',                              SPECIAL CASE
	 * 'pdf'                          => 'application/pdf',                                     SPECIAL CASE
	 * 'swf'                          => 'application/x-shockwave-flash',
	 * 'class'                        => 'application/java',
	 * 'tar'                          => 'application/x-tar',
	 * 'zip'                          => 'application/zip',
	 * 'gz|gzip'                      => 'application/x-gzip',
	 * 'rar'                          => 'application/rar',
	 * '7z'                           => 'application/x-7z-compressed',
	 * 'exe'                          => 'application/x-msdownload',
	 *
	 * // MS Office formats                                                                     SPECIAL CASE - All MS Office files to msoffice
	 *                                                                  'doc'                          => 'application/msword',
	 *                                                                  'pot|pps|ppt'                  => 'application/vnd.ms-powerpoint',
	 *                                                                  'wri'                          => 'application/vnd.ms-write',
	 *                                                                  'xla|xls|xlt|xlw'              => 'application/vnd.ms-excel',
	 *                                                                  'mdb'                          => 'application/vnd.ms-access',
	 *                                                                  'mpp'                          => 'application/vnd.ms-project',
	 *
	 * 'docx'                         => 'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
	 * 'docm'                         => 'application/vnd.ms-word.document.macroEnabled.12',
	 * 'dotx'                         => 'application/vnd.openxmlformats-officedocument.wordprocessingml.template',
	 * 'dotm'                         => 'application/vnd.ms-word.template.macroEnabled.12',
	 * 'xlsx'                         => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
	 * 'xlsm'                         => 'application/vnd.ms-excel.sheet.macroEnabled.12',
	 * 'xlsb'                         => 'application/vnd.ms-excel.sheet.binary.macroEnabled.12',
	 * 'xltx'                         => 'application/vnd.openxmlformats-officedocument.spreadsheetml.template',
	 * 'xltm'                         => 'application/vnd.ms-excel.template.macroEnabled.12',
	 * 'xlam'                         => 'application/vnd.ms-excel.addin.macroEnabled.12',
	 * 'pptx'                         => 'application/vnd.openxmlformats-officedocument.presentationml.presentation',
	 * 'pptm'                         => 'application/vnd.ms-powerpoint.presentation.macroEnabled.12',
	 * 'ppsx'                         => 'application/vnd.openxmlformats-officedocument.presentationml.slideshow',
	 * 'ppsm'                         => 'application/vnd.ms-powerpoint.slideshow.macroEnabled.12',
	 * 'potx'                         => 'application/vnd.openxmlformats-officedocument.presentationml.template',
	 * 'potm'                         => 'application/vnd.ms-powerpoint.template.macroEnabled.12',
	 * 'ppam'                         => 'application/vnd.ms-powerpoint.addin.macroEnabled.12',
	 * 'sldx'                         => 'application/vnd.openxmlformats-officedocument.presentationml.slide',
	 * 'sldm'                         => 'application/vnd.ms-powerpoint.slide.macroEnabled.12',
	 * 'onetoc|onetoc2|onetmp|onepkg' => 'application/onenote',
	 * doc,pot,pps,ppt,wri,xla,xls,xlt,xlw,mdb,mpp,docx,docm,dotx,dotm,xlsx,xlsm,xlsb,xltx,xltm,xlam,pptx,pptm,ppsx,ppsm,potx,potm,ppam,sldx,sldm,onetoc,onetoc2,onetmp,onepkg
	 *
	 * // OpenOffice formats
	 * 'odt'                          => 'application/vnd.oasis.opendocument.text',
	 * 'odp'                          => 'application/vnd.oasis.opendocument.presentation',
	 * 'ods'                          => 'application/vnd.oasis.opendocument.spreadsheet',
	 * 'odg'                          => 'application/vnd.oasis.opendocument.graphics',
	 * 'odc'                          => 'application/vnd.oasis.opendocument.chart',
	 * 'odb'                          => 'application/vnd.oasis.opendocument.database',
	 * 'odf'                          => 'application/vnd.oasis.opendocument.formula',
	 *
	 * // WordPerfect formats
	 * 'wp|wpd'                       => 'application/wordperfect',
	 *
	 * // iWork formats
	 * 'key'                          => 'application/vnd.apple.keynote',
	 * 'numbers'                      => 'application/vnd.apple.numbers',
	 * 'pages'                        => 'application/vnd.apple.pages',
	 */
	add_filter( 'wp_handle_upload_prefilter', 'syn_handle_upload_prefilter' );
	function syn_handle_upload_prefilter( $file ) {
		slog( get_allowed_mime_types() );
		// First check that the site is not using the month/year option for uploads and if it is, reset the options cache and upate the option
		if ( false === get_option( 'uploads_use_yearmonth_folders' ) && false === update_option( 'uploads_use_yearmonth_folders', false ) ) {
			update_option( 'uploads_use_yearmonth_folders', 0 );
		}
		if ( ! isset( $file[ 'type' ] ) || empty( $file[ 'type' ] ) ) {
			return $file;
		}
		$mime_type_arr = explode( '/', $file[ 'type' ] );
		$mime_cat      = $mime_type_arr[ 0 ];
		$mime_subcat   = $mime_type_arr[ 1 ];
		switch ( $mime_cat ) {
			case 'image':
				add_filter( 'upload_dir', 'syn_upload_dir_image' );
				break;
			case 'video':
				add_filter( 'upload_dir', 'syn_upload_dir_video' );
				break;
			case 'text':
				add_filter( 'upload_dir', 'syn_upload_dir_text' );
				break;
			case 'audio':
				add_filter( 'upload_dir', 'syn_upload_dir_audio' );
				break;
			case 'application':
				if ( 'pdf' == $mime_subcat ) {
					add_filter( 'upload_dir', 'syn_upload_dir_pdf' );
				} elseif ( 'javascript' == $mime_subcat ) {
					add_filter( 'upload_dir', 'syn_upload_dir_js' );
				} elseif ( 'html' == $mime_subcat ) {
					add_filter( 'upload_dir', 'syn_upload_dir_html' );
				} elseif ( 'css' == $mime_subcat ) {
					add_filter( 'upload_dir', 'syn_upload_dir_css' );
				} else {
					$file_ext = pathinfo( $file[ 'name' ], PATHINFO_EXTENSION );
					if ( in_array( $file_ext, [ 'doc', 'pot', 'pps', 'ppt', 'wri', 'xla', 'xls', 'xlt', 'xlw', 'mdb', 'mpp', 'docx', 'docm', 'dotx', 'dotm', 'xlsx', 'xlsm', 'xlsb', 'xltx',
					                            'xltm', 'xlam', 'pptx', 'pptm', 'ppsx', 'ppsm', 'potx', 'potm', 'ppam', 'sldx', 'sldm', 'onetoc', 'onetoc2', 'onetmp', 'onepkg' ] ) ) {
						add_filter( 'upload_dir', 'syn_upload_dir_msoffice' );
					} else {
						add_filter( 'upload_dir', 'syn_upload_dir_other' ); // this is also the default case for the switch
					}
				}
				break;
			default:
				add_filter( 'upload_dir', 'syn_upload_dir_other' );
				break;
		}

		return $file;
	}

	//add_filter( 'upload_mimes', 'syn_upload_mimes' );
	function syn_upload_mimes( $mimes ) {
		/*
		 *  Array
			(
			    [jpg|jpeg|jpe] => image/jpeg
			    [png] => image/png
			    [gif] => image/gif
			    [mov|qt] => video/quicktime
			    [avi] => video/avi
			    [mpeg|mpg|mpe] => video/mpeg
			    [3gp|3gpp] => video/3gpp
			    [3g2|3gp2] => video/3gpp2
			    [mid|midi] => audio/midi
			    [pdf] => application/pdf
			    [doc] => application/msword
			    [docx] => application/vnd.openxmlformats-officedocument.wordprocessingml.document
			    [docm] => application/vnd.ms-word.document.macroEnabled.12
			    [pot|pps|ppt] => application/vnd.ms-powerpoint
			    [pptx] => application/vnd.openxmlformats-officedocument.presentationml.presentation
			    [pptm] => application/vnd.ms-powerpoint.presentation.macroEnabled.12
			    [odt] => application/vnd.oasis.opendocument.text
			    [ppsx] => application/vnd.openxmlformats-officedocument.presentationml.slideshow
			    [ppsm] => application/vnd.ms-powerpoint.slideshow.macroEnabled.12
			    [xla|xls|xlt|xlw] => application/vnd.ms-excel
			    [xlsx] => application/vnd.openxmlformats-officedocument.spreadsheetml.sheet
			    [xlsm] => application/vnd.ms-excel.sheet.macroEnabled.12
			    [xlsb] => application/vnd.ms-excel.sheet.binary.macroEnabled.12
			    [key] => application/vnd.apple.keynote
			    [mp3|m4a|m4b] => audio/mpeg
			    [ogg|oga] => audio/ogg
			    [flac] => audio/flac
			    [wav] => audio/wav
			    [mp4|m4v] => video/mp4
			    [webm] => video/webm
			    [ogv] => video/ogg
			    [flv] => video/x-flv
			)
		 */
		foreach ( $mimes as $key => $value ) {
			$mime_arr = explode( '/', $value );
			$mime_cat = $mime_arr[ 0 ];
			$mime_subcat = $mime_arr[ 1 ];
			if ( ! in_array( $mime_cat, [ 'image', 'audio', 'video', 'text' ] ) ) {
				$mime_exts = explode( '|' , $key );
			}
		}
	}

	/**
	 * Upload directory for images
	 */
	function syn_upload_dir_image( $params ) {
		$params[ 'subdir' ] = '';
		$params[ 'path' ]   = trailingslashit( $params[ 'path' ] ) . 'image';
		$params[ 'url' ]    = $params[ 'url' ] . '/image';
		wp_mkdir_p( $params[ 'path' ] );

		return $params;
	}

	function syn_upload_dir_video( $params ) {
		$params[ 'subdir' ] = '';
		$params[ 'path' ]   = trailingslashit( $params[ 'path' ] ) . 'video';
		$params[ 'url' ]    = $params[ 'url' ] . '/video';
		wp_mkdir_p( $params[ 'path' ] );

		return $params;
	}

	function syn_upload_dir_text( $params ) {
		$params[ 'subdir' ] = '';
		$params[ 'path' ]   = trailingslashit( $params[ 'path' ] ) . 'text';
		$params[ 'url' ]    = $params[ 'url' ] . '/text';
		wp_mkdir_p( $params[ 'path' ] );

		return $params;
	}

	function syn_upload_dir_audio( $params ) {
		$params[ 'subdir' ] = '';
		$params[ 'path' ]   = trailingslashit( $params[ 'path' ] ) . 'audio';
		$params[ 'url' ]    = $params[ 'url' ] . '/audio';
		wp_mkdir_p( $params[ 'path' ] );

		return $params;
	}

	function syn_upload_dir_pdf( $params ) {
		$params[ 'subdir' ] = '';
		$params[ 'path' ]   = trailingslashit( $params[ 'path' ] ) . 'pdf';
		$params[ 'url' ]    = $params[ 'url' ] . '/pdf';
		wp_mkdir_p( $params[ 'path' ] );

		return $params;
	}

	function syn_upload_dir_html( $params ) {
		$params[ 'subdir' ] = '';
		$params[ 'path' ]   = trailingslashit( $params[ 'path' ] ) . 'html';
		$params[ 'url' ]    = $params[ 'url' ] . '/html';
		wp_mkdir_p( $params[ 'path' ] );

		return $params;
	}

	function syn_upload_dir_js( $params ) {
		$params[ 'subdir' ] = '';
		$params[ 'path' ]   = trailingslashit( $params[ 'path' ] ) . 'js';
		$params[ 'url' ]    = $params[ 'url' ] . '/js';
		wp_mkdir_p( $params[ 'path' ] );

		return $params;
	}

	function syn_upload_dir_css( $params ) {
		$params[ 'subdir' ] = '';
		$params[ 'path' ]   = trailingslashit( $params[ 'path' ] ) . 'css';
		$params[ 'url' ]    = $params[ 'url' ] . '/css';
		wp_mkdir_p( $params[ 'path' ] );

		return $params;
	}

	function syn_upload_dir_msoffice( $params ) {
		$params[ 'subdir' ] = '';
		$params[ 'path' ]   = trailingslashit( $params[ 'path' ] ) . 'msoffice';
		$params[ 'url' ]    = $params[ 'url' ] . '/msoffice';
		wp_mkdir_p( $params[ 'path' ] );

		return $params;
	}

	function syn_upload_dir_other( $params ) {
		$params[ 'subdir' ] = '';
		$params[ 'path' ]   = trailingslashit( $params[ 'path' ] ) . 'other';
		$params[ 'url' ]    = $params[ 'url' ] . '/other';
		wp_mkdir_p( $params[ 'path' ] );

		return $params;
	}

	/**
	 * Redirect attachment pages to parent
	 */
	add_action( 'template_redirect', 'syn_redirect_attachments' );
	function syn_redirect_attachments() {
		global $post;
		if ( is_attachment() && isset( $post->post_parent ) && is_numeric( $post->post_parent ) && ( $post->post_parent != 0 ) ) {
			wp_redirect( get_permalink( $post->post_parent ), 301 );
			exit;
			wp_reset_postdata();
		}
	}

	// Limit media library grid access to own files for roles less than editor
	add_filter( 'ajax_query_attachments_args', 'syn_limit_media_library_grid' );
	function syn_limit_media_library_grid( $query ) {
		$user_id = get_current_user_id();
		if ( $user_id && ! syn_current_user_can( 'editor' ) ) {
			$query[ 'author' ] = $user_id;
		}

		return $query;
	}

	// Prevent users < editor from accessing media other than their own
	add_filter( 'pre_get_posts', 'syn_limit_media_library_list' );
	function syn_limit_media_library_list( $query ) {
		global $pagenow;
		global $user_ID;
		if ( is_admin() && 'upload.php' == $pagenow && ! syn_current_user_can( 'editor' ) ) {
			$query->set( 'author', $user_ID );
		}

		return $query;
	}

	// Convert PDF search result links from attachment page to the PDF file itself
	//add_filter( 'the_permalink', 'syn_force_direct_pdf_links', 10, 2 );
	add_filter( 'attachment_link', 'syn_force_direct_pdf_links', 10, 2 );
	function syn_force_direct_pdf_links( $permalink, $post_id ) {
		//global $post;
		if ( 'application/pdf' == get_post_mime_type( $post_id ) ) {
			// if the result is a PDF, link directly to the file not the attachment page
			$permalink = wp_get_attachment_url( $post_id );
		}

		return esc_url( $permalink );
	}

	////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	/// ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	/// ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	/// ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	/// ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	/// ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	/// ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	/// ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	// Boneyard
	////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	/// ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	/// ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	/// ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	/// ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	/// ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	/// ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	///
	/**
	 * File site uploads in folders according to mime type - images, videos, audio, applications
	 *
	 * $uploads is an array with the following keys
	 * [path] - base directory and sub directory or full path to upload directory.
	 * [url] - base url and sub directory or absolute URL to upload directory.
	 * [subdir] - sub directory if uploads use year/month folders option is on (e.g. '/2018/05')
	 * [basedir] - path without subdir.
	 * [baseurl] - URL path without subdir.
	 * [error] - set to false.
	 */
	//add_filter( 'upload_dir', 'syn_upload_dir' );
	function syn_upload_dir_0( $uploads ) {
		// The base directory and URL are set to /uploads in the webroot (vs /wp-content/uploads) by the UPLOADS constant in wp-config.php
		$uploads[ 'path' ] = '';
		$uploads[ 'url' ]  = '';
		//$uploads['subdir'] = '';
		//$uploads['basedir'] = '';
		//$uploads['baseurl'] = '';
		//$uploads['error'] = 0;
	}

	/**
	 * Filters the uploads directory data.
	 *
	 * @param array $uploads Array of upload directory data with keys of 'path', 'url', 'subdir, 'basedir', and 'error'.
	 */
	//add_filter( 'upload_dir', 'syn_upload_dir' );
	function ___syn_upload_dir( $uploads ) {
		/*slog( '--------------$_FILES' );
		//slog( $_FILES );
		//slog( '--------------$_POST' );
		//slog( $_POST );*/
		//slog( '--------------$_REQUEST' );
		//slog( $_REQUEST );
		//$uploads['basedir'] = 'C:\xampp\htdocs\master\uploads';
		//$uploads['baseurl'] = 'http://master.localhost/uploads';
		//$uploads['path'] = $uploads['basedir'];
		//$uploads['url'] = $uploads['baseurl'];
		//$uploads['subdir'] = '\subdir';
		//slog( '+++++++++++++++++++++++++++ $uploads +++++++++++++++++++++++++++' );
		//slog( $uploads );
		//return $uploads;
		/*
		 * Determines if uploading from inside a post/page/cpt - if not, default upload directory is used
		 */
		//$use_default_dir = ( isset( $_REQUEST[ 'post_id' ] ) && $_REQUEST[ 'post_id' ] == 0 ) ? true : false;
		if ( ! empty( $uploads[ 'error' ] ) ) { //|| $use_default_dir
			return $uploads;
		}
		$doc_root = $_SERVER[ 'DOCUMENT_ROOT' ];
		$host     = $_SERVER[ 'HTTP_HOST' ];
		$http     = isset( $_SERVER[ 'HTTPS' ] ) && $_SERVER[ 'HTTPS' ] === 'on' ? 'https' : 'http';
		//slog( '--------------$_REQUEST' );
		//slog( $_REQUEST );
		if ( $_FILES ) {
			//slog( '--------------$_FILES' );
			//slog( $_FILES );
			$file_type     = $_FILES[ 'async-upload' ][ 'type' ];
			$file_type_arr = explode( '/', $file_type );
			$mime_cat      = $file_type_arr[ 0 ];
			switch ( $mime_cat ) {
				case 'image':
					$type_dir = 'images';
					break;
				case 'video':
					$type_dir = 'videos';
					break;
				case 'audio':
					$type_dir = 'audio';
					break;
				case 'text':
					$type_dir = 'text';
					break;
				case 'application':
					if ( 'pdf' == $file_type_arr[ 1 ] ) {
						$type_dir = 'pdfs';
					} else {
						$type_dir = 'others';
					}
					break;
				default:
					$type_dir = 'other';
					break;
			}
		} elseif ( $_POST[ 'name' ] ) {
			//slog( '--------------$_POST' );
			//slog( $_POST );
			$ext = substr( strrchr( $_POST[ 'name' ], '.' ), 1 );
			switch ( $ext ) {
				case 'jpg':
				case 'jpeg':
				case 'png':
				case 'gif':
					$type_dir = 'images';
					break;
				case 'wmv':
				case 'avi':
				case 'flv':
				case 'mov':
				case 'qt':
				case 'mpeg':
				case 'mpg':
				case 'mpe':
				case 'mp4':
				case 'webm':
				case 'mkv':
					$type_dir = 'videos';
					break;
				case 'mp3':
				case 'ram':
				case 'wav':
				case 'mid':
				case 'midi':
				case 'wma':
				case 'ra':
					$type_dir = 'audio';
					break;
				case 'txt':
				case 'csv':
				case 'css':
				case 'htm':
				case 'html':
					$type_dir = 'text';
					break;
				case 'pdf':
					$type_dir = 'pdfs';
					break;
				default:
					$type_dir = 'others';
					break;
			}
		}
		if ( is_multisite() ) {
			$blog_id              = get_current_blog_id();
			$uploads[ 'basedir' ] = $doc_root . '/uploads/sites/' . $blog_id . '/' . $type_dir;
			$uploads[ 'baseurl' ] = $http . '://' . $host . '/uploads/sites/' . $blog_id . '/' . $type_dir;
		} else {
			$uploads[ 'basedir' ] = $doc_root . '/uploads/' . $type_dir;
			$uploads[ 'baseurl' ] = $http . '://' . $host . '/uploads/' . $type_dir;
		}
		/*if ( 'image' == $file_type_arr[ 0 ] ) {
			$uploads[ 'basedir' ] = $uploads[ 'basedir' ] . '/images';
			$uploads[ 'baseurl' ] = $uploads[ 'baseurl' ] . '/images';
		} elseif ( 'text' == $file_type_arr[ 0 ] ) {
			$uploads[ 'basedir' ] = $uploads[ 'basedir' ] . '/text';
			$uploads[ 'baseurl' ] = $uploads[ 'baseurl' ] . '/text';
		} elseif ( 'application' == $file_type_arr[ 0 ] ) {
			$uploads[ 'basedir' ] = $uploads[ 'basedir' ] . '/applications';
			$uploads[ 'baseurl' ] = $uploads[ 'baseurl' ] . '/applications';
		} elseif ( 'video' == $file_type_arr[ 0 ] ) {
			$uploads[ 'basedir' ] = $uploads[ 'basedir' ] . '/videos';
			$uploads[ 'baseurl' ] = $uploads[ 'baseurl' ] . '/videos';
		} elseif ( 'audio' == $file_type_arr[ 0 ] ) {
			$uploads[ 'basedir' ] = $uploads[ 'basedir' ] . '/audios';
			$uploads[ 'baseurl' ] = $uploads[ 'baseurl' ] . '/audios';
		} else {
			$uploads[ 'basedir' ] = $uploads[ 'basedir' ] . '/other';
			$uploads[ 'baseurl' ] = $uploads[ 'baseurl' ] . '/other';
		}*/
		/*} else {
			slog('files do not exist');
		}*/
		//error or uploading not from a post/page/cpt
		/*
		 * Save uploads in ID based folders
		 *
		 */
		/*
		 $customdir = '/' . $_REQUEST['post_id'];
		*/
		/*
		 * Save uploads in SLUG based folders
		 *
		 */
		/*
		 * $the_post  = get_post( $_REQUEST[ 'post_id' ] );
		$customdir = '/' . $the_post->post_name;
		*/
		/*
		 * Save uploads in AUTHOR based folders
		 *
		 * ATTENTION, CAUTION REQUIRED:
		 * This one may have security implications as you will be exposing the user names in the media paths
		 * Here, the *display_name* is being used, but normally it is the same as *user_login*
		 *
		 * The right thing to do would be making the first/last name mandatories
		 * And use:
		 * $customdir = '/' . $the_author->first_name . $the_author->last_name;
		 *
		 */
		/*
		  $the_post = get_post($_REQUEST['post_id']);
		  $the_author = get_user_by('id', $the_post->post_author);
		  $customdir = '/' . $the_author->data->display_name;
		*/
		/*
		 * Save uploads in FILETYPE based folders
		 * when using this method, you may want to change the check for $use_default_dir
		 *
		 */
		//$path[ 'path' ]   = str_replace( $path[ 'subdir' ], '', $path[ 'path' ] ); //remove default subdir (year/month)
		//$path[ 'url' ]    = str_replace( $path[ 'subdir' ], '', $path[ 'url' ] );
		//$path[ 'subdir' ] = $customdir;
		$uploads[ 'subdir' ] = '';
		$uploads[ 'path' ]   = $uploads[ 'basedir' ];
		$uploads[ 'url' ]    = $uploads[ 'baseurl' ];
		//slog( '+++++++++++++++++++++++++++ $uploads +++++++++++++++++++++++++++' );
		//slog( $uploads );
		return $uploads;
	}

	//add_filter( 'upload_dir', 'syn_upload_dir__' );
	function syn_upload_dir__( $params ) {
		if ( empty( $_REQUEST ) || 'heartbeat' == $_REQUEST[ 'action' ] || 'wp-remove-post-lock' == $_REQUEST[ 'action' ] ) {
			return;
		}
		//slog( '---$_REQUEST----' );
		//slog( $_REQUEST );
		$doc_root = $_SERVER[ 'DOCUMENT_ROOT' ];
		$host     = $_SERVER[ 'HTTP_HOST' ];
		$http     = isset( $_SERVER[ 'HTTPS' ] ) && $_SERVER[ 'HTTPS' ] === 'on' ? 'https' : 'http';
		if ( is_multisite() ) {
			$blog_id             = get_current_blog_id();
			$params[ 'basedir' ] = $doc_root . '/uploads/sites/' . $blog_id;
			$params[ 'baseurl' ] = $http . '://' . $host . '/uploads/sites/' . $blog_id;
		} else {
			$params[ 'basedir' ] = $doc_root . '/uploads';
			$params[ 'baseurl' ] = $http . '://' . $host . '/uploads';
		}
		if ( $_FILES ) {
			$file_type     = $_FILES[ 'async-upload' ][ 'type' ];
			$file_type_arr = explode( '/', $file_type );
			if ( 'image' == $file_type_arr[ 0 ] ) {
				$params[ 'basedir' ] = $params[ 'basedir' ] . '/images';
				$params[ 'baseurl' ] = $params[ 'baseurl' ] . '/images';
			} elseif ( 'text' == $file_type_arr[ 0 ] ) {
				$params[ 'basedir' ] = $params[ 'basedir' ] . '/text';
				$params[ 'baseurl' ] = $params[ 'baseurl' ] . '/text';
			} elseif ( 'application' == $file_type_arr[ 0 ] ) {
				$params[ 'basedir' ] = $params[ 'basedir' ] . '/applications';
				$params[ 'baseurl' ] = $params[ 'baseurl' ] . '/applications';
			} elseif ( 'video' == $file_type_arr[ 0 ] ) {
				$params[ 'basedir' ] = $params[ 'basedir' ] . '/videos';
				$params[ 'baseurl' ] = $params[ 'baseurl' ] . '/videos';
			} elseif ( 'audio' == $file_type_arr[ 0 ] ) {
				$params[ 'basedir' ] = $params[ 'basedir' ] . '/audios';
				$params[ 'baseurl' ] = $params[ 'baseurl' ] . '/audios';
			} else {
				$params[ 'basedir' ] = $params[ 'basedir' ] . '/other';
				$params[ 'baseurl' ] = $params[ 'baseurl' ] . '/other';
			}
		} else {
			//slog( 'files do not exist' );
		}
		$params[ 'path' ] = $params[ 'basedir' ];
		$params[ 'url' ]  = $params[ 'baseurl' ];

		//slog ($params);
		return $params;
	}

	//add_filter( 'wp_handle_upload_prefilter', 'syn_handle_upload_prefilter__' );
	function syn_handle_upload_prefilter__( $upload ) {
		//slog( 'Now syn_handle_upload' );
		$type        = $upload[ 'type' ];
		$type_arr    = explode( '/', $type );
		$type_cat    = $type_arr[ 0 ];
		$type_subcat = $type_arr[ 1 ];
		// upload image files (png, jpg, gif, etc)
		if ( 'image' == $type_cat ) {
			add_filter( 'upload_dir', 'syn_image_upload_dir' );

			//slog( $upload );
			return $upload;
		}
		// upload PDF files
		if ( 'pdf' == $type_subcat ) {
			add_filter( 'upload_dir', 'syn_pdf_upload_dir' );

			return $upload;
		}
		// upload video files (wmv, avi, mp4, mov, mpeg, etc)
		if ( 'video' == $type_cat ) {
			add_filter( 'upload_dir', 'syn_video_upload_dir' );

			return $upload;
		}
		// upload audio files (mpeg, mp3, wav, wma, etc)
		if ( 'audio' == $type_cat ) {
			add_filter( 'upload_dir', 'syn_audio_upload_dir' );

			return $upload;
		}
		// upload text files (csv, html, css, etc)
		if ( 'text' == $type_cat ) {
			add_filter( 'upload_dir', 'syn_text_upload_dir' );

			return $upload;
		}
		// upload application files such as MS Office, Open Office, etc
		if ( 'application' == $type_cat ) {
			add_filter( 'upload_dir', 'syn_application_upload_dir' );

			return $upload;
		}
		// upload all other types of files
		add_filter( 'upload_dir', 'syn_other_upload_dir' );

		return $upload;
		/*$file_arr = explode( '.', $upload[ 'file' ] );
		$file_arr_len = count( $file_arr );
		$file_ext = $file_arr[ $file_arr_len - 1 ];
		if ( in_array( $file_ext, [ 'doc', 'docx', 'xls', 'xlsx', 'ppt', 'pptx', 'pub', 'pubx', ])) {
			add_filter( 'upload_dir', 'syn_msoffice_upload_dir' );
			return $upload;
		}*/
		/*slog( '---$upload------');
		//slog( $upload );
		//slog( '----$context----');
		//slog( $context );
		return $upload;*/
	}