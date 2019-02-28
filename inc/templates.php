<?php
	
	function syntric_header() {
		$lb  = syntric_linebreak();
		$tab = syntric_tab();
		// todo: need a page/post/calendar/etc-specific cap check based on role and author
		/*if ( is_user_logged_in() && syntric_current_user_can_edit() ) {
			syntric_editor();
		}*/
		echo '<div id="fb-root"></div>';
		echo '<div class="print-header print-header-name d-print-block" aria-hidden="true">' . get_bloginfo( 'name', 'display' ) . '</div>' . $lb;
		echo '<a class="sr-only sr-only-focusable skip-to-content-link" href="#content">' . esc_html( 'Skip to content', 'syntric' ) . '</a>' . $lb;
		/*if ( 'school_district' == syntric_get_organization_type() && ( 'www.amadorcoe.org' == $_SERVER[ 'HTTP_HOST' ] || 'amadorcoe.org' == $_SERVER[ 'HTTP_HOST' ] || 'www.acusd.org' == $_SERVER[ 'HTTP_HOST' ] || 'acusd.org' == $_SERVER[ 'HTTP_HOST' ] ) ) {
			echo '<header class="head">' . $lb;
			echo $tab . '<div class="container-fluid">' . $lb;
			echo $tab . $tab . '<div class="row">' . $lb;
			echo $tab . $tab . $tab . '<div class="col">' . $lb;
			echo $tab . $tab . $tab . $tab . '<nav class="portal-links" aria-label="Portal links">' . $lb;
			echo $tab . $tab . $tab . $tab . $tab . '<a href="https://amadorcusd.asp.aeries.net/teacher/Login.aspx?page=default.aspx" class="teacher-portal" target="_blank">Teacher Portal <span class="fa fa-sign-in"></span></a>' . $lb;
			echo $tab . $tab . $tab . $tab . $tab . '<a href="https://amadorcusd.asp.aeries.net/student/LoginParent.aspx?page=default.aspx" class="parent-portal" target="_blank">Parent Portal <span class="fa fa-sign-in"></span></a>' . $lb;
			echo $tab . $tab . $tab . $tab . '</nav>' . $lb;
			echo $tab . $tab . $tab . '</div>' . $lb;
			echo $tab . $tab . '</div>' . $lb;
			echo $tab . '</div>' . $lb;
			echo '</header>' . $lb;
		}*/
		syntric_primary_nav();
		get_search_form();
		syntric_banner();
		syntric_breadcrumbs();
		//syntric_sidebar( 'header' );
	}
	
	function syntric_banner() {
		global $post;
		// todo: come back and fix this...kinda clunky
		if( has_header_image() ) {
			$header_image           = get_header_image();
			$banner_style_attribute = ' style="background-image: url(' . $header_image . ');" ';
		} else {
			$banner_style_attribute = ' style="min-height: 0;" ';
		}
		$jumbotrons = get_field( 'syn_jumbotrons', 'option' );
		if( has_header_image() || $jumbotrons ) {
			$lb  = syntric_linebreak();
			$tab = syntric_tab();
			echo '<div class="banner-wrapper" aria-hidden="true"' . $banner_style_attribute . 'role="banner">' . $lb;
			if( 'syntric' == strtolower( syntric_current_theme() ) ) {
				echo syntric_jumbotron();
			}
			echo '</div>' . $lb;
		}
	}
	
	function syntric_jumbotron() {
		global $post;
		if( ! $post ) {
			return;
		}
		$jumbotrons = get_field( 'syn_jumbotrons', 'option' );
		if( $jumbotrons ) {
			$jumbotron = false;
			foreach( $jumbotrons as $_jumbotron ) {
				$filters        = $_jumbotron[ 'filters' ];
				$start_datetime = $_jumbotron[ 'start_datetime' ];
				$end_datetime   = $_jumbotron[ 'end_datetime' ];
				$pass_filters   = ( 0 < count( $filters ) ) ? syntric_process_filters( $filters, $post ) : true;
				$pass_schedule  = ( $start_datetime || $end_datetime ) ? syntric_process_schedule( $start_datetime, $end_datetime ) : true;
				if( $pass_filters && $pass_schedule ) {
					$jumbotron = $_jumbotron;
					break;
				}
			}
			if( $jumbotron ) {
				$lb  = syntric_linebreak();
				$tab = syntric_tab();
				echo '<div class="jumbotron-wrapper">' . $lb;
				echo $tab . '<h1 class="jumbotron-headline">' . $jumbotron[ 'headline' ] . '</h1>' . $lb;
				echo $tab . '<div class="jumbotron-caption">' . $jumbotron[ 'caption' ] . '</div>' . $lb;
				if( $jumbotron[ 'include_button' ] ) {
					$button_href   = ( 'page' == $jumbotron[ 'button_target' ] ) ? $jumbotron[ 'button_page' ] : $jumbotron[ 'button_url' ];
					$window_target = ( 'page' == $jumbotron[ 'button_target' ] ) ? '_self' : '_blank';
					echo $tab . '<a href="' . $button_href . '" class="btn btn-lg btn-primary jumbotron-button" target="' . $window_target . '">' . $jumbotron[ 'button_text' ] . '</a>' . $lb;
				}
				echo '</div>' . $lb;
			}
		}
	}
	
	/**
	 * Template related functions - post formats, page templates and custom template tags
	 */
	//
	// Page templates *********************************************************
	/**
	 * Filter the theme page templates.
	 *
	 * @param array    $page_templates Page templates.
	 * @param WP_Theme $this           WP_Theme instance.
	 * @param WP_Post  $post           The post being edited, provided for context, or null.
	 *
	 * @return array (Maybe) modified page templates array.
	 */
	add_filter( 'theme_page_templates', 'syntric_filter_theme_page_templates', 20, 3 );
	function syntric_filter_theme_page_templates( $page_templates, $theme, $post ) {
		$organization_is_school = syntric_organization_is_school();
		// filter page templates according to organization type
		if( ! $organization_is_school ) {
			unset( $page_templates[ 'page-templates/department.php' ] );
			unset( $page_templates[ 'page-templates/teachers.php' ] );
			unset( $page_templates[ 'page-templates/teacher.php' ] );
			unset( $page_templates[ 'page-templates/course.php' ] );
			unset( $page_templates[ 'page-templates/class.php' ] );
			unset( $page_templates[ 'page-templates/bell-schedule.php' ] );
			unset( $page_templates[ 'page-templates/schedules.php' ] );
		}
		
		return $page_templates;
	}
	
	//
	// Excerpt related functions **********************************************
	add_filter( 'excerpt_more', 'syntric_custom_excerpt_more', 99 );
	if( ! function_exists( 'syntric_custom_excerpt_more' ) ) {
		/**
		 * Removes the ... from the excerpt read more link
		 *
		 * @param string $more The excerpt.
		 *
		 * @return string
		 */
		function syntric_custom_excerpt_more( $more ) {
			//return '<a href="' . esc_url( get_the_permalink( get_the_ID() ) ) . '" class="read-more-link">Read More</a>';
			//return ' <span class="read-more-link">Read More</span>';
			return ' ...';
		}
	}
	/**
	 * Filter the except length
	 *
	 * @param int $length Excerpt length.
	 *
	 * @return int (Maybe) modified excerpt length.
	 */
	add_filter( 'excerpt_length', 'syntric_excerpt_length', 99 );
	if( ! function_exists( 'syntric_excerpt_length' ) ) {
		function syntric_excerpt_length() {
			return 20;
		}
	}
	//
	// Display functions ******************************************************
	/**
	 * Display post "posted on" and "author" info
	 */
	if( ! function_exists( 'syntric_posted_on' ) ) :
		function syntric_posted_on() {
			$time_string = '<time class="post-date published updated" datetime="%1$s">%2$s</time>';
			if( get_the_time( 'U' ) !== get_the_modified_time( 'U' ) ) {
				$time_string = '<time class="post-date published" datetime="%1$s">%2$s</time><time class="updated" datetime="%3$s">%4$s</time>';
			}
			$time_string = sprintf( $time_string, esc_attr( get_the_date( 'c' ) ), esc_html( get_the_date() ), esc_attr( get_the_modified_date( 'c' ) ), esc_html( get_the_modified_date() ) );
			$posted_on   = sprintf( esc_html_x( 'Posted on %s', 'post date', 'syntric' ), '<a href="' . esc_url( get_the_permalink() ) . '" rel="bookmark">' . $time_string . '</a>' );
			$byline      = sprintf( esc_html_x( 'by %s', 'post author', 'syntric' ), '<span class="author vcard"><a class="url fn n" href="' . esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ) . '">' . esc_html( get_the_author() ) . '</a></span>' );
			echo '<span class="posted-on">' . $posted_on . '</span><span class="byline"> ' . $byline . '</span>'; // WPCS: XSS OK.
		}
	endif;
	/**
	 * Display post meta for categories, tags and comments.
	 */
	if( ! function_exists( 'syntric_entry_footer' ) ) :
		function syntric_entry_footer() {
			// Hide category and tag text for pages.
			if( 'post' === get_post_type() ) {
				/* translators: used between list items, there is a space after the comma */
				$categories_list = get_the_category_list( esc_html__( ', ', 'syntric' ) );
				if( $categories_list && syntric_blog_categorized() ) {
					printf( '<span class="cat-links">' . esc_html__( 'Posted in %1$s', 'syntric' ) . '</span>', $categories_list ); // WPCS: XSS OK.
				}
				/* translators: used between list items, there is a space after the comma */
				$tags_list = get_the_tag_list( '', esc_html__( ', ', 'syntric' ) );
				if( $tags_list ) {
					printf( '<span class="tags-links">' . esc_html__( 'Tagged %1$s', 'syntric' ) . '</span>', $tags_list ); // WPCS: XSS OK.
				}
			}
			if( ! is_single() && ! post_password_required() && ( comments_open() || get_comments_number() ) ) {
				echo '<span class="comments-link">';
				/* translators: %s: post title */
				comments_popup_link( sprintf( wp_kses( __( 'Leave a Comment<span class="sr-only"> on %s</span>', 'syntric' ), [ 'span' => [ 'class' => [] ] ] ), get_the_title() ) );
				echo '</span>';
			}
			edit_post_link( sprintf( /* translators: %s: Name of current post */
				esc_html__( 'Edit %s', 'syntric' ), the_title( '<span class="sr-only">"', '"</span>', false ) ), '<span class="edit-link">', '</span>' );
		}
	endif;
	//
	// Category related functions *********************************************
	/**
	 * Does blog have more than one category
	 *
	 * @return bool
	 */
	function syntric_blog_categorized() {
		if( false === ( $syntric_transient_categories = get_transient( 'syntric_transient_categories' ) ) ) {
			// Create an array of all the categories that are attached to posts.
			$syntric_transient_categories = get_categories( [ 'fields' => 'ids', 'hide_empty' => 1, // We only need to know if there is more than one category.
			                                                  'number' => 2, ] );
			// Count the number of categories that are attached to the posts.
			$syntric_transient_categories = count( $syntric_transient_categories );
			set_transient( 'syntric_transient_categories', $syntric_transient_categories );
		}
		if( $syntric_transient_categories > 1 ) {
			// This blog has more than 1 category so syntric_blog_categorized should return true.
			return true;
		} else {
			// This blog has only 1 category so syntric_blog_categorized should return false.
			return false;
		}
	}
	
	/**
	 * Flush transients used in syntric_blog_categorized
	 */
	add_action( 'edit_category', 'syntric_flush_transient_categories' );
	add_action( 'save_post', 'syntric_flush_transient_categories' );
	function syntric_flush_transient_categories() {
		if( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
			return;
		}
		// Like, beat it. Dig?
		delete_transient( 'syntric_transient_categories' );
	}
	
	//add_filter( 'gallery_style', 'syntric_gallery_style' );
	function syntric_gallery_style( $gallery_style ) {
		return $gallery_style;
	}
	
	//add_filter( 'use_default_gallery_style', 'syntric_use_default_gallery_style', 10 );
	function syntric_use_default_gallery_style( $print ) {
		return $print;
	}
	
	// fires when get_post_gallery() is called
	//add_filter( 'get_post_gallery', 'syntric_get_post_gallery', 10, 3 );
	function syntric_get_post_gallery( $gallery, $post, $galleries ) {
		return $gallery;
	}
	
	// Alter what is returned by the gallery shortcode
	//add_filter( 'post_gallery', 'syntric_post_gallery', 10, 3 );
	function syntric_post_gallery( $output, $atts, $instance ) {
		$img_ids = ( ! is_array( $atts[ 'ids' ] ) ) ? explode( ',', $atts[ 'ids' ] ) : $atts[ 'ids' ];
		//$size    = ( isset( $atts[ 'size' ] ) ) ? $atts[ 'size' ] : syntric_guess_gallery_image_size( $atts );
		$size   = syntric_guess_gallery_image_size( $atts );
		$cols   = ( isset( $atts[ 'columns' ] ) ) ? $atts[ 'columns' ] : 4;
		$output = '<div class="gallery columns-' . $atts[ 'columns' ] . '">';
		foreach( $img_ids as $img_id ) {
			$caption = wp_get_attachment_caption( $img_id );
			$output  .= '<a class="column gallery-item">';
			$output  .= wp_get_attachment_image( $img_id, $size, false, [ 'class' => 'gallery-image img-fluid' ] );
			if( ! empty( $caption ) ) {
				$output .= '<div class="gallery-caption">' . $caption . '</div>';
			}
			$output .= '</a>';
		}
		$output .= '</div>';
		
		return $output;
	}
	
	function syntric_guess_gallery_image_size( $atts ) {
		switch( $atts[ 'columns' ] ) {
			case 1:
				return 'large';
				break;
			case 2:
				return 'large';
				break;
			case 3:
				return 'medium_large';
				break;
			case 4:
				return 'medium_large';
				break;
			case 5:
				return 'medium';
				break;
			case 6:
				return 'medium';
				break;
			case 7:
				return 'thumbnail';
				break;
			case 8:
				return 'thumbnail';
				break;
			case 9:
				return 'icon';
				break;
		}
	}