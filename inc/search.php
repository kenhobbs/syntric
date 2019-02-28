<?php
	
	/**
	 * Search modifications
	 */
	add_filter( 'pre_get_posts', 'syntric_search_query' );
	function syntric_search_query( $query ) {
		global $wp_query;
		if( ! is_admin() && $query -> is_main_query() ) {
			if( $query -> is_search ) {
				// exclude top-level nav items (including front page) as they don't contain content
				$query -> set( 'post_parent__not_in', [ 0 ] );
				$query -> set( 'post_type', [
					'post',
					'page',
					'syntric_calendar',
				] );
				$query -> set( 'post_status', [ 'publish' ] );
				add_filter( 'posts_distinct', 'syntric_search_posts_distinct', 10, 2 );
				add_filter( 'posts_join', 'syntric_search_posts_join', 10, 2 );
				add_filter( 'posts_where', 'syntric_search_posts_where', 10, 2 );
				remove_all_filters( '__after_loop' );
			}
		}
	}
	
	/**
	 * Modify the search query to prevent duplicates by adding "DISTINCT"
	 *
	 * http://codex.wordpress.org/Plugin_API/Filter_Reference/posts_distinct
	 */
	// Do not delete! The function is called by hook in pre_get_posts for searches
	function syntric_search_posts_distinct( $distinct, \WP_Query $q ) {
		if( ! is_admin() && $q -> is_search() && $q -> is_main_query() ) {
			//remove_filter( 'posts_join', 'syntric_search_posts_join' );
			$distinct = "DISTINCT";
		}
		
		return $distinct;
	}
	
	/**
	 * Modify the search query to include post meta by joining wp_postmeta table to SQL
	 *
	 * http://codex.wordpress.org/Plugin_API/Filter_Reference/posts_join
	 */
	// Do not delete! The function is called by hook in pre_get_posts for searches
	function syntric_search_posts_join( $join, \WP_Query $q ) {
		global $wp_query, $wpdb;
		if( ! is_admin() && $q -> is_search() && $q -> is_main_query() ) {
			//remove_filter( 'posts_join', 'syntric_search_posts_join' );
			$join .= " LEFT OUTER JOIN $wpdb->postmeta ON $wpdb->posts.ID = $wpdb->postmeta.post_id ";
		}
		
		return $join;
	}
	
	/**
	 * Modify the search query to limit it to pages and posts (excludes other CPTs)
	 *
	 * http://codex.wordpress.org/Plugin_API/Filter_Reference/posts_where
	 */
	// Do not delete! The function is called by hook in pre_get_posts for searches
	function syntric_search_posts_where( $where, \WP_Query $q ) {
		if( ! is_admin() && $q -> is_search() && $q -> is_main_query() ) {
			$search_term = $q -> get( 's' );
			$where       = " AND wp_posts.post_type IN ('post', 'page', 'syntric_calendar')";
			$where       .= " AND wp_posts.post_status = 'publish'";
			$where       .= " AND wp_posts.post_parent NOT IN (0)";
			$where       .= " AND ( wp_posts.post_content LIKE '%" . $search_term . "%' OR wp_posts.post_title LIKE '%" . $search_term . "%' )";
			$where       .= " AND trim(coalesce(wp_posts.post_content, '')) <>''";
		}
		
		return $where;
	}
	
	/**
	 * Modify the search query to group by post_title - used to return only a single recurring event
	 */
	//add_filter( 'posts_groupby', 'syntric_posts_groupby_search', 20 );
	function syntric_posts_groupby_search( $groupby ) {
		if( ! is_admin() && is_search() && is_main_query() && isset( $_REQUEST[ 's' ] ) ) {
			$groupby = 'wp_posts.post_title';
		}
		
		return $groupby;
	}
	
	/**
	 * Modify the search query number of results per page
	 */
	//add_filter( 'request', 'syntric_request_search' );
	function syntric_request_search( $query_vars ) {
		if( ! is_admin() && is_search() && is_main_query() && isset( $_REQUEST[ 's' ] ) ) {
			$query_vars[ 'posts_per_page' ] = 40;
		}
		
		return $query_vars;
	}
	
	/**
	 * Modify the search query return order to sort by post/page title
	 */
	//add_filter( 'posts_orderby', 'syntric_orderby_search' );
	function syntric_orderby_search( $orderby ) {
		if( ! is_admin() && is_search() && is_main_query() && isset( $_REQUEST[ 's' ] ) ) {
			$orderby = 'wp_posts.post_title ASC';
		}
		
		return $orderby;
	}


