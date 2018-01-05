<?php
	/**
	 * Organization
	 *
	 * Notes
	 * 1. phone and fax are formatted by the generic filter (eg acf/update_value/name=phone) in syn-apps.php
	 * 2. id is populated by generic filter (eg acf/update_value/name=id) in syn-app.php
	 * 3. Only one organization should be set to "primary"
	 */
	add_filter( 'acf/update_value/name=organization_id', 'syn_update_id' );
	add_filter( 'acf/update_value/name=syn_organization_id', 'syn_update_id' );
// Format fields
	add_filter( 'acf/update_value/name=syn_organization_phone', 'syn_update_phone', 20 );
	add_filter( 'acf/update_value/name=syn_organization_fax', 'syn_update_phone', 20 );
// Prepare fields
	add_filter( 'acf/prepare_field/name=organization_id', 'syn_prepare_organization_fields' );
	add_filter( 'acf/prepare_field/name=syn_organization_id', 'syn_prepare_organization_fields' );
	function syn_prepare_organization_fields( $field ) {
		if( is_admin() ) {
			if( 'organization_id' == $field[ '_name' ] ) {
				$field[ 'wrapper' ][ 'hidden' ] = 1;
			}
			if( 'syn_organization_id' == $field[ '_name' ] ) {
				$field[ 'wrapper' ][ 'hidden' ] = 1;
			}
		}

		return $field;
	}