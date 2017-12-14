<?php
add_filter( 'acf/update_value/name=google_map_id', 'syn_update_id' );
/**
 * Load the Google Map field in the Google Map Widget config form with registered maps
 */
add_filter( 'acf/load_field/name=syn_google_map_widget_map', 'syn_load_google_maps' );
add_filter( 'acf/load_field/key=field_59b118daf73d0', 'syn_load_organizations' );
add_filter( 'acf/prepare_field/name=google_map_id', 'syn_prepare_google_map_fields' );
add_filter( 'acf/prepare_field/name=default_organization', 'syn_prepare_google_map_fields' );
function syn_prepare_google_map_fields( $field ) {
	if ( is_admin() ) {
		if ( 'google_map_id' == $field[ '_name' ] ) {
			$field[ 'wrapper' ][ 'hidden' ] = 1;
		}
		if ( 'default_organization' == $field[ '_name' ] ) {
			$field[ 'label' ]   = '';
			$field[ 'message' ] = 'Use ' . get_field( 'syn_organization', 'option' );
		}

		return $field;
	}
}

/**
 * Enqueue Google Maps API and local scripts
 */
//add_action( 'wp_enqueue_scripts', 'syn_google_maps_enqueue_scripts' );
function syn_google_maps_enqueue_scripts() {
	$google_api_key = get_field( 'syn_google_api_key', 'option' );
	if ( have_rows( 'syn_google_maps', 'option' ) && $google_api_key ) {
		wp_enqueue_script( 'syntric-google-maps', get_template_directory_uri() . '/syntric-apps/assets/js/syntric-google-maps.js', array( 'jquery'), null, true );
		wp_enqueue_script( 'google-maps', '//maps.google.com/maps/api/js?key=' . $google_api_key . '&libraries=places,geometry', array('syntric-google-maps'), null, true );
	}
}

/**
 * Sets up the rendering of a Google Map by writing inline javascript
 *
 * @param $google_map_id
 * @param $container
 */
function syn_get_google_map( $google_map_id, $container ) {
	if ( have_rows( 'syn_google_maps', 'option' ) ) {
		while ( have_rows( 'syn_google_maps', 'option' ) ) : the_row();
			if ( $google_map_id == get_sub_field( 'google_map_id' ) ) {
				$google_map_config                           = array();
				$google_map_config[ 'container' ]            = $container;
				$google_map_config[ 'google_map_id' ]        = $google_map_id;
				$google_map_config[ 'name' ]                 = get_sub_field( 'name' );
				$google_map_config[ 'markers' ]              = get_sub_field( 'markers' );
				$google_map_config[ 'include_markers' ]      = ( get_sub_field( 'include_markers' ) && $google_map_config[ 'markers' ] ) ? 1 : 0;
				$google_map_config[ 'center_lat' ]           = get_sub_field( 'center_lat' );
				$google_map_config[ 'center_lng' ]           = get_sub_field( 'center_lng' );
				$google_map_config[ 'zoom_level' ]           = get_sub_field( 'zoom_level' );
				$google_map_config[ 'include_styles' ]       = ( get_sub_field( 'include_styles' ) ) ? 1 : 0;
				$styles                                      = get_sub_field( 'styles' );
				$styles                                      = preg_replace( '/[\t]+/', '', $styles );
				$google_map_config[ 'styles' ]               = preg_replace( '/[\r\n]+/', '', $styles );
				$google_map_config[ 'include_boundary' ]     = ( get_sub_field( 'include_boundary' ) ) ? 1 : 0;
				$google_map_config[ 'boundary_coordinates' ] = get_sub_field( 'boundary_coordinates' );
				$google_map_config[ 'map_type_id' ]          = 'roadmap';
				$google_map_config[ 'map_type_control' ]     = 1;
				$google_map_config[ 'zoom_control' ]         = 1;
				$google_map_config[ 'street_view_control' ]  = 0;
				$google_map_config_json                      = json_encode( $google_map_config, JSON_NUMERIC_CHECK );
				/*if ( $google_map_config[ 'include_markers'] ) {
					$_markers = array();
					foreach ( $google_map_config[ 'markers' ] as $key => $val ) {
					}
				}*/
				?>
				<script type="text/javascript">
					(function ($) {
						renderMap(<?php echo $google_map_config_json; ?>);
					})(jQuery);
				</script>
				<?php
				break;
			}
		endwhile;
	}
}