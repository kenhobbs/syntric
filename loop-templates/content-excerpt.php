<article <?php post_class(); ?> id="post-<?php the_ID(); ?>">
	<?php
	echo '<header class="post-header">';
	echo '<h2 class="post-title">';
	$has_content = syn_has_content( get_the_content( null, true ) );
	if ( $has_content ) {
		echo '<a href="' . get_the_permalink() . '" rel="bookmark">';
	}
	the_title();
	if ( $has_content ) {
		echo '</a>';
	}
	echo '</h2>';
	if ( 'syn_event' == get_post_type() ) {
		$calendar = get_the_title( get_field( 'syn_event_calendar_id', get_the_ID() ) );
		$dates    = syn_get_event_dates( get_the_ID() );
		$location = get_field( 'syn_event_location', get_the_ID() );
		echo '<span class="post-category">' . $calendar . '</span>';
		echo '<span class="post-date">' . $dates . '</span>';
		if ( ! empty( $location ) ) {
			echo '<span class="post-location">' . $location . '</span>';
		}
	} elseif ( 'post' == get_post_type() ) {
		echo '<span class="post-category">' . syn_get_taxonomies_terms() . '</span>';
		echo '<span class="post-date">' . get_the_date() . '</span>';
	} elseif ( 'page' == get_post_type() ) {
		echo '<span class="post-category">Page</span>';
	}
	echo '</header>';
	if ( $has_content ) :
		echo '<div class="post-content">';
		the_excerpt();
		echo '</div>';
	endif;
	?>
</article>

