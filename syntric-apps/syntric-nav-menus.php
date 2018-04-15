<?php
	/**
	 * Nav Menu hooks for applying classes and attributes without using a custom Menu Walker
	 *
	 * Order of execution
	 * 1. wp_nav_menu_args / widget_nav_menu_args
	 * 2. wp_nav_menu_objects (li elements)
	 * 3. nav_menu_link_attributes (a elements)
	 * 4. wp_nav_menu (full menu html)
	 *
	 * wp_nav_menu() filters
	 * --------------------------
	 ******************************************************** wp_nav_menu_args
	 * pre_wp_nav_menu
	 * wp_nav_menu_container_allowedtags
	 ******************************************************** wp_nav_menu_objects - calls walk_nav_menu_tree which calls Walker_Nav_Menu or custom walker
	 * wp_nav_menu_items
	 * wp_nav_menu_{$menu->slug}_items
	 ******************************************************** wp_nav_menu
	 *
	 * Walker_Nav_Menu filters
	 * --------------------------
	 * start_lvl()
	 * nav_menu_submenu_css_class
	 *
	 * start_el()
	 * nav_menu_item_args
	 * nav_menu_css_class
	 * nav_menu_item_id
	 ******************************************************** nav_menu_link_attributes
	 * the_title
	 * nav_menu_item_title
	 * walker_nav_menu_start_el
	 *
	 * WP_Nav_Menu_Widget::widget()
	 * -------------------------
	 * widget_title
	 ******************************************************** widget_nav_menu_args then calls wp_nav_menu
	 *
	 */
	/**
	 * Add syn_nav_menu_widget to $nav_menu_args so widget can be caught with hooks
	 */
	add_filter( 'wp_nav_menu_args', 'syn_nav_menu_args', 10 );
	function syn_nav_menu_args( $nav_menu_args ) {
		/**
		 * $nav_menu_args is an array of args the were passed to wp_nav_menu
		 */
		$menu_classes = explode( ' ', $nav_menu_args[ 'menu_class' ] );
		if ( ! in_array( 'navbar-nav', $menu_classes ) ) {
			$nav_menu_args[ 'container' ] = '';
		}
		if ( syn_remove_whitespace() ) {
			$nav_menu_args[ 'item_spacing' ] = 'discard';
		}

		return $nav_menu_args;
	}

	/**
	 * Filters the sorted list of menu item objects before generating the menu’s HTML.
	 *
	 * If apply styles, apply list item (li) styles here
	 *
	 * https://developer.wordpress.org/reference/hooks/wp_nav_menu_objects/
	 *
	 * $sorted_menu_items is an array of WP_Post objects
	 * $args is a WP_Term object
	 */
	add_filter( 'wp_nav_menu_objects', 'syn_nav_menu_objects', 10, 2 );
	function syn_nav_menu_objects( $sorted_menu_items, $args ) {
		/**
		 * $sorted_menu_items is an array of WP_Post objects
		 * $args is an array where key 'menu' is a WP_Term object and all other keys in the args the were passed to wp_nav_menu
		 */
		global $post;
		$menu_classes = ( property_exists( $args, 'menu_class' ) && ! empty( $args->menu_class ) ) ? explode( ' ', $args->menu_class ) : [];
		if ( in_array( 'navbar-nav', $menu_classes ) ) {
			for ( $i = 1; $i <= count( $sorted_menu_items ); $i ++ ) {
				$smi_classes = $sorted_menu_items[ $i ]->classes;
				$classes     = [];
				$classes[]   = 'nav-item';
				if ( in_array( 'menu-item-has-children', $smi_classes ) ) {
					$classes[] = 'has-children';
					$classes[] = 'dropdown';
				}
				if ( in_array( 'current-menu-ancestor', $smi_classes ) || in_array( 'current-page-ancestor', $smi_classes ) ) {
					$classes[] = 'current-ancestor';
				}
				if ( in_array( 'current-menu-parent', $smi_classes ) || in_array( 'current-page-parent', $smi_classes ) ) {
					$classes[] = 'current-parent';
				}
				if ( in_array( 'current-menu-item', $smi_classes ) || in_array( 'current_page_item', $smi_classes ) ) {
					$classes[] = 'current-item';
					$classes[] = 'active';
				}
				$sorted_menu_items[ $i ]->classes = $classes;
			}
		} elseif ( in_array( 'list-group', $menu_classes ) ) {
			$top_ancestor_id       = syn_get_top_ancestor_id( $post->ID );
			$in_ancestor           = 0;
			$prev_menu_item_parent = 0;
			$smi                   = [];
			for ( $j = 1; $j <= count( $sorted_menu_items ); $j ++ ) {
				$mi             = get_post( $sorted_menu_items[ $j ]->object_id );
				$is_custom_link = ( 'custom' == $sorted_menu_items[ $j ]->type );
				$is_published   = ( $mi instanceof WP_Post && 'publish' == $mi->post_status ) ? true : false;
				if ( $in_ancestor && ( $is_custom_link || $is_published ) ) {
					if ( ! $is_custom_link && 0 == wp_get_post_parent_id( $sorted_menu_items[ $j ]->object_id ) ) {
						break;
					}
					$smi_classes = $sorted_menu_items[ $j ]->classes;
					$classes     = [];
					$classes[]   = 'list-group-item list-group-item-action';
					if ( in_array( 'current-menu-ancestor', $smi_classes ) || in_array( 'current-page-ancestor', $smi_classes ) ) {
						$classes[] = 'current-ancestor';
					}
					if ( in_array( 'current-menu-parent', $smi_classes ) || in_array( 'current-page-parent', $smi_classes ) ) {
						$classes[] = 'current-parent';
					}
					if ( in_array( 'current-menu-item', $smi_classes ) || in_array( 'current_page_item', $smi_classes ) ) {
						$classes[] = 'current-item';
						$classes[] = 'active';
					}
					if ( in_array( 'menu-item-has-children', $smi_classes ) ) {
						$classes[] = 'has-children';
					}
					$sorted_menu_items[ $j ]->classes = $classes;
					$smi[]                            = $sorted_menu_items[ $j ];
				}
				if ( ! $in_ancestor && $top_ancestor_id == $sorted_menu_items[ $j ]->object_id ) {
					$in_ancestor = 1;
				}
			}

			return $smi;
		} elseif ( in_array( 'admin-nav-menu', $menu_classes ) ) {
			for ( $i = 1; $i <= count( $sorted_menu_items ); $i ++ ) {
				$smi_classes = $sorted_menu_items[ $i ]->classes;
				$classes     = [];
				//$classes[]   = 'nav-item';
				if ( in_array( 'menu-item-has-children', $smi_classes ) ) {
					$classes[] = 'has-children';
					$classes[] = 'dropdown';
				}
				if ( in_array( 'current-menu-ancestor', $smi_classes ) || in_array( 'current-page-ancestor', $smi_classes ) ) {
					$classes[] = 'current-ancestor';
				}
				if ( in_array( 'current-menu-parent', $smi_classes ) || in_array( 'current-page-parent', $smi_classes ) ) {
					$classes[] = 'current-parent';
				}
				if ( in_array( 'current-menu-item', $smi_classes ) || in_array( 'current_page_item', $smi_classes ) ) {
					$classes[] = 'current-item';
					$classes[] = 'active';
				}
				$sorted_menu_items[ $i ]->classes = $classes;
			}
		}

		return $sorted_menu_items;
	}

	/**
	 * Filters the HTML attributes applied to a menu item’s anchor element.
	 *
	 * If apply styles, apply anchor styles here
	 */
	add_filter( 'nav_menu_link_attributes', 'syn_nav_menu_link_attributes', 10, 4 );
	function syn_nav_menu_link_attributes( $atts, $item, $args, $depth ) {
		$menu_classes = ( property_exists( $args, 'menu_class' ) && ! empty( $args->menu_class ) ) ? explode( ' ', $args->menu_class ) : [];
		// primary-nav
		if ( in_array( 'navbar-nav', $menu_classes ) ) {
			if ( 0 == $depth ) {
				if ( in_array( 'has-children', $item->classes ) ) {
					$atts[ 'href' ]          = '#';
					$atts[ 'class' ]         = 'dropdown-toggle nav-link depth-' . $depth;
					$atts[ 'role' ]          = 'button';
					$atts[ 'data-toggle' ]   = 'dropdown';
					$atts[ 'aria-haspopup' ] = 'true';
					$atts[ 'aria-expanded' ] = 'false';
				} else {
					$atts[ 'class' ] = 'nav-link depth-' . $depth;
				}
			} else {
				$atts[ 'class' ] = 'dropdown-item depth-' . $depth;
			}
			unset( $atts[ 'title' ] );
		}
		// admin-nav-menu
		if ( in_array( 'admin-nav-menu', $menu_classes ) ) {
			$atts[ 'href' ] = 'post.php?post=' . $item->object_id . '&action=edit';
			//if ( 0 == $depth ) {
			if ( in_array( 'has-children', $item->classes ) ) {
				$atts[ 'class' ] = 'dropdown-toggle depth-' . $depth;
				//$atts[ 'role' ]          = 'button';
				//$atts[ 'data-toggle' ]   = 'dropdown';
				$atts[ 'aria-haspopup' ] = 'true';
				$atts[ 'aria-expanded' ] = 'false';
			} else {
				$atts[ 'class' ] = 'depth-' . $depth;
			}
		}

		return $atts;
	}

	/**
	 * Filters menu & submenu containers - ul
	 */
	add_filter( 'nav_menu_submenu_css_class', 'syn_nav_menu_submenu_css_class', 10, 3 );
	function syn_nav_menu_submenu_css_class( $classes, $args, $depth ) {
		$menu_classes = ( property_exists( $args, 'menu_class' ) && ! empty( $args->menu_class ) ) ? explode( ' ', $args->menu_class ) : [];
		// primary-nav
		if ( in_array( 'navbar-nav', $menu_classes ) ) {
			$classes = [ 'dropdown-menu' ];
		}

		return $classes;
	}

	/**
	 * Echo the primary nav which is a Bootstrap 4 navbar
	 */
	function syn_primary_nav() {
		$lb   = syn_get_linebreak();
		$tab  = syn_get_tab();
		$args = [
			'theme_location'  => 'primary',
			'container'       => 'div',
			'container_id'    => 'primary-nav-collapse',
			'container_class' => 'collapse navbar-collapse',
			'menu_class'      => 'navbar-nav',
			'depth'           => 2,
			'item_spacing'    => ( syn_remove_whitespace() ) ? 'discard' : 'preserve',
		];
		//echo '<nav id="primary-navbar" class="navbar navbar-expand-xl navbar-light sticky-top">' . $lb;
		echo '<nav id="primary-navbar" class="navbar">' . $lb;
		echo $tab . '<div class="navbar-brand-toggler">' . $lb;
		echo $tab . $tab . '<button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#' . $args[ 'container_id' ] . '" aria-controls="' . $args[ 'container_id' ] . '" aria-expanded="false" aria-label="Toggle navigation">' . $lb;
		echo $tab . $tab . $tab . '<span class="fa fa-bars"></span>' . $lb;
		echo $tab . $tab . '</button>' . $lb;
		if ( has_custom_logo() || display_header_text() ) {
			$name    = esc_attr( get_bloginfo( 'name', 'display' ) );
			$tagline = esc_attr( get_bloginfo( 'description', 'display' ) );
			echo $tab . $tab . '<a class="navbar-brand" href="' . esc_url( home_url( '/' ) ) . '" rel="home">' . $lb;
			if ( has_custom_logo() ) {
				echo wp_get_attachment_image( get_theme_mod( 'custom_logo' ), 'thumbnail', false, [ 'class' => 'brand-logo', 'alt' => $name . ' Logo' ] );
			}
			if ( display_header_text() ) {
				echo $tab . $tab . $tab . '<div>' . $lb;
				if ( ! empty( $name ) ) {
					echo $tab . $tab . $tab . $tab . '<div>' . $name . '</div>' . $lb;
				}
				if ( ! empty( $tagline ) ) {
					echo $tab . $tab . $tab . $tab . '<div>' . $tagline . '</div>' . $lb;
				}
				echo $tab . $tab . $tab . '</div>' . $lb;
			}
			echo $tab . $tab . '</a>' . $lb;
		}
		echo $tab . '</div>' . $lb;
		wp_nav_menu( $args );
		echo '</nav>' . $lb;
	}