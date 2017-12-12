<article <?php post_class(); ?> id="post-<?php the_ID(); ?>">
	<div class="post-content">
		<?php
		syn_get_sidebars( 'main', 'top' );
		the_content();
		syn_get_sidebars( 'main', 'bottom' );
		?>
	</div>
</article>