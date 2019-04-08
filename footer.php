<?php
	
	syntric_sidebar( 'footer-sidebar-1' );
	syntric_sidebar( 'footer-sidebar-2' );
	syntric_sidebar( 'footer-sidebar-3' );
	syntric_sidebar( 'sub-footer-sidebar' );
	wp_footer();
	echo '</body>';
	echo '</html>';
	
	
	
	//syntric_footer();
	/*$login_logout_url = (is_user_logged_in()) ? esc_url( wp_logout_url( get_the_permalink() ) ) :  esc_url( wp_login_url( get_the_permalink() ) );
	echo '<footer class="foot">';
	echo  '<div class="container-fluid">';
	echo   '<div class="row">';
	echo    '<div class="non-discrimination col">' . get_bloginfo( 'name' ) . ' does not discriminate on the basis of race, color, national origin, age, religion, political affiliation, gender, mental or physical disability, sexual orientation, parental or marital status, or any other basis protected by federal, state, or local law, ordinance or regulation, in its educational program(s) or employment.</div>';
	echo   '</div>';
	echo   '<div class="row">';
	echo    '<div class="col">';
	echo     '<div class="copyright">&copy; ' . date( 'Y' ) . ' ' . get_bloginfo( 'name' ) . '</div>';
	echo    '</div>';
	echo    '<div class="col">';
	echo     '<div id="google-translate" class="google-translate"></div>';
	echo    '</div>';
	echo    '<div class="col">';
	echo     '<div class="login-bug">';
	echo      '<a href="' . $login_logout_url . '" class="btn btn-sm btn-danger login-button">Logout</a>';
	echo      '<a href="http://www.syntric.com" target="_blank" class="bug">';
	echo       '<img src="' . esc_url( get_template_directory_uri() ) . '/assets/images/syntric-logo-bug.png" alt="Syntric logo">';
	echo      '</a>';
	echo     '</div>';
	echo    '</div>';
	echo   '</div>';
	echo  '</div>';
	echo '</footer>';
	if( syntric_is_staging() && comments_open() && is_user_logged_in() ) {
		comments_template();
	}*/
	