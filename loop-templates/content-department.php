<?php if ( ! empty( the_content() ) ) : ?>
	<article <?php post_class(); ?> id="post-<?php the_ID(); ?>">
		<div class="post-content">
			<?php the_content(); ?>
		</div>
	</article>
<?php endif; ?><?php syn_display_department_courses(); ?>