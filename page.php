<?php
	
	get_header();
	$lb  = syntric_linebreak();
	$tab = syntric_tab();
	echo '<div id="page-wrapper" class="content-wrapper ' . get_post_type() . '-wrapper">' . $lb;
	echo '<div class="' . esc_html( get_theme_mod( 'syntric_container_type' ) ) . '">' . $lb;
	echo '<div class="row">' . $lb;
	syntric_sidebar( 'main', 'left' );
	echo '<main id="content" class="col content-area content">' . $lb;
	echo '<h1 class="page-title" role="heading">' . get_the_title() . syntric_get_post_badges() . '</h1>' . $lb;
	syntric_sidebar( 'main', 'top' );
	if( have_posts() ) :
		while( have_posts() ) : the_post();
			if( syntric_has_content( $post -> post_content ) ) :
				echo '<article class="' . implode( ' ', get_post_class() ) . '" id="post-' . $post -> ID . '">' . $lb;
				the_content();
				echo '</article>' . $lb;
			endif;
		endwhile;
	endif;
	syntric_sidebar( 'main', 'bottom' );
	echo '</main>' . $lb;
	syntric_sidebar( 'main', 'right' );
	echo '</div>' . $lb;
	echo '</div>' . $lb;
	echo '</div>' . $lb;
	get_footer();
