<?php
	/**
	 * Template Name: Schedules
	 * Template Post Type: page
	 * Template for displaying bell schedules
	 *
	 * @package syntric
	 */
	get_header();
	$lb        = syn_get_linebreak();
	$tab       = syn_get_tab();
	$schedules = get_field( 'syn_schedules', get_the_ID() );
	echo '<div id="bell-schedule-wrapper" class="content-wrapper ' . get_post_type() . '-wrapper">' . $lb;
	echo '<div class="' . esc_html( get_theme_mod( 'syntric_container_type' ) ) . '">' . $lb;
	echo '<div class="row">' . $lb;
	syn_sidebar( 'main', 'left' );
	echo '<main id="content" class="col content-area content">' . $lb;
	echo '<h1 class="page-title" role="heading">' . get_the_title() . '</h1>' . $lb;
	syn_sidebar( 'main', 'top' );
	if ( $schedules ) {
		foreach ( $schedules as $schedule ) {
			echo '<h2>' . $schedule[ 'schedule_type' ] . '</h2>';
			echo '<table>';
			echo '<caption class="sr-only">' . $schedule[ 'schedule_type' ] . '</caption>';
			echo '<thead>';
			echo '<tr>';
			echo '<th scope="col" nowrap>Period</th>';
			echo '<th scope="col" nowrap>Start Time</th>';
			echo '<th scope="col" nowrap>End Time</th>';
			//echo '<th scope="col" nowrap>Instructional Period</th>';
			echo '</tr>';
			echo '</thead>';
			echo '<tbody>';
			foreach ( $schedule[ 'periods' ] as $period ) {
				$instructional_period = ( $period[ 'is_instructional_period' ] ) ? 'Yes' : 'No';
				echo '<tr valign="top">';
				echo '<td style="width: 50%;">' . $period[ 'label' ] . '</td>';
				echo '<td style="text-align: right; width: 25%" nowrap>' . $period[ 'start_time' ] . '</td>';
				echo '<td style="text-align: right; width: 25%" nowrap>' . $period[ 'end_time' ] . '</td>';
				//echo '<td style="text-align: right;" nowrap>' . $instructional_period . '</td>';
				echo '</tr>';
			}
			echo '</tbody>';
			echo '</table>';
		}
	} else {
		echo 'No schedules are available.';
	}
	if ( have_posts() ) :
		while( have_posts() ) : the_post();
			if ( syn_has_content( $post->post_content ) ) :
				echo '<article class="' . implode( ' ', get_post_class() ) . '" id="post-' . $post->ID . '">' . $lb;
				the_content();
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