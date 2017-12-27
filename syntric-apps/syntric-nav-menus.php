<?php
/**
 * Echo the primary nav which is a Bootstrap 4 navbar
 */
function syn_primary_nav() {
	echo '<nav class="primary-nav-wrapper navbar navbar-expand-lg navbar-dark d-print-none">';
	syn_navbar_brand();
	echo '<button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#nav-collapse" aria-controls="nav-collapse" aria-expanded="false" aria-label="Toggle navigation">';
	echo '<span class="fa fa-bars"></span>';
	echo '</button>';
	echo '<div id="nav-collapse" class="collapse navbar-collapse">';
	syn_nav_menu();
	echo '</div>';
	echo '</nav>';
}

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
		'theme_location'  => 'primary',
		'menu'            => '',
		'menu_class'      => 'navbar-nav ml-auto', // %2$s
		'menu_id'         => 'primary-nav', // %1$s
		'container'       => '',
		'container_class' => '',
		'container_id'    => '',
		//'fallback_cb'         => '',
		//'echo' => true,
		'depth'           => 2,
		//'before' => '',
		//'after' => '',
		//'link_before' => '',
		//'link_after' => '',
		//'items_wrap' => '', this requires printf()
		//'item_spacing'    => 'discard',
		//'submenu_classes'     => 'dropdown-menu dropdown-menu-right',
		//'item_classes'        => 'nav-item',
		//'link_classes'        => 'nav-link',
		//'parent_item_classes' => 'dropdown',
	) );
}

function syn_navbar_brand() {
	if ( has_custom_logo() || display_header_text() ) {
		$organization = esc_attr( get_bloginfo( 'name', 'display' ) );
		echo '<a class="navbar-brand" href="' . esc_url( home_url( '/' ) ) . '" rel="home">';
		if ( has_custom_logo() ) {
			echo wp_get_attachment_image( get_theme_mod( 'custom_logo' ), 'full', false, array( 'alt' => $organization . ' Logo' ) );
		}
		if ( display_header_text() ) {
			echo $organization;
		}
		echo '</a>';
	}
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
function syn_nav_menu_args( $nav_menu_args ) {
/**
 * slog( $nav_menu_args );
 * (
	[menu] =>
    [container] =>
    [container_class] =>
    [container_id] =>
    [menu_class] => navbar-nav ml-auto
	[menu_id] => primary-nav
	[echo] => 1
    [fallback_cb] => wp_page_menu
	[before] =>
    [after] =>
    [link_before] =>
    [link_after] =>
    [items_wrap] => <ul id="%1$s" class="%2$s">%3$s</ul>
                                                     [item_spacing] => discard
	[depth] => 2
    [walker] =>
    [theme_location] => primary
)*/

	return $nav_menu_args;
}

add_filter( 'widget_nav_menu_args', 'syn_widget_nav_menu_args', 10, 4 );
function syn_widget_nav_menu_args( $nav_menu_args, $nav_menu = null, $args = null, $instance = null ) {
	$args[ 'max_depth' ]           = ( isset( $nav_menu_args[ 'depth' ] ) ) ? $nav_menu_args[ 'depth' ] : 0;
	$args[ 'widget' ]              = ( isset( $nav_menu_args[ 'widget' ] ) ) ? $nav_menu_args[ 'widget' ] : false;
	$args[ 'submenu_classes' ]     = ( isset( $nav_menu_args[ 'submenu_classes' ] ) ) ? $nav_menu_args[ 'submenu_classes' ] : '';
	$args[ 'item_classes' ]        = ( isset( $nav_menu_args[ 'item_classes' ] ) ) ? $nav_menu_args[ 'item_classes' ] : '';
	$args[ 'link_classes' ]        = ( isset( $nav_menu_args[ 'link_classes' ] ) ) ? $nav_menu_args[ 'link_classes' ] : '';
	$args[ 'parent_item_classes' ] = ( isset( $nav_menu_args[ 'parent_item_classes' ] ) ) ? $nav_menu_args[ 'parent_item_classes' ] : '';

	return $nav_menu_args;
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
/**
 * slog( $sorted_menu_items );
(
	[1] => WP_Post Object
	(
[ID] => 469
[post_author] => 1
[post_date] => 2017-08-02 04:31:48
[post_date_gmt] => 2017-08-02 04:31:48
[post_content] =>
[post_title] =>
[post_excerpt] =>
[post_status] => publish
[comment_status] => closed
[ping_status] => closed
[post_password] =>
[post_name] => 469
[to_ping] =>
[pinged] =>
[post_modified] => 2017-12-24 04:41:13
[post_modified_gmt] => 2017-12-24 04:41:13
[post_content_filtered] =>
[post_parent] => 0
[guid] => http://master.localhost/469/
[menu_order] => 1
[post_type] => nav_menu_item
[post_mime_type] =>
[comment_count] => 0
[filter] => raw
[db_id] => 469
[menu_item_parent] => 0
[object_id] => 2
[object] => page
[type] => post_type
[type_label] => Page
[url] => http://master.localhost/school/
[title] => School
[target] =>
[attr_title] =>
[description] =>
[classes] => Array
(
	[0] =>
	[1] => menu-item
	[2] => menu-item-type-post_type
	[3] => menu-item-object-page
	[4] => menu-item-has-children
)
[xfn] => page
[current] =>
[current_item_ancestor] =>
[current_item_parent] =>
        )
)*/
/**
 * slog( $args );
(
	[menu] => WP_Term Object
	(
		[term_id] => 14
            [name] => Primary
	[slug] => primary
	[term_group] => 0
            [term_taxonomy_id] => 14
            [taxonomy] => nav_menu
	[description] =>
            [parent] => 0
            [count] => 198
            [filter] => raw
        )

    [container] =>
    [container_class] =>
    [container_id] =>
    [menu_class] => navbar-nav ml-auto
	[menu_id] => primary-nav
	[echo] => 1
    [fallback_cb] => wp_page_menu
	[before] =>
    [after] =>
    [link_before] =>
    [link_after] =>
    [items_wrap] => <ul id="%1$s" class="%2$s">%3$s</ul>
                                                     [item_spacing] => discard
	[depth] => 2
    [walker] =>
    [theme_location] => primary
)*/
//global $post;
//$item_classes = ( isset( $args->item_classes ) ) ? explode( ' ', $args->item_classes ) : array();
//$menu_items   = array();
/*if ( isset( $args->widget ) && $args->widget ) {
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
}*/
/*if ( $args->menu_id && 'primary-nav' == $args->menu_id ) {

	if ( isset( $args->item_classes ) ) {
		foreach ( $sorted_menu_items as $sorted_menu_item ) {
			$is_parent                 = ( in_array( 'menu-item-has-children', $sorted_menu_item->classes ) ) ? true : false;
			$parent_item_classes       = ( isset( $args->parent_item_classes ) && $is_parent ) ? explode( ' ', $args->parent_item_classes ) : array();
			$sorted_menu_item->classes = array_merge( $sorted_menu_item->classes, $args->item_classes, $parent_item_classes );;
			$menu_items[] = $sorted_menu_item;
		}

		return $menu_items;
	}
}*/

	// primary-nav
	if ( isset( $args->menu_id ) && 'primary-nav' == $args->menu_id ) {
		for ( $i=1; $i<=count( $sorted_menu_items ); $i++ ) {
			$sorted_menu_items[$i]->classes[] = 'nav-item';
			if ( in_array( 'current-menu-item', $sorted_menu_items[$i]->classes ) ) {
				$sorted_menu_items[$i]->classes[] = 'active';
			}
			if ( in_array( 'menu-item-has-children', $sorted_menu_items[$i]->classes ) ) {
				$sorted_menu_items[$i]->classes[] = 'dropdown';
			}
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
/**
 * slog($atts);
(
[title] =>
[target] =>
[href] => http://master.localhost/school/test-migration-vs-wix/
)
 */
/**
 * slog( $args );
(
[menu] => WP_Term Object
(
[term_id] => 14
[name] => Primary
[slug] => primary
[term_group] => 0
[term_taxonomy_id] => 14
[taxonomy] => nav_menu
[description] =>
[parent] => 0
[count] => 198
[filter] => raw
)

[container] =>
[container_class] =>
[container_id] =>
[menu_class] => navbar-nav ml-auto
[menu_id] => primary-nav
[echo] => 1
[fallback_cb] => wp_page_menu
[before] =>
[after] =>
[link_before] =>
[link_after] =>
[items_wrap] => <ul id="%1$s" class="%2$s">%3$s</ul>
[item_spacing] => discard
[depth] => 2
[walker] =>
[theme_location] => primary
)
*/
/**
 * slog( $item->classes );
(
[0] =>
[1] => menu-item
[2] => menu-item-type-post_type
[3] => menu-item-object-page
[4] => menu-item-has-children
)
*/
/**
 * $depth - 0+
 */
/*slog($item);
slog($atts);
slog($depth);*/
/*$atts[ 'title' ] = '';
$atts[ 'class' ] = '';
//$link_classes    = ( isset( $args->link_classes ) ) ? $args->link_classes : '';
if ( isset( $args->widget ) && $args->widget ) {
	//$atts[ 'class' ] = $link_classes . ' depth-' . $depth;
	$atts[ 'class' ] .= ( $item->current ) ? 'active' : '';
	$max_depth       = ( isset( $args->max_depth ) ) ? (int) $args->max_depth : 0;
	if ( $max_depth != 0 && $depth + 1 > $max_depth ) {
		$atts[ 'style' ] = 'display: none;';
	}
}*/
/*if ( $args->menu_id && 'primary-nav' == $args->menu_id ) {
	//$atts[ 'class' ] = $link_classes . ' depth-' . $depth;
	$has_children = ( in_array( 'menu-item-has-children', $item->classes ) ) ? true : false;
	if ( $has_children ) {
		if ( $depth == 0 ) {
			// todo: add the id back to use the aria-labelledby attribute on the dropdown
			//$atts[ 'id' ] = 'true';
			$atts[ 'aria-haspopup' ] = 'true';
			$atts[ 'aria-expanded' ] = 'false';
			$atts[ 'data-toggle' ]   = 'dropdown';
			$atts[ 'class' ]         .= 'dropdown-toggle';
		}
	}
	if ( in_array( 'current-menu-item', $item->classes ) != false ) {
		$atts[ 'class' ] .= ' active';
	}
}*/
	// primary-nav
	if ( isset( $args->menu_id ) && 'primary-nav' == $args->menu_id ) {
		$atts['id'] = 'menu-item-id-' . $item->ID;
		if ( 0 == $depth ) {
			if ( in_array( 'menu-item-has-children', $item->classes ) ) {
				$atts['href'] = '#';
				$atts['class'] = 'nav-link dropdown-toggle';
				$atts['role'] = 'button';
				$atts['data-toggle'] = 'dropdown';
				$atts['aria-haspopup'] = 'true';
				$atts['aria-expanded'] = 'false';
			} else {
				$atts['class'] = 'nav-link';
			}
		}
		if ( 0 < $depth ) {
			$atts['class'] = 'dropdown-item';
		}
	}

	return $atts;
}


/**
 * Filters menu & submenu containers - ul
 */
add_filter( 'nav_menu_submenu_css_class', 'syn_nav_menu_submenu_css_class', 10, 3 );
function syn_nav_menu_submenu_css_class( $classes, $args, $depth ) {
/**
* slog( $classes );
(
	[0] => sub-menu
)
*/
/**
* slog( $args );
(
[menu] => WP_Term Object
	(
		[term_id] => 14
            [name] => Primary
	[slug] => primary
	[term_group] => 0
            [term_taxonomy_id] => 14
            [taxonomy] => nav_menu
	[description] =>
            [parent] => 0
            [count] => 198
            [filter] => raw
        )

    [container] =>
    [container_class] =>
    [container_id] =>
    [menu_class] => navbar-nav ml-auto
	[menu_id] => primary-nav
	[echo] => 1
    [fallback_cb] => wp_page_menu
	[before] =>
    [after] =>
    [link_before] =>
    [link_after] =>
    [items_wrap] => <ul id="%1$s" class="%2$s">%3$s</ul>
                                                     [item_spacing] => discard
	[depth] => 2
    [walker] =>
    [theme_location] => primary
)
*/
/**
 * slog( $depth ); always 0 for primary nav
 */
/*if ( isset( $args->submenu_classes ) ) {
	$submenu_classes = explode( ' ', $args->submenu_classes );
	$classes         = array_merge( $classes, $submenu_classes );
}*/
	// primary-nav
	if ( isset( $args->menu_id ) && 'primary-nav' == $args->menu_id ) {
		$classes[] = 'dropdown-menu';
		$menu_classes = explode( ' ', $args->menu_class );
		if ( in_array( 'ml-auto', $menu_classes ) ) {
			$classes[] = 'dropdown-menu-right';
		}
	}
	return $classes;
}

//
//
// Bone yard
//
//
function _______syn_get_navbar_brand() {
	$out = '';
	if ( has_custom_logo() || display_header_text() ) {
		$organization = esc_attr( get_bloginfo( 'name', 'display' ) );
		$out          .= '<a class="navbar-brand" href="' . esc_url( home_url( '/' ) ) . '" rel="home">';
		if ( has_custom_logo() ) {
			$out .= wp_get_attachment_image( get_theme_mod( 'custom_logo' ), 'full', false, array( 'alt' => $organization . ' Logo' ) );
		}
		if ( display_header_text() ) {
			$out .= $organization;
		}
		$out .= '</a>';
	}

	return $out;
}