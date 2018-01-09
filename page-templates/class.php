<?php
/**
 * Template Name: Class
 * Template Post Type: page
 * Template for displaying a class page (e.g. 3rd Period Algebra 1, Mr. Smiths's 3rd Grade Class, etc.)
 *
 * @package syntric
 */
$teacher_id     = get_field( 'syn_page_class_teacher' );
$teacher        = syn_get_teacher( $teacher_id );
$periods_active = get_field( 'syn_periods_active', 'option' );
$rooms_active   = get_field( 'syn_rooms_active', 'option' );
$class_id       = get_field( 'syn_page_class' );
$class          = syn_get_teacher_class( $teacher_id, $class_id );
get_header(); ?>
	<div id="class-wrapper" class="content-wrapper <?php echo get_post_type(); ?>-wrapper">
		<div class="<?php echo esc_html( get_theme_mod( 'syntric_container_type' ) ); ?>">
			<div class="row">
				<?php syn_sidebar( 'main', 'left' ); ?>
				<main id="content" class="col content-area content">
					<h1 class="page-title" role="heading">
						<?php the_title(); ?>
						<?php
						echo '<span class="badge badge-pill badge-secondary">' . $class[ 'term' ] . '</span>';
						if ( $teacher ) {
							echo '<span class="badge badge-pill badge-secondary">' . $teacher->display_name . '</span>';
						}
						if ( $periods_active && ! empty( $class[ 'period' ] ) ) {
							echo '<span class="badge badge-pill badge-secondary">Period ' . $class[ 'period' ] . '</span>';
						}
						if ( $rooms_active && ! empty( $class[ 'room' ] ) ) {
							echo '<span class="badge badge-pill badge-secondary">Room ' . $class[ 'room' ] . '</span>';
						}
						?>
					</h1>
					<?php
					syn_sidebar( 'main', 'top' );
					if ( have_posts() ) {
						while ( have_posts() ) : the_post();
							get_template_part( 'loop-templates/content-class' );
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