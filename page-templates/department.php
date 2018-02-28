<?php
	/**
	 * Template Name: Department
	 * Template Post Type: page
	 * Template for displaying a department page (e.g. Social Studies, Human Resources, Academics (elementary) etc.)
	 *
	 * @package syntric
	 */
	get_header();
	if ( syn_remove_whitespace() ) {
		$lb  = '';
		$tab = '';
	} else {
		$lb  = "\n";
		$tab = "\t";
	}
	echo '<div id="department-wrapper" class="content-wrapper ' . get_post_type() . '-wrapper">' . $lb;
	echo '<div class="' . esc_html( get_theme_mod( 'syntric_container_type' ) ) . '">' . $lb;
	echo '<div class="row">' . $lb;
	syn_sidebar( 'main', 'left' );
	echo '<main id="content" class="col content-area content">' . $lb;
	echo '<h1 class="page-title" role="heading">' . get_the_title() . syn_post_badges() . '</h1>' . $lb;
	syn_sidebar( 'main', 'top' );
	if ( have_posts() ) :
		while( have_posts() ) : the_post();
			if ( syn_has_content( the_content() ) ) :
				echo '<article ' . post_class() . ' id="post-' . the_ID() . '">' . $lb;
				the_content();
				echo '</article>' . $lb;
			endif;
		endwhile;
	endif;
	syn_display_department_courses();
	syn_sidebar( 'main', 'bottom' );
	echo '</main>' . $lb;
	syn_sidebar( 'main', 'right' );
	echo '</div>' . $lb;
	echo '</div>' . $lb;
	echo '</div>' . $lb;
	get_footer();
