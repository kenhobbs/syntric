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
	<div id="fb-root"></div>
	<div class="print-header print-header-name d-print-block" aria-hidden="true"><?php echo get_bloginfo( 'name', 'display' ); ?></div>
	<a class="sr-only sr-only-focusable d-print-none" href="#content"><?php esc_html_e( 'Skip to content', 'syntric' ); ?></a>
	<nav class="primary-nav-wrapper navbar navbar-expand-lg d-print-none">
		<div class="navbar-brand-toggler-wrapper d-flex justify-content-between align-items-center">
			<div class="brand">
				<?php echo syn_get_navbar_brand(); ?>
			</div>
			<div class="toggler">
				<button class="navbar-toggler collapsed" type="button" data-toggle="collapse" data-target="#nav-collapse" aria-controls="nav-collapse" aria-expanded="false" aria-label="Toggle navigation">
					<i class="fa fa-bars" aria-hidden="true"></i>
				</button>
			</div>
		</div>
		<div id="nav-collapse" class="navbar-collapse collapse" aria-expanded="false">
			<?php syn_nav_menu(); ?>
		</div>
	</nav>
	<?php get_search_form(); ?>
	<?php syn_get_banner(); ?>
	<?php syn_get_sidebars( 'header' ); ?>
	<?php syn_get_breadcrumbs(); ?>
