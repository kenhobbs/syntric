<?php get_header(); ?>
	<div id="page-wrapper" class="content-wrapper <?php echo get_post_type(); ?>-wrapper">
		<div class="<?php echo esc_html( get_theme_mod( 'syntric_container_type' ) ); ?>">
			<div class="row">
				<?php syn_sidebar( 'main', 'left' ); ?>
				<main id="content" class="col content-area content">
					<h1 class="page-title" role="heading">
						<?php echo get_the_title(); ?>
					</h1>
					<?php syn_sidebar( 'main', 'top' ); ?>
					<?php if ( have_posts() ) : ?>
						<?php while ( have_posts() ) : the_post(); ?>
							<?php if ( syn_has_content( the_content() ) ) : ?>
								<article <?php post_class(); ?> id="post-<?php the_ID(); ?>">
									<?php the_content(); ?>
								</article>
							<?php endif; ?>
						<?php endwhile; ?>
					<?php endif; ?>
					<?php syn_sidebar( 'main', 'bottom' ); ?>
				</main>
				<?php syn_sidebar( 'main', 'right' ); ?>
			</div>
		</div>
	</div>
<?php get_footer(); ?>