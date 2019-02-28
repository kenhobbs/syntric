<?php
	/**
	 * Syntric Apps: Social Media
	 *
	 * Custom outputs and widgets for Facebook and Twitter
	 */
	
	// need to be more selective (vs. retrieving posts from all registered FB pages - pass an argument)
	// todo: make more selective & cache posts?  save into WP Posts?
	function syntric_get_facebook_page_posts( $facebook_page_id, $number = 5 ) {
		if( have_rows( 'syntric_facebook_pages', 'option' ) ) {
			$page       = false;
			$auth_token = false;
			while( have_rows( 'syntric_facebook_pages', 'option' ) ) : the_row();
				if( $facebook_page_id == get_sub_field( 'facebook_page_id' ) ) {
					$page      = get_sub_field( 'page' );
					$auth_type = get_sub_field( 'auth_type' );
					if( 'app_token' == $auth_type ) {
						$auth_token = get_sub_field( 'app_token' );
					} elseif( 'app_client' == $auth_type ) {
						$app_id     = get_sub_field( 'app_id' );
						$app_secret = get_sub_field( 'app_secret' );
						$auth_token = $app_id . '|' . $app_secret;
					}
					break;
				}
			endwhile;
			if( $page && $auth_token ) {
				$url      = 'https://graph.facebook.com/' . $page . '/feed?fields=name,created_time,description,message,picture,status_type,type,link,permalink_url,actions,is_published,from,full_picture,attachments{media,type,url,title,target,description,subattachments}&limit=' . $number . '&access_token=' . $auth_token;
				$response = wp_remote_get( $url );
				if( $response ) {
					$body = wp_remote_retrieve_body( $response );
					if( $body ) {
						$body = json_decode( $body );
						
						return $body;
					}
				}
			}
		}
		
		return false;
	}
	
	function syntric_get_facebook_page( $facebook_page_id ) {
		$facebook_pages = get_field( 'syn_facebook_pages', 'option' );
		if( count( $facebook_pages ) ) {
			foreach( $facebook_pages as $facebook_page ) {
				if( $facebook_page_id == $facebook_page[ 'facebook_page_id' ] ) {
					return $facebook_page[ 'page' ];
				}
			}
		}
		
		return;
	}
	
	//add_action( 'wp_enqueue_scripts', 'syntric_enqueue_facebook_scripts' );
	function syntric_enqueue_facebook_scripts() {
		$facebook_pages = get_field( 'syn_facebook_pages', 'option' );
		if( $facebook_pages ) {
			wp_enqueue_script( 'syntric-facebook', get_template_directory_uri() . '/syntric-apps/assets/js/syntric-facebook.js', [ 'jquery' ], null, false );
		}
	}