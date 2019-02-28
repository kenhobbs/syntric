<?php get_header(); ?>
	<div id="404-wrapper" class="content-wrapper <?php echo get_post_type(); ?>-wrapper">
		<div class="<?php echo esc_html( get_theme_mod( 'syntric_container_type' ) ); ?>">
			<div class="row">
				<?php syntric_sidebar( 'main', 'left' ); ?>
				<main id="content" class="col content-area content">
					<h1 class="page-title" role="heading">Page Not Found</h1>
					<?php syntric_sidebar( 'main', 'top' ); ?>
					<?php get_template_part( 'loop-templates/content-none' ); ?>
					<?php syntric_sidebar( 'main', 'bottom' ); ?>
				</main>
				<?php syntric_sidebar( 'main', 'right' ); ?>
			</div>
		</div>
	</div>
<?php get_footer(); ?>