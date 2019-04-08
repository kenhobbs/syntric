<?php
	
	if( comments_open() ) {
		;
		echo '<div class="comments-wrapper d-print-none">';
		echo '<div class="container-fluid">';
		echo '<div class="row">';
		echo '<div class="col">';
		echo '<h1>Submit Comment</h1>';
		if( is_user_logged_in() ) :
			echo '<p>Submit a comment about this page using the form below.</p>';
		else :
			echo '<p><a href="' . wp_login_url( get_the_permalink() ) . '">Login</a> to submit comments.</p>';
		endif;
		echo '</div>';
		echo '</div>';
		if( is_user_logged_in() ) :
			echo '<div class="row">';
			echo '<div class="col-lg-4">';
			comment_form();
			echo '</div>';
			echo '<div class="col-lg-8">';
			echo '<h2>Comments</h2>';
			if( have_comments() ) :
				echo '<ul class="comments-list">';
				wp_list_comments();
				echo '</ul>';
			else :
				echo '<p>No comments.</p>';
			endif;
			echo '</div>';
			echo '</div>';
		endif;
		echo '</div>';
		echo '</div>';
	}