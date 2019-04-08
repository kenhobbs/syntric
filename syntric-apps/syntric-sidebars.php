<?php
	/**
	 * Syntric Apps: Widgets & Sidebars
	 *
	 * todo: implement layout (fixed, full-width, full-bleed) control for sidebar sections
	 * todo: break down Syntric Apps: Sidebars & Widgets admin UI so that Header/Main/Footer sidebars are on separate sub-tabs
	 * todo: test all filter variations
	 */
	
	//add_filter( 'acf/update_value/name=sidebar_id', 'syn_update_id' );
	/**
	 * Register sidebars
	 */
	add_action( 'widgets_init', 'syntric_sidebars_init' );
	function syntric_sidebars_init() {
		$settings = get_field( 'syntric_settings', 'option' );
		$sidebars = $settings[ 'sidebars' ];
		if( $sidebars ) {
			foreach( $sidebars as $sidebar ) {
				$id       = $sidebar[ 'value' ];
				$name     = $sidebar[ 'label' ];
				$id_array = explode( '-', $id );
				if( 'header' == $id_array[ 0 ] || 'footer' == $id_array[ 0 ] ) {
					$class = 'sidebar ' . $id_array[ 0 ] . '-sidebar';
				} elseif( 'super' == $id_array[ 0 ] && 'header' == $id_array[ 1 ] ) {
					$class = 'sidebar super-header-sidebar';
				} else {
					$class = 'sidebar ' . $id;
				}
				register_sidebar( [
					'name'          => $name,
					'id'            => $id,
					'description'   => '',
					'class'         => $class,
					'before_widget' => '<div' . ' id="%1$s" class="widget %2$s">',
					'after_widget'  => '</div>',
					'before_title'  => '<h2>',
					'after_title'   => '</h2>',
				] );
			}
		}
	}
	
	function syntric_sidebar( $sidebar_id ) {
		global $post;
		global $wp_registered_sidebars;
		if( ! $post ) {
			return;
		};
		
		$active_widgets = syntric_sidebar_active_widgets( $sidebar_id );
		if( count( $active_widgets ) ) {
			$wp_sidebar       = $wp_registered_sidebars[ $sidebar_id ];
			$wp_sidebar_class = $wp_sidebar[ 'class' ];
			if( in_array( $sidebar_id, [ 'main-left-sidebar', 'main-right-sidebar' ] ) ) {
				echo '<aside id="' . $sidebar_id . '" class="' . $wp_sidebar_class . ' col-xl-3">';
				dynamic_sidebar( $sidebar_id );
				//syn_columns( 1, 3 );
				echo '</aside>';
			} elseif( in_array( $sidebar_id, [ 'main-top-sidebar', 'main-bottom-sidebar' ] ) ) {
				echo '<section id="' . $sidebar_id . '" class="' . $wp_sidebar_class . '">';
				dynamic_sidebar( $sidebar_id );
				echo '</section>';
			} elseif( in_array( $sidebar_id, [
				'header-sidebar-1',
				'header-sidebar-2',
				'header-sidebar-3',
				'footer-sidebar-1',
				'footer-sidebar-2',
				'footer-sidebar-3',
			] ) ) {
				echo '<section id="' . $sidebar_id . '" class="' . $wp_sidebar_class . '">';
				echo '<div class="container-fluid">';
				echo '<div class="row">';
				dynamic_sidebar( $sidebar_id );
				echo '</div>';
				echo '</div>';
				echo '</section>';
			} elseif( 'super-header-sidebar' == $sidebar_id ) {
				echo '<header id="' . $sidebar_id . '" class="' . $wp_sidebar_class . '">';
				dynamic_sidebar( $sidebar_id );
				echo '</header>';
			} elseif( 'sub-footer-sidebar' == $sidebar_id ) {
				echo '<footer id="' . $sidebar_id . '" class="' . $wp_sidebar_class . '">';
				dynamic_sidebar( $sidebar_id );
				echo '</footer>';
			}
		}
		
		return;
	}
	
	/**
	 * Get active widgets for sidebar
	 *
	 * @param $sidebar_id
	 * @param $post_id
	 *
	 * @return array
	 */
	function syntric_sidebar_active_widgets( $sidebar_id ) {
		$sidebars_widgets = get_option( 'sidebars_widgets', [] );
		if( isset( $sidebars_widgets[ $sidebar_id ] ) ) {
			return $sidebars_widgets[ $sidebar_id ];
		}
		
		return [];
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
			$settings = get_field( 'syntric_settings', 'option' );
			$sidebars = $settings[ 'sidebars' ];
			foreach( $sidebars as $sidebar ) {
				if( $params[ 0 ][ 'id' ] == $sidebar[ 'value' ] ) {
					$active_widgets = syntric_sidebar_active_widgets( $params[ 0 ][ 'id' ] );
					$widget_count   = count( $active_widgets );
					if( 0 < $widget_count && in_array( $sidebar[ 'value' ], [ 'super-header-sidebar', 'header-sidebar-1', 'header-sidebar-2', 'header-sidebar-3', 'footer-sidebar-1', 'footer-sidebar-2', 'footer-sidebar-3', 'sub-footer-sidebar' ] ) ) {
						$params[ 0 ][ 'before_widget' ] = str_replace( 'class="', 'class="col-xl-' . 12 / $widget_count . ' ' . 'widgets-' . $widget_count . ' ', $params[ 0 ][ 'before_widget' ] );
					}
				}
			}
		}
		
		return $params;
	}