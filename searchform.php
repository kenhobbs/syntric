<?php
/**
 * Search form template
 *
 * This is returned whenever get_search_form() is called
 *
 * @package syntric
 */
?>
<div class="search-wrapper d-print-none">
	<form method="get" id="searchform" class="form-inline search-form" action="<?php echo esc_url( home_url( '/' ) ); ?>" role="search">
		<label class="assistive-text sr-only sr-only-focusable" for="s"><?php esc_html_e( 'Search', 'syntric' ); ?></label>
		<div class="input-group">
			<input id="s" name="s" type="text" class="form-control search-input" placeholder="Search" required="required"/>
			<span class="input-group-btn">
				<button id="searchsubmit" name="submit" type="submit" class="btn search-button" aria-label="Submit search">
					<i class="fa fa-search"></i></button>
			</span>
		</div>
	</form>
</div>
