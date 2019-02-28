<?php
	/**
	 * Pagination layout.
	 *
	 * @package syntric
	 */
	
	/**
	 * Custom Pagination with numbers
	 * Credits to http://www.wpbeginner.com/wp-themes/how-to-add-numeric-pagination-in-your-wordpress-theme/
	 */
	function syntric_pagination() {
		global $wp_query;
		if( $wp_query -> max_num_pages <= 1 ) {
			return;
		}
		$big = 999999999; // need an unlikely integer
		echo '<nav class="pagination-nav" aria-label="Page navigation">';
		echo paginate_links( [
			//'base' => str_replace( $big, '%#%', esc_url( get_pagenum_link( $big ) ) ),
			//'format' => '?paged=%#%',
			'current'            => max( 1, get_query_var( 'paged' ) ),
			'total'              => $wp_query -> max_num_pages,
			'type'               => 'list',
			'prev_text'          => '<span class="sr-only">Previous Page</span><i class="fa fa-angle-left" aria-hidden="true"></i>',
			'next_text'          => '<span class="sr-only">Next Page</span><i class="fa fa-angle-right" aria-hidden="true"></i>',
			'before_page_number' => '<span class="sr-only">' . __( 'Page', 'syntric' ) . ' </span>',
			'show_all'           => true,
		] );
		echo '</nav>';
	}
	
	//add_filter('next_posts_link_attributes', 'syntric_previous_next_posts_link_attributes');
	//add_filter('previous_posts_link_attributes', 'syntric_previous_next_posts_link_attributes');
	function syntric_previous_next_posts_link_attributes() {
		return 'class="page-link"';
	}


