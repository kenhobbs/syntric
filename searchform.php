<?php
	/**
	 * Search form template
	 *
	 * This is returned whenever get_search_form() is called
	 *
	 * @package syntric
	 */
	$lb = syn_get_linebreak();
	$tab = syn_get_tab();
	echo '<div class="search-wrapper d-print-none">' . $lb;
	echo $tab . '<form method="get" id="searchform" class="form-inline search-form" action="' . esc_url( home_url( '/' ) ) . '" role="search">' . $lb;
	echo $tab . $tab . '<label class="assistive-text sr-only sr-only-focusable" for="s">Search</label>' . $lb;
	echo $tab . $tab . '<div class="input-group">' . $lb;
	echo $tab . $tab . $tab . '<input id="s" name="s" type="text" minlength="3" class="form-control search-input" placeholder="Search" required="required"/>' . $lb;
	echo $tab . $tab . $tab . '<span class="input-group-btn">' . $lb;
	echo $tab . $tab . $tab . $tab . '<button id="searchsubmit" name="submit" type="submit" class="btn search-button" aria-label="Submit search">' . $lb;
	echo $tab . $tab . $tab . $tab . '<i class="fa fa-search"></i>' . $lb;
	echo $tab . $tab . $tab . $tab . '</button>' . $lb;
	echo $tab . $tab . $tab . '</span>' . $lb;
	echo $tab . $tab . '</div>' . $lb;
	echo $tab . '</form>' . $lb;
	echo '</div>' . $lb;

