<?php
	get_header();
	$lb = syn_get_linebreak();
	$tab = syn_get_tab();
	echo '<div id="attachment-wrapper" class="content-wrapper ' . get_post_type() . '-wrapper">' . $lb;
	echo '<div class="' . esc_html( get_theme_mod( 'syntric_container_type' ) ) . '">' . $lb;
	echo '<div class="row">' . $lb;
	syn_sidebar( 'main', 'left' );
	echo '<main id="content" class="col content-area content">' . $lb;
	echo '<h1 class="page-title" role="heading">' . get_the_title() . '</h1>' . $lb;
	syn_sidebar( 'main', 'top' );
	if ( have_posts() ) :
		while( have_posts() ) : the_post();
			if ( syn_has_content( $post->post_content ) ) :
				$images = array();
				$image_sizes = get_intermediate_image_sizes();
				array_unshift( $image_sizes, 'full' );
				foreach( $image_sizes as $image_size ) {
					$image = wp_get_attachment_image_src( get_the_ID(), $image_size );
					$name = $image[1] . 'x' . $image[2];
					$images[] = '<a href="' . $image[0] . '">' . $name . '</a>';
				}
				echo '<article class="' . implode( ' ', get_post_class() ) . '" id="post-' . $post->ID . '">' . $lb;
				echo wp_get_attachment_image( get_the_ID(), 'medium_large' );
				echo '<div class="mt-3">';
				echo '<h2>Sizes</h2>';
				echo '<p>Dimensions are in pixels.</p>';
				echo implode( ' / ', $images );
				echo '</div>';
				echo '</article>' . $lb;
			endif;
		endwhile;
	endif;
	syn_sidebar( 'main', 'bottom' );
	echo '</main>' . $lb;
	syn_sidebar( 'main', 'right' );
	echo '</div>' . $lb;
	echo '</div>' . $lb;
	echo '</div>' . $lb;
	get_footer();