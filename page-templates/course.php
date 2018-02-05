<?php
/**
 * Template Name: Course
 * Template Post Type: page
 * Template for displaying a course page (e.g. Algebra 1, AP English, etc, etc.)
 *
 * @package syntric
 */
//$course_id = get_field( 'syn_page_course' );
//$course = syn_get_course( $course_id );
get_header(); ?>
	<div id="course-wrapper" class="content-wrapper <?php echo get_post_type(); ?>-wrapper">
		<div class="<?php echo esc_html( get_theme_mod( 'syntric_container_type' ) ); ?>">
			<div class="row">
				<?php syn_sidebar( 'main', 'left' ); ?>
				<main id="content" class="col content-area content">
					<h1 class="page-title" role="heading">
						<?php the_title(); ?>
					</h1>
					<?php
					syn_sidebar( 'main', 'top' );
					if ( have_posts() ) {
						while ( have_posts() ) : the_post();
							get_template_part( 'loop-templates/content-course' );
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