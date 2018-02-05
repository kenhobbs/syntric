<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	<meta name="mobile-web-app-capable" content="yes">
	<meta name="apple-mobile-web-app-capable" content="yes">
	<meta name="apple-mobile-web-app-title" content="<?php bloginfo( 'name' ); ?> - <?php bloginfo( 'description' ); ?>">
	<link rel="profile" href="http://gmpg.org/xfn/11">
	<?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php
	if ( is_plugin_active( 'advanced-custom-fields-pro/acf.php' ) ) :
		if ( 'school_district' == syn_get_organization_type() ) {
			syn_head();
		}
		syn_primary_nav();
		get_search_form();
		syn_banner();
		syn_breadcrumbs();
		syn_sidebar( 'header' );
	else :
		echo '<p>Wordpress is not configured to run Syntric Framework</p>';
	endif;

