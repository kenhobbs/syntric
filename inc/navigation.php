<?php
	
	/**
	 * Echo the primary nav which is a Bootstrap 4 navbar
	 */
	function syntric_primary_nav() {
		$lb   = syntric_linebreak();
		$tab  = syntric_tab();
		$args = [
			'theme_location'  => 'primary',
			'container'       => 'ul',
			'container_id'    => 'primary-nav-collapse',
			'container_class' => 'collapse navbar-collapse',
			'menu_class'      => 'navbar-nav',
			'depth'           => 2,
			'item_spacing'    => ( syntric_remove_whitespace() ) ? 'discard' : 'preserve',
		];
		//echo '<nav id="primary-navbar" class="navbar navbar-expand-xl navbar-light sticky-top">' . $lb;
		echo '<nav id="primary-navbar" class="navbar">' . $lb;
		echo $tab . '<div class="navbar-brand-toggler">' . $lb;
		echo $tab . $tab . '<button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#' . $args[ 'container_id' ] . '" aria-controls="' . $args[ 'container_id' ] . '" aria-expanded="false" aria-label="Toggle navigation">' . $lb;
		echo $tab . $tab . $tab . '<span class="fa fa-bars"></span>' . $lb;
		echo $tab . $tab . '</button>' . $lb;
		if( has_custom_logo() || display_header_text() ) {
			//echo $tab . $tab . '<a class="navbar-brand" href="' . esc_url( home_url( '/' ) ) . '" rel="home">' . $lb;
			echo $tab . $tab . '<div class="navbar-brand">' . $lb;
			if( has_custom_logo() ) {
				echo wp_get_attachment_image( get_theme_mod( 'custom_logo' ), 'thumbnail', false, [ 'class' => 'brand-logo', 'alt' => 'Logo' ] );
			}
			if( display_header_text() ) {
				$name    = esc_attr( get_bloginfo( 'name', 'display' ) );
				$tagline = esc_attr( get_bloginfo( 'description', 'display' ) );
				//echo $tab . $tab . $tab . '<div>' . $lb;
				if( ! empty( $name ) ) {
					echo $tab . $tab . $tab . '<div class="site-title">' . $lb;
					echo $tab . $tab . $tab . $tab . '<a href="' . esc_url( home_url( '/' ) ) . '" rel="home">' . $name . '</a>' . $lb;
					echo $tab . $tab . $tab . '</div>' . $lb;
				}
				if( ! empty( $tagline ) ) {
					echo $tab . $tab . $tab . $tab . '<div class="site-description">' . $tagline . '</div>' . $lb;
				}
				//echo $tab . $tab . $tab . '</div>' . $lb;
			}
			echo $tab . $tab . '</div>' . $lb;
			//echo $tab . $tab . '</a>' . $lb;
		}
		echo $tab . '</div>' . $lb;
		wp_nav_menu( $args );
		echo '</nav>' . $lb;
	}