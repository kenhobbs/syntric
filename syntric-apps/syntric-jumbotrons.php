<?php
	add_filter( 'acf/update_value/name=jumbotron_id', 'syn_update_id' );
	add_filter( 'acf/prepare_field/name=jumbotron_id', 'syn_prepare_jumbotron_fields' );
	function syn_prepare_jumbotron_fields( $field ) {
		if ( is_admin() ) {
			if ( 'jumbotron_id' == $field[ '_name' ] ) {
				$field[ 'wrapper' ][ 'hidden' ] = 1;
			}
		}

		return $field;
	}