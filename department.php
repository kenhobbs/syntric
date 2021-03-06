<?php

/**
 * Template Name: Department
 * Template Post Type: page
 * Template for displaying a department page (e.g. Social Studies, Human Resources, Academics (elementary) etc.)
 *
 * @package syntric
 */
get_header();
echo '<div id="department-wrapper" class="content-wrapper ' . get_post_type() . '-wrapper">';
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
echo '<h2>Courses</h2>';
syntric_display_department_courses( $post -> ID );
syntric_sidebar( 'main-bottom-sidebar' );
echo '</main>';
syntric_sidebar( 'main-right-sidebar' );
echo '</div>';
echo '</div>';
echo '</div>';
get_footer();
