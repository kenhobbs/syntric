<?php
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
				<?php syn_sidebar( 'main', 'left' ); ?>
				<main id="content" class="col content-area content">
					<h1 class="page-title" role="heading">
						<?php the_author(); ?>
					</h1>
					<?php syn_sidebar( 'main', 'top' ); ?>
					<pre>
						<?php
							//slog($post);
							if ( get_queried_object() instanceof WP_User ) {
								slog( get_queried_object() );
								$author = get_queried_object();
							}
							//$author_name = isset($_GET['author_name']) ? $_GET['author_name'] : '';
							//slog($_GET['author_name']);
							//$curauth = ( ! empty($author_name) ) ? get_user_by('slug', $author_name) : '';
							//slog($curauth);
							$author_comments = get_comments( [ 'author__in' => $author->ID, ] );
							slog( $author_comments );
						?>
					</pre>
					<?php /*if ( have_posts() ) : */ ?><!--
						<?php /*while( have_posts() ) : the_post(); */ ?>
							<?php /*if ( syn_has_content( the_content() ) ) : */ ?>
								<article <?php /*post_class(); */ ?> id="post-<?php /*the_ID(); */ ?>">
									<?php /*the_content(); */ ?>
								</article>
							<?php /*endif; */ ?>
						<?php /*endwhile; */ ?>
					--><?php /*endif; */ ?>
					<?php syn_sidebar( 'main', 'bottom' ); ?>
				</main>
				<?php syn_sidebar( 'main', 'right' ); ?>
			</div>
		</div>
	</div>
<?php get_footer(); ?>