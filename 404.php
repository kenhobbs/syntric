<?php get_header(); ?>
	<div id="404-wrapper" class="content-type-wrapper <?php echo get_post_type(); ?>-content-wrapper">
		<div class="<?php echo esc_html( get_theme_mod( 'syntric_container_type' ) ); ?>">
			<div class="row">
				<?php syn_get_sidebars( 'main', 'left' ); ?>
				<main id="content" class="col content-area content">
					<header class="page-header">
						<h1 class="page-title">
							Page Not Found </h1>
					</header>
					<?php syn_get_sidebars( 'main', 'top' ); ?>
					<?php get_template_part( 'loop-templates/content-none' ); ?>
					<?php syn_get_sidebars( 'main', 'bottom' ); ?>
				</main>
				<?php syn_get_sidebars( 'main', 'right' ); ?>
			</div>
		</div>
	</div>
<?php get_footer(); ?>