<?php
/**
 * Template Name: Teachers
 * Template Post Type: page
 * Template for displaying teachers parent (top ancestor of teacher pages)
 *
 * @package syntric
 */
?><?php get_header(); ?>
	<div id="teachers-wrapper" class="content-wrapper <?php echo get_post_type(); ?>-wrapper">
		<div class="<?php echo esc_html( get_theme_mod( 'syntric_container_type' ) ); ?>">
			<div class="row">
				<?php syn_sidebar( 'main', 'left' ); ?>
				<main id="content" class="col content-area content">
					<h1 class="page-title"><?php the_title(); ?></h1>
					<?php
					syn_sidebar( 'main', 'top' );
					if ( have_posts() ) {
						while ( have_posts() ) : the_post();
							get_template_part( 'loop-templates/content-teachers' );
						endwhile;
					} else {
						get_template_part( 'loop-templates/content-none' );
					}
					syn_sidebar( 'main', 'bottom' );
					?>
				</main>
				<?php syn_sidebar( 'main', 'right' ); ?>
			</div>
		</div>
	</div>
<?php get_footer(); ?>