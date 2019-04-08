<?php
	
	/**
	 * Echo the primary nav which is a Bootstrap 4 navbar
	 */
	/*function syntric_primary_nav() {
		
		;
		$args = [
			'theme_location'  => 'primary',
			'container'       => 'ul',
			'container_id'    => 'primary-nav-collapse',
			'container_class' => 'collapse navbar-collapse',
			'menu_class'      => 'navbar-nav',
			'depth'           => 2,
			'item_spacing'    => ( syntric_remove_whitespace() ) ? 'discard' : 'preserve',
		];
		//echo '<nav id="primary-navbar" class="navbar navbar-expand-xl navbar-light sticky-top">';
		echo '<nav id="primary-navbar" class="navbar">';
		echo  '<div class="navbar-brand-toggler">';
		echo   '<button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#' . $args[ 'container_id' ] . '" aria-controls="' . $args[ 'container_id' ] . '" aria-expanded="false" aria-label="Toggle navigation">';
		echo    '<span class="fa fa-bars"></span>';
		echo   '</button>';
		if( has_custom_logo() || display_header_text() ) {
			//echo   '<a class="navbar-brand" href="' . esc_url( home_url( '/' ) ) . '" rel="home">';
			echo   '<div class="navbar-brand">';
			if( has_custom_logo() ) {
				echo wp_get_attachment_image( get_theme_mod( 'custom_logo' ), 'thumbnail', false, [ 'class' => 'brand-logo', 'alt' => 'Logo' ] );
			}
			if( display_header_text() ) {
				$name    = esc_attr( get_bloginfo( 'name', 'display' ) );
				$tagline = esc_attr( get_bloginfo( 'description', 'display' ) );
				//echo    '<div>';
				if( ! empty( $name ) ) {
					echo    '<div class="site-title">';
					echo     '<a href="' . esc_url( home_url( '/' ) ) . '" rel="home">' . $name . '</a>';
					echo    '</div>';
				}
				if( ! empty( $tagline ) ) {
					echo     '<div class="site-description">' . $tagline . '</div>';
				}
				//echo    '</div>';
			}
			echo   '</div>';
			//echo   '</a>';
		}
		echo  '</div>';
		wp_nav_menu( $args );
		echo '</nav>';
	}*/