<?php
	function syn_has_content( $str ) {
		return trim( str_replace( '&nbsp;', '', strip_tags( $str ) ) ) != '';
	}

	function syn_is_dev() {
		$host_parts     = explode( '.', $_SERVER[ 'HTTP_HOST' ] );
		$last_host_part = $host_parts[ count( $host_parts ) - 1 ];
		if ( 'localhost' == $last_host_part ) {
			return true;
		}
		if ( $_SERVER[ 'SERVER_ADDR' ] == '127.0.0.1' || '::1' == $_SERVER[ 'SERVER_ADDR' ] ) {
			return true;
		}

		return false;
	}

	function syn_remove_whitespace() {
		if ( ! syn_is_dev() && ! syn_is_staging() ) {
			return true;
		}
		return false;
	}

	function syn_is_staging() {
		$host_parts             = explode( '.', $_SERVER[ 'HTTP_HOST' ] );
		$next_to_last_host_part = $host_parts[ count( $host_parts ) - 2 ];
		$last_host_part         = $host_parts[ count( $host_parts ) - 1 ];
		if ( 'www.syntric.com' == $_SERVER[ 'HTTP_HOST' ] || 'syntric.com' == $_SERVER[ 'HTTP_HOST' ] ) {
			return false;
		}
		if ( 'localhost' == $last_host_part ) {
			return false;
		}
		if ( 3 == count( $host_parts ) && 'syntric' == $next_to_last_host_part && 'com' == $last_host_part ) {
			return true;
		}

		return false;
	}

	function _______notinuse______syn_get_http_host() {
		return $_SERVER[ 'HTTP_HOST' ];
	}

	function syn_get_top_ancestor_id( $post_id ) {
		$p = get_post( $post_id );
		if ( $p->post_parent ) {
			$ancestors = array_reverse( get_post_ancestors( $post_id ) );

			return $ancestors[ 0 ];
		}

		return $post_id;
	}

	function syn_generate_permanent_id() {
		$random1 = mt_rand( 1, mt_getrandmax() );
		$random2 = mt_rand( 1000, mt_getrandmax() );
		$random3 = mt_rand( 1000000, mt_getrandmax() );
		$value   = 'id-' . $random1 . '-' . $random2 . '-' . $random3;

		return $value;
	}

	function syn_generate_random_number( $min = 1, $max = 10000 ) {
		$random = mt_rand( $min, $max );

		return $random;
	}

	function syn_generate_password() {
		return wp_generate_password( 12, true );
		/*$seed = '!@#$%^&*()_+-=~;,./:<>?abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890';
		$pass = array(); //remember to declare $pass as an array
		$alphaLength = strlen($seed) - 1; //put the length -1 in cache
		for ($i = 0; $i < 12; $i++) {
			$n = rand(0, $alphaLength);
			$pass[] = $seed[$n];
		}
		return implode($pass); //turn the array into a string*/
	}

	function _____noinuse________syn_generate_login( $name ) {
		$login = str_replace( ' ', '', $name );
		$login = strtolower( $login );
		$login .= syn_generate_random_number();

		return $login;
	}

	function syn_sanitize_string_to_class( $string ) {
		$class = strtolower( $string );
		$class = str_replace( ' ', '-', $class );
		$class = preg_replace( "/[^a-z0-9-]/", "", $class );
		$class = preg_replace( "/[-]+/", "-", $class );
		$class = sanitize_html_class( $class );

		return $class;
	}

	function syn_get_taxonomies_terms() {
		global $post;
		$ret         = '';
		$taxes_terms = wp_get_object_terms( $post->ID, get_object_taxonomies( $post ), [ 'orderby' => 'term_group' ] );
		if ( count( $taxes_terms ) ) {
			foreach ( $taxes_terms as $tax_term ) {
				if ( 'microblog' == $tax_term->taxonomy ) {
					$ret = $tax_term->name;

					return $ret;
				} elseif ( 'category' == $tax_term->taxonomy && 'microblogs' != $tax_term->slug ) {
					$ret = $tax_term->name;

					return $ret;
				}
			}
		}

		return $ret;
	}

//add_filter( 'private_title_format', 'syn_private_title_format', 10, 2 );
	function syn_private_title_format( $prepend, $post ) {
		//return '%s <i class="fa fa-eye-slash" aria-hidden="true"></i><span class="sr-only">(Private)</span>';
		return '%s';
	}

//add_filter( 'protected_title_format', 'syn_protected_title_format', 10, 2 );
	function syn_protected_title_format( $prepend, $post ) {
		//return '%s <i class="fa fa-lock" aria-hidden="true"></i><span class="sr-only">(Protected)</span>';
		return '%s';
	}

//add_filter( 'get_the_archive_title', 'syn_get_archive_title' );
	function syn_get_archive_title( $title ) {
		if ( is_archive() ) {
			$title = post_type_archive_title();
		} elseif ( is_category() ) {
			$title = single_cat_title( '', false );
		} elseif ( is_tag() ) {
			$title = single_tag_title( '', false );
		} elseif ( is_author() ) {
			$title = '<span class="vcard">' . get_the_author() . '</span>';
		} elseif ( is_post_type_archive() ) {
			$title = post_type_archive_title( '', false );
		} elseif ( is_tax() ) {
			$title = single_term_title( '', false );
		}

		return $title;
	}

	function slog( $log, $js = 0 ) {
		//$message = ( is_array( $log ) || is_object( $log ) ) ? implode( ' / ', $log ) : $log;
		if ( is_array( $log ) || is_object( $log ) ) {
			error_log( print_r( $log, true ) );
		} else {
			error_log( $log );
		}
	}
