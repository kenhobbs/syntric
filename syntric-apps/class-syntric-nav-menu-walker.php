<?php
	class Syntric_Nav_Menu_Walker extends Walker_Nav_Menu {
		function start_el( &$output, $item, $depth = 0, $args = [], $id = 0 ) {
			$classes     = empty( $item->classes ) ? [] : (array) $item->classes;
			$class_names = join( ' ', apply_filters( 'nav_menu_css_class', array_filter( $classes ), $item ) );
			$class_names .= ' depth-' . $depth;
			! empty ( $class_names ) and $class_names = 'class="' . esc_attr( $class_names ) . '"';
			$output     .= "";
			$attributes = '';
			! empty( $item->attr_title ) and $attributes .= ' title="' . esc_attr( $item->attr_title ) . '"';
			! empty( $item->target ) and $attributes .= ' target="' . esc_attr( $item->target ) . '"';
			! empty( $item->xfn ) and $attributes .= ' rel="' . esc_attr( $item->xfn ) . '"';
			! empty( $item->url ) and $attributes .= ' href="' . esc_attr( $item->url ) . '"';
			$title       = apply_filters( 'the_title', $item->title, $item->ID );
			$item_output = $args->before . "<a$attributes $class_names>" . $args->link_before . $title . '</a>' . $args->link_after . $args->after;
			$output      .= apply_filters( 'walker_nav_menu_start_el', $item_output, $item, $depth, $args );
		}
		public function start_lvl( &$output, $depth = 0, $args = [] ) {
			if ( ( isset( $args->item_spacing ) && 'discard' === $args->item_spacing ) || syn_remove_whitespace() ) {
				$t = '';
				$n = '';
			} else {
				$t = "\t";
				$n = "\n";
			}
			$indent = str_repeat( $t, $depth );
			// Default class.
			$classes = [];
			/**
			 * Filters the CSS class(es) applied to a menu list element.
			 *
			 * @since 4.8.0
			 *
			 * @param array    $classes The CSS classes that are applied to the menu `<ul>` element.
			 * @param stdClass $args    An object of `wp_nav_menu()` arguments.
			 * @param int      $depth   Depth of menu item. Used for padding.
			 */
			$class_names = join( ' ', apply_filters( 'nav_menu_submenu_css_class', $classes, $args, $depth ) );
			$class_names .= $class_names . 'sub-list-group level-' . ( $depth + 1 );
			$class_names = $class_names ? ' class="' . esc_attr( $class_names ) . '"' : '';
			$output .= "{$n}{$indent}<div$class_names>{$n}";
		}
		public function end_lvl( &$output, $depth = 0, $args = array() ) {
			if ( ( isset( $args->item_spacing ) && 'discard' === $args->item_spacing ) || syn_remove_whitespace() ) {
				$t = '';
				$n = '';
			} else {
				$t = "\t";
				$n = "\n";
			}
			$indent = str_repeat( $t, $depth );
			$output .= "$indent</div>{$n}";
		}
		public function end_el( &$output, $item, $depth = 0, $args = array() ) {
			if ( ( isset( $args->item_spacing ) && 'discard' === $args->item_spacing ) || syn_remove_whitespace() ) {
				$t = '';
				$n = '';
			} else {
				$t = "\t";
				$n = "\n";
			}
			$output .= "{$n}";
		}
	}