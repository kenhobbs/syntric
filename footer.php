<?php
	if ( is_plugin_active( 'advanced-custom-fields-pro/acf.php' ) ) :
		syn_sidebar( 'footer' );
		syn_footer();
	endif;
	wp_footer();
	echo '</body></html>';