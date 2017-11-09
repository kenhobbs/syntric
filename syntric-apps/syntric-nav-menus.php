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
 * Generate the main nav menu
 */
function syn_nav_menu() {
	wp_nav_menu( array(
		'theme_location'      => 'primary',
		'container'           => '',
		'container_class'     => '',
		'container_id'        => '',
		'menu'                => '',
		'menu_id'             => 'primary-nav-menu',
		'menu_class'          => 'navbar-nav',
		'depth'               => 2,
		'fallback_cb'         => '',
		'submenu_classes'     => 'dropdown-menu',
		'item_classes'        => 'nav-item',
		'link_classes'        => 'nav-link',
		'parent_item_classes' => 'dropdown',
	) );
}

/**
 * Populate organization selects
 *
 * @param $field
 *
 * @return mixed
 */
add_filter( 'acf/load_field/name=syn_nav_menu_widget_menu', 'syn_load_nav_menu' );
/**
 * Add syn_nav_menu_widget to $nav_menu_args so widget can be caught with hooks
 */
add_filter( 'wp_nav_menu_args', 'syn_nav_menu_args', 10 );
add_filter( 'widget_nav_menu_args', 'syn_nav_menu_args', 10, 4 );
function syn_nav_menu_args( $nav_menu_args, $nav_menu = null, $args = null, $instance = null ) {
	$args[ 'max_depth' ]           = ( isset( $nav_menu_args[ 'depth' ] ) ) ? $nav_menu_args[ 'depth' ] : 0;
	$args[ 'widget' ]              = ( isset( $nav_menu_args[ 'widget' ] ) ) ? $nav_menu_args[ 'widget' ] : false;
	$args[ 'submenu_classes' ]     = ( isset( $nav_menu_args[ 'submenu_classes' ] ) ) ? $nav_menu_args[ 'submenu_classes' ] : '';
	$args[ 'item_classes' ]        = ( isset( $nav_menu_args[ 'item_classes' ] ) ) ? $nav_menu_args[ 'item_classes' ] : '';
	$args[ 'link_classes' ]        = ( isset( $nav_menu_args[ 'link_classes' ] ) ) ? $nav_menu_args[ 'link_classes' ] : '';
	$args[ 'parent_item_classes' ] = ( isset( $nav_menu_args[ 'parent_item_classes' ] ) ) ? $nav_menu_args[ 'parent_item_classes' ] : '';

	return $nav_menu_args;
}

/**
 * Filters menu & submenu containers - ul
 */
add_filter( 'nav_menu_submenu_css_class', 'syn_nav_menu_submenu_css_class', 10, 3 );
function syn_nav_menu_submenu_css_class( $classes, $args, $depth ) {
	if ( isset( $args->submenu_classes ) ) {
		$submenu_classes = explode( ' ', $args->submenu_classes );
		$classes         = array_merge( $classes, $submenu_classes );
	}

	return $classes;
}

/**
 * Filters the sorted list of menu item objects before generating the menu’s HTML.
 *
 * If apply styles, apply list item (li) styles here
 *
 * https://developer.wordpress.org/reference/hooks/wp_nav_menu_objects/
 */
add_filter( 'wp_nav_menu_objects', 'syn_nav_menu_objects', 10, 2 );
function syn_nav_menu_objects( $sorted_menu_items, $args ) {
	global $post;
	$item_classes = ( isset( $args->item_classes ) ) ? explode( ' ', $args->item_classes ) : array();
	$menu_items   = array();
	if ( isset( $args->widget ) && $args->widget ) {
		$ancestor_post_id     = syn_get_top_ancestor_id( $post->ID );
		$pre_ancestor_post_id = 0;
		$pre_ancestor_nav_id  = 0;
		$in_ancestor          = false;
		foreach ( $sorted_menu_items as $sorted_menu_item ) {
			if ( ! $in_ancestor && $sorted_menu_item->object_id == $ancestor_post_id ) {
				$pre_ancestor_post_id = $sorted_menu_item->post_parent;
				$pre_ancestor_nav_id  = $sorted_menu_item->menu_item_parent;
				$in_ancestor          = true;
				continue;
			} elseif ( $in_ancestor ) {
				if ( ( $sorted_menu_item->type == 'post_type' && $sorted_menu_item->post_parent == $pre_ancestor_post_id && $sorted_menu_item->menu_item_parent == $pre_ancestor_nav_id ) ||
				     ( $sorted_menu_item->type != 'post_type' && $sorted_menu_item->menu_item_parent == $pre_ancestor_nav_id )
				) {
					$in_ancestor = false;
					continue;
				}
			}
			if ( $in_ancestor ) {
				$sorted_menu_item->classes = array_merge( $sorted_menu_item->classes, $item_classes );
				$menu_items[]              = $sorted_menu_item;
			}
		}

		return $menu_items;
	} else {
		if ( isset( $args->item_classes ) ) {
			foreach ( $sorted_menu_items as $sorted_menu_item ) {
				$is_parent                 = ( in_array( 'menu-item-has-children', $sorted_menu_item->classes ) ) ? true : false;
				$parent_item_classes       = ( isset( $args->parent_item_classes ) && $is_parent ) ? explode( ' ', $args->parent_item_classes ) : array();
				$sorted_menu_item->classes = array_merge( $sorted_menu_item->classes, $item_classes, $parent_item_classes );;
				$menu_items[] = $sorted_menu_item;
			}

			return $menu_items;
		}
	}

	return $sorted_menu_items;
}

/**
 * Filters each anchor hyperlink in the menu
 *
 * If apply styles, apply anchor styles here
 */
add_filter( 'nav_menu_link_attributes', 'syn_nav_menu_link_attributes', 10, 4 );
function syn_nav_menu_link_attributes( $atts, $item, $args, $depth ) {
	$atts[ 'title' ] = '';
	$link_classes    = ( isset( $args->link_classes ) ) ? $args->link_classes : '';
	if ( isset( $args->widget ) && $args->widget ) {
		$atts[ 'class' ] = $link_classes . ' depth-' . $depth;
		$atts[ 'class' ] .= ( $item->current ) ? ' active' : '';
		$max_depth       = ( isset( $args->max_depth ) ) ? (int) $args->max_depth : 0;
		if ( $max_depth != 0 && $depth + 1 > $max_depth ) {
			$atts[ 'style' ] = 'display: none;';
		}
	} else {
		$atts[ 'class' ] = $link_classes . ' depth-' . $depth;
		$has_children    = ( in_array( 'menu-item-has-children', $item->classes ) ) ? true : false;
		if ( $has_children ) {
			if ( $depth == 0 ) {
				$atts[ 'data-toggle' ] = 'dropdown';
				$atts[ 'class' ]       .= ' dropdown-toggle';
			} else {
				$atts[ 'slideout-toggle' ] = 'slideout';
				$atts[ 'class' ]           .= ' slideout-toggle';
			}
		}
		if ( in_array( 'current-menu-item', $item->classes ) != false ) {
			$atts[ 'class' ] .= ' active';
		}
	}

	return $atts;
}