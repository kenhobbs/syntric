<?php
	// todo: This needs to be turned into script
	
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
	 */ ?><?php get_header(); ?>
	<div id="author-wrapper" class="content-wrapper <?php echo get_post_type(); ?>-wrapper">
		<div class="<?php echo esc_html( get_theme_mod( 'syntric_container_type' ) ); ?>">
			<div class="row">
				<?php syntric_sidebar( 'main', 'left' ); ?>
				<main id="content" class="col content-area content">
					<h1 class="page-title" role="heading">
						<?php the_author(); ?>
					</h1>
					<?php syntric_sidebar( 'main', 'top' ); ?>
					<pre>
						<?php
							
							if( get_queried_object() instanceof WP_User ) {
								$author = get_queried_object();
							}
							$author_comments = get_comments( [ 'author__in' => $author -> ID, ] );
						?>
					</pre>
					<?php /*if ( have_posts() ) : */ ?><!--
						<?php /*while( have_posts() ) : the_post(); */ ?>
							<?php /*if ( syntric_has_content( the_content() ) ) : */ ?>
								<article <?php /*post_class(); */ ?> id="post-<?php /*the_ID(); */ ?>">
									<?php /*the_content(); */ ?>
								</article>
							<?php /*endif; */ ?>
						<?php /*endwhile; */ ?>
					--><?php /*endif; */ ?>
					<?php syntric_sidebar( 'main', 'bottom' ); ?>
				</main>
				<?php syntric_sidebar( 'main', 'right' ); ?>
			</div>
		</div>
	</div>
<?php get_footer(); ?>