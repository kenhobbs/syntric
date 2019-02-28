<?php
	
	if( is_plugin_active( 'advanced-custom-fields-pro/acf.php' ) ) :
		syntric_sidebar( 'footer' );
		syntric_footer();
	endif;
	wp_footer();
	echo '</body></html>';