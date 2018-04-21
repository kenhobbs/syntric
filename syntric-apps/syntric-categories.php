<?php
	//post_edit_category_parent_dropdown_args
	add_filter( 'taxonomy_parent_dropdown_args', 'syn_taxonomy_parent_dropdown_args', 10, 3 );
	function syn_taxonomy_parent_dropdown_args( $args, $tax, $context ) {
		$microblogs_cat_children = syn_get_microblogs_category_children();
		$exclude_mb_children     = [];
		if ( $microblogs_cat_children ) {
			foreach ( $microblogs_cat_children as $microblogs_cat_child ) {
				$exclude_mb_children[] = $microblogs_cat_child->term_id;
			}
		}
		if ( count( $exclude_mb_children ) ) {
			if ( isset( $args[ 'exclude_tree' ] ) ) {
				if ( is_array( $args[ 'exclude_tree' ] ) ) {
					$args[ 'exclude_tree' ] = array_merge( $args[ 'exclude_tree' ], $exclude_mb_children );
				} else {
					$exclude_mb_children[]  = $args[ 'exclude_tree' ];
					$args[ 'exclude_tree' ] = $exclude_mb_children;
				}
			} else {
				$args[ 'exclude_tree' ] = $exclude_mb_children;
			}
		}

		/*slog('post_edit_category_dropdown_args');
		slog( $args );*/

		return $args;
	}