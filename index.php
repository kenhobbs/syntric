<?php get_header(); ?>
<div id="index-wrapper" class="content-type-wrapper <?php echo get_post_type(); ?>-content-wrapper">
	<div class="<?php echo esc_html( get_theme_mod( 'syntric_container_type' ) ); ?>">
		<div class="row">
			<main id="content" class="col content-area content">
				<header class="page-header">
					<h1 class="page-title"><?php the_title(); ?></h1>
				</header>
				<?php get_template_part( 'loop-templates/content', 'none' ); ?>
			</main>
		</div>
	</div>
</div>
<?php get_footer(); ?>
