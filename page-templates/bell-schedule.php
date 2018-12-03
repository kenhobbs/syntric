<?php
	/**
	 * Template Name: Bell Schedule
	 * Template Post Type: page
	 * Template for displaying bell schedules
	 *
	 * @package syntric
	 */
	get_header();
	$lb             = syn_get_linebreak();
	$tab            = syn_get_tab();
	$bell_schedules = get_field( 'syn_schedules', 'option' );
	echo '<div id="bell-schedule-wrapper" class="content-wrapper ' . get_post_type() . '-wrapper">' . $lb;
	echo '<div class="' . esc_html( get_theme_mod( 'syntric_container_type' ) ) . '">' . $lb;
	echo '<div class="row">' . $lb;
	syn_sidebar( 'main', 'left' );
	echo '<main id="content" class="col content-area content">' . $lb;
	echo '<h1 class="page-title" role="heading">' . get_the_title() . '</h1>' . $lb;
	syn_sidebar( 'main', 'top' );
	if ( $bell_schedules ) {
		foreach ( $bell_schedules as $bell_schedule ) {
			$schedule = $bell_schedule[ 'schedule' ];
			echo '<table>';
			echo '<caption>' . $schedule[ 'name' ] . '</caption>';
			echo '<thead>';
			echo '<tr>';
			echo '<th scope="col">Header</th>';
			echo '<th scope="col">Header</th>';
			echo '<th scope="col">Header</th>';
			echo '<th scope="col">Header</th>';
			echo '</tr>';
			echo '</thead>';
		}
	}
	echo '<tbody>';
	echo '<tr valign="top">';
	echo '<td>&nbsp;</td>';
	echo '<td>&nbsp;</td>';
	echo '<td>&nbsp;</td>';
	echo '<td>&nbsp;</td>';
	echo '</tr>';
	echo '<tr valign="top">';
	echo '<td>&nbsp;</td>';
	echo '<td>&nbsp;</td>';
	echo '<td>&nbsp;</td>';
	echo '<td>&nbsp;</td>';
	echo '</tr>';
	echo '</tbody>';
	echo '</table>';
	if ( have_posts() ) :
		while( have_posts() ) : the_post();
			if ( syn_has_content( $post->post_content ) ) :
				echo '<article class="' . implode( ' ', get_post_class() ) . '" id="post-' . $post->ID . '">' . $lb;
				the_content();
				echo '</article>' . $lb;
				var_dump( $bell_schedules );
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