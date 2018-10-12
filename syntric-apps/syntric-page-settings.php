<?php
	/** Prohibit roles less than editor from editing pages other than their own (where they are the author) */
	add_filter( 'pre_get_posts', 'syn_filter_author_pages_list' );
	function syn_filter_author_pages_list( $query ) {
		global $pagenow;
		global $user_ID;
		if ( is_admin() && 'edit.php' == $pagenow && isset( $_GET[ 'post_type' ] ) && 'page' == $_GET[ 'post_type' ] && ! syn_current_user_can( 'editor' ) ) {
			$query->set( 'author', $user_ID );
			$query->set( 'orderby', 'menu_order' );
			$query->set( 'order', 'ASC' );
		}

		return $query;
	}
