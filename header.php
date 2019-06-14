<?php

;
echo '<!DOCTYPE html>';
echo '<html ' . get_language_attributes() . '>';
echo '<head>';
echo '<meta charset="' . get_bloginfo( 'charset' ) . '">';
echo '<meta http-equiv="X-UA-Compatible" content="IE=edge">';
echo '<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">';
echo '<meta name="mobile-web-app-capable" content="yes">';
echo '<meta name="apple-mobile-web-app-capable" content="yes">';
echo '<meta name="apple-mobile-web-app-title" content="' . get_bloginfo( 'name' ) . ' - ' . get_bloginfo( 'description' ) . '">';
echo '<link rel="profile" href="http://gmpg.org/xfn/11">';
wp_head();
echo '</head>';
echo '<body ' . implode( ' ', get_body_class() ) . '>';
echo '<div id="fb-root" aria-hidden="true"></div>';
echo '<div class="print-header print-header-name d-print-block" aria-hidden="true">' . get_bloginfo( 'name', 'display' ) . '</div>';
echo '<a class="sr-only sr-only-focusable skip-to-content-link" href="#content">' . esc_html( 'Skip to content', 'syntric' ) . '</a>';
/**
 * Super-header sidebar
 */
syntric_sidebar( 'super-header-sidebar' );
/**
 * Primary navbar
 */
syntric_primary_nav();
/**
 * Search form
 */
get_search_form();
/**
 * Banner + Jumbotron
 */
syntric_banner();
/**
 * Breadcrumb navigation
 */
syntric_breadcrumbs();
syntric_sidebar( 'header-sidebar-1' );
syntric_sidebar( 'header-sidebar-2' );
syntric_sidebar( 'header-sidebar-3' );

