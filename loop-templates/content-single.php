<?php if ( 'syn_calendar' == get_post_type() ) : ?>
	<!--<a href="" class="sr-only sr-only-focusable">Screen reader friendly version</a>-->
	<?php //syn_render_accessible_calendar( get_the_ID() ); ?>
	<div id="full-calendar">
		<?php the_title(); ?> is loading...
	</div>
	<script type="text/javascript">
		(function ($) {
			fetchCalendarEvents(<?php echo get_the_ID(); ?>);
		})(jQuery);
	</script>
<?php elseif ( 'syn_event' == get_post_type() ) : ?><?php $dates = syn_get_event_dates( get_the_ID() ); //todo: simplify this to a single function call; ?><?php $location = get_field( 'syn_event_location', get_the_ID() ); ?>
	<article <?php post_class(); ?> id="post-<?php the_ID(); ?>">
		<header class="post-header">
			<span class="post-date"><?php echo $dates; ?></span>
			<?php if ( ! empty( $location ) ) : ?>
				<span class="post-location"><?php echo $location; ?></span>
			<?php endif; ?>
		</header>
		<div class="post-content">
			<?php the_content(); ?>
		</div>
	</article>
<?php else : ?>
	<article <?php post_class(); ?> id="post-<?php the_ID(); ?>">
		<header class="post-header">
			<!--<span class="post-category"><?php /*echo syn_get_taxonomies_terms(); */ ?></span>-->
			<span class="post-date"><?php echo get_the_date(); ?></span>
		</header>
		<div class="post-content">
			<?php the_content(); ?>
		</div>
	</article>
<?php endif; ?>