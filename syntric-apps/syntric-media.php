<?php


/**
 * Register Media Categories and File Types taxonomies
 */
add_action( 'init', 'syntric_register_media_taxonomies', 1 );
function syntric_register_media_taxonomies() {
	// media_category taxonomy & defaults
	$tax_args = [ 'labels' => [ 'name'         => _x( 'Media Categories', 'syntric' ), 'singular_name' => _x( 'Media Category', 'syntric' ),
	                            'search_items' => __( 'Search Media Categories', 'syntric' ), 'all_items' => __( 'Media Categories', 'syntric' ),
	                            'edit_item'    => __( 'Edit Media Category', 'syntric' ), 'update_item' => __( 'Update Media Category', 'syntric' ),
	                            'add_new_item' => __( 'Add New Media Category', 'syntric' ), 'new_item_name' => __( 'New Media Category Name', 'syntric' ),
	                            'menu_name'    => __( 'Categories', 'syntric' ), ], 'description' => 'Media categories', 'public' => false, 'publicly_queryable' => false,
	              'hierarchical' => true, 'show_ui' => true, 'show_in_menu' => true, 'show_in_nav_menus' => false, 'show_in_rest' => true, 'rest_base' => 'media-categories',
	              'show_in_quick_edit' => true, 'show_admin_column' => true, 'rewrite' => false, 'query_var' => false, ];
	register_taxonomy( 'media_category', 'attachment', $tax_args );
	if( ! term_exists( 'Images', 'media_category' ) ) {
		$media_categories = [ 'Images', 'Publications', 'Documents', 'Forms', ];
		foreach( $media_categories as $media_category ) {
			$res            = wp_insert_term( $media_category, 'media_category' );
			$sub_categories = [];
			if( is_array( $res ) && ! is_wp_error( $res ) ) {
				$term_id = $res[ 'term_id' ];
				if( 'Images' == $media_category ) {
					$sub_categories[] = 'Banner';
					$sub_categories[] = 'Headshot';
				}
				if( 'Publications' == $media_category ) {
					$sub_categories[] = 'Handbook';
					$sub_categories[] = 'Newsletter';
					$sub_categories[] = 'Calendar';
				}
				if( 'Documents' == $media_category ) {
					$sub_categories[] = 'Report';
					$sub_categories[] = 'Legal';
					$sub_categories[] = 'Communication';
				}
				if( 'Forms' == $media_category ) {
					$sub_categories[] = 'Enrollment';
					$sub_categories[] = 'Athletics';
					$sub_categories[] = 'Activities';
				}
				foreach( $sub_categories as $sub_category ) {
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
add_filter( 'wp_dropdown_cats', 'syntric_dropdown_cats', 10 );
function syntric_dropdown_cats( $dropdown ) {
	global $pagenow;
	if( is_admin() && ( 'upload.php' == $pagenow ) ) {
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
add_filter( 'wp_handle_upload_prefilter', 'syntric_handle_upload_prefilter' );
function syntric_handle_upload_prefilter( $file ) {
	// First check that the site is not using the month/year option for uploads and if it is, reset the options cache and upate the option
	if( false === get_option( 'uploads_use_yearmonth_folders' ) && false === update_option( 'uploads_use_yearmonth_folders', false ) ) {
		update_option( 'uploads_use_yearmonth_folders', 0 );
	}
	if( ! isset( $file[ 'type' ] ) || empty( $file[ 'type' ] ) ) {
		return $file;
	}
	$mime_type_arr = explode( '/', $file[ 'type' ] );
	$mime_cat      = $mime_type_arr[ 0 ];
	$mime_subcat   = $mime_type_arr[ 1 ];
	switch( $mime_cat ) {
		case 'image':
			add_filter( 'upload_dir', 'syntric_upload_dir_image' );
		break;
		case 'video':
			add_filter( 'upload_dir', 'syntric_upload_dir_video' );
		break;
		case 'text':
			add_filter( 'upload_dir', 'syntric_upload_dir_text' );
		break;
		case 'audio':
			add_filter( 'upload_dir', 'syntric_upload_dir_audio' );
		break;
		case 'application':
			if( 'pdf' == $mime_subcat ) {
				add_filter( 'upload_dir', 'syntric_upload_dir_pdf' );
			} elseif( 'javascript' == $mime_subcat ) {
				add_filter( 'upload_dir', 'syntric_upload_dir_js' );
			} elseif( 'html' == $mime_subcat ) {
				add_filter( 'upload_dir', 'syntric_upload_dir_html' );
			} elseif( 'css' == $mime_subcat ) {
				add_filter( 'upload_dir', 'syntric_upload_dir_css' );
			} else {
				$file_ext = pathinfo( $file[ 'name' ], PATHINFO_EXTENSION );
				if( in_array( $file_ext, [ 'doc', 'pot', 'pps', 'ppt', 'wri', 'xla', 'xls', 'xlt', 'xlw', 'mdb', 'mpp', 'docx', 'docm', 'dotx', 'dotm', 'xlsx', 'xlsm', 'xlsb', 'xltx',
				                           'xltm', 'xlam', 'pptx', 'pptm', 'ppsx', 'ppsm', 'potx', 'potm', 'ppam', 'sldx', 'sldm', 'onetoc', 'onetoc2', 'onetmp', 'onepkg' ] ) ) {
					add_filter( 'upload_dir', 'syntric_upload_dir_msoffice' );
				} else {
					add_filter( 'upload_dir', 'syntric_upload_dir_other' ); // this is also the default case for the switch
				}
			}
		break;
		default:
			add_filter( 'upload_dir', 'syntric_upload_dir_other' );
		break;
	}

	return $file;
}

/**
 * Upload directory for images
 */
function syntric_upload_dir_image( $params ) {
	$params[ 'subdir' ] = '';
	$params[ 'path' ]   = trailingslashit( $params[ 'path' ] ) . 'image';
	$params[ 'url' ]    = $params[ 'url' ] . '/image';
	wp_mkdir_p( $params[ 'path' ] );

	return $params;
}

function syntric_upload_dir_video( $params ) {
	$params[ 'subdir' ] = '';
	$params[ 'path' ]   = trailingslashit( $params[ 'path' ] ) . 'video';
	$params[ 'url' ]    = $params[ 'url' ] . '/video';
	wp_mkdir_p( $params[ 'path' ] );

	return $params;
}

function syntric_upload_dir_text( $params ) {
	$params[ 'subdir' ] = '';
	$params[ 'path' ]   = trailingslashit( $params[ 'path' ] ) . 'text';
	$params[ 'url' ]    = $params[ 'url' ] . '/text';
	wp_mkdir_p( $params[ 'path' ] );

	return $params;
}

function syntric_upload_dir_audio( $params ) {
	$params[ 'subdir' ] = '';
	$params[ 'path' ]   = trailingslashit( $params[ 'path' ] ) . 'audio';
	$params[ 'url' ]    = $params[ 'url' ] . '/audio';
	wp_mkdir_p( $params[ 'path' ] );

	return $params;
}

function syntric_upload_dir_pdf( $params ) {
	$params[ 'subdir' ] = '';
	$params[ 'path' ]   = trailingslashit( $params[ 'path' ] ) . 'pdf';
	$params[ 'url' ]    = $params[ 'url' ] . '/pdf';
	wp_mkdir_p( $params[ 'path' ] );

	return $params;
}

function syntric_upload_dir_html( $params ) {
	$params[ 'subdir' ] = '';
	$params[ 'path' ]   = trailingslashit( $params[ 'path' ] ) . 'html';
	$params[ 'url' ]    = $params[ 'url' ] . '/html';
	wp_mkdir_p( $params[ 'path' ] );

	return $params;
}

function syntric_upload_dir_js( $params ) {
	$params[ 'subdir' ] = '';
	$params[ 'path' ]   = trailingslashit( $params[ 'path' ] ) . 'js';
	$params[ 'url' ]    = $params[ 'url' ] . '/js';
	wp_mkdir_p( $params[ 'path' ] );

	return $params;
}

function syntric_upload_dir_css( $params ) {
	$params[ 'subdir' ] = '';
	$params[ 'path' ]   = trailingslashit( $params[ 'path' ] ) . 'css';
	$params[ 'url' ]    = $params[ 'url' ] . '/css';
	wp_mkdir_p( $params[ 'path' ] );

	return $params;
}

function syntric_upload_dir_msoffice( $params ) {
	$params[ 'subdir' ] = '';
	$params[ 'path' ]   = trailingslashit( $params[ 'path' ] ) . 'msoffice';
	$params[ 'url' ]    = $params[ 'url' ] . '/msoffice';
	wp_mkdir_p( $params[ 'path' ] );

	return $params;
}

function syntric_upload_dir_other( $params ) {
	$params[ 'subdir' ] = '';
	$params[ 'path' ]   = trailingslashit( $params[ 'path' ] ) . 'other';
	$params[ 'url' ]    = $params[ 'url' ] . '/other';
	wp_mkdir_p( $params[ 'path' ] );

	return $params;
}

/**
 * Redirect attachment pages to parent
 */
add_action( 'template_redirect', 'syntric_redirect_attachments' );
function syntric_redirect_attachments() {
	global $post;
	if( is_attachment() && isset( $post -> post_parent ) && is_numeric( $post -> post_parent ) && ( $post -> post_parent != 0 ) ) {
		wp_redirect( get_permalink( $post -> post_parent ), 301 );
		exit;
		wp_reset_postdata();
	}
}

// Limit media library grid access to own files for roles less than editor
add_filter( 'ajax_query_attachments_args', 'syntric_limit_media_library_grid' );
function syntric_limit_media_library_grid( $query ) {
	$user_id = get_current_user_id();
	if( $user_id && ! syntric_current_user_can( 'editor' ) ) {
		$query[ 'author' ] = $user_id;
	}

	return $query;
}

// Prevent users < editor from accessing media other than their own
add_filter( 'pre_get_posts', 'syntric_limit_media_library_list' );
function syntric_limit_media_library_list( $query ) {
	global $pagenow;
	global $user_ID;
	if( is_admin() && 'upload.php' == $pagenow && ! syntric_current_user_can( 'editor' ) ) {
		$query -> set( 'author', $user_ID );
	}

	return $query;
}

// Convert PDF search result links from attachment page to the PDF file itself
//add_filter( 'the_permalink', 'syntric_force_direct_pdf_links', 10, 2 );
add_filter( 'attachment_link', 'syntric_force_direct_pdf_links', 10, 2 );
function syntric_force_direct_pdf_links( $permalink, $post_id ) {
	//global $post;
	if( 'application/pdf' == get_post_mime_type( $post_id ) ) {
		// if the result is a PDF, link directly to the file not the attachment page
		$permalink = wp_get_attachment_url( $post_id );
	}

	return esc_url( $permalink );
}