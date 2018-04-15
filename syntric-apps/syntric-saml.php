<?php
	/**
	 * Created by PhpStorm.
	 * User: Ken
	 * Date: 3/15/2018
	 * Time: 11:56 AM
	 */
	add_filter( 'wp_saml_auth_option', function( $value, $option_name ) {
		// Use the OneLogin bundled library to connect to Google Apps
		if ( 'connection_type' === $option_name ) {
			return 'internal';
		}
		// Configuration details OneLogin uses to connect to Google Apps
		if ( 'internal_config' === $option_name ) {
			// ID for the service provider (e.g. your WordPress site)
			$value[ 'sp' ][ 'entityId' ] = 'urn:wp-saml-auth';
			// URL that Google Apps will redirect back to after authenticating.
			$value[ 'sp' ][ 'assertionConsumerService' ][ 'url' ] = 'https://wp-saml-auth.dev';
			// ID provided for the Google Apps account.
			// 'abc123' will be something specific to your account.
			$value[ 'idp' ][ 'entityId' ] = 'https://accounts.google.com/o/saml2?idpid=abc123';
			// URL that WordPress will redirect to for authentication.
			// 'abc123' will be a unique value specific to your account.
			$value[ 'idp' ][ 'singleSignOnService' ][ 'url' ] = 'https://accounts.google.com/o/saml2/idp?idpid=abc123';
			// x509 certificate provided by Google Apps
			// Make sure to keep the file_get_contents because the entire certificate needs to be read into memory.
			$value[ 'idp' ][ 'x509cert' ] = file_get_contents( ABSPATH . '/private/GoogleIDPCertificate-wp-saml-auth.dev.pem' );

			return $value;
		}

		return $value;
	} );