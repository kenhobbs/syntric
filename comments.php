<?php if ( comments_open() ) :
	if ( ( isset( $args->item_spacing ) && 'discard' === $args->item_spacing ) || syn_remove_whitespace() ) {
		$tab = '';
		$lb = '';
	} else {
		$tab = "\t";
		$lb = "\n";
	}
	echo '<div class="comments-wrapper d-print-none">';
	echo $tab . '<div class="container-fluid">';
	echo $tab . $tab . '<div class="row">';
	echo $tab . $tab . $tab . '<div class="col text-center">END OF PAGE</div>';
	echo $tab . $tab . $tab . '</div>';
	echo $tab . $tab . '<div class="row">';
	echo $tab . $tab . $tab . '<div class="col">';
	echo $tab . $tab . $tab . $tab . '<h1>Site Review</h1>';
	if ( is_user_logged_in() ) :
		echo $tab . $tab . $tab . $tab . '<p>Submit feedback using the form below. Please include full copy for any text changes. To send files such as PDFs or images, mention it in your feedback and you will receive an email with instructions to send files.</p>';
	else :
		echo $tab . $tab . $tab . $tab . '<p>You must <a href="' . wp_login_url( get_the_permalink() ) . '">login</a> to submit site review feeback</p>';
	endif;
	echo $tab . $tab . $tab . '</div>';
	echo $tab . $tab . '</div>';
	if ( is_user_logged_in() ) :
		echo $tab . $tab . '<div class="row">';
		echo $tab . $tab . $tab . '<div class="col-lg-4">';
		comment_form();
		echo $tab . $tab . $tab . '</div>';
		echo $tab . $tab . $tab . '<div class="col-lg-8">';
			echo $tab . $tab . $tab . $tab . '<h2>Feedback & Replies</h2>';
			if ( have_comments() ) :
				echo $tab . $tab . $tab . $tab . '<ul class="comments-list">';
				wp_list_comments();
				echo $tab . $tab . $tab . $tab . '</ul>';
			else :
				echo $tab . $tab . $tab . $tab . '<p>No feedback has been submitted for this page.</p>';
			endif;
			echo $tab . $tab . $tab . '</div>';
		echo $tab . $tab . '</div>';
	endif;
	echo $tab . '</div>';
echo '</div>';
endif; ?>