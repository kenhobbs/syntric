<?php
	get_header();
	echo '<div id="archive-wrapper" class="content-wrapper ' . get_post_type() . '-wrapper">';
	echo '<div class="container-fluid">';
	echo '<div class="row">';
	syntric_sidebar( 'main-left-sidebar' );
	echo '<main id="content" class="col content-area content">';
	echo '<h1 class="page-title" role="heading">' . get_the_title( get_option( 'page_for_posts' ) ) . '</h1>';
	syntric_sidebar( 'main-top-sidebar' );
	if( have_posts() ) :
		echo '<div class="list-group">';
		while( have_posts() ) : the_post();
			echo '<article id="post-' . $post -> ID . '" class="list-group-item">';
			if( has_post_thumbnail() ) :
				echo '<div class="list-group-item-feature">';
				the_post_thumbnail( 'thumbnail', [ 'class' => 'alignleft' ] );
				echo '</div>';
			endif;
			echo '<div class="list-group-item-content">';
			echo '<h2 class="post-title">';
			echo '<a href="' . get_the_permalink() . '" rel="bookmark">';
			the_title();
			echo '</a>';
			echo syntric_get_excerpt_badges( $post -> ID );
			echo '</h2>';
			echo '<div class="post-date">' . get_the_date() . '</div>';
			if( syntric_has_content( $post -> post_content ) ) {
				echo '<div class="post-content">';
				the_excerpt();
				echo '</div>';
			}
			echo '</div>';
			echo '</article>';
		endwhile;
		echo '</div>';
		syntric_pagination();
	endif;
	syntric_sidebar( 'main-bottom-sidebar' );
	echo '</main>';
	syntric_sidebar( 'main-right-sidebar' );
	echo '</div>';
	echo '</div>';
	echo '</div>';
	get_footer();
	
	
	/*
	;
	get_header();
	echo '<div id="home-wrapper" class="content-wrapper ' . get_post_type() . '-wrapper">';
	echo  '<div class="container-fluid">';
	echo   '<div class="row">';
	syntric_sidebar( 'main-left-sidebar' );
	echo    '<main id="content" class="col content-area content">';
	echo     '<h1 class="page-title" role="heading">' . get_the_title( get_option( 'page_for_posts' ) ) . '</h1>';
	syntric_sidebar( 'main-top-sidebar' );
	if( have_posts() ) :
		//echo '<div class="list-group">';
		while( have_posts() ) : the_post();
			echo     '<article id="post-' . $post -> ID . '" class="' . implode( ' ', get_post_class() ) . '">';
			echo '<header class="post-header">';
			echo      '<h2 class="post-title">';
			echo       '<a href="' . get_the_permalink() . '" rel="bookmark">';
			the_title();
			echo '</a>';
			echo syntric_get_excerpt_badges( $post -> ID );
			echo      '</h2>';
			echo      '<div class="post-date">' . get_the_date() . '</div>';
			echo '</header>';
			if( syntric_has_content( $post -> post_content ) ) {
				echo      '<div class="post-content">';
				the_excerpt();
				echo      '</div>';
			}
			echo     '</article>';
		endwhile;
		//echo '</div>';
		syntric_pagination();
	endif;
	syntric_sidebar( 'main-bottom-sidebar' );
	echo    '</main>';
	syntric_sidebar( 'main-right-sidebar' );
	echo   '</div>';
	echo  '</div>';
	echo '</div>';
	get_footer();*/
	
	/*
	 * echo '<article class="' . implode( ' ', get_post_class() ) . '" id="post-' . $post->ID . '">';
			echo '<header class="post-header">';
			echo '<h2 class="post-title">';
			echo '<a href="' . get_the_permalink() . '" rel="bookmark">';
			the_title();
			echo '</a>';
			echo '</h2>';
			if ( 'syn_event' == get_post_type() ) {
				$calendar = get_the_title( get_field( 'syn_event_calendar_id', get_the_ID() ) );
				$dates    = syn_get_event_dates( get_the_ID() );
				$location = get_field( 'syn_event_location', get_the_ID() );
				echo '<span class="post-category">' . $calendar . '</span>';
				echo '<span class="post-date">' . $dates . '</span>';
				if ( ! empty( $location ) ) {
					echo '<span class="post-location">' . $location . '</span>';
				}
			} elseif ( 'post' == get_post_type() ) {
				echo '<span class="post-category">' . syn_get_taxonomies_terms() . '</span>';
				echo '<span class="post-date">' . get_the_date() . '</span>';
			}
			echo '</header>';
			echo '<div class="post-content">';
			the_excerpt();
			echo '</div>';
		echo '</article>';

	 */