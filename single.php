<?php
	get_header();
	echo '<div id="single-wrapper" class="content-wrapper ' . get_post_type() . '-wrapper">';
	echo '<div class="container-fluid">';
	echo '<div class="row">';
	syntric_sidebar( 'main-left-sidebar' );
	echo '<main id="content" class="col content-area content">';
	echo '<h1 class="page-title" role="heading">' . get_the_title() . syntric_get_post_badges() . '</h1>';
	syntric_sidebar( 'main-top-sidebar' );
if( have_posts() ) :
		while( have_posts() ) : the_post();
			if( 'syntric_calendar' == get_post_type() ) :
				echo '<div id="fullcalendar">';
				echo '<span>Calendar is loading</span>';
				echo '</div>';
			elseif( 'syntric_event' == get_post_type() ) :
				$dates    = syntric_get_event_dates( get_the_ID() );
				$location = get_field( 'location', get_the_ID() );
				echo '<article class="' . implode( ' ', get_post_class() ) . '" id="post-' . $post -> ID . '">';
				echo '<header class="post-header">';
				echo '<span class="post-date">' . $dates . '</span>';
				if( ! empty( $location ) ) :
					echo '<span class="post-location">' . $location . '</span>';
				endif;
				echo '</header>';
				echo '<div class="post-content">';
				the_content();
				echo '</div>';
				echo '</article>';
			else :
				echo '<article class="' . implode( ' ', get_post_class() ) . '" id="post-' . $post -> ID . '">';
				echo '<header class="post-header">';
				echo '<span class="post-date">' . get_the_date() . '</span>';
				echo '</header>';
				echo '<div class="post-content">';
				the_content();
				echo '</div>';
				echo '</article>';
			endif;
		endwhile;
endif;
	syntric_sidebar( 'main-bottom-sidebar' );
	echo '</main>';
	syntric_sidebar( 'main-right-sidebar' );
	echo '</div>';
	echo '</div>';
	echo '</div>';
	get_footer();
?>