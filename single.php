<?php get_header(); ?>
	<div id="single-wrapper" class="content-wrapper <?php echo get_post_type(); ?>-wrapper">
		<div class="<?php echo esc_html( get_theme_mod( 'syntric_container_type' ) ); ?>">
			<div class="row">
				<?php syn_sidebar( 'main', 'left' ); ?>
				<main id="content" class="col content-area content">
					<h1 class="page-title" role="heading">
					<?php
						echo get_the_title();
						if( 'syn_calendar' == get_post_type() ) {
							echo '<span class="badge badge-pill badge-secondary">Calendar</span>';
						}
						if( 'syn_event' == get_post_type() ) {
							$calendar = get_the_title( get_field( 'syn_event_calendar_id', get_the_ID() ) );
							echo '<span class="badge badge-pill badge-secondary">' . $calendar . '</span>';
						}
						if( 'post' == get_post_type() ) {
							echo '<span class="badge badge-pill badge-secondary">' . syn_get_taxonomies_terms() . '</span>';
						};
					?>
					</h1>
					<?php
						syn_sidebar( 'main', 'top' );
						if( have_posts() ) {
							while( have_posts() ) : the_post();
								get_template_part( 'loop-templates/content', 'single' );
							endwhile;
						} else {
							get_template_part( 'loop-templates/content', 'none' );
						}
						syn_sidebar( 'main', 'bottom' );
					?>
				</main>
				<?php syn_sidebar( 'main', 'right' ); ?>
			</div>
		</div>
	</div>
<?php get_footer(); ?>