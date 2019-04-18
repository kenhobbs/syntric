<?php

/**
 * Template Name: Roster
 * Template Post Type: page
 * Template for displaying a roster such as a Staff page
 *
 * @package syntric
 */
get_header();
echo '<div id="teachers-wrapper" class="content-wrapper ' . get_post_type() . '-wrapper">';
echo '<div class="container-fluid">';
echo '<div class="row">';
syntric_sidebar( 'main-left-sidebar' );
echo '<main id="content" class="col content-area content">';
echo '<h1 class="page-title" role="heading">' . get_the_title() . syntric_get_post_badges() . '</h1>';
syntric_sidebar( 'main-top-sidebar' );
if( have_posts() ) :
	while( have_posts() ) : the_post();
		if( syntric_has_content( $post -> post_content ) ) :
			echo '<article id="post-' . $post -> ID . '" class="' . implode( ' ', get_post_class() ) . '">';
			the_content();
			echo '</article>';
		endif;
	endwhile;
endif;
syntric_display_roster();
syntric_sidebar( 'main-bottom-sidebar' );
echo '</main>';
syntric_sidebar( 'main-right-sidebar' );
echo '</div>';
echo '</div>';
echo '</div>';
get_footer();