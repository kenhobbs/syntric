<?php

/**
 * Template Name: Course
 * Template Post Type: page
 * Template for displaying a course page (e.g. Algebra 1, AP English, etc, etc.)
 *
 * @package syntric
 */
$course = syntric_get_course( '15580658122226' );
//slog( $course );
get_header();
echo '<div id="course-wrapper" class="content-wrapper ' . get_post_type() . '-wrapper">';
echo '<div class="container-fluid">';
echo '<div class="row">';
syntric_sidebar( 'main-left-sidebar' );
echo '<main id="content" class="col content-area content">';
echo '<h1 class="page-title" role="heading">' . get_the_title() . syntric_get_post_badges() . '</h1>';
syntric_sidebar( 'main-top-sidebar' );
if( have_posts() ) :
	while( have_posts() ) : the_post();
		if( syntric_has_content( $post -> post_content ) ) :
			echo '<article class="' . implode( ' ', get_post_class() ) . '" id="post-' . $post -> ID . '">';
			the_content();
			echo '</article>';
		endif;
	endwhile;
endif;
syntric_display_course( $post -> ID );
syntric_sidebar( 'main-bottom-sidebar' );
echo '</main>';
syntric_sidebar( 'main-right-sidebar' );
echo '</div>';
echo '</div>';
echo '</div>';
get_footer();