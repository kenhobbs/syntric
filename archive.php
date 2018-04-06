<?php
	get_header();
	$lb  = syn_get_linebreak();
	$tab = syn_get_tab();
	echo '<div id="archive-wrapper" class="content-wrapper ' . get_post_type() . '-wrapper">' . $lb;
	echo $tab . '<div class="' . esc_html( get_theme_mod( 'syntric_container_type' ) ) . '">' . $lb;
	echo $tab . $tab . '<div class="row">' . $lb;
	syn_sidebar( 'main', 'left' );
	echo $tab . $tab . $tab . '<main id="content" class="col content-area content">' . $lb;
	echo $tab . $tab . $tab . $tab . '<h1 class="page-title" role="heading">' . get_the_archive_title() . '</h1>' . $lb;
	syn_sidebar( 'main', 'top' );
	if ( have_posts() ) :
		while( have_posts() ) : the_post();
			//echo '<article class="' . implode( ' ', get_post_class() ) . '" id="post-' . $post->ID . '">' . $lb;
			echo $tab . $tab . $tab . $tab . '<article id="post-' . $post->ID . '">' . $lb;
			echo $tab . $tab . $tab . $tab . $tab . '<h2 class="post-title">' . $lb;
			echo $tab . $tab . $tab . $tab . $tab . $tab . '<a href="' . get_the_permalink() . '" rel="bookmark">';
			the_title();
			echo '</a>' . $lb;
			syn_excerpt_badges( $post->ID );
			echo $tab . $tab . $tab . $tab . $tab . '</h2>' . $lb;
			echo $tab . $tab . $tab . $tab . $tab . '<div class="post-date">' . get_the_date() . '</div>' . $lb;
			if ( syn_has_content( $post->post_content ) ) {
				echo $tab . $tab . $tab . $tab . $tab . '<div class="post-content">' . $lb;
				the_excerpt();
				echo $tab . $tab . $tab . $tab . $tab . '</div>' . $lb;
			}
			echo $tab . $tab . $tab . $tab . '</article>' . $lb;
		endwhile;
		syn_pagination();
	endif;
	syn_sidebar( 'main', 'bottom' );
	echo $tab . $tab . $tab . '</main>' . $lb;
	syn_sidebar( 'main', 'right' );
	echo $tab . $tab . '</div>' . $lb;
	echo $tab . '</div>' . $lb;
	echo '</div>' . $lb;
	get_footer();
