<?php
	
	get_header();
	$lb            = syntric_linebreak();
	$tab           = syntric_tab();
	$results       = $wp_query -> found_posts;
	$results_label = ( 1 < $results || 0 == $results ) ? ' results' : ' result';
	echo '<div id="search-wrapper" class="content-wrapper ' . get_post_type() . '-wrapper">' . $lb;
	echo '<div class="' . esc_html( get_theme_mod( 'syntric_container_type' ) ) . '">' . $lb;
	echo '<div class="row">' . $lb;
	echo '<main id="content" class="col content-area content">' . $lb;
	echo '<h1 class="page-title" role="heading">' . $lb;
	echo 'Search Results<span class="badge badge-pill badge-secondary">' . $results . $results_label . '</span>' . $lb;
	echo '</h1>' . $lb;
	if( have_posts() ) {
		while( have_posts() ) : the_post();
			//echo '<article class="' . implode( ' ', get_post_class() ) . '" id="post-' . $post->ID . '">' . $lb;
			echo '<article id="post-' . $post -> ID . '">' . $lb;
			echo '<h2 class="post-title">' . $lb;
			echo '<a href="' . get_the_permalink() . '" rel="bookmark">' . get_the_title() . '</a>' . $lb;
			if( 'post' == $post -> post_type ) {
				echo syntric_get_excerpt_badges( $post -> ID );
			}
			echo '</h2>' . $lb;
			/*if ( 'post' == $post->post_type ) {
				echo '<span class="post-category">' . syntric_get_taxonomies_terms() . '</span>' . $lb;
				echo '<span class="post-date">' . get_the_date() . '</span>' . $lb;
			}*/
			if( syntric_has_content( $post -> post_content ) ) {
				echo '<div class="post-content">' . $lb;
				the_excerpt();
				echo '</div>' . $lb;
			}
			echo '</article>' . $lb;
		endwhile;
	} else {
		get_template_part( 'loop-templates/content', 'none' );
	}
	syntric_pagination();
	echo '</main>' . $lb;
	echo '</div>' . $lb;
	echo '</div>' . $lb;
	echo '</div>' . $lb;
	get_footer();