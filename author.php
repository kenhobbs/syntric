<?php
	// todo: This needs to be tested
	
	/**
	 *
	 * the_author()
	 * get_the_author()
	 * the_author_link()
	 * get_the_author_link()
	 * the_author_meta()
	 * the_author_posts()
	 * the_author_posts_link()
	 * wp_dropdown_users()
	 * wp_list_authors()
	 * get_author_posts_url()
	 */;
	get_header();
	if( get_queried_object() instanceof WP_User ) {
		$author = get_queried_object();
	}
	echo '<div id="author-wrapper" class="content-wrapper author-wrapper">';
	echo '<div class="container-fluid">';
	echo '<div class="row">';
	syntric_sidebar( 'main-left-sidebar' );
	echo '<main id="content" class="col content-area content">';
	echo '<h1 class="page-title" role="heading">' . get_the_author() . '</h1>';
	syntric_sidebar( 'main-top-sidebar' );
	$author_comments = get_comments( [ 'author__in' => $author -> ID, ] );
	if( $author_comments ) {
		echo '<h2>Comments</h2>';
		foreach( $author_comments as $author_comment ) {
			echo '<div class="comment">' . $author_comment -> comment_content . '</div>';
		}
	}
	syntric_sidebar( 'main-bottom-sidebar' );
	echo '</main>';
	syntric_sidebar( 'main-right-sidebar' );
	echo '</div>';
	echo '</div>';
	echo '</div>';
	get_footer();
