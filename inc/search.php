<?php
	/**
	 * Modify the search query to limit it to pages and posts (excludes other CPTs)
	 *
	 * http://codex.wordpress.org/Plugin_API/Filter_Reference/posts_where
	 */
	add_filter( 'posts_where', 'syn_posts_where_search', 10 );
	function syn_posts_where_search( $where ) {
		global $wp_query;
		slog( '------------------------------------start where--------------------------------' );
		slog( $where );
		slog( '------------------------------------end where--------------------------------' );
		slog( '------------------------------------start global wp_query--------------------------------' );
		slog( $wp_query );
		slog( '------------------------------------start global wp_query--------------------------------' );

		return $where;
	}

	/*global $wpdb;
	//if(isset($_GET['aioAlphaSearchMode']) && $_GET['aioAlphaSearchMode'] == 1){
	if ( ! is_admin() && is_search() && is_main_query() && isset( $_GET[ 's' ] ) ) {

		$search_string = esc_sql($_GET['s']);

		$where .= " AND " . $wpdb->posts . ".post_title LIKE \'". $searchAlphabet . "%' ";

		// use only if the post meta db table has been joined to the search tables using posts_join filter
		$where .= " AND ($wpdb->postmeta.meta_key = 'JDReview_CustomFields_ReivewOrNewsPostType' AND $wpdb->postmeta.meta_value = 'JDReview_PostType_ReviewPost') ";
		return $where;
	}

	if ( ! is_admin() && is_search() && is_main_query() && isset( $_REQUEST[ 's' ] ) ) {
		$where = "
			AND wp_posts.post_content <> '' AND wp_posts.post_content != ''
			AND (
			wp_posts.post_title LIKE '%" . get_search_query() . "%' 
			OR wp_posts.post_content LIKE '%" . get_search_query() . "%'
			OR wp_posts.post_excerpt LIKE '%" . get_search_query() . "%'
			)
			AND wp_posts.post_parent > 0
			AND wp_posts.post_type IN ('post', 'page', 'syn_calendar')
			AND wp_posts.post_status = 'publish'
		";
	}

	return $where;*/
	/*
	 * SELECT SQL_CALC_FOUND_ROWS wp_posts.ID
	 * FROM wp_posts
	 * LEFT JOIN wp_postmeta ON wp_posts.ID = wp_postmeta.post_id
	 * WHERE 1=1
	 * AND ((
	 *      (wp_posts.post_title LIKE '%ass%') OR
	 *      (wp_posts.post_excerpt LIKE '%ass%') OR
	 *      (wp_posts.post_content LIKE '%ass%')
	 * ))
	 * AND wp_posts.post_type IN ('post', 'page', 'attachment', 'syn_calendar')
	 * AND (
	 *      wp_posts.post_status = 'publish' OR
	 *      wp_posts.post_status = 'acf-disabled' OR
	 *      wp_posts.post_author = 1 AND wp_posts.post_status = 'private'
	 * )
	 * AND wp_posts.post_title LIKE \'ass%'
	 * AND (wp_postmeta.meta_key = 'JDReview_CustomFields_ReivewOrNewsPostType'
	 * AND wp_postmeta.meta_value = 'JDReview_PostType_ReviewPost')
	 * ORDER BY wp_posts.post_title ASC LIMIT 0, 20
	 */
	/**
	 * Modify the search query return order to sort by post/page title
	 */
//add_filter( 'posts_orderby', 'syn_orderby_search' );
	function syn_orderby_search( $orderby ) {
		if ( ! is_admin() && is_search() && is_main_query() && isset( $_REQUEST[ 's' ] ) ) {
			$orderby = 'wp_posts.post_title ASC';
		}

		return $orderby;
	}

// Inactive
	/**
	 * Modify the search query to prevent duplicates by adding "DISTINCT"
	 *
	 * http://codex.wordpress.org/Plugin_API/Filter_Reference/posts_distinct
	 */
//add_filter( 'posts_distinct', 'syn_posts_distinct_search' );
	function syn_posts_distinct_search() {
		if ( ! is_admin() && is_search() && is_main_query() && isset( $_REQUEST[ 's' ] ) ) {
			return "DISTINCT";
		}

		return;
	}

	/**
	 * Modify the search query to include post meta by joining wp_postmeta table to SQL
	 *
	 * http://codex.wordpress.org/Plugin_API/Filter_Reference/posts_join
	 */
	//add_filter( 'posts_join', 'syn_posts_join_search' );
	function syn_posts_join_search( $join ) {
		global $wp_query, $wpdb;
		if ( ! empty( $wp_query->query_vars[ 's' ] ) ) {
			$join .= "LEFT JOIN $wpdb->postmeta ON $wpdb->posts.ID = $wpdb->postmeta.post_id ";
		}

		return $join;
	}

	/**
	 * Modify the search query to group by post_title - used to return only a single recurring event
	 */
//add_filter( 'posts_groupby', 'syn_posts_groupby_search', 20 );
	function syn_posts_groupby_search( $groupby ) {
		if ( ! is_admin() && is_search() && is_main_query() && isset( $_REQUEST[ 's' ] ) ) {
			$groupby = 'wp_posts.post_title';
		}

		return $groupby;
	}

	/**
	 * Modify the search query number of results per page
	 */
//add_filter( 'request', 'syn_request_search' );
	function syn_request_search( $query_vars ) {
		if ( ! is_admin() && is_search() && is_main_query() && isset( $_REQUEST[ 's' ] ) ) {
			$query_vars[ 'posts_per_page' ] = 40;
		}

		return $query_vars;
	}




