<?php
	/**
	 * Template Name: Teachers
	 * Template Post Type: page
	 * Template for displaying teachers parent (top ancestor of teacher pages)
	 *
	 * @package syntric
	 */
	get_header();
	$lb = syn_get_linebreak();
	$tab = syn_get_tab();
	echo '<div id="teachers-wrapper" class="content-wrapper ' . get_post_type() . '-wrapper">' . $lb;
	echo '<div class="' . esc_html( get_theme_mod( 'syntric_container_type' ) ) . '">' . $lb;
	echo '<div class="row">' . $lb;
	syn_sidebar( 'main', 'left' );
	echo '<main id="content" class="col content-area content">' . $lb;
	echo '<h1 class="page-title" role="heading">' . get_the_title() . '</h1>' . $lb;
	syn_sidebar( 'main', 'top' );
	if ( have_posts() ) :
		while( have_posts() ) : the_post();
			if ( syn_has_content( $post->post_content ) ) :
				echo '<article class="' . implode( ' ', get_post_class() ) . '" id="post-' . $post->ID . '">' . $lb;
				the_content();
				echo '</article>' . $lb;
			endif;
		endwhile;
	endif;
	syn_display_teachers();
	syn_sidebar( 'main', 'bottom' );
	echo '</main>' . $lb;
	syn_sidebar( 'main', 'right' );
	echo '</div>' . $lb;
	echo '</div>' . $lb;
	echo '</div>' . $lb;
	get_footer();