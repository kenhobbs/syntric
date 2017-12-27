<?php
/**
 * Modify the search query to limit it to pages and posts (excludes other CPTs)
 *
 * http://codex.wordpress.org/Plugin_API/Filter_Reference/posts_where
 */
//add_filter( 'posts_where', 'syn_posts_where_search' );
function syn_posts_where_search( $where ) {
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

	return $where;
}

/**
 * Modify the search query return order to sort by post/page title
 */
add_filter( 'posts_orderby', 'syn_orderby_search' );
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
	if ( ! is_admin() && is_search() && is_main_query() && isset( $_REQUEST[ 's' ] ) ) {
		$join .= 'LEFT JOIN ' . $wpdb->postmeta . ' ON ' . $wpdb->posts . '.ID = ' . $wpdb->postmeta . '.post_id';
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




