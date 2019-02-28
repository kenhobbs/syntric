<?php if( comments_open() ) :
	if( ( isset( $args -> item_spacing ) && 'discard' === $args -> item_spacing ) || syntric_remove_whitespace() ) {
		$tab = '';
		$lb  = '';
	} else {
		$tab = "\t";
		$lb  = "\n";
	}
	echo '<div class="comments-wrapper d-print-none">';
	echo $tab . '<div class="container-fluid">';
	//echo $tab . $tab . '<div class="row">';
	//echo $tab . $tab . $tab . '<div class="col text-center">END OF PAGE</div>';
	//echo $tab . $tab . $tab . '</div>';
	echo $tab . $tab . '<div class="row">';
	echo $tab . $tab . $tab . '<div class="col">';
	echo $tab . $tab . $tab . $tab . '<h1>Submit Comment</h1>';
	if( is_user_logged_in() ) :
		echo $tab . $tab . $tab . $tab . '<p>Submit a comment about this page using the form below.</p>';
	else :
		echo $tab . $tab . $tab . $tab . '<p><a href="' . wp_login_url( get_the_permalink() ) . '">Login</a> to submit comments.</p>';
	endif;
	echo $tab . $tab . $tab . '</div>';
	echo $tab . $tab . '</div>';
	if( is_user_logged_in() ) :
		echo $tab . $tab . '<div class="row">';
		echo $tab . $tab . $tab . '<div class="col-lg-4">';
		comment_form();
		echo $tab . $tab . $tab . '</div>';
		echo $tab . $tab . $tab . '<div class="col-lg-8">';
		echo $tab . $tab . $tab . $tab . '<h2>Comments</h2>';
		if( have_comments() ) :
			echo $tab . $tab . $tab . $tab . '<ul class="comments-list">';
			wp_list_comments();
			echo $tab . $tab . $tab . $tab . '</ul>';
		else :
			echo $tab . $tab . $tab . $tab . '<p>No comments.</p>';
		endif;
		echo $tab . $tab . $tab . '</div>';
		echo $tab . $tab . '</div>';
	endif;
	echo $tab . '</div>';
	echo '</div>';
endif;