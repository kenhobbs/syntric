<?php
	/**
	 * Syntric Apps: Widgets & Sidebars
	 *
	 * todo: implement layout (fixed, full-width, full-bleed) control for sidebar sections
	 * todo: break down Syntric Apps: Sidebars & Widgets admin UI so that Header/Main/Footer sidebars are on separate sub-tabs
	 * todo: test all filter variations
	 */
	add_filter( 'acf/update_value/name=sidebar_id', 'syn_update_id' );
	/**
	 * Register sidebars
	 */
	add_action( 'widgets_init', 'syn_sidebars_init' );
	function syn_sidebars_init() {
		$header_count      = 0;
		$main_left_count   = 0;
		$main_top_count    = 0;
		$main_bottom_count = 0;
		$main_right_count  = 0;
		$footer_count      = 0;
		if( have_rows( 'syn_sidebars', 'option' ) ) {
			while( have_rows( 'syn_sidebars', 'option' ) ) : the_row();
				$section       = get_sub_field( 'section' );
				$sidebar_class = $section[ 'value' ];
				if( 'header' == $section[ 'value' ] ) {
					$header_count ++;
					$sidebar_class .= '-' . $header_count;
				} elseif( 'footer' == $section[ 'value' ] ) {
					$footer_count ++;
					$sidebar_class .= '-' . $footer_count;
				} else {
					$location = get_sub_field( 'location' );
					if( 'left' == $location[ 'value' ] ) {
						$main_left_count ++;
						$sidebar_class .= '-' . $location[ 'value' ] . '-' . $main_left_count;
					} elseif( 'top' == $location[ 'value' ] ) {
						$main_top_count ++;
						$sidebar_class .= '-' . $location[ 'value' ] . '-' . $main_top_count;
					} elseif( 'bottom' == $location[ 'value' ] ) {
						$main_bottom_count ++;
						$sidebar_class .= '-' . $location[ 'value' ] . '-' . $main_bottom_count;
					} else {
						$main_right_count ++;
						$sidebar_class .= '-' . $location[ 'value' ] . '-' . $main_right_count;
					}
				}
				register_sidebar( [
					'name'          => get_sub_field( 'name' ),
					'id'            => get_sub_field( 'sidebar_id' ),
					'description'   => get_sub_field( 'description' ),
					'class'         => $sidebar_class, // this gets overwritten
					'before_widget' => '<div' . ' id="%1$s" class="widget %2$s">',
					'after_widget'  => '</div>',
					'before_title'  => '<h2>',
					'after_title'   => '</h2>',
				] );
			endwhile;
		}
	}

	/**
	 * Get sidebars for page template, section and location
	 */
	function syn_sidebar( $section, $location = null ) {
		global $post;
		global $wp_registered_sidebars;
		if( ! $post ) {
			return;
		}
		if ( syn_remove_whitespace() ) {
			$lb  = '';
			$tab = '';
		} else {
			$lb  = "\n";
			$tab = "\t";
		}
		$sidebars = get_field( 'syn_sidebars', 'option' );
		if( $sidebars ) {
			foreach( $sidebars as $sidebar ) {
				// check if sidebar is active
				$sidebar_id      = $sidebar[ 'sidebar_id' ];
				$sidebar_active  = $sidebar[ 'active' ];
				$sidebar_section = $sidebar[ 'section' ][ 'value' ];
				$sidebar_filters = $sidebar[ 'filters' ];
				//$active          = true;
				if( ! $sidebar_active ) {
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
				}
				// check sidebar filters
				/*if( $sidebar_filters ) {
					$active = syn_process_filters( $sidebar_filters, $post );
				}*/
				// we are now filtered down to the only active and relevant sidebars for this call
				if( ! $sidebar_filters || syn_process_filters( $sidebar_filters, $post ) ) {
					$active_widgets = syn_sidebar_active_widgets( $sidebar_id, $post->ID );
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
						if( 'main' == $section && in_array( $sidebar_location, [ 'left', 'right' ] ) ) {
							echo '<aside class="' . $wp_sidebar_class . ' col-xl-3 sidebar ' . $sidebar_section . '-' . $sidebar_location . '-sidebar ' . $widgets_classes . '">' . $lb;
							dynamic_sidebar( $sidebar_id );
							//syn_columns( 1, 3 );
							echo '</aside>' . $lb;
						} elseif( 'main' == $section && in_array( $sidebar_location, [ 'top', 'bottom' ] ) ) {
							echo '<section class="' . $wp_sidebar_class . ' sidebar ' . $sidebar_section . '-' . $sidebar_location . '-sidebar ' . $widgets_classes . '">' . $lb;
							dynamic_sidebar( $sidebar_id );
							echo '</section>' . $lb;
						} elseif( in_array( $section, [ 'header', 'footer' ] ) ) {
							$sidebar_layout    = $sidebar[ 'layout' ][ 'value' ];
							$sl_array = explode( '-', $sidebar_layout);
							$sidebar_class = ( 1 == count( $sl_array ) ) ? 'fixed' : $sl_array[count($sl_array)-1];
							$sidebar_class .= ' widgets-' . count($active_widgets);
							$row_class       = ( 'container-bleed' == $sidebar_layout ) ? 'row no-gutters' : 'row';
							echo '<section class="' . $wp_sidebar_class . ' sidebar ' . $sidebar_section . '-sidebar ' . $sidebar_class . ' ' . $widgets_classes . '">' . $lb;
							echo $tab . '<div class="' . $sidebar_layout . '">' . $lb;
							echo $tab . $tab . '<div class="' . $row_class . '">' . $lb;
							dynamic_sidebar( $sidebar_id );
							echo $tab . $tab . '</div>' . $lb;
							echo $tab . '</div>' . $lb;
							echo '</section>' . $lb;
						}
					}
				}
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
	function syn_sidebar_active_widgets( $sidebar_id, $post_id ) {
		$sidebars_widgets = get_option( 'sidebars_widgets', [] );
		$sidebar_widgets  = $sidebars_widgets[ $sidebar_id ];
		$active_widgets   = [];
		//slog($sidebar_widgets);
		foreach( $sidebar_widgets as $widget ) {
			//slog($widget);
			$widget_array = explode( '-', $widget );
			if( $widget_array[ 0 ] == 'syn' || 'nav_menu' == $widget ) {
				array_pop( $widget_array );
				$widget_name = implode( '_', $widget_array );
				$dynamic     = get_field( $widget_name . '_dynamic', 'widget_' . $widget );
				if( $dynamic ) {
					array_pop( $widget_array );
					$widget_fieldname = implode( '_', $widget_array );
					$widget_active    = get_field( $widget_fieldname . '_active', $post_id );
					if( $widget_active ) {
						$active_widgets[] = $widget;
						continue;
					}
				} else {
					$active_widgets[] = $widget;
				}
			} else {
				// handle WP widgets here
				$active_widgets[] = $widget;
			}
		}

		return $active_widgets;
	}

	/**
	 * Dynamically create responsive columns for horizontal sidebars
	 *
	 * todo: handle cases where number of widgets is an odd number
	 */
	add_filter( 'dynamic_sidebar_params', 'syn_dynamic_sidebar_params' );
	function syn_dynamic_sidebar_params( $params ) {
		global $post;
		if( ! is_admin() ) {
			$sidebars = get_field( 'syn_sidebars', 'option' );
			foreach( $sidebars as $sidebar ) {
				if( $params[ 0 ][ 'id' ] == $sidebar[ 'sidebar_id' ] ) {
					$active_widgets = syn_sidebar_active_widgets( $params[ 0 ][ 'id' ], $post->ID );
					$widget_count   = count( $active_widgets );
					if( 0 < $widget_count && ( 'header' == $sidebar[ 'section' ][ 'value' ] || 'footer' == $sidebar[ 'section' ][ 'value' ] ) ) {
						$params[ 0 ][ 'before_widget' ] = str_replace( 'class="', 'class="col-xl-' . 12/$widget_count . ' ', $params[ 0 ][ 'before_widget' ] );
					}
				}
			}
		}
		return $params;
	}

	/**
	 * Set id and format phone
	 */
	add_action( 'acf/save_post', 'syn_save_sidebars', 20 );
	function syn_save_sidebars( $post_id ) {
		// don't save for autosave
		if( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
			return;
		}
		if( is_admin() && isset( $_REQUEST[ 'page' ] ) && 'syntric-sidebars-widgets' == $_REQUEST[ 'page' ] ) {
			$lb = "\n";
			if( have_rows( 'syn_sidebars', 'option' ) ) {
				while( have_rows( 'syn_sidebars', 'option' ) ) : the_row();
					// set id
					/*$sidebar_id = get_sub_field( 'sidebar_id' );
					if ( ! isset( $sidebar_id ) || empty( $sidebar_id ) ) {
						update_sub_field( 'sidebar_id', syn_generate_permanent_id() );
					}*/
					// set name
					$sidebar_section  = get_sub_field( 'section' );
					$sidebar_location = get_sub_field( 'location' );
					$name             = $sidebar_section[ 'label' ];
					$name             .= ( 'main' == $sidebar_section[ 'value' ] ) ? ' > ' . $sidebar_location[ 'label' ] : '';
					$name_array       = [];
					// description
					$description_array = [];
					// run through filters
					if( have_rows( 'filters' ) ) {
						while( have_rows( 'filters' ) ) : the_row();
							$filter_parameter     = get_sub_field( 'parameter' );
							$filter_operator      = get_sub_field( 'operator' );
							$filter_value         = get_sub_field( 'value' );
							$filter_operator_sign = ( 'is' == $filter_operator[ 'value' ] ) ? '+' : '-';
							switch( $filter_parameter[ 'value' ] ) :
								case 'post_type' :
									$post_type_value     = $filter_value[ 'post_type_value' ];
									$name_array[]        = $filter_operator_sign . $post_type_value[ 'label' ];
									$description_array[] = 'Post type ' . $filter_operator[ 'label' ] . ' ' . $post_type_value[ 'label' ];
									break;
								case 'post_category' :
									$post_category_value = $filter_value[ 'post_category_value' ];
									$post_category       = get_the_category_by_ID( $post_category_value );
									$name_array[]        = $filter_operator_sign . $post_category;
									$description_array[] = 'Post category ' . $filter_operator[ 'label' ] . ' ' . $post_category;
									break;
								case 'page_template' :
									$page_template_value = $filter_value[ 'page_template_value' ];
									$name_array[]        = $filter_operator_sign . $page_template_value[ 'label' ];
									$description_array[] = 'Post template ' . $filter_operator[ 'label' ] . ' ' . $page_template_value[ 'label' ];
									break;
								case 'post' :
									$post_value          = $filter_value[ 'post_value' ];
									$name_array[]        = $filter_operator_sign . get_the_title( $post_value );
									$description_array[] = 'Post ' . $filter_operator[ 'label' ] . ' ' . get_the_title( $post_value );
									break;
								case 'page' :
									$page_value          = $filter_value[ 'page_value' ];
									$name_array[]        = $filter_operator_sign . get_the_title( $page_value );
									$description_array[] = 'Page ' . $filter_operator[ 'label' ] . ' ' . get_the_title( $page_value );
									break;
							endswitch;
						endwhile;
					}
					if( count( $name_array ) ) {
						$name .= ' (' . implode( ', ', $name_array ) . ')';
					}
					update_sub_field( 'name', $name );
					$sidebar_layout = get_sub_field( 'layout' );
					if( 'main' != $sidebar_section[ 'value' ] ) {
						$description_array[] = $sidebar_layout[ 'label' ] . ' layout';
					}
					if( count( $description_array ) ) {
						update_sub_field( 'description', implode( ' / ', $description_array ) );
					}
				endwhile;
			}
		}
	}