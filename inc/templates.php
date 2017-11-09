<?php
/**
 * Template related functions - post formats, page templates and custom template tags
 */
//
// Page templates *********************************************************
/**
 * Filter the theme page templates.
 *
 * @param array $page_templates Page templates.
 * @param WP_Theme $this WP_Theme instance.
 * @param WP_Post $post The post being edited, provided for context, or null.
 *
 * @return array (Maybe) modified page templates array.
 */
add_filter( 'theme_page_templates', 'syn_filter_theme_page_templates', 20, 3 );
function syn_filter_theme_page_templates( $page_templates, $theme, $post ) {
	$organization_is_school = syn_organization_is_school();
	// filter page templates according to organization type
	if ( ! $organization_is_school ) {
		unset( $page_templates[ 'page-templates/department.php' ] );
		unset( $page_templates[ 'page-templates/teachers.php' ] );
		unset( $page_templates[ 'page-templates/teacher.php' ] );
		unset( $page_templates[ 'page-templates/class.php' ] );
	}

	return $page_templates;
}

//
// Excerpt related functions **********************************************
add_filter( 'excerpt_more', 'syn_custom_excerpt_more', 99 );
if ( ! function_exists( 'syn_custom_excerpt_more' ) ) {
	/**
	 * Removes the ... from the excerpt read more link
	 *
	 * @param string $more The excerpt.
	 *
	 * @return string
	 */
	function syn_custom_excerpt_more( $more ) {
		return '<a href="' . esc_url( get_the_permalink( get_the_ID() ) ) . '" class="read-more">read more</a>';
	}
}
/**
 * Filter the except length
 *
 * @param int $length Excerpt length.
 *
 * @return int (Maybe) modified excerpt length.
 */
add_filter( 'excerpt_length', 'syn_excerpt_length', 99 );
if ( ! function_exists( 'syn_excerpt_length' ) ) {
	function syn_excerpt_length() {
		return 18;
	}
}
//
// Display functions ******************************************************
/**
 * Display post "posted on" and "author" info
 */
if ( ! function_exists( 'syn_posted_on' ) ) :
	function syn_posted_on() {
		$time_string = '<time class="post-date published updated" datetime="%1$s">%2$s</time>';
		if ( get_the_time( 'U' ) !== get_the_modified_time( 'U' ) ) {
			$time_string = '<time class="post-date published" datetime="%1$s">%2$s</time><time class="updated" datetime="%3$s">%4$s</time>';
		}
		$time_string = sprintf(
			$time_string,
			esc_attr( get_the_date( 'c' ) ),
			esc_html( get_the_date() ),
			esc_attr( get_the_modified_date( 'c' ) ),
			esc_html( get_the_modified_date() )
		);
		$posted_on   = sprintf(
			esc_html_x( 'Posted on %s', 'post date', 'syntric' ),
			'<a href="' . esc_url( get_the_permalink() ) . '" rel="bookmark">' . $time_string . '</a>'
		);
		$byline      = sprintf(
			esc_html_x( 'by %s', 'post author', 'syntric' ),
			'<span class="author vcard"><a class="url fn n" href="' . esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ) . '">' . esc_html( get_the_author() ) . '</a></span>'
		);
		echo '<span class="posted-on">' . $posted_on . '</span><span class="byline"> ' . $byline . '</span>'; // WPCS: XSS OK.
	}
endif;
/**
 * Display post meta for categories, tags and comments.
 */
if ( ! function_exists( 'syn_entry_footer' ) ) :
	function syn_entry_footer() {
		// Hide category and tag text for pages.
		if ( 'post' === get_post_type() ) {
			/* translators: used between list items, there is a space after the comma */
			$categories_list = get_the_category_list( esc_html__( ', ', 'syntric' ) );
			if ( $categories_list && syn_blog_categorized() ) {
				printf( '<span class="cat-links">' . esc_html__( 'Posted in %1$s', 'syntric' ) . '</span>', $categories_list ); // WPCS: XSS OK.
			}
			/* translators: used between list items, there is a space after the comma */
			$tags_list = get_the_tag_list( '', esc_html__( ', ', 'syntric' ) );
			if ( $tags_list ) {
				printf( '<span class="tags-links">' . esc_html__( 'Tagged %1$s', 'syntric' ) . '</span>', $tags_list ); // WPCS: XSS OK.
			}
		}
		if ( ! is_single() && ! post_password_required() && ( comments_open() || get_comments_number() ) ) {
			echo '<span class="comments-link">';
			/* translators: %s: post title */
			comments_popup_link( sprintf( wp_kses( __( 'Leave a Comment<span class="screen-reader-text"> on %s</span>', 'syntric' ), array( 'span' => array( 'class' => array() ) ) ), get_the_title() ) );
			echo '</span>';
		}
		edit_post_link(
			sprintf(
			/* translators: %s: Name of current post */
				esc_html__( 'Edit %s', 'syntric' ),
				the_title( '<span class="screen-reader-text">"', '"</span>', false )
			),
			'<span class="edit-link">',
			'</span>'
		);
	}
endif;
//
// Category related functions *********************************************
/**
 * Does blog have more than one category
 *
 * @return bool
 */
function syn_blog_categorized() {
	if ( false === ( $syn_transient_categories = get_transient( 'syn_transient_categories' ) ) ) {
		// Create an array of all the categories that are attached to posts.
		$syn_transient_categories = get_categories(
			array(
				'fields'     => 'ids',
				'hide_empty' => 1,
				// We only need to know if there is more than one category.
				'number'     => 2,
			)
		);
		// Count the number of categories that are attached to the posts.
		$syn_transient_categories = count( $syn_transient_categories );
		set_transient( 'syn_transient_categories', $syn_transient_categories );
	}
	if ( $syn_transient_categories > 1 ) {
		// This blog has more than 1 category so syn_blog_categorized should return true.
		return true;
	} else {
		// This blog has only 1 category so syn_blog_categorized should return false.
		return false;
	}
}

/**
 * Flush transients used in syn_blog_categorized
 */
add_action( 'edit_category', 'syn_flush_transient_categories' );
add_action( 'save_post', 'syn_flush_transient_categories' );
function syn_flush_transient_categories() {
	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
		return;
	}
	// Like, beat it. Dig?
	delete_transient( 'syn_transient_categories' );
}