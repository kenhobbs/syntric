<?php
	
	;
	echo '<!DOCTYPE html>';
	echo '<html ' . get_language_attributes() . '>';
	echo '<head>';
	echo '<meta charset="' . get_bloginfo( 'charset' ) . '">';
	echo '<meta http-equiv="X-UA-Compatible" content="IE=edge">';
	echo '<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">';
	echo '<meta name="mobile-web-app-capable" content="yes">';
	echo '<meta name="apple-mobile-web-app-capable" content="yes">';
	echo '<meta name="apple-mobile-web-app-title" content="' . get_bloginfo( 'name' ) . ' - ' . get_bloginfo( 'description' ) . '">';
	echo '<link rel="profile" href="http://gmpg.org/xfn/11">';
	wp_head();
	echo '</head>';
	echo '<body ' . implode( ' ', get_body_class() ) . '>';
	echo '<div id="fb-root" aria-hidden="true"></div>';
	echo '<div class="print-header print-header-name d-print-block" aria-hidden="true">' . get_bloginfo( 'name', 'display' ) . '</div>';
	echo '<a class="sr-only sr-only-focusable skip-to-content-link" href="#content">' . esc_html( 'Skip to content', 'syntric' ) . '</a>';
	/**
	 * Super-header sidebar
	 */
	syntric_sidebar( 'super-header-sidebar' );
	/**
	 * Primary navbar
	 */
	/*$nav_menu_args = [
		'theme_location'  => 'primary',
		'container'       => 'div',
		'container_id'    => 'primary-nav-collapse',
		'container_class' => 'collapse navbar-collapse',
		'menu_class'      => 'navbar-nav',
		'depth'           => 2,
		'item_spacing'    => ( syntric_remove_whitespace() ) ? 'discard' : 'preserve',
	];*/
	/*echo  '<nav id="primary-navbar" class="navbar navbar-expand-xl navbar-light sticky-top">';
	echo   '<button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#' . $nav_menu_args[ 'container_id' ] . '" aria-controls="' . $nav_menu_args[ 'container_id' ] . '" aria-expanded="false" aria-label="Toggle navigation">';
	echo    '<span class="fa fa-bars"></span>';
	echo   '</button>';
	echo   '<a class="navbar-brand" href="' . esc_url( home_url( '/' ) ) . '">';
	if( has_custom_logo() ) {
		echo wp_get_attachment_image( get_theme_mod( 'custom_logo' ), 'thumbnail', false, [ 'class' => 'brand-logo', 'alt' => 'Logo' ] );
	}
	if( display_header_text() ) {
		$name    = esc_attr( get_bloginfo( 'name', 'display' ) );
		$tagline = esc_attr( get_bloginfo( 'description', 'display' ) );
		echo    '<div class="header-text">';
		if( ! empty( $name ) ) {
			echo     '<div class="site-name">' . $name . '</div>';
		}
		if( ! empty( $tagline ) ) {
			echo     '<div class="site-tagline">' . $tagline . '</div>';
		}
		echo    '</div>';
	}
	echo   '</a>';
	wp_nav_menu( [] );
	echo  '</nav>';*/
	syntric_primary_nav();
	/**
	 * Search form
	 */
	get_search_form();
	/**
	 * Banner + Jumbotron
	 */
	$banner_style = ( has_header_image() ) ? ' style="background-image: url(' . get_header_image() . ');" ' : ' style="min-height: 0;" ';
	$jumbotrons   = get_field( 'syntric_jumbotrons', 'option' );
	echo '<div class="banner-wrapper" aria-hidden="true"' . $banner_style . 'role="banner">';
	if( $jumbotrons ) {
		foreach( $jumbotrons as $jumbotron ) {
			$start_datetime = $jumbotron[ 'start_datetime' ];
			$end_datetime   = $jumbotron[ 'end_datetime' ];
			$pass_schedule  = ( $start_datetime || $end_datetime ) ? syntric_process_schedule( $start_datetime, $end_datetime ) : true;
			$pass_filters   = true;
			if( isset( $jumbotron[ 'filters' ] ) && count( $jumbotron[ 'filters' ] ) ) {
				$pass_filters = syntric_process_filters( $jumbotron[ 'filters' ] );
			}
			if( $pass_filters && $pass_schedule ) {
				echo '<div class="jumbotron-wrapper">';
				echo '<h1 class="jumbotron-headline">' . $jumbotron[ 'headline' ] . '</h1>';
				echo '<div class="jumbotron-caption">' . $jumbotron[ 'caption' ] . '</div>';
				if( $jumbotron[ 'include_button' ] ) {
					$button_href   = ( 'page' == $jumbotron[ 'button_target' ] ) ? $jumbotron[ 'button_page' ] : $jumbotron[ 'button_url' ];
					$window_target = ( 'page' == $jumbotron[ 'button_target' ] ) ? '_self' : '_blank';
					echo '<a href="' . $button_href . '" class="btn btn-lg btn-primary jumbotron-button" target="' . $window_target . '">' . $jumbotron[ 'button_text' ] . '</a>';
				}
				echo '</div>';
				break;
			}
		}
	}
	echo '</div>';
	syntric_breadcrumbs();
	syntric_sidebar( 'header-sidebar-1' );
	syntric_sidebar( 'header-sidebar-2' );
	syntric_sidebar( 'header-sidebar-3' );

