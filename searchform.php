<?php
	
	get_header();
	echo '<div class="search-wrapper d-print-none">';
	echo '<form method="get" id="searchform" class="form-inline search-form" action="' . esc_url( home_url( '/' ) ) . '" role="search">';
	echo '<label class="assistive-text sr-only sr-only-focusable" for="s">Search site by keywords</label>';
	echo '<div class="input-group">';
	echo '<input id="s" name="s" type="text" minlength="3" class="form-control search-input" required="required"/>';
	echo '<span class="input-group-btn">';
	echo '<button id="searchsubmit" name="submit" type="submit" class="btn search-button" aria-label="Submit search">';
	echo '<i class="fa fa-search"></i>';
	echo '</button>';
	echo '</span>';
	echo '</div>';
	echo '</form>';
	echo '</div>';