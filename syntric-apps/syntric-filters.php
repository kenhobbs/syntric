<?php
	
	/**
	 * Syntric filter is used to specify when a widget, sidebar or other object is activated and/or displayed.
	 *
	 * Filters include:
	 *
	 * Post Type - page, post, calendar, event, etc.
	 * Post Category - Announcements, Microblogs, News, Uncategorized, etc.
	 * Post - a specific post
	 * Page Type - Front page, Home page, Top level page, Parent page, Child page, Grandchild page, etc.
	 * Page Template - Default, Activity, Course/Classes, Department, etc.
	 * Page - a specific page
	 */
	function syntric_process_filters( $filters, $post ) {
		if( is_array( $filters ) && 0 < count( $filters ) ) {
			foreach( $filters as $filter ) {
				$parameter = $filter[ 'parameter' ][ 'value' ];
				$operator  = $filter[ 'operator' ][ 'value' ];
				switch( $parameter ) :
					case 'post_type':
						$post_type = $post -> post_type;
						$value     = $filter[ 'value' ][ 'post_type_value' ][ 'value' ];
						if( ( 'is' == $operator && $post_type != $value ) ||
						    ( 'is_not' == $operator && $post_type == $value )
						) {
							return false;
						}
						break;
					case 'post_category':
						$categories = get_the_category( $post -> ID );
						$category   = $categories[ 0 ];
						$cat_id     = (int) $category -> cat_ID;
						$value      = (int) $filter[ 'value' ][ 'post_category_value' ];
						if( ( 'is' == $operator && $cat_id != $value ) ||
						    ( 'is_not' == $operator && $cat_id == $value )
						) {
							return false;
						}
						break;
					case 'post':
						$value = $filter[ 'value' ][ 'post_value' ];
						if( ( 'is' == $operator && $post -> ID != $value ) ||
						    ( 'is_not' == $operator && $post -> ID == $value )
						) {
							return false;
						}
						break;
					case 'page_template':
						$page_template = get_post_meta( $post -> ID, '_wp_page_template', true );
						$value         = $filter[ 'value' ][ 'page_template_value' ][ 'value' ];
						if( ( 'is' == $operator && $page_template != $value ) ||
						    ( 'is_not' == $operator && $page_template == $value )
						) {
							return false;
						}
						break;
					case 'page':
						$value = $filter[ 'value' ][ 'page_value' ];
						if( ( 'is' == $operator && $post -> ID != $value ) ||
						    ( 'is_not' == $operator && $post -> ID == $value )
						) {
							return false;
						}
						break;
				endswitch;
			}
		}
		
		return true;
	}
	
	/**
	 * Process schedule (start date/time and end date/time
	 *
	 * @param $start_datetime
	 * @param $end_datetime
	 *
	 * @return bool
	 */
	function syntric_process_schedule( $start_datetime, $end_datetime ) {
		$start_timestamp = ( ! empty( $start_datetime ) ) ? strtotime( $start_datetime ) : 1;
		$end_timestamp   = ( ! empty( $end_datetime ) ) ? strtotime( $end_datetime ) : 1;
		$now             = time();
		$start_diff      = ( $start_timestamp != 1 ) ? $now - $start_timestamp : $start_timestamp;
		$end_diff        = ( $end_timestamp != 1 ) ? $end_timestamp - $now : $end_timestamp;
		if( $start_diff < 0 || $end_diff < 0 ) {
			return false;
		}
		
		return true;
	}