<?php
	get_header();
	$lb  = syn_get_linebreak();
	$tab = syn_get_tab();
	echo '<div id="archive-wrapper" class="content-wrapper ' . get_post_type() . '-wrapper">' . $lb;
	echo '<div class="' . esc_html( get_theme_mod( 'syntric_container_type' ) ) . '">' . $lb;
	echo '<div class="row">' . $lb;
	syn_sidebar( 'main', 'left' );
	echo '<main id="content" class="col content-area content">' . $lb;
	//echo '<h1 class="page-title" role="heading">' . single_cat_title( '', false ) . '</h1>' . $lb;
	echo '<h1 class="page-title" role="heading">' . get_the_archive_title() . '</h1>' . $lb;
	syn_sidebar( 'main', 'top' );
	if ( have_posts() ) :
		while( have_posts() ) : the_post();
			//echo '<article class="' . implode( ' ', get_post_class() ) . '" id="post-' . $post->ID . '">' . $lb;
			echo '<article id="post-' . $post->ID . '">' . $lb;
			echo '<h2 class="post-title">' . $lb;
			echo '<a href="' . get_the_permalink() . '" rel="bookmark">' . $lb;
			the_title();
			echo '</a>' . $lb;
			syn_excerpt_badges( $post->ID );
			echo '</h2>' . $lb;
			echo '<span class="post-date">' . get_the_date() . '</span>' . $lb;
			if ( syn_has_content( $post->post_content ) ) {
				echo '<div class="post-content">' . $lb;
				the_excerpt();
				echo '</div>' . $lb;
			}
			echo '</article>' . $lb;
		endwhile;
		syn_pagination();
	endif;
	syn_sidebar( 'main', 'bottom' );
	echo '</main>' . $lb;
	syn_sidebar( 'main', 'right' );
	echo '</div>' . $lb;
	echo '</div>' . $lb;
	echo '</div>' . $lb;
	get_footer();
