<?php get_header();
	if ( syn_remove_whitespace() ) {
		$lb  = '';
		$tab = '';
	} else {
		$lb  = "\n";
		$tab = "\t";
	}
	echo '<div id="page-wrapper" class="content-wrapper ' . get_post_type() . '-wrapper">' . $lb;
	echo '<div class="' . esc_html( get_theme_mod( 'syntric_container_type' ) ) . '">' . $lb;
	echo '<div class="row">' . $lb;
	syn_sidebar( 'main', 'left' );
	echo '<main id="content" class="col content-area content">' . $lb;
	echo '<h1 class="page-title" role="heading">' . get_the_title() . '</h1>' . $lb;
	syn_sidebar( 'main', 'top' );
	if ( have_posts() ) :
		while( have_posts() ) : the_post();
			if ( syn_has_content( the_content() ) ) :
				echo '<article ' . post_class() . ' id="post-' . the_ID() . '">' . $lb;
				the_content();
				echo '</article>' . $lb;
			endif;
		endwhile;
	endif;
	syn_sidebar( 'main', 'bottom' );
	echo '</main>' . $lb;
	syn_sidebar( 'main', 'right' );
	echo '</div>' . $lb;
	echo '</div>' . $lb;
	echo '</div>' . $lb;
	get_footer(); ?>
<!--<div id="page-wrapper" class="content-wrapper <?php /*echo get_post_type(); */ ?>-wrapper">
	<div class="<?php /*echo esc_html( get_theme_mod( 'syntric_container_type' ) ); */ ?>">
		<div class="row">
			<?php /*syn_sidebar( 'main', 'left' ); */ ?>
			<main id="content" class="col content-area content">
				<h1 class="page-title" role="heading">
					<?php /*echo get_the_title(); */ ?>
				</h1>
				<?php /*syn_sidebar( 'main', 'top' ); */ ?>
				<?php /*if ( have_posts() ) : */ ?>
					<?php /*while ( have_posts() ) : the_post(); */ ?>
						<?php /*if ( syn_has_content( the_content() ) ) : */ ?>
							<article <?php /*post_class(); */ ?> id="post-<?php /*the_ID(); */ ?>">
								<?php /*the_content(); */ ?>
							</article>
						<?php /*endif; */ ?>
					<?php /*endwhile; */ ?>
				<?php /*endif; */ ?>
				<?php /*syn_sidebar( 'main', 'bottom' ); */ ?>
			</main>
			<?php /*syn_sidebar( 'main', 'right' ); */ ?>
		</div>
	</div>
</div>-->
