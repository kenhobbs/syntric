<?php
	
	;
	get_header();
	echo '<div id="attachment-wrapper" class="content-wrapper ' . get_post_type() . '-wrapper">';
	echo '<div class="container-fluid">';
	echo '<div class="row">';
	syntric_sidebar( 'main-left-sidebar' );
	echo '<main id="content" class="col content-area content">';
	echo '<h1 class="page-title" role="heading">' . get_the_title() . '</h1>';
	syntric_sidebar( 'main-top-sidebar' );
	if( have_posts() ) :
		while( have_posts() ) : the_post();
			$images      = [];
			$image_sizes = get_intermediate_image_sizes();
			array_unshift( $image_sizes, 'full' );
			foreach( $image_sizes as $image_size ) {
				$image    = wp_get_attachment_image_src( get_the_ID(), $image_size );
				$name     = $image[ 1 ] . 'x' . $image[ 2 ];
				$images[] = '<a href="' . $image[ 0 ] . '">' . $name . '</a>';
			}
			echo '<article class="' . implode( ' ', get_post_class() ) . '" id="post-' . $post -> ID . '">';
			echo wp_get_attachment_image( get_the_ID(), 'medium_large' );
			echo '<div class="mt-3">';
			echo '<h2>Sizes</h2>';
			echo '<p>Dimensions are in pixels.</p>';
			echo implode( ' / ', $images );
			echo '</div>';
			echo '</article>';
		endwhile;
	endif;
	syntric_sidebar( 'main-bottom-sidebar' );
	echo '</main>';
	syntric_sidebar( 'main-right-sidebar' );
	echo '</div>';
	echo '</div>';
	echo '</div>';
	get_footer();