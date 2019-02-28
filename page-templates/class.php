<?php
	
	/**
	 * Template Name: Class
	 * Template Post Type: page
	 * Template for displaying a class page (e.g. 3rd Period Algebra 1, Mr. Smiths's 3rd Grade Class, etc.)
	 *
	 * @package syntric
	 */
	get_header();
	$lb    = syntric_linebreak();
	$tab   = syntric_tab();
	$class = syntric_get_class();
	echo '<div id="class-wrapper" class="content-wrapper ' . get_post_type() . '-wrapper">' . $lb;
	echo '<div class="' . esc_html( get_theme_mod( 'syntric_container_type' ) ) . '">' . $lb;
	echo '<div class="row">' . $lb;
	syntric_sidebar( 'main', 'left' );
	echo '<main id="content" class="col content-area content">' . $lb;
	echo '<h1 class="page-title" role="heading">' . get_the_title() . syntric_get_post_badges() . '</h1>' . $lb;
	syntric_sidebar( 'main', 'top' );
	if( have_posts() ) :
		while( have_posts() ) : the_post();
			if( syntric_has_content( $post -> post_content ) ) :
				//var_dump( 'class on class.php' );
				//ar_dump( $class );
				////////////////////////////////////////////////////////////////////////////////////////////////////////////////
				//echo 'Class object follows...';
				//var_dump( syntric_get_class( $post->ID ) );
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
