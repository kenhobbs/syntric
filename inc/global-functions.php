<?php
	
	function syntric_current_theme() {
		$theme = wp_get_theme();
		
		return $theme -> get( 'Name' );
	}
	
	function syntric_get_home_page() {
		$home_page = get_page_by_path( 'home' );
		if( ! $home_page instanceof WP_Post ) {
			$home_props = [ 'post_title'   => 'Home',
			                'post_content' => '',
			                'post_type'    => 'page',
			                'post_name'    => 'home',
			                'post_status'  => 'publish',
			                'post_author'  => get_current_user_id(),
			                'post_parent'  => 0,
			                'menu_order'   => 0, ];
			$home_id    = wp_insert_post( $home_props );
			$home_page  = get_post( $home_id, OBJECT );
			update_post_meta( $home_id, '_np_nav_status', 'hide' );
		}
		
		return $home_page;
	}
	
	function syntric_get_posts_page() {
		$posts_page = get_page_by_path( 'news' );
		if( ! $posts_page instanceof WP_Post ) {
			$posts_props = [ 'post_title'   => 'News',
			                 'post_content' => '',
			                 'post_type'    => 'page',
			                 'post_name'    => 'news',
			                 'post_status'  => 'publish',
			                 'post_author'  => get_current_user_id(),
			                 'post_parent'  => 0,
			                 'menu_order'   => 1, ];
			$posts_id    = wp_insert_post( $posts_props );
			$posts_page  = get_post( $posts_id, OBJECT );
		}
		
		return $posts_page;
	}
	
	function syntric_get_privacy_policy_page() {
		$pp_page = get_page_by_path( 'privacy-policy' );
		if( ! $pp_page instanceof WP_Post ) {
			$pp_props = [ 'post_title'   => 'Privacy Policy',
			              'post_content' => '',
			              'post_type'    => 'page',
			              'post_name'    => 'privacy-policy',
			              'post_status'  => 'publish',
			              'post_author'  => get_current_user_id(),
			              'post_parent'  => 0,
			              'menu_order'   => 999999, ];
			$pp_id    = wp_insert_post( $pp_props );
			$pp_page  = get_post( $pp_id, OBJECT );
			update_post_meta( $pp_id, '_np_nav_status', 'hide' );
		}
		
		return $pp_page;
	}
	
	function syntric_linebreak() {
		if( syntric_remove_whitespace() ) {
			return '';
		}
		
		return "\n";
	}
	
	function syntric_tab() {
		if( syntric_remove_whitespace() ) {
			return '';
		}
		
		return "\t";
	}
	
	// todo: improve the next 3 (syntric_is_dev, syntric_is_staging, syntric_remove_whitespace), they get run every request
	function syntric_is_dev() {
		$host_parts     = explode( '.', $_SERVER[ 'HTTP_HOST' ] );
		$last_host_part = $host_parts[ count( $host_parts ) - 1 ];
		if( 'localhost' == $last_host_part || $_SERVER[ 'SERVER_ADDR' ] == '127.0.0.1' || '::1' == $_SERVER[ 'SERVER_ADDR' ] ) {
			return true;
		}
		
		return false;
	}
	
	function syntric_is_staging() {
		$host_parts             = explode( '.', $_SERVER[ 'HTTP_HOST' ] );
		$next_to_last_host_part = $host_parts[ count( $host_parts ) - 2 ];
		$last_host_part         = $host_parts[ count( $host_parts ) - 1 ];
		if( 'www.syntric.com' == $_SERVER[ 'HTTP_HOST' ] || 'syntric.com' == $_SERVER[ 'HTTP_HOST' ] ) {
			return false;
		}
		if( 'localhost' == $last_host_part ) {
			return false;
		}
		if( 3 == count( $host_parts ) && 'syntric' == $next_to_last_host_part && 'com' == $last_host_part ) {
			return true;
		}
		
		return false;
	}
	
	function syntric_remove_whitespace() {
		if( ! syntric_is_dev() || is_admin() ) {
			return true;
		}
		
		return false;
	}
	
	function syntric_get_top_ancestor_id( $post_id ) {
		$p = get_post( $post_id );
		if( $p -> post_parent ) {
			$ancestors = array_reverse( get_post_ancestors( $post_id ) );
			
			return $ancestors[ 0 ];
		}
		
		return $post_id;
	}
	
	function syntric_generate_permanent_id() {
		$random1 = mt_rand( 1, mt_getrandmax() );
		$random2 = mt_rand( 1000, mt_getrandmax() );
		$random3 = mt_rand( 1000000, mt_getrandmax() );
		$value   = 'id-' . $random1 . '-' . $random2 . '-' . $random3;
		
		return $value;
	}
	
	function syntric_generate_random_number( $min = 1, $max = 10000 ) {
		$random = mt_rand( $min, $max );
		
		return $random;
	}
	
	function syntric_generate_password() {
		return wp_generate_password( 12, true );
	}
	
	function syntric_sanitize_string_to_class( $string ) {
		$class = strtolower( $string );
		$class = str_replace( ' ', '-', $class );
		$class = preg_replace( "/[^a-z0-9-]/", "", $class );
		$class = preg_replace( "/[-]+/", "-", $class );
		$class = sanitize_html_class( $class );
		
		return $class;
	}
	
	function syntric_get_taxonomies_terms() {
		global $post;
		$ret         = '';
		$taxes_terms = wp_get_object_terms( $post -> ID, get_object_taxonomies( $post ), [ 'orderby' => 'term_group' ] );
		if( count( $taxes_terms ) ) {
			foreach( $taxes_terms as $tax_term ) {
				if( 'microblog' == $tax_term -> taxonomy ) {
					$ret = $tax_term -> name;
					
					return $ret;
				} elseif( 'category' == $tax_term -> taxonomy && 'microblogs' != $tax_term -> slug ) {
					$ret = $tax_term -> name;
					
					return $ret;
				}
			}
		}
		
		return $ret;
	}
	
	add_filter( 'get_the_archive_title', 'syntric_get_archive_title' );
	function syntric_get_archive_title( $title ) {
		if( is_archive() ) {
			//$title = post_type_archive_title();
			$title = single_cat_title( '', false );
		} elseif( is_category() ) {
			$title = single_cat_title( '', false );
		} elseif( is_tag() ) {
			$title = single_tag_title( '', false );
		} elseif( is_author() ) {
			$title = '<span class="vcard">' . get_the_author() . '</span>';
		} elseif( is_post_type_archive() ) {
			$title = post_type_archive_title( '', false );
		} elseif( is_tax() ) {
			$title = single_term_title( '', false );
		}
		
		return $title;
	}
	
	function slog( $log, $js = 0 ) {
		//$message = ( is_array( $log ) || is_object( $log ) ) ? implode( ' / ', $log ) : $log;
		if( is_array( $log ) || is_object( $log ) ) {
			error_log( print_r( $log, true ) );
		} else {
			error_log( $log );
		}
	}
	
	// Boneyard
	function syntric_has_content( $str ) {
		return strlen( trim( str_replace( '&nbsp;', '', strip_tags( $str ) ) ) );
	}
	
	//add_filter( 'private_title_format', 'syntric_private_title_format', 10, 2 );
	function syntric_private_title_format( $prepend, $post ) {
		//return '%s <i class="fa fa-eye-slash" aria-hidden="true"></i><span class="sr-only">(Private)</span>';
		return '%s';
	}
	
	//add_filter( 'protected_title_format', 'syntric_protected_title_format', 10, 2 );
	function syntric_protected_title_format( $prepend, $post ) {
		//return '%s <i class="fa fa-lock" aria-hidden="true"></i><span class="sr-only">(Protected)</span>';
		return '%s';
	}