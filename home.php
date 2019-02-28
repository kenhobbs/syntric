<?php
	
	get_header();
	$lb  = syntric_linebreak();
	$tab = syntric_tab();
	echo '<div id="home-wrapper" class="content-wrapper ' . get_post_type() . '-wrapper">' . $lb;
	echo $tab . '<div class="' . esc_html( get_theme_mod( 'syntric_container_type' ) ) . '">' . $lb;
	echo $tab . $tab . '<div class="row">' . $lb;
	syntric_sidebar( 'main', 'left' );
	echo $tab . $tab . $tab . '<main id="content" class="col content-area content">' . $lb;
	echo $tab . $tab . $tab . $tab . '<h1 class="page-title" role="heading">' . get_the_title( get_option( 'page_for_posts' ) ) . '</h1>' . $lb;
	syntric_sidebar( 'main', 'top' );
	if( have_posts() ) :
		echo '<div class="list-group">';
		while( have_posts() ) : the_post();
			echo $tab . $tab . $tab . $tab . '<article id="post-' . $post -> ID . '" class="list-group-item">' . $lb;
			if( has_post_thumbnail() ) :
				echo '<div class="list-group-item-feature">';
				the_post_thumbnail( 'thumbnail', [ 'class' => 'alignleft' ] );
				echo '</div>';
			endif;
			echo '<div class="list-group-item-content">';
			echo $tab . $tab . $tab . $tab . $tab . '<h2 class="post-title">' . $lb;
			echo $tab . $tab . $tab . $tab . $tab . $tab . '<a href="' . get_the_permalink() . '" rel="bookmark">';
			the_title();
			echo '</a>' . $lb;
			echo syntric_get_excerpt_badges( $post -> ID );
			echo $tab . $tab . $tab . $tab . $tab . '</h2>' . $lb;
			echo $tab . $tab . $tab . $tab . $tab . '<div class="post-date">' . get_the_date() . '</div>' . $lb;
			if( syntric_has_content( $post -> post_content ) ) {
				echo $tab . $tab . $tab . $tab . $tab . '<div class="post-content">' . $lb;
				the_excerpt();
				echo $tab . $tab . $tab . $tab . $tab . '</div>' . $lb;
			}
			echo '</div>';
			echo $tab . $tab . $tab . $tab . '</article>' . $lb;
		endwhile;
		echo '</div>';
		syntric_pagination();
	endif;
	syntric_sidebar( 'main', 'bottom' );
	echo $tab . $tab . $tab . '</main>' . $lb;
	syntric_sidebar( 'main', 'right' );
	echo $tab . $tab . '</div>' . $lb;
	echo $tab . '</div>' . $lb;
	echo '</div>' . $lb;
	get_footer();