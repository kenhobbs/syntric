<?php
/**
 * Register Media Categories and File Types taxonomies
 */
add_action( 'init', 'syn_register_media_taxonomies', 1 );
function syn_register_media_taxonomies() {
	// media_category taxonomy & defaults
	$tax_args = array(
		'labels'             => array(
			'name'          => _x( 'Media Categories', 'syntric' ),
			'singular_name' => _x( 'Media Category', 'syntric' ),
			'search_items'  => __( 'Search Media Categories', 'syntric' ),
			'all_items'     => __( 'Media Categories', 'syntric' ),
			'edit_item'     => __( 'Edit Media Category', 'syntric' ),
			'update_item'   => __( 'Update Media Category', 'syntric' ),
			'add_new_item'  => __( 'Add New Media Category', 'syntric' ),
			'new_item_name' => __( 'New Media Category Name', 'syntric' ),
			'menu_name'     => __( 'Categories', 'syntric' ),
		),
		'description'        => 'Media categories',
		'public'             => false,
		'publicly_queryable' => false,
		'hierarchical'       => true,
		'show_ui'            => true,
		'show_in_menu'       => true,
		'show_in_nav_menus'  => false,
		'show_in_rest'       => true,
		'rest_base'          => 'media-categories',
		'show_in_quick_edit' => true,
		'show_admin_column'  => true,
		'rewrite'            => false,
		'query_var'          => false,
	);
	register_taxonomy( 'media_category', 'attachment', $tax_args );
	if ( ! term_exists( 'Images', 'media_category' ) ) {
		$media_categories = array(
			'Images',
			'Publications',
			'Documents',
			'Forms',
		);
		foreach ( $media_categories as $media_category ) {
			$res            = wp_insert_term( $media_category, 'media_category' );
			$sub_categories = array();
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
					wp_insert_term( $sub_category, 'media_category', array( 'parent' => $term_id ) );
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
 * Handle uploads, direct to custom folders according to file type
 */
add_filter( 'wp_handle_upload_prefilter', 'syn_handle_upload_filter' );
function syn_handle_upload_filter( $file ) {
	global $pagenow;
	if ( $pagenow == 'upload.php' || $pagenow == 'async-upload.php' ) {
		$file_type     = $file[ 'type' ];
		$file_type_arr = explode( '/', $file_type );
		if ( $file_type_arr[ 0 ] == 'image' ) {
			add_filter( 'upload_dir', 'syn_image_upload_dir' );

			return $file;
		}
		if ( $file_type_arr[ 1 ] == 'pdf' ) {
			add_filter( 'upload_dir', 'syn_pdf_upload_dir' );

			return $file;
		}
		$file_ext_arr     = explode( '.', $file[ 'name' ] );
		$file_ext_arr_pos = count( $file_ext_arr ) - 1;
		if ( in_array( $file_ext_arr[ $file_ext_arr_pos ], array( 'doc', 'docx', 'xls', 'xlsx', 'ppt', 'pptx' ) ) ) {
			add_filter( 'upload_dir', 'syn_msoffice_upload_dir' );

			return $file;
		}
		add_filter( 'upload_dir', 'syn_other_upload_dir' );
	}

	return $file;
}

/**
 * Upload directory for PDFs
 */
function syn_pdf_upload_dir( $param ) {
	$pdf_dir         = '/pdfs';
	$param[ 'path' ] = $param[ 'path' ] . $pdf_dir;
	$param[ 'url' ]  = $param[ 'url' ] . $pdf_dir;

	return $param;
}

/**
 * Upload directory for images
 */
function syn_image_upload_dir( $param ) {
	$pdf_dir         = '/images';
	$param[ 'path' ] = $param[ 'path' ] . $pdf_dir;
	$param[ 'url' ]  = $param[ 'url' ] . $pdf_dir;

	return $param;
}

/**
 * Upload directory for Microsoft Office files
 */
function syn_msoffice_upload_dir( $param ) {
	$pdf_dir         = '/msoffice';
	$param[ 'path' ] = $param[ 'path' ] . $pdf_dir;
	$param[ 'url' ]  = $param[ 'url' ] . $pdf_dir;

	return $param;
}

/**
 * Upload directory for "other" files
 */
function syn_other_upload_dir( $param ) {
	$pdf_dir         = '/files';
	$param[ 'path' ] = $param[ 'path' ] . $pdf_dir;
	$param[ 'url' ]  = $param[ 'url' ] . $pdf_dir;

	return $param;
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