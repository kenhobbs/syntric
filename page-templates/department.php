<?php
	/**
	 * Template Name: Department
	 * Template Post Type: page
	 * Template for displaying a department page (e.g. Social Studies, Human Resources, Academics (elementary) etc.)
	 *
	 * @package syntric
	 */
?>
<?php get_header(); ?>
<div id="department-wrapper" class="content-wrapper <?php echo get_post_type(); ?>-wrapper">
	<div class="<?php echo esc_html( get_theme_mod( 'syntric_container_type' ) ); ?>">
		<div class="row">
			<?php syn_sidebar( 'main', 'left' ); ?>
			<main id="content" class="col content-area content">
				<h1 class="page-title"><?php the_title(); ?></h1>
				<?php
					syn_sidebar( 'main', 'top' );
					//syn_columns( 2, 8 );
					if( have_posts() ) {
						while( have_posts() ) : the_post();
							get_template_part( 'loop-templates/content-department' );
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
