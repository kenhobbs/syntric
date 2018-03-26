<?php
	get_header();
	$lb = syn_get_linebreak();
	$tab = syn_get_tab();
	echo '<div id="home-wrapper" class="content-wrapper ' . get_post_type() . '-wrapper">';
	echo '<div class="' . esc_html( get_theme_mod( 'syntric_container_type' ) ) . '">';
	echo '<div class="row">';
	syn_sidebar( 'main', 'left' );
	echo '<main id="content" class="col content-area content">';
	echo '<h1 class="page-title" role="heading">' . get_the_title( get_option( 'page_for_posts' ) ) . '</h1>';
	syn_sidebar( 'main', 'top' );
	if ( have_posts() ) {
		while( have_posts() ) : the_post();
			//echo '<article class="' . implode( ' ', get_post_class() ) . '" id="post-' . $post->ID . '">' . $lb;
			echo '<article id="post-' . $post->ID . '">' . $lb;
			echo '<h2 class="post-title">';
			echo '<a href="' . get_the_permalink() . '" rel="bookmark">';
			the_title();
			echo '</a>';
			syn_excerpt_badges( $post->ID );
			echo '</h2>';
			echo '<span class="post-date">' . get_the_date() . '</span>';
			if ( syn_has_content( $post->post_content ) ) {
				echo '<div class="post-content">';
				the_excerpt();
				echo '</div>';
			}
			echo '</article>';
		endwhile;
	} else {
		get_template_part( 'loop-templates/content', 'none' );
	}
	syn_pagination();
	syn_sidebar( 'main', 'bottom' );
	echo '</main>';
	syn_sidebar( 'main', 'right' );
	echo '</div>';
	echo '</div>';
	echo '</div>';
	get_footer();