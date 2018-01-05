<?php get_header(); ?>
<div id="index-wrapper" class="content-wrapper <?php echo get_post_type(); ?>-wrapper">
	<div class="<?php echo esc_html( get_theme_mod( 'syntric_container_type' ) ); ?>">
		<div class="row">
			<main id="content" class="col content-area content">
				<h1 class="page-title" role="heading"><?php the_title(); ?></h1>
				<?php get_template_part( 'loop-templates/content', 'none' ); ?>
			</main>
		</div>
	</div>
</div>
<?php get_footer(); ?>
