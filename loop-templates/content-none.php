<?php
/**
 * The template part for displaying a message that posts cannot be found.
 *
 * Learn more: http://codex.wordpress.org/Template_Hierarchy
 *
 * @package syntric
 */
?>
<article class="no-results not-found">
	<div class="post-content clearfix">
		<p>
			<?php
			if ( is_search() ) {
				esc_html_e( 'No results for this search', 'syntric' );
			} else {
				esc_html_e( 'No content was found', 'syntric' );
			}
			?>
		</p>
	</div>
</article>
