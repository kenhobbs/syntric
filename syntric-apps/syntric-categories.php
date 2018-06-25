<?php
	/**
	 * Filter category parent dropdown - remove microblogs (children of category Microblogs)
	 */
	add_filter( 'taxonomy_parent_dropdown_args', 'syn_taxonomy_parent_dropdown_args', 10, 3 );
	function syn_taxonomy_parent_dropdown_args( $args, $tax, $context ) {
		$microblogs = syn_get_microblog_categories();
		$exclude     = [];
		if ( $microblogs ) {
			foreach ( $microblogs as $microblog ) {
				$exclude[] = $microblog->term_id;
			}
		}
		$microblogs_category = syn_get_microblogs_category();
		if ( $microblogs_category instanceof WP_Term ) {
			$exclude[] = $microblogs_category->term_id;
		}
		if ( count( $exclude ) ) {
			if ( isset( $args[ 'exclude_tree' ] ) ) {
				if ( is_array( $args[ 'exclude_tree' ] ) ) {
					$args[ 'exclude_tree' ] = array_merge( $args[ 'exclude_tree' ], $exclude );
				} else {
					$exclude[]  = $args[ 'exclude_tree' ];
					$args[ 'exclude_tree' ] = $exclude;
				}
			} else {
				$args[ 'exclude_tree' ] = $exclude;
			}
		}

		return $args;
	}

	/**
	 * Identify "fixed" categories - those that shouldn't be edited manually
	 *
	 * Fixed categories are the default category (term_id = 1) which is set to News, Microblogs and all it's children
	 *
	 * @param $cat (required) A WP_Term object
	 *
	 * @return bool - true if a fixed column, false if not
	 */
	function syn_is_fixed_category( $cat ) {
		if ( 1 == $cat->term_id ) {
			return true;
		}
		$microblogs_category = syn_get_microblogs_category();
		if ( $microblogs_category->term_id == $cat->term_id || $microblogs_category->term_id == $cat->parent ) {
			return true;
		}
		return false;
	}

	/**
	 * Note that this filter and it's peers may be of good use in other contexts.
	 *
	 * Row action filters:
	 *
	 * tag_row_actions
	 * page_row_actions
	 * post_row_actions
	 * user_row_actions
	 * comment_row_actions
	 * media_row_actions
	 * ms_user_row_actions
	 * {$taxonomy}_row_actions
	 *
	 */
	add_filter( 'category_row_actions', 'syn_category_row_actions', 10, 2 );
	function syn_category_row_actions( $actions, $category ){
		global $pagenow;
		if ( 'edit-tags.php' == $pagenow ) {
			//slog($actions);
			if ( syn_is_fixed_category( $category ) ) {
				$actions = [];
				$actions['do-not-edit'] = 'This category is automatically generated, edit with care';
			}
		}

		return $actions;
	}

// boneyard

	function ___notinuse___syn_is_microblogs_category( $cat ) {
		if ( 'Microblogs' == $cat->name && 0 == $cat->parent ) {
			return true;
		}
		return false;
	}

	function ___notinuse___syn_is_microblog_category( $cat ) {
		$microblogs_category = syn_get_microblogs_category();
		if ( $microblogs_category->term_id == $cat->parent ) {
			return true;
		}
		return false;
	}

	//add_filter( 'manage_category_custom_column', 'syn_manage_category_custom_column', 10, 2 );
	function syn_manage_category_custom_column( $column, $term_id ) {
		//slog( 'current_screen, column and term_id--------------------------------');
		//slog( get_current_screen() );
		//slog($column);
		//slog($term_id);
		return $column;
	}

	//add_filter( 'term_name', 'syn_term_name', 10, 2 );
	function syn_term_name( $pad_tag_name, $tag ) {
		//slog( 'pad_tag_name, tag--------------------------------');
		//slog($pad_tag_name);
		//slog($tag);
		$ptn = str_replace( '&#8212; ', '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;', $pad_tag_name );
		return $ptn;
	}