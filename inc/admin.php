<?php
/**
 * User profile contact methods mods
 */
add_filter( 'user_contactmethods', 'syn_remove_user_profile_fields' );
function syn_remove_user_profile_fields( $fields ) {
	unset( $fields[ 'aim' ] );
	unset( $fields[ 'jabber' ] );
	unset( $fields[ 'yim' ] );

	return $fields;
}

