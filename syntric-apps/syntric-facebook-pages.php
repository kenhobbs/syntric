<?php
	/**
	 * Syntric Apps: Social Media
	 *
	 * Custom outputs and widgets for Facebook and Twitter
	 */
	//add_action( 'wp_enqueue_scripts', 'syn_enqueue_facebook_scripts' );
	function syn_enqueue_facebook_scripts() {
		$facebook_pages = get_field( 'syn_facebook_pages', 'option' );
		if ( $facebook_pages ) {
			wp_enqueue_script( 'syntric-facebook', get_template_directory_uri() . '/syntric-apps/assets/js/syntric-facebook.js', [ 'jquery' ], null, false );
		}
	}

//load_field filters
	add_filter( 'acf/load_field/name=syn_facebook_page_widget_page', 'syn_load_facebook_pages' );
// update_value filters
	add_filter( 'acf/update_value/name=facebook_page_id', 'syn_update_id' );
// prepare_field filters
	add_filter( 'acf/prepare_field/name=facebook_page_id', 'syn_prepare_facebook_page_fields' );
	function syn_prepare_facebook_page_fields( $field ) {
		global $pagenow;
		global $post;
		if ( is_admin() && isset( $_REQUEST[ 'page' ] ) ) {
			if ( 'facebook_page_id' == $field[ '_name' ] ) {
				$field[ 'wrapper' ][ 'hidden' ] = 1;
			}
		}

		return $field;
	}

	// need to be more selective (vs. retrieving posts from all registered FB pages - pass an argument)
	// todo: make more selective & cache posts?  save into WP Posts?
	function syn_get_facebook_page_posts( $facebook_page_id, $number ) {
		if ( have_rows( 'syn_facebook_pages', 'option' ) ) {
			$page       = false;
			$auth_token = false;
			while( have_rows( 'syn_facebook_pages', 'option' ) ) : the_row();
				if ( $facebook_page_id == get_sub_field( 'facebook_page_id' ) ) {
					$page      = get_sub_field( 'page' );
					$auth_type = get_sub_field( 'auth_type' );
					if ( 'app_token' == $auth_type ) {
						$auth_token = get_sub_field( 'app_token' );
					} elseif ( 'app_client' == $auth_type ) {
						$app_id     = get_sub_field( 'app_id' );
						$app_secret = get_sub_field( 'app_secret' );
						$auth_token = $app_id . '|' . $app_secret;
					}
					break;
				}
			endwhile;
			//slog( $page . ' & ' . $auth_type . ' & ' . $auth_token );
			if ( $page && $auth_token ) {
				$url      = 'https://graph.facebook.com/' . $page . '/feed?fields=name,created_time,description,message,picture,status_type,type,link,permalink_url,actions,is_published,from,application&limit=' . $number . '&access_token=' . $auth_token;
				$response = wp_remote_get( $url );
				//slog( $url );
				if ( $response ) {
					$body = wp_remote_retrieve_body( $response );
					if ( $body ) {
						$body = json_decode( $body );

						return $body;
					}
				}
			}
		}

		return false;
	}