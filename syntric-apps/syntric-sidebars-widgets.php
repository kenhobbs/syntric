<?php
	/**
	 * Syntric Apps: Widgets
	 */
	
	/**
	 * Return sidebar custom fields for a widget
	 *
	 * @param $widget_id
	 */
	function syntric_widget_sidebar( $widget_id ) {
		$sidebars_widgets  = get_option( 'sidebars_widgets', [] );
		$widget_sidebar_id = '';
		foreach( $sidebars_widgets as $key => $value ) {
			if( in_array( $widget_id, $value ) ) {
				$widget_sidebar_id = $key;
				break;
			}
		}
		$sidebars = get_field( 'syn_sidebars', 'option' );
		foreach( $sidebars as $sidebar ) {
			$sidebar_id = $sidebar[ 'sidebar_id' ];
			if( $widget_sidebar_id == $sidebar_id ) {
				return $sidebar;
			}
		}
		
		return;
	}
	
	/**
	 * Returns a class representing the containing sidebar for a widget based on the sidebar's section, location (main) and layout (header/footer)
	 *
	 * For example, if the passed widget is in a footer sidebar that is set to full width this will return footer-fluid.
	 *
	 * @param $widget_id (required) id of the widget for which we want to return it's containing sidebar class
	 *
	 * @return string class value returns ex. main-left, main-top, footer-fluid, etc.
	 */
	function syntric_get_sidebar_class( $widget_id ) {
		$sidebar = syntric_widget_sidebar( $widget_id );
		$section = $sidebar[ 'section' ][ 'value' ];
		if( 'main' == $section ) {
			$location = $sidebar[ 'location' ][ 'value' ];
			
			return $section . '-' . $location;
		} else {
			$layout       = $sidebar[ 'layout' ][ 'value' ];
			$layout_array = explode( '-', $layout );
			$layout       = ( 1 == count( $layout_array ) ) ? 'fixed' : $layout_array[ 1 ];
			
			return $section . '-' . $layout;
		}
	}
	
	/**
	 * Get sidebars for page template, section and location
	 */
	function syntric_sidebar( $section, $location = null ) {
		global $post;
		global $wp_registered_sidebars;
		if( ! $post ) {
			return;
		}
		$lb  = syntric_linebreak();
		$tab = syntric_tab();
		//$sidebars = get_field( 'syn_sidebars', 'option' );
		if( $wp_registered_sidebars ) {
			foreach( $wp_registered_sidebars as $sidebar ) {
				/*slog( is_active_sidebar( $sidebar['id'] ) );*/
				// check if sidebar is active
				/*$sidebar_id      = $sidebar[ 'sidebar_id' ];
				$sidebar_active  = $sidebar[ 'active' ];
				$sidebar_section = $sidebar[ 'section' ][ 'value' ];
				$sidebar_filters = $sidebar[ 'filters' ];*/
				//$active          = true;
				/*if( ! $sidebar_active ) {
					continue;
				}
				// check if sidebar belongs in this section
				if( $section != $sidebar_section ) {
					continue;
				}
				// if main section, check if sidebar belongs in this location
				if( 'main' == $section ) {
					$sidebar_location = $sidebar[ 'location' ][ 'value' ];
					if( $location != $sidebar_location ) {
						continue;
					}
				}
				// check if sidebar has any assigned widgets
				if( ! is_active_sidebar( $sidebar_id ) ) {
					continue;
				}*/
				// check sidebar filters
				/*if( $sidebar_filters ) {
					$active = syntric_process_filters( $sidebar_filters, $post );
				}*/
				// we are now filtered down to the only active and relevant sidebars for this call
				/*if( ! $sidebar_filters || syntric_process_filters( $sidebar_filters, $post ) ) {
					$active_widgets = syntric_sidebar_active_widgets( $sidebar_id, $post -> ID );
					//var_dump( $active_widgets );
					if( count( $active_widgets ) ) {
						$widgets_classes = [];
						foreach( $active_widgets as $widget ) {
							$widget_class_array = explode( '-', $widget );
							array_pop( $widget_class_array );
							array_pop( $widget_class_array );
							array_shift( $widget_class_array );
							$widgets_classes[] = implode( '-', $widget_class_array );
						}
						$wp_sidebar       = $wp_registered_sidebars[ $sidebar_id ];
						$wp_sidebar_class = $wp_sidebar[ 'class' ];
						$widgets_classes  = implode( ' ', $widgets_classes );
						if( 'main' == $section && in_array( $sidebar_location, [ 'left', 'right', ] ) ) {
							$col = ( 'left' == $sidebar_location ) ? ' col-lg-3' : ' col-xl-3';
							echo '<aside class="' . $wp_sidebar_class . $col . ' sidebar ' . $sidebar_section . '-' . $sidebar_location . '-sidebar ' . $widgets_classes . '">' . $lb;
							dynamic_sidebar( $sidebar_id );
							//syntric_columns( 1, 3 );
							echo '</aside>' . $lb;
						} elseif( 'main' == $section && in_array( $sidebar_location, [ 'top', 'bottom', ] ) ) {
							echo '<section class="' . $wp_sidebar_class . ' sidebar ' . $sidebar_section . '-' . $sidebar_location . '-sidebar ' . $widgets_classes . '">' . $lb;
							dynamic_sidebar( $sidebar_id );
							echo '</section>' . $lb;
						} elseif( in_array( $section, [ 'header', 'footer', ] ) ) {
							$sidebar_layout = $sidebar[ 'layout' ][ 'value' ];
							$sl_array       = explode( '-', $sidebar_layout );
							$sidebar_class  = ( 1 == count( $sl_array ) ) ? 'fixed' : $sl_array[ count( $sl_array ) - 1 ];
							$sidebar_class  .= ' widgets-' . count( $active_widgets );
							echo '<section class="' . $wp_sidebar_class . ' sidebar ' . $sidebar_section . '-sidebar ' . $sidebar_class . ' ' . $widgets_classes . '">' . $lb;
							echo $tab . '<div class="' . $sidebar_layout . '">' . $lb;
							echo $tab . $tab . '<div class="row">' . $lb;
							dynamic_sidebar( $sidebar_id );
							echo $tab . $tab . '</div>' . $lb;
							echo $tab . '</div>' . $lb;
							echo '</section>' . $lb;
						}
					}
				}*/
			}
		}
	}
	
	/**
	 * Get active widgets for sidebar
	 *
	 * @param $sidebar_id
	 * @param $post_id
	 *
	 * @return array
	 */
	function syntric_sidebar_active_widgets( $sidebar_id, $post_id ) {
		$sidebars_widgets = get_option( 'sidebars_widgets', [] );
		$sidebar_widgets  = $sidebars_widgets[ $sidebar_id ];
		$active_widgets   = [];
		foreach( $sidebar_widgets as $widget ) {
			$widget_array = explode( '-', $widget );
			// @ this point $widget is the base_id from the widget class (syn-nav-menu-widget, syn-categories-menu-widget, syn-calendars-menu-widget, etc
			// todo: why/what is the next conditional doing with 'nav_menu' == $widget?? ---> || 'nav_menu' == $widget
			// process Syntric widgets...
			if( 'syn' == $widget_array[ 0 ] ) {
				/*if ( 'nav' == $widget_array[ 1 ] && 'menu' == $widget_array[ 2 ] && ! syntric_nav_menu_children_count( $post_id ) ) {
					return $active_widgets;
				}*/
				array_pop( $widget_array ); // remove the integer at the end of the widget name eg - syn-nav-menu-widget-2 where the 2 is removed from the array
				$widget_name = implode( '_', $widget_array ); // $widget name is the name of the widget minus the integer eg syn-nav-menu, syn-upcoming-events, etc
				$dynamic     = get_field( $widget_name . '_dynamic', 'widget_' . $widget );
				if( $dynamic ) {
					array_pop( $widget_array ); // remove "widget" from end of $widget_id/$widget_name...syntric_nav_menu
					$widget_fieldname = implode( '_', $widget_array );
					$widget_active    = get_field( $widget_fieldname . '_active', $post_id );
					if( ! $widget_active ) {
						continue;
					}
					switch( $widget_fieldname ) :
						case 'syntric_nav_menu':
							if( ! syntric_nav_menu_children_count( $post_id ) ) {
								continue;
							}
							break;
						/*case 'syntric_categories_menu':
							if ( ! syntric_categories_menu_children_count( $post_id ) ) {
								continue;
							}
							break;
						case 'syntric_calendars_menu':
							if ( ! syntric_calendars_menu_children_count( $post_id ) ) {
								continue;
							}
							break;
						case 'syntric_microblogs_menu':
							if ( ! syntric_microblogs_menu_children_count( $post_id ) ) {
								continue;
							}
							break;*/
						case 'syntric_upcoming_events':
							break;
					endswitch;
					
					// todo: this is stupid...change syntric_calendar in page widgets to syntric_upcoming_events
					if( 'syntric_upcoming_events' == $widget_fieldname ) {
					} else {
						$widget_active = get_field( $widget_fieldname . '_active', $post_id );
					}
					if( $widget_active ) {
						$active_widgets[] = $widget;
						continue;
					}
				} else {
					$active_widgets[] = $widget;
				} //&& syntric_categories_menu_cats_count()
			} else {
				$active_widgets[] = $widget;
			}
			/*if ( 'syn' == $widget_array[0] && 'categories' == $widget_array[1] && 'menu' == $widget_array[2] && 0 != syntric_categories_menu_cats_count() ) {
				echo ' categories menu';
				echo ' cats count' .  syntric_categories_menu_cats_count();
				var_dump( $active_widgets );
				return $active_widgets;
			}
			if ( 'syn' == $widget_array[0] && 'calendars' == $widget_array[1] && 'menu' == $widget_array[2] && ! syntric_calendars_menu_cals_count()  ) {
				echo ' calendars_menu';
				return $active_widgets;
			}
			// handle WP widgets here
			echo ' default';*/
		}
		
		//var_dump( $active_widgets);
		return $active_widgets;
	}
	
	/**
	 * Dynamically create responsive columns for horizontal sidebars
	 *
	 * todo: handle cases where number of widgets is an odd number
	 */
	add_filter( 'dynamic_sidebar_params', 'syntric_dynamic_sidebar_params' );
	function syntric_dynamic_sidebar_params( $params ) {
		global $post;
		if( ! is_admin() ) {
			$sidebars = get_field( 'syn_sidebars', 'option' );
			if( $sidebars ) {
				foreach( $sidebars as $sidebar ) {
					$section = $sidebar[ 'section' ][ 'value' ];
					if( $params[ 0 ][ 'id' ] == $sidebar[ 'sidebar_id' ] && ( 'header' == $section || 'footer' == $section ) ) {
						$active_widgets = syntric_sidebar_active_widgets( $params[ 0 ][ 'id' ], $post -> ID );
						$widget_count   = count( $active_widgets );
						if( $widget_count ) {
							if( 1 == $widget_count ) {
								$col_classes = 'col-12';
							} elseif( 2 == $widget_count ) {
								$col_classes = 'col-sm-6';
							} elseif( 3 == $widget_count ) {
								$col_classes = 'col-lg-4 col-sm-12';
							} elseif( 4 == $widget_count ) {
								$col_classes = 'col-lg-3 col-md-6';
							} elseif( 5 == $widget_count ) {
								$first_widget  = $active_widgets[ 0 ];
								$second_widget = $active_widgets[ 1 ];
								if( $params[ 0 ][ 'widget_id' ] == $first_widget || $params[ 0 ][ 'widget_id' ] == $second_widget ) {
									$col_classes = 'col-sm-6';
								} else {
									$col_classes = 'col-lg-4 col-md-6';
								}
							} elseif( 6 == $widget_count ) {
								$col_classes = 'col-lg-4 col-md-6';
							} elseif( 7 == $widget_count ) {
								$col_classes = 'col';
								/*$first_widget = $active_widgets[ 0 ];
								$second_widget = $active_widgets[ 1 ];
								$third_widget = $active_widgets[ 2 ];
								if ( $params[ 0 ][ 'widget_id' ] == $first_widget || $params[ 0 ][ 'widget_id' ] == $second_widget || $params[ 0 ][ 'widget_id' ] == $third_widget ) {
									$col_classes = 'col-sm-6';
								} else {
									$col_classes = 'col-lg-2 col-md-4';
								}*/
							} elseif( 8 == $widget_count ) {
								$col_classes = 'col-lg-3 col-md-4 col-sm-6';
							} elseif( 9 == $widget_count ) {
								$col_classes = 'col-md-4';
							} elseif( 10 == $widget_count ) {
								$first_widget = $active_widgets[ 0 ];
								if( $params[ 0 ][ 'widget_id' ] == $first_widget ) {
									$col_classes = 'col-12';
								} else {
									$col_classes = 'col-md-4';
								}
							} else {
								$lg_col_units = 12 / $widget_count;
								$md_col_units = 12 / ( $widget_count / 2 );
								$col_classes  = 'col-lg-' . $lg_col_units . ' col-md-' . $md_col_units;
							}
							$params[ 0 ][ 'before_widget' ] = str_replace( 'class="', 'class="' . $col_classes . ' ', $params[ 0 ][ 'before_widget' ] );
						}
					}
				}
			}
		}
		
		return $params;
	}
	
	/*function syntric_get_wordpress_widgets() {
		$wp_widget_factory = $GLOBALS[ 'wp_widget_factory' ];
		$wp_widgets        = $wp_widget_factory -> widgets;
	}*/