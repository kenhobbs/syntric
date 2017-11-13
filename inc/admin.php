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

add_action( 'wp_login', 'syn_wp_login' );
function syn_wp_login( $user_login, $user ) {
	if ( $user instanceof WP_User ) {
		$args = array(
			'ID'          => $user->ID,
			'admin_color' => 'light',
		);
		wp_update_user( $args );
	}
}

add_filter( 'screen_options_show_screen', 'syn_screen_options_show_screen' );
function syn_screen_options_show_screen() {
	return current_user_can( 'administrator' );
}

add_action( 'admin_head', 'syn_remove_admin_help_tab' );
function syn_remove_admin_help_tab() {
	$screen = get_current_screen();
	$screen->remove_help_tabs();
}