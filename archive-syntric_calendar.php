<?php

get_header();
echo '<div id="archive-wrapper" class="content-wrapper ' . get_post_type() . '-wrapper">';
echo '<div class="container-fluid">';
echo '<div class="row">';
syntric_sidebar( 'main-left-sidebar' );
echo '<main id="content" class="col content-area content">';
echo '<h1 class="page-title" role="heading">Calendars</h1>';
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
