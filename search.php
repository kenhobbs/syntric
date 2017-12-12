<?php get_header(); ?>
<div id="search-wrapper" class="content-wrapper">
	<div class="<?php echo esc_html( get_theme_mod( 'syntric_container_type' ) ); ?>">
		<div class="row">
			<main id="content" class="col content-area">
				<header class="page-header">
					<h1 class="page-title">
						Search Results
						<span class="badge badge-pill"><?php echo $wp_query->found_posts . ' results'; ?></span>
						<!-- found for "' . get_search_query() . '"'-->
					</h1>
				</header>
				<?php
				if ( have_posts() ) {
					while ( have_posts() ) : the_post();
						get_template_part( 'loop-templates/content', 'excerpt' );
					endwhile;
				} else {
					get_template_part( 'loop-templates/content', 'none' );
				}
				syn_pagination();
				?>
			</main>
		</div>
	</div>
</div>
<?php get_footer(); ?>
