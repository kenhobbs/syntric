<?php
	
	get_header();
	$results       = $wp_query -> found_posts;
	$results_label = ( 1 < $results || 0 == $results ) ? ' results' : ' result';
	echo '<div id="search-wrapper" class="content-wrapper ' . get_post_type() . '-wrapper">';
	echo '<div class="container-fluid">';
	echo '<div class="row">';
	echo '<main id="content" class="col content-area content">';
	echo '<h1 class="page-title" role="heading">';
	echo 'Search Results<span class="badge badge-pill badge-secondary">' . $results . $results_label . '</span>';
	echo '</h1>';
	if( have_posts() ) {
		while( have_posts() ) : the_post();
			//echo '<article class="' . implode( ' ', get_post_class() ) . '" id="post-' . $post->ID . '">';
			echo '<article id="post-' . $post -> ID . '">';
			echo '<h2 class="post-title">';
			echo '<a href="' . get_the_permalink() . '" rel="bookmark">' . get_the_title() . '</a>';
			if( 'post' == $post -> post_type ) {
				echo syntric_get_excerpt_badges( $post -> ID );
			}
			echo '</h2>';
			/*if ( 'post' == $post->post_type ) {
				echo '<span class="post-category">' . syntric_get_taxonomies_terms() . '</span>';
				echo '<span class="post-date">' . get_the_date() . '</span>';
			}*/
			if( syntric_has_content( $post -> post_content ) ) {
				echo '<div class="post-content">';
				the_excerpt();
				echo '</div>';
			}
			echo '</article>';
		endwhile;
	} else {
		get_template_part( 'loop-templates/content', 'none' );
	}
	syntric_pagination();
	echo '</main>';
	echo '</div>';
	echo '</div>';
	echo '</div>';
	get_footer();