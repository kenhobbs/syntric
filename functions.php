<?php
	global $pagenow;
	//echo $pagenow;
	//if ( ! is_admin() && 'wp-login.php' !== $pagenow ) {
		// The function is_plugin_active (in plugin.php) is loaded after functions.php.  So in order to use is_plugin_active we need to include plugin.php.
		include_once( ABSPATH . 'wp-admin/includes/plugin.php' );
		if ( is_plugin_active( 'advanced-custom-fields-pro/acf.php' ) ) {
			// If ACF is loaded, include all files in /inc and /syntric-apps/syntric-apps.php
			//require get_template_directory() . '/syntric-apps/syntric-apps.php';
			require get_template_directory() . '/inc/admin.php';
			require get_template_directory() . '/inc/comments.php';
			require get_template_directory() . '/inc/customizer.php';
			require get_template_directory() . '/inc/editor.php';
			require get_template_directory() . '/inc/enqueue.php';
			require get_template_directory() . '/inc/extras.php';
			require get_template_directory() . '/inc/pagination.php';
			require get_template_directory() . '/inc/search.php';
			require get_template_directory() . '/inc/security.php';
			require get_template_directory() . '/inc/setup.php';
			require get_template_directory() . '/inc/templates.php';
			require get_template_directory() . '/inc/utility.php';
			require get_template_directory() . '/syntric-apps/syntric-apps.php';
			//require get_template_directory() . '/syntric-apps/syntric-frontenberg.php';
		//} else {
			// If ACF isn't loaded, present a "not configured" screen
			//echo '<div style="text-align: center">';
			//echo '<img src="' . get_template_directory_uri() . '/screenshot.png" alt="Syntric Framework & Theme for Districts & Schools">';
			//echo '<p>Syntric Theme + Framework is not configured</p>';
			//echo '</div>';
		}
	//}



