<?php
//
// todo: list
// remove class['page'] custom field and all references to it
// current user, available anywhere on this page
// Speed up admin load time by not loading WP Custom Fields
// https://www.advancedcustomfields.com/blog/acf-pro-5-5-13-update/

add_filter( 'acf/settings/remove_wp_meta_box', '__return_true' );

if( is_multisite() ) {
	//require get_template_directory() . '/syntric-apps/syntric-multisite.php';
}

require get_template_directory() . '/syntric-apps/syntric-user.php';
require get_template_directory() . '/syntric-apps/syntric-calendar.php';
require get_template_directory() . '/syntric-apps/syntric-event.php';
require get_template_directory() . '/syntric-apps/syntric-blocks-widgets-sidebars.php';
require get_template_directory() . '/syntric-apps/syntric-media.php';
require get_template_directory() . '/syntric-apps/syntric-nav-menus.php';
require get_template_directory() . '/syntric-apps/syntric-departments.php';
require get_template_directory() . '/syntric-apps/syntric-courses.php';
require get_template_directory() . '/syntric-apps/syntric-classes.php';
require get_template_directory() . '/syntric-apps/syntric-data-functions.php';
// todo: check this out...commented out late and didn't regression test
/*if ( ! syntric_current_user_is( 'teacher' ) ) {
	add_filter( 'show_admin_bar', '__return_false' );
}*/
/**
 * Register option pages using ACF
 */
if( function_exists( 'acf_add_options_page' ) ) {
	//$current_user            = wp_get_current_user();
	//$current_user_cfs        = get_field( 'syntric_user', 'user_' . $current_user -> ID );
	//$current_user_is_teacher = $current_user_cfs[ 'is_teacher' ];
	//if( ! $current_user_is_teacher ) {
	$settings          = get_field( 'syntric_settings', 'options' );
	$organization_type = $settings[ 'organization_type' ];
	switch( $organization_type ) :
		case 'elementary_school' :
		case 'middle_school' :
		case 'jr_high_school' :
		case 'high_school' :
		case 'alt_school' :
		case 'adult_school' :
		case 'community_school' :
		case 'primary_school' :
		case 'secondary_school' :
		case 'intermediate_school' :
		case 'charter_school' :
		case 'online_school' :
		case 'home_school' :
			// School
			acf_add_options_page( [ 'page_title' => 'School',
			                        'menu_title' => 'School',
			                        'menu_slug'  => 'syntric-organization',
			                        'capability' => 'manage_options',
			                        'redirect'   => false, ] );
			// Schedules
			acf_add_options_sub_page( [ 'page_title'  => 'Schedules',
			                            'menu_title'  => 'Schedules',
			                            'menu_slug'   => 'syntric-schedules',
			                            'parent_slug' => 'syntric-organization',
			                            'capability'  => 'manage_options', ] );
			// Buildings
			acf_add_options_sub_page( [ 'page_title'  => 'Buildings',
			                            'menu_title'  => 'Buildings',
			                            'menu_slug'   => 'syntric-buildings',
			                            'parent_slug' => 'syntric-organization',
			                            'capability'  => 'manage_options', ] );
			// Rooms
			acf_add_options_sub_page( [ 'page_title'  => 'Rooms',
			                            'menu_title'  => 'Rooms',
			                            'menu_slug'   => 'syntric-rooms',
			                            'parent_slug' => 'syntric-organization',
			                            'capability'  => 'manage_options', ] );
			// Departments
			acf_add_options_sub_page( [ 'page_title'  => 'Departments',
			                            'menu_title'  => 'Departments',
			                            'menu_slug'   => 'syntric-departments',
			                            'parent_slug' => 'syntric-organization',
			                            'capability'  => 'manage_options', ] );
			// Schedules
			acf_add_options_sub_page( [ 'page_title'  => 'Schedules',
			                            'menu_title'  => 'Schedules',
			                            'menu_slug'   => 'syntric-schedules',
			                            'parent_slug' => 'syntric-organization',
			                            'capability'  => 'manage_options', ] );
			/*// Periods
			acf_add_options_sub_page( [ 'page_title'  => 'Periods',
										'menu_title'  => 'Periods',
										'menu_slug'   => 'syntric-periods',
										'parent_slug' => 'syntric-school',
										'capability'  => 'manage_options', ] );*/
			// Courses
			acf_add_options_sub_page( [ 'page_title'  => 'Courses',
			                            'menu_title'  => 'Courses',
			                            'menu_slug'   => 'syntric-courses',
			                            'parent_slug' => 'syntric-organization',
			                            'capability'  => 'manage_options', ] );
			// Classes
			acf_add_options_sub_page( [ 'page_title'  => 'Classes',
			                            'menu_title'  => 'Classes',
			                            'menu_slug'   => 'syntric-classes',
			                            'parent_slug' => 'syntric-organization',
			                            'capability'  => 'manage_options', ] );
		break;
		case 'district' :
			// District
			acf_add_options_page( [ 'page_title' => 'District',
			                        'menu_title' => 'District',
			                        'menu_slug'  => 'syntric-organization',
			                        'capability' => 'manage_options',
			                        'redirect'   => false, ] );
			// Districts
			acf_add_options_page( [ 'page_title'  => 'Schools',
			                        'menu_title'  => 'Schools',
			                        'menu_slug'   => 'syntric-schools',
			                        'parent_slug' => 'syntric-organization',
			                        'capability'  => 'manage_options', ] );
		break;
		case 'coe' :
			// COE
			acf_add_options_page( [ 'page_title' => 'COE',
			                        'menu_title' => 'COE',
			                        'menu_slug'  => 'syntric-organization',
			                        'capability' => 'manage_options',
			                        'redirect'   => false, ] );
			// Districts
			acf_add_options_page( [ 'page_title'  => 'Districts',
			                        'menu_title'  => 'Districts',
			                        'menu_slug'   => 'syntric-districts',
			                        'parent_slug' => 'syntric-organization',
			                        'capability'  => 'manage_options', ] );
		break;
	endswitch;

	// Organizations
	acf_add_options_page( [ 'page_title'  => 'Organizations',
	                        'menu_title'  => 'Organizations',
	                        'menu_slug'   => 'syntric-organizations',
	                        'parent_slug' => 'syntric-organization',
	                        'capability'  => 'manage_options' ] );
	// Jumbotron
	acf_add_options_page( [ 'page_title' => 'Jumbotrons',
	                        'menu_title' => 'Jumbotrons',
	                        'menu_slug'  => 'syntric-jumbotrons',
	                        'capability' => 'manage_options',
	                        'redirect'   => false, ] );
	// Tools > Data Functions
	acf_add_options_sub_page( [ 'page_title'  => 'Data Functions',
	                            'menu_title'  => 'Data Functions',
	                            'menu_slug'   => 'syntric-data-functions',
	                            'parent_slug' => 'tools.php',
	                            'capability'  => 'manage_options', ] );
	// Tools > Field Group UIs
	acf_add_options_sub_page( [ 'page_title'  => 'Field Groups',
	                            'menu_title'  => 'Field Groups',
	                            'menu_slug'   => 'syntric-field-group-uis',
	                            'parent_slug' => 'tools.php',
	                            'capability'  => 'manage_options', ] );
	// Syntric Settings
	acf_add_options_sub_page( [ 'page_title'  => 'Syntric Settings',
	                            'menu_title'  => 'Syntric Settings',
	                            'menu_slug'   => 'syntric-settings',
	                            'parent_slug' => 'options-general.php',
	                            'capability'  => 'manage_options', ] );
	//} else {
	// My Classes
	/*acf_add_options_page( [ 'page_title' => 'My Classes',
	                        'menu_title' => 'My Classes',
	                        'menu_slug'  => 'syntric-my-classes',
	                        'capability' => 'edit_pages', ] );*/
	// My Pages
	/*acf_add_options_page( [ 'page_title' => 'My Pages',
	                        'menu_title' => 'My Pages',
	                        'menu_slug'  => 'syntric-my-pages',
	                        'capability' => 'edit_pages', ] );*/
	//}
}

/**
 * Remove 3rd party plugin hooks
 */
add_action( 'wp_head', 'syntric_remove_plugin_hooks' );
function syntric_remove_plugin_hooks() {
	global $tinymce_templates;
	if( $tinymce_templates ) {
		remove_filter( 'post_row_actions', [ $tinymce_templates, 'row_actions' ], 10, 2 );
		remove_filter( 'page_row_actions', [ $tinymce_templates, 'row_actions' ], 10, 2 );
		remove_action( 'wp_before_admin_bar_render', [ $tinymce_templates, 'wp_before_admin_bar_render', ] );
	}
}

/**
 * Loads after a successful login and runs setup routines and validates data
 *
 * 1. Reset all admin meta boxes
 */
add_action( 'wp_login', 'syntric_login', 10, 2 );
function syntric_login( $user_login, $user ) {
	syntric_reset_user_meta_boxes( $user -> ID );
}

/**
 * Sets the number of columns in admin screens
 */
add_filter( 'screen_layout_columns', 'syntric_screen_layout_columns' );
function syntric_screen_layout_columns( $columns ) {
	$columns[ 'dashboard' ] = 2;

	return $columns;
}

/**
 * Allows the customization of the dashboard
 */
add_filter( 'get_user_option_screen_layout_dashboard', 'syntric_layout_dashboard' );
function syntric_layout_dashboard() {
	return 1;
}

/**
 * Show/hide meta boxes
 */
add_filter( 'default_hidden_meta_boxes', 'syntric_hidden_meta_boxes', 10, 2 );
function syntric_hidden_meta_boxes( $hidden, $screen ) {
	if( is_admin() ) {
		// hide from everyone...
		$hidden[] = 'postexcerpt'; // Post Excerpts
		$hidden[] = 'trackbacksdiv'; // Send Trackbacks
		$hidden[] = 'postcustom'; // Custom Fields
		$hidden[] = 'commentstatusdiv'; // Discussion
		$hidden[] = 'commentsdiv'; // Comments - Add comment
		$hidden[] = 'slugdiv'; // Slug
		$hidden[] = 'authordiv'; // Author
		//$hidden[] = 'postimagediv'; // Featured Image
		$hidden[] = 'formatdiv'; // Post Format (for themes that use Post Format)
		$hidden[] = 'categorydiv'; // Categories
		$hidden[] = 'microblogdiv'; // Microblogs
		$hidden[] = 'revisionsdiv'; // Revisions
		$hidden[] = 'tagsdiv-microblog'; // Tags
		// hide from everyone but administrator and editor
		if( ! syntric_current_user_can( 'administrator' ) && ! syntric_current_user_can( 'editor' ) ) {
			$hidden[] = 'pageparentdiv'; // Page Attributes
			$hidden[] = 'acf-group_59c5d19611678'; // Class page settings
			$hidden[] = 'acf-group_59c5d0f68dd2f'; // Teacher page settings
			//$hidden[] = 'submitdiv'; // Publish
		}
	}

	return $hidden;
}

add_filter( 'logout_redirect', 'syntric_logout_redirect', 20, 3 );
function syntric_logout_redirect( $redirect_to, $request, $user ) {
	return home_url();
}

// After footer (after all scripts are loaded)
/**
 * Print inline javascript to trigger asyncronous API calls last.
 * todo: change this so timing of calls are optimized for optimized page rendering
 */
add_action( 'wp_print_footer_scripts', 'syntric_print_after_footer', 99 );
function syntric_print_after_footer() {
	global $post;
	// todo: rather than just looking for maps, look to see if there is an active map widget...or put call in widget itself.
	// todo: convert this inline javascript to <script async src... to speed rendering
	if( 'syntric_calendar' == get_post_type() ) :
		echo '<script type="text/javascript">';
		echo '(function ($) {';
		echo 'fetchCalendar(' . $post -> ID . ');';
		echo '})(jQuery);';
		echo '</script>';
	endif;
}

function syntric_get_google_api_key() {
	$settings        = get_field( 'syntric_settings', 'options' );
	$google_settings = $settings[ 'google' ];
	$api_key         = ( isset( $google_settings[ 'api_key' ] ) && ! empty( $google_settings[ 'api_key' ] ) ) ? $google_settings[ 'api_key' ] : 0;

	return $api_key;
}

add_filter( 'acf/fields/google_map/api', 'syntric_google_map_api' );
function syntric_google_map_api( $api ) {
	$syntric_settings = get_field( 'syntric_settings', 'options' );
	$api[ 'key' ]     = $syntric_settings[ 'google' ][ 'api_key' ];

	return $api;
}

/*********************  Academics section ******************************/
/**
 * Get the Academics page.
 *
 * @return bool|mixed|\WP_Post
 *
 * todo: fix to set menu_order more intelligently by looking at the organization type and neighboring menu items
 */
function syntric_get_academics_page( $force_create = 1 ) {
	$academics_pages = get_posts( [
		'numberposts' => - 1,
		'title'       => 'Academics',
		'post_type'   => 'page',
		'post_status' => [ 'publish', 'draft', 'future', 'pending', 'private', ],
		'post_parent' => 0,
	] );
	$args            = [
		'post_title'     => 'Academics',
		'post_name'      => syntric_sluggify( 'Academics' ),
		'post_status'    => 'publish',
		'post_type'      => 'page',
		'comment_status' => 'closed',
		'menu_order'     => 4,
		'post_parent'    => 0,
	];
	if( 0 == count( $academics_pages ) ) {
		if( ! $force_create ) {
			return false;
		}
	}
	if( 1 < count( $academics_pages ) ) {
		$academics_page = array_shift( $academics_pages ); // get oldest department page
		foreach( $academics_pages as $page ) {
			syntric_delete_department_page( $page -> ID );
		}
		$args[ 'ID' ] = $academics_page -> ID;
	}
	if( 1 == count( $academics_pages ) ) {
		$academics_page = $academics_pages[ 0 ];
		$args[ 'ID' ]   = $academics_page -> ID;
	}
	$page_id = wp_insert_post( $args );

	return get_post( $page_id );
}

/**
 * Get the teachers page.
 *
 * @return array|null|\WP_Post
 */

function syntric_get_teachers_page( $force_create = 1 ) {
	$teachers_pages = get_posts( [
		'numberposts'  => - 1,
		'post_type'    => 'page',
		'post_status'  => [ 'publish', 'draft', 'future', 'pending', 'private', ],
		'meta_key'     => '_wp_page_template',
		'meta_value'   => 'teachers.php',
		'meta_compare' => '=',
		'orderby'      => 'post_date', // oldest page returned first
		'order'        => 'ASC',
	] );
	$academics_page = syntric_get_academics_page();
	$args           = [
		'post_title'     => 'Teachers',
		'post_name'      => syntric_sluggify( 'Teachers' ),
		'post_status'    => 'publish',
		'post_type'      => 'page',
		'comment_status' => 'closed',
		'menu_order'     => 0,
		'post_parent'    => $academics_page -> ID,
	];
	if( 0 == count( $teachers_pages ) ) {
		if( ! $force_create ) {
			return false;
		}
	}
	if( 1 < count( $teachers_pages ) ) {
		$teachers_page = array_shift( $teachers_pages );
		foreach( $teachers_pages as $page ) {
			syntric_delete_teachers_page( $page -> ID );
		}
		$args[ 'ID' ] = $teachers_page -> ID;
	}
	if( 1 == count( $teachers_pages ) ) {
		$teachers_page = $teachers_pages[ 0 ];
		$args[ 'ID' ]  = $teachers_page -> ID;
	}
	$page_id = wp_insert_post( $args );
	update_post_meta( $page_id, '_wp_page_template', 'teachers.php' );

	return get_post( $page_id );
	/*$academics_page = syntric_get_academics_page();
	$args           = [
		'post_status' => 'publish',
		'post_type'   => 'page',
		'post_title'  => 'Teachers',
		'menu_order'  => 0,
		'post_name'   => syntric_sluggify( 'Teachers' ),
		'post_parent' => $academics_page -> ID,
	];
	if( $teachers_page instanceof WP_Post ) {
		$args[ 'ID' ] = $teachers_page -> ID;
	}
	$teachers_page_id = wp_insert_post( $args );
	update_post_meta( $teachers_page_id, '_wp_page_template', 'teachers.php' );

	return get_post( $teachers_page_id );*/
}

/**
 * Filters & Schedules
 */

/**
 * Syntric filter is used to specify when a widget, sidebar or other object is activated and/or displayed.
 *
 * Filters include:
 * Post - a specific post
 * Post Type - page, post, calendar, event, etc.
 * Post Category - Announcements, Microblogs, News, Uncategorized, etc.
 * Page - a specific page
 * Page Template - Default, Teachers, Teacher, Department, Course, Class, Schedule, etc.
 *
 * @param array $filters Set of filter groups each with param, operator and value
 *
 * @return bool             True if filters pass, false if not
 */
function syntric_process_filters( $filter_groups = [] ) {
	global $post;
	if( is_array( $filter_groups ) && count( $filter_groups ) ) {
		$group_results = [];
		//slog('filter_groups');
		//slog($filter_groups);
		foreach( $filter_groups as $filter_group ) {
			//slog('filter_group');
			//slog($filter_group);
			if( isset( $filter_group[ 'filter_group' ] ) ) {
				$filter_group = $filter_group[ 'filter_group' ];
				foreach( $filter_group as $filter ) {
					//slog('filter');
					//slog( $filter );
					$parameter = $filter[ 'parameter' ];
					$operator  = $filter[ 'operator' ];
					switch( $parameter ) :
						case 'post':
							$value = $filter[ 'value' ][ 'post_value' ];
							if( ( 'is' == $operator && $post -> ID != $value ) || ( 'is_not' == $operator && $post -> ID == $value ) ) {
								$group_results[] = false;
								break;
							}
						break;
						case 'post_type':
							$value = $filter[ 'value' ][ 'post_type_value' ];
							if( ( 'is' == $operator && $post -> post_type != $value ) || ( 'is_not' == $operator && $post -> post_type == $value ) ) {
								$group_results[] = false;
								break;
							}
						break;
						case 'post_category':
							if( is_home() ) {
								$group_results[] = false;
								break;
							}
							$categories = get_the_category( $post -> ID );
							$value      = (int) $filter[ 'value' ][ 'post_category_value' ];
							if( ( 'is' == $operator && $categories[ 0 ] -> term_id != $value ) || ( 'is_not' == $operator && $categories[ 0 ] -> term_id == $value ) ) {
								$group_results[] = false;
								break;
							}
						break;
						case 'page':
							$value = $filter[ 'value' ][ 'page_value' ];
							if( ( 'is' == $operator && $post -> ID != $value ) || ( 'is_not' == $operator && $post -> ID == $value ) ) {
								$group_results[] = false;
								break;
							}
						break;
						case 'page_template':
							$page_template = get_post_meta( $post -> ID, '_wp_page_template', true );
							$value         = $filter[ 'value' ][ 'page_template_value' ];
							if( ( 'is' == $operator && $page_template != $value ) || ( 'is_not' == $operator && $page_template == $value ) ) {
								$group_results[] = false;
								break;
							}
						break;
					endswitch;
				}
			}
		}
		if( count( $group_results ) == count( $filter_groups ) ) {
			return false;
		}
	}

	return true;
}

/**
 * Process schedule (start date/time and end date/time)
 *
 * @param $start_datetime
 * @param $end_datetime
 *
 * @return bool
 */
function syntric_process_schedule( $start_datetime = '', $end_datetime = '' ) {
	$start_timestamp = ( ! empty( $start_datetime ) ) ? strtotime( $start_datetime ) : 1;
	$end_timestamp   = ( ! empty( $end_datetime ) ) ? strtotime( $end_datetime ) : 1;
	$now             = time();
	$start_diff      = ( $start_timestamp != 1 ) ? $now - $start_timestamp : $start_timestamp;
	$end_diff        = ( $end_timestamp != 1 ) ? $end_timestamp - $now : $end_timestamp;
	if( $start_diff < 0 || $end_diff < 0 ) {
		return false;
	}

	return true;
}

/*************************************** Etc **********************************************/
// todo: check if this can be removed and replaced with function that is doing this for departments, courses, classes, etc
function syntric_trash_descendant_pages( $post_id ) {
	$descendants = syntric_get_descendant_pages( $post_id );
	foreach( $descendants as $descendant ) {
		wp_delete_post( $descendant -> ID, false );
	}
}

function syntric_get_page_children( $post_id ) {
	$page = get_post( $post_id );
	if( $page -> post_type != 'page' ) {
		return;
	}
	$q         = new WP_Query();
	$all_pages = $q -> query( [ 'post_type' => 'page', 'post_status' => [ 'publish', 'draft', 'future', 'pending', 'private', ] ] );
	$children  = get_page_children( $post_id, $all_pages );

	return $children;
}

/**
 * @param      $pages array of IDs or WP_posts, ID or WP_Post
 * @param bool $force_delete
 *
 * @return bool
 */
function syntric_delete_pages( $pages, $force_delete = false ) {
	if( ! is_bool( $force_delete ) ) {
		$force_delete = false;
	}
	if( is_array( $pages ) ) {
		foreach( $pages as $page ) {
			if( is_numeric( $page ) ) {
				wp_delete_post( $page, $force_delete );
			}
			if( $page instanceof WP_Post ) {
				wp_delete_post( $page -> ID, $force_delete );
			}
		}

		return true;
	}
	if( is_numeric( $pages ) ) {
		wp_delete_post( $pages, $force_delete );

		return true;
	}
	if( $pages instanceof WP_Post ) {
		wp_delete_post( $pages -> ID, $force_delete );

		return true;
	}

	return false;
}

function syntric_get_organization_type() {
	$settings = get_field( 'syntric_settings', 'options' );

	return $settings[ 'organization_type' ];
}

function syntric_organization_is_school() {
	$organization_type = syntric_get_organization_type();
	$school_types      = [ 'elementary_school',
	                       'primary_school',
	                       'secondary_school',
	                       'adult_ed_school',
	                       'adult_education_school',
	                       'alterative_school',
	                       'school',
	                       'high_school',
	                       'middle_school',
	                       'jr_high_school',
	                       'community_school' ];
	if( in_array( $organization_type, $school_types ) ) {
		return true;
	}

	return false;
}

function syntric_sluggify( $string ) {
	if( 3 >= strlen( $string ) ) {
		$pw_string = syntric_generate_password( 8 );
		$string    = $string . $pw_string;
	}
	$slug = str_replace( ' ', '-', $string );
	$slug = str_replace( '/', '-', $slug );
	$slug = preg_replace( "/[^A-Za-z0-9\-]/", '', $slug );
	$slug = str_replace( '--', '-', $slug );
	$slug = str_replace( '---', '-', $slug );

	return strtolower( $slug );
}

function syntric_banner() {
	$banner_style = ( has_header_image() ) ? ' style="background-image: url(' . get_header_image() . ');" ' : ' style="min-height: 0;" ';

	echo '<div class="banner-wrapper" aria-hidden="true"' . $banner_style . 'role="banner">';
	syntric_jumbotron();
	echo '</div>';
}

/**
 * Outputs a Jumbtron if one is displayable
 */

function syntric_jumbotron() {
	$jumbotrons = get_field( 'field_5c7d477aa23d9', 'options' );
	if( $jumbotrons ) {
		foreach( $jumbotrons as $jumbotron ) {
			// schedule check
			$start_datetime = ( isset( $jumbotron[ 'start_datetime' ] ) ) ? $jumbotron[ 'start_datetime' ] : '';
			$end_datetime   = ( isset( $jumbotron[ 'end_datetime' ] ) ) ? $jumbotron[ 'end_datetime' ] : '';
			$pass_schedule  = syntric_process_schedule( $start_datetime, $end_datetime );
			if( ! $pass_schedule ) {
				continue;
			}
			// filter check
			$filter_groups = ( isset( $jumbotron[ 'syntric_filters' ] ) ) ? $jumbotron[ 'syntric_filters' ] : [];
			$pass_filters  = syntric_process_filters( $filter_groups );
			if( ! $pass_filters ) {
				continue;
			}
			echo '<div class="jumbotron-wrapper">';
			echo '<h1 class="jumbotron-headline">' . $jumbotron[ 'headline' ] . '</h1>';
			echo '<div class="jumbotron-caption">' . $jumbotron[ 'caption' ] . '</div>';
			if( $jumbotron[ 'include_button' ] ) {
				$button_href   = ( 'page' == $jumbotron[ 'button_target' ] ) ? $jumbotron[ 'button_page' ] : $jumbotron[ 'button_url' ];
				$window_target = ( 'page' == $jumbotron[ 'button_target' ] ) ? '_self' : '_blank';
				echo '<a href="' . $button_href . '" class="btn btn-lg btn-primary jumbotron-button" target="' . $window_target . '">' . $jumbotron[ 'button_text' ] . '</a>';
			}
			echo '</div>';
			break;
		}
	}
}

/**
 * Outputs a breadcrumb nav based on where one is in the site
 */
function syntric_breadcrumbs() {
	global $post;
	if( is_front_page() ) {
		return;
	}
	$breadcrumbs = '<div class="breadcrumb-wrapper">';
	$breadcrumbs .= '<div class="container-fluid">';
	$breadcrumbs .= '<nav class="breadcrumb">';
	$breadcrumbs .= '<a class="breadcrumb-item" href="' . home_url() . '">Home</a>';
	if( is_home() ) {
		$post_type_labels = get_post_type_labels( get_post_type_object( 'post' ) );
		$breadcrumbs      .= '<a class="breadcrumb-item active" href="' . esc_url( get_post_type_archive_link( 'post' ) ) . '">' . esc_html( $post_type_labels -> name ) . '</a>';
	}
	if( is_archive() ) {
		$active = ( is_archive() ) ? ' active' : '';
		if( 'syntric_event' == $post -> post_type || 'syntric_calendar' == $post -> post_type ) {
			$post_type_labels = get_post_type_labels( get_post_type_object( 'syntric_calendar' ) );
			$breadcrumbs      .= '<span class="breadcrumb-item">' . esc_html( $post_type_labels -> name ) . '</span>';
			$breadcrumbs      .= '<span class="breadcrumb-item' . $active . '">' . esc_html( $post_type_labels -> name ) . '</span>';
		} elseif( 'syntric_calendar' == $post -> post_type ) {
			$post_type_labels = get_post_type_labels( get_post_type_object( 'syntric_calendar' ) );
			$breadcrumbs      .= '<span class="breadcrumb-item' . $active . '">' . esc_html( $post_type_labels -> name ) . '</span>';
		} else {
			$categories  = get_the_category( $post -> ID );
			$breadcrumbs .= '<a class="breadcrumb-item' . $active . '" href="' . esc_url( get_category_link( $categories[ 0 ] -> term_id ) ) . '">' . esc_html( $categories[ 0 ] -> name ) . '</a>';
		}
	}
	if( is_attachment() ) {
		$breadcrumbs .= '<span class="breadcrumb-item active">Attachment</span>';
	}
	if( is_single() && ! is_attachment() ) { // post, syntric_calendar or syntric_event
		if( 'syntric_event' == $post -> post_type ) {
			$calendar_post_type_labels = get_post_type_labels( get_post_type_object( 'syntric_calendar' ) );
			$event_post_type_labels    = get_post_type_labels( get_post_type_object( 'syntric_event' ) );
			$event_calendar_id         = get_field( 'syntric_event_calendar_id', $post -> ID );
			$breadcrumbs               .= '<span class="breadcrumb-item">' . esc_html( $calendar_post_type_labels -> name ) . '</span>';
			$breadcrumbs               .= '<a class="breadcrumb-item" href="' . esc_url( get_the_permalink( $event_calendar_id ) ) . '">' . esc_html( get_the_title( $event_calendar_id ) ) . '</a>';
			$breadcrumbs               .= '<span class="breadcrumb-item">' . esc_html( $event_post_type_labels -> name ) . '</span>';
		} elseif( 'syntric_calendar' == $post -> post_type ) {
			$calendar_post_type_labels = get_post_type_labels( get_post_type_object( 'syntric_calendar' ) );
			$breadcrumbs               .= '<span class="breadcrumb-item">' . esc_html( $calendar_post_type_labels -> name ) . '</span>';
			//} elseif ( 'attachment' == $post->post_type ) {
			//$attachment = wp_get_attachment_metadata( $post->ID );
			//$breadcrumbs               .= '<span class="breadcrumb-item">Attachment</span>';
		} else {
			$categories  = get_the_category( $post -> ID );
			$breadcrumbs .= '<a class="breadcrumb-item active" href="' . esc_url( get_category_link( $categories[ 0 ] -> term_id ) ) . '">' . esc_html( $categories[ 0 ] -> name ) . '</a>';
		}
		$breadcrumbs .= '<a class="breadcrumb-item active" href="' . esc_url( get_the_permalink( $post -> ID ) ) . '">' . esc_html( get_the_title( $post -> ID ) ) . '</a>';
	}
	if( is_page() ) {
		$ancestor_ids = array_reverse( get_post_ancestors( $post -> ID ) );
		if( $ancestor_ids ) {
			foreach( $ancestor_ids as $ancestor_id ) {
				$ancestor = get_post( $ancestor_id );
				if( 0 == $ancestor -> post_parent ) {
					$breadcrumbs .= '<span class="breadcrumb-item">' . esc_html( $ancestor -> post_title ) . '</span>';
				} else {
					$breadcrumbs .= '<a class="breadcrumb-item" href="' . esc_url( get_the_permalink( $ancestor -> ID ) ) . '">' . esc_html( $ancestor -> post_title ) . '</a>';
				}
			}
		}
		$breadcrumbs .= '<a class="breadcrumb-item active" href="' . esc_url( get_the_permalink( $post -> ID ) ) . '">' . esc_html( get_the_title( $post -> ID ) ) . '</a>';
	}
	if( is_search() ) {
		$breadcrumbs .= '<span class="breadcrumb-item active">Search Results</span>';
	}
	if( is_404() ) {
		$breadcrumbs .= '<span class="breadcrumb-item active">Page Not Found</span>';
	}
	$breadcrumbs .= '</nav>';
	$breadcrumbs .= '</div>';
	$breadcrumbs .= '</div>';
	echo $breadcrumbs;
}

// badges
function syntric_get_post_badges( $post_id = null ) {
	global $post;
	$post_id    = ( ! isset( $post_id ) ) ? $post -> ID : $post_id;
	$post       = get_post( $post_id );
	$badge_text = '';
	switch( $post -> post_type ) {
		case 'syntric_calendar' :
			$badge_text = 'Calendar';
		break;
		case 'syntric_event' :
			$calendar   = get_the_title( get_field( 'syntric_event_calendar_id', get_the_ID() ) );
			$badge_text = $calendar . ' Event';
		break;
		case 'post' :
			$badge_text = syntric_get_taxonomies_terms();
		break;
		case 'page' :
			$page_template = basename( get_page_template( $post_id ), '.php' );
			switch( $page_template ) {
				case 'class':
					$badge_text = 'Class';
				break;
				case 'course':
					$badge_text = 'Course';
				break;
				case 'department':
					$badge_text = 'Department';
				break;
				case 'teacher':
					$badge_text = 'Teacher';
				break;
				case 'teachers':
					$badge_text = '';
				break;
				default:
					$badge_text = '';
				break;
			}
		break;
	}
	if( strlen( $badge_text ) ) {
		return '<div class="badge badge-pill badge-secondary">' . $badge_text . '</div>';
	}
}

function syntric_get_excerpt_badges( $post_id = null ) {
	$post_id = syntric_resolve_post_id( $post_id );
	$post    = get_post( $post_id );
	switch( $post -> post_type ) {
		case 'syntric_event' :
			$calendar = get_the_title( get_field( 'syntric_event_calendar_id', get_the_ID() ) );

			return '<div class="badge badge-pill badge-dark">' . $calendar . '</div>';
		break;
		case 'post' :
			return '<div class="badge badge-pill badge-dark">' . syntric_get_taxonomies_terms() . '</div>';
		break;
	}

	return '';
}

// front-end lists
function syntric_display_teachers() {
	if( is_admin() || 'teachers' != basename( get_page_template(), '.php' ) ) {
		return;
	}
	$teachers = syntric_get_teachers(); // returns an array of WP_User objects
	if( ! $teachers ) {
		return;
	}
	echo '<table class="teachers-table">';
	echo '<thead>';
	echo '<tr>';
	echo '<th scope="col">Name</th>';
	//echo    '<th scope="col">Title</th>';
	echo '<th scope="col">Email</th>';
	echo '<th scope="col">Phone</th>';
	echo '<th scope="col">Classes</th>';
	echo '</tr>';
	echo '</thead>';
	echo '<tbody>';
	foreach( $teachers as $user ) {
		$user_cf           = get_field( 'syntric_user', 'user_' . $user -> ID );
		$teacher_page      = syntric_get_teacher_page( $user -> ID );
		$link_teacher_page = ( $teacher_page instanceof WP_Post && 'publish' == $teacher_page -> post_status ) ? 1 : 0;
		$phone             = $user_cf[ 'phone' ];
		$phone             .= ( 0 < strlen( $phone ) && isset( $user_cf[ 'ext' ] ) && ! empty( $user_cf[ 'ext' ] ) ) ? ' x' . $user_cf[ 'ext' ] : '';
		echo '<tr valign="top">';
		echo '<td class="user-name">';
		if( $link_teacher_page ) {
			echo '<a href="' . get_the_permalink( $teacher_page -> ID ) . '">';
		}
		echo trim( $user_cf[ 'prefix' ] . ' ' . $user -> user_firstname . ' ' . $user -> user_lastname );
		if( $link_teacher_page ) {
			echo '</a>';
		}
		echo '</td>';
		echo '<td class="user-email">';
		echo '<a href="mailto:' . antispambot( $user -> user_email, true ) . '" class="user-email" title="Email">' . antispambot( $user -> user_email ) . '</a>';
		echo '</td>';
		echo '<td class="user-phone">' . $phone . '</td>';
		$teacher_classes   = syntric_get_teacher_classes( $user -> ID );
		$teacher_class_arr = [];
		if( $teacher_classes ) {
			foreach( $teacher_classes as $class ) {
				$before_course = '';
				$after_course  = '';
				if( $class[ 'include_page' ] ) {
					$class_page    = syntric_get_class_page( $class[ 'id' ] );
					$before_course = '<a href="' . get_the_permalink( $class_page -> ID ) . '">';
					$after_course  = '</a>';
				}
				$teacher_class_arr[] = $before_course . 'Period ' . $class[ 'period' ][ 'label' ] . ' ' . $class[ 'course' ][ 'label' ] . $after_course;
			}
		}
		echo '<td class="classes">' . implode( '<br>', $teacher_class_arr ) . '</td>';
		echo '</tr>';
	}
	echo '</tbody>';
	echo '</table>';
}

function syntric_display_teacher_classes( $user_id = null, $format = 'cards' ) {
	global $post;
	if( is_admin() || 'teacher' != basename( get_page_template(), '.php' ) ) {
		return;
	}
	$user_id = ( isset( $user_id ) ) ? $user_id : get_field( 'syntric_teacher_page_teacher', $post -> ID );
	if( ! $user_id ) {
		return;
	}
	$teacher_classes = syntric_get_teacher_classes( $user_id );
	if( ! $teacher_classes ) {
		return;
	}
	echo '<aside class="sidebar main-right-sidebar col-xl-3">';
	echo '<div class="widget">';
	if( 'cards' == $format ) {
		//echo '<div class="card-group">';
		foreach( $teacher_classes as $class ) {
			echo '<div class="card border-secondary mb-3">';
			echo '<div class="card-header">Period ' . $class[ 'period' ][ 'label' ] . '</div>';
			echo '<div class="card-body">';
			//echo '<h5 class="text-white">Period ' . $class[ 'period' ][ 'label' ] . '</h5>';
			echo '<h3>' . $class[ 'course' ][ 'label' ] . '</h3>';
			echo '<p class="card-text">Some quick example text to build on the card title and make up the bulk of the card\'s content.</p>';
			echo '<a href="#" class="btn btn-sm btn-primary">Go</a>';
			echo '</div>';
			echo '</div>';
		}
		//echo '</div>';
	} elseif( 'list-group' == $format ) {
		echo '<div class="list-group">';
		foreach( $teacher_classes as $class ) {
			$before_item = '';
			$after_item  = '';
			if( $class[ 'include_page' ] ) {
				$class_page  = syntric_get_class_page( $class[ 'id' ] );
				$before_item = '<a href="' . get_the_permalink( $class_page -> ID ) . '" class="list-group-item list-group-item-action">';
				$after_item  = '</a>';
			}
			echo $before_item;
			echo '<div class="d-flex w-100 justify-content-between">';
			echo '<h5 class="mb-1">' . $class[ 'course' ][ 'label' ] . '</h5>';
			echo '<small>Period ' . $class[ 'period' ][ 'label' ] . '</small>';
			echo '</div>';
			echo '<p>' . 'This is a description of the class' . '</p>';
			echo '<small>Room ' . $class[ 'room' ][ 'label' ] . '</small>';
			echo $after_item;
		}
		echo '</div>';
	} elseif( 'table' == $format ) {
		echo '<table class="teacher-classes-table">';
		echo '<thead>';
		echo '<tr>';
		echo '<th scope="col">Period</th>';
		echo '<th scope="col">Course</th>';
		echo '<th scope="col">Room</th>';
		echo '</tr>';
		echo '</thead>';
		echo '<tbody>';
		foreach( $teacher_classes as $class ) {
			$before_course = '';
			$after_course  = '';
			if( $class[ 'include_page' ] ) {
				$class_page    = syntric_get_class_page( $class[ 'id' ] );
				$before_course = '<a href="' . get_the_permalink( $class_page -> ID ) . '">';
				$after_course  = '</a>';
			}
			echo '<tr>';
			echo '<td class="period">' . $class[ 'period' ][ 'label' ] . '</td>';
			echo '<td class="course">' . $before_course . $class[ 'course' ][ 'label' ] . $after_course . '</td>';
			echo '<td class="room">' . $class[ 'room' ][ 'label' ] . '</td>';
			echo '</tr>';
		}
		echo '</tbody>';
		echo '</table>';
	}
	echo '</div>';
	echo '</aside>';
}

function syntric_display_schedules() {
	global $post;
	if( is_admin() || 'schedules' != basename( get_page_template(), '.php' ) ) {
		return;
	}
	$schedules_page       = get_field( 'syntric_schedules_page', $post -> ID );
	$schedules_to_display = $schedules_page[ 'schedules' ];
	$schedules            = get_field( 'syntric_schedules', 'options' );
	if( $schedules ) {
		foreach( $schedules as $schedule ) {
			if( in_array( $schedule[ 'name' ], $schedules_to_display ) ) {
				$display = $schedule[ 'display' ];
				echo '<h2>' . $schedule[ 'name' ] . '</h2>';
				echo '<table>';
				echo '<caption class="sr-only">' . $schedule[ 'name' ] . '</caption>';
				echo '<thead>';
				echo '<tr>';
				echo '<th scope="col" nowrap>Period</th>';
				echo '<th style="width: 17%;" scope="col" nowrap>Start Time</th>';
				echo '<th style="width: 17%;" scope="col" nowrap>End Time</th>';
				if( is_array( $display ) && in_array( 'dow', $display ) ) {
					echo '<th style="width: 35%;" scope="col" nowrap>Days of Week</th>';
				}
				if( is_array( $display ) && in_array( 'instructional_period', $display ) ) {
					echo '<th style="width: 13%;" scope="col" nowrap>Instructional Period</th>';
				}
				echo '</tr>';
				echo '</thead>';
				echo '<tbody>';
				foreach( $schedule[ 'schedule' ] as $_schedule ) {
					echo '<tr valign="top">';
					echo '<td>' . $_schedule[ 'period' ] . '</td>';
					echo '<td style="text-align: right;" nowrap>' . $_schedule[ 'start_time' ] . '</td>';
					echo '<td style="text-align: right;" nowrap>' . $_schedule[ 'end_time' ] . '</td>';
					if( is_array( $display ) && in_array( 'dow', $display ) ) {
						echo '<td>' . implode( '/', $_schedule[ 'dow' ] ) . '</td>';
					}
					if( is_array( $display ) && in_array( 'instructional_period', $display ) ) {
						$instructional_period = ( $_schedule[ 'instructional_period' ] ) ? 'Yes' : 'No';
						echo '<td style="text-align: center;" nowrap>' . $instructional_period . '</td>';
					}
					echo '</tr>';
				}
				echo '</tbody>';
				echo '</table>';
			}
		}
	}
}

function syntric_display_roster() {
	global $post;
	if( is_admin() || 'roster' != basename( get_page_template(), '.php' ) ) {
		return;
	}
	$roster = get_field( 'syntric_roster', $post -> ID );
	if( count( $roster[ 'people' ] ) ) {
		$people  = $roster[ 'people' ];
		$display = $roster[ 'display' ];
		//echo '<h2>' . $roster[ 'title' ] . '</h2>';
		echo '<table class="roster-table">';
		echo '<thead>';
		echo '<tr>';
		echo '<th scope="col">Name</th>';
		echo '<th scope="col">Title</th>';
		if( in_array( 'email', $display ) ) {
			echo '<th scope="col">Email</th>';
		}
		if( in_array( 'phone', $display ) ) {
			echo '<th scope="col">Phone</th>';
		}
		echo '</tr>';
		echo '</thead>';
		echo '<tbody>';
		$row_counter = 1;
		foreach( $people as $person ) {
			$user_id = $person[ 'person' ]; // returns User ID
			$user    = get_user_by( 'ID', $user_id );
			if( $user instanceof WP_User ) {
				$user_custom_fields = get_field( 'syntric_user', 'user_' . $user_id );
				echo '<tr valign="top">';
				echo '<td class="contact-name">' . trim( $user_custom_fields[ 'prefix' ] . ' ' . $user -> display_name ) . '</td>';
				echo '<td class="contact-title">' . str_replace( '|', ' / ', $user_custom_fields[ 'title' ] ) . '</td>';
				if( in_array( 'email', $display ) ) {
					echo '<td class="contact-email"><a href="mailto:' . antispambot( $user -> user_email, true ) . '" class="user-email" title="Email">' . antispambot( $user -> user_email ) . '</a></td>';
				}
				if( in_array( 'phone', $display ) ) {
					$phone = $user_custom_fields[ 'phone' ];
					$phone .= ( isset( $user_custom_fields[ 'ext' ] ) && ! empty( $user_custom_fields[ 'ext' ] ) ) ? ' x' . $user_custom_fields[ 'ext' ] : '';
					echo '<td class="contact-phone">' . $phone . '</td>';
				}
				echo '</tr>';
			} else {
				$delete_result = delete_row( 'syntric_roster', $row_counter, $post -> ID );
				if( ! $delete_result ) {
					echo '<!-- failed to delete row ' . $row_counter;
				}
			}
			$row_counter ++;
		}
		echo '</tbody>';
		echo '</table>';
	}
}

function syntric_get_time_interval( $date_time ) {
	$pub_interval = date_diff( date_create(), date_create( $date_time ) );
	$pub_days     = ( 0 < (int) $pub_interval -> format( '%a' ) ) ? $pub_interval -> format( '%a' ) : '';
	$pub_hours    = ( 0 < (int) $pub_interval -> format( '%h' ) ) ? $pub_interval -> format( '%h' ) : '';
	$pub_minutes  = ( 0 < (int) $pub_interval -> format( '%i' ) ) ? $pub_interval -> format( '%i' ) : '';
	$pub_since    = '';
	if( 1 < $pub_days ) {
		$pub_since = $pub_days . ' days';
	} elseif( 1 < $pub_hours ) {
		$pub_since = $pub_hours . ' hours';
	} elseif( 1 < $pub_minutes ) {
		$pub_since = $pub_minutes . ' minutes';
	} else {
		$pub_since = 'Just now';
	}

	return $pub_since;
}

// admin/dashboard lists
function syntric_list_pendings( $deprecated, $mb_args ) {
	$user_id    = get_current_user_id();
	$is_teacher = get_field( 'syntric_user_is_teacher', 'user_' . $user_id );
	$post_type  = $mb_args[ 'args' ][ 'post_type' ];
	$args       = [ 'numberposts'   => - 1,
	                'post_type'     => $post_type,
	                'post_status'   => [ 'pending' ],
	                //'orderby'       => array( 'menu_order' => 'ASC' ),
	                'no_found_rows' => true, ];
	if( $is_teacher ) {
		$args[ 'author' ] = $user_id;
	}
	$pendings = get_posts( $args );
	echo '<table class="admin-list">';
	echo '<thead>';
	echo '<tr>';
	echo '<th scope="col">Title</th>';
	echo '<th class="status" scope="col">Status</th>';
	if( 'page' == $post_type ) {
		echo '<th class="template" scope="col" nowrap="nowrap">Template</th>';
	} elseif( 'post' == $post_type ) {
		echo '<th class="category" scope="col" nowrap="nowrap">Category</th>';
	}
	echo '<th class="owner" scope="col">Author</th>';
	echo '<th class="submitted" scope="col">Submitted</th>';
	echo '</tr>';
	echo '</thead>';
	echo '<tbody>';
	if( $pendings ) {
		foreach( $pendings as $pending ) {
			echo '<tr>';
			echo '<td nowrap="nowrap">';
			echo '<a href="/wp-admin/post.php?action=edit&post=' . $pending -> ID . '">' . $pending -> post_title . '</a>';
			echo '</td>';
			echo '<td>' . ucwords( $pending -> post_status ) . '</td>';
			if( 'page' == $pending -> post_type ) {
				echo '<td>' . ucwords( get_post_type( $pending -> ID ) ) . '</td>';
			} elseif( 'post' == $pending -> post_type ) {
				$categories = get_the_category( $pending -> ID );
				$cats       = [];
				foreach( $categories as $category ) {
					$cats[] = $category -> name;
				}
				echo '<td>' . implode( ', ', $cats ) . '</td>';
			}
			echo '<td>' . get_the_author_meta( 'display_name', $pending -> post_author ) . '</td>';
			echo '<td>' . syntric_get_time_interval( $pending -> post_date ) . ' ago</td>';
			echo '</tr>';
		}
	} else {
		echo '<tr>';
		echo '<td colspan="5">No ' . $post_type . 's are pending</td>';
		echo '</tr>';
	}
	echo '</tbody>';
	echo '</table>';
}

function syntric_list_recently_published( $deprecated, $mb_args ) {
	$user_id    = get_current_user_id();
	$is_teacher = get_field( 'syn_user_is_teacher', 'user_' . $user_id );
	$post_type  = $mb_args[ 'args' ][ 'post_type' ];
	$args       = [ 'numberposts'   => 5,
	                'post_type'     => $post_type,
	                'post_status'   => [ 'publish' ],
	                'no_found_rows' => true, ];
	if( $is_teacher ) {
		$args[ 'author' ] = $user_id;
	}
	$recents = get_posts( $args );
	echo '<table class="admin-list">';
	echo '<thead>';
	echo '<tr>';
	echo '<th scope="col">Title</th>';
	echo '<th class="status" scope="col">Status</th>';
	if( 'page' == $post_type ) {
		echo '<th class="template" scope="col" nowrap="nowrap">Template</th>';
	} elseif( 'post' == $post_type ) {
		echo '<th class="category" scope="col" nowrap="nowrap">Category</th>';
	}
	echo '<th class="owner" scope="col">Author</th>';
	echo '<th class="published" scope="col">Published</th>';
	echo '</tr>';
	echo '</thead>';
	echo '<tbody>';
	foreach( $recents as $recent ) {
		echo '<tr>';
		echo '<td>';
		echo '<a href="/wp-admin/post.php?action=edit&post=' . $recent -> ID . '">' . $recent -> post_title . '</a>';
		echo '</td>';
		$status = ( 'publish' == $recent -> post_status ) ? 'Published' : $recent -> post_status;
		echo '<td>' . ucwords( $status ) . '</td>';
		if( 'page' == $post_type ) {
			echo '<td>' . ucwords( get_post_type( $recent -> ID ) ) . '</td>';
		} elseif( 'post' == $post_type ) {
			$categories = get_the_category( $recent -> ID );
			$cats       = [];
			foreach( $categories as $category ) {
				$cats[] = $category -> name;
			}
			echo '<td>' . implode( ', ', $cats ) . '</td>';
		}
		echo '<td>' . get_the_author_meta( 'display_name', $recent -> post_author ) . '</td>';
		echo '<td>' . syntric_get_time_interval( $recent -> post_date ) . ' ago</td>';
		echo '</tr>';
	}
	echo '</tbody>';
	echo '</table>';
}

// ACF filters

/**
 * Field group reference
 * Buildings
 * Calendar
 * Calendars Menu Widget
 * Class Page (class template pages)
 * Classes
 * Contact Widget
 * Course Page (course template pages)
 * Courses
 * Data Functions
 * Department Page (department template pages)
 * Departments
 * Events
 * Facebook Widget
 * Filters
 * Google Map Widget
 * Jumbotrons
 * Organization
 * Organizations
 * Quick Nav
 * Recent Posts Widget
 * Rooms
 * Roster
 * Schedules
 * Schedules Page (schedules template pages)
 * Teacher Page (teacher template pages)
 * Upcoming Events Widget
 * User
 */

/**
 * Update any empty ID fields (name=id)
 */
add_filter( 'acf/update_value/name=id', 'syntric_set_id', 10, 3 );
function syntric_set_id( $value, $post_id, $field ) {
	if( is_admin() && 'id' == $field[ '_name' ] && empty( $value ) ) {
		return syntric_unique_id();
	}

	return $value;
}

/**
 * Format any phone type fields (name=phone, name=fax)
 */
add_filter( 'acf/update_value/name=phone', 'syntric_format_phone' );
add_filter( 'acf/update_value/name=fax', 'syntric_format_phone' );
function syntric_format_phone( $value ) {
// is $phone set and at least 7 characters
	if( strlen( $value ) < 7 ) {
		return '';
	}
// strip out all non-numeric characters
	$phone  = preg_replace( "/[^0-9]/", "", $value );
	$length = strlen( $phone );
// after stripping out non-numerics, check again if $phone at least 7 digits
	if( strlen( $phone ) < 7 ) {
		return '';
	}
	switch( $length ) {
		case 7:
			$value = preg_replace( "/([0-9]{3})([0-9]{4})/", "$1-$2", $phone );
		break;
		case 10:
			$value = preg_replace( "/([0-9]{3})([0-9]{3})([0-9]{4})/", "($1) $2-$3", $phone );
		break;
		case 11: // if 11 characters, assume 1 prefix, strip it out
			$value = preg_replace( "/([0-9]{1})([0-9]{3})([0-9]{3})([0-9]{4})/", "($2) $3-$4", $phone );
		break;
		default:
			$value = $phone;
		break;
	}

	return $value;
}

/**
 * Prepare field options (select, radio, checkbox)
 */
/////////////////////////////////////////////////// Buildings

/////////////////////////////////////////////////// Calendar

/////////////////////////////////////////////////// Calendar Menu Widget
add_filter( 'acf/load_field/key=field_5cc52160b3522', 'syntric_acf_load_field' ); // syntric_calendar_menu_widget_calendar

/////////////////////////////////////////////////// Class Page (class template pages)
add_filter( 'acf/load_field/key=field_5cb5bd08723f3', 'syntric_acf_load_field' ); // syntric_class_page_class

/////////////////////////////////////////////////// Classes
add_filter( 'acf/load_field/key=field_5cb5ba20c5f23', 'syntric_acf_load_field' ); // syntric_classes_course
add_filter( 'acf/load_field/key=field_5cb5ba2fc5f24', 'syntric_acf_load_field' ); // syntric_classes_period
add_filter( 'acf/load_field/key=field_5cb5ba41c5f25', 'syntric_acf_load_field' ); // syntric_classes_room
add_filter( 'acf/load_field/key=field_5cb6d14cff0bd', 'syntric_acf_load_field' ); // syntric_classes_teacher
//add_filter( 'acf/load_field/key=field_5cc5009425367', 'syntric_acf_load_field' ); // syntric_classes_page

/////////////////////////////////////////////////// Contact Widget
add_filter( 'acf/load_field/key=field_5c80da686ae5e', 'syntric_acf_load_field' ); // syntric_contact_widget_person
add_filter( 'acf/load_field/key=field_5ca1538c6091b', 'syntric_acf_load_field' ); // syntric_contact_widget_organization

/////////////////////////////////////////////////// Course Page (course template pages)
add_filter( 'acf/load_field/key=field_5c97d03566669', 'syntric_acf_load_field' ); // syntric_course_page_course

/////////////////////////////////////////////////// Courses
add_filter( 'acf/load_field/key=field_5c6fae31bbd6d', 'syntric_acf_load_field' ); // syntric_courses_department
add_filter( 'acf/load_field/key=field_5cc4f7dd991be', 'syntric_acf_load_field' ); // syntric_courses_page

/////////////////////////////////////////////////// Data Functions

/////////////////////////////////////////////////// Department Page (department template pages)
add_filter( 'acf/load_field/key=field_5c97d08f2b7d3', 'syntric_acf_load_field' ); // syntric_department_page_department

/////////////////////////////////////////////////// Departments
//add_filter( 'acf/load_field/key=field_5cc5004c9ba09', 'syntric_acf_load_field' ); // syntric_departments_page

/////////////////////////////////////////////////// Events
// calendar

/////////////////////////////////////////////////// Facebook Widget
// todo: look at this...possible changes syntric_facebook_widget (drop _page_)
add_filter( 'acf/load_field/key=field_5c80da7164177', 'syntric_acf_load_field' ); // syntric_facebook_page_widget_facebook_page

/////////////////////////////////////////////////// Filters
// post types
add_filter( 'acf/load_field/key=field_59a1f43953657', 'syntric_acf_load_field' ); // filters_value_post_type_value
// page templates
add_filter( 'acf/load_field/key=field_59a1f76e5365c', 'syntric_acf_load_field' ); // filters_value_page_template_value

/////////////////////////////////////////////////// Google Map Widget
// filters

/////////////////////////////////////////////////// Jumbotrons
// filters

/////////////////////////////////////////////////// Organization

/////////////////////////////////////////////////// Organizations

/////////////////////////////////////////////////// Quick Nav

/////////////////////////////////////////////////// Recent Posts Widget
// filters

/////////////////////////////////////////////////// Rooms
add_filter( 'acf/load_field/key=field_5c6fada5bbd68', 'syntric_acf_load_field' ); // syntric_rooms_building

/////////////////////////////////////////////////// Roster
//add_filter( 'acf/load_field/key=xxxxxxxxx', 'syntric_acf_load_field' ); // syntric_roster_person

/////////////////////////////////////////////////// Schedules

/////////////////////////////////////////////////// Schedules Page (schedules template pages)
add_filter( 'acf/load_field/key=field_5c97d1fb103b3', 'syntric_acf_load_field' ); // syntric_schedules_page_schedules

/////////////////////////////////////////////////// Teacher Page (teacher template pages)
add_filter( 'acf/load_field/key=field_5cadb2ec3de2d', 'syntric_acf_load_field' ); // syntric_teacher_page_teacher

/////////////////////////////////////////////////// Upcoming Events Widget
add_filter( 'acf/load_field/key=field_5c80dbcba56dc', 'syntric_acf_load_field' ); // syntric_upcoming_events_widget_calendar
// filters

/////////////////////////////////////////////////// User
//add_filter( 'acf/load_field/key=xxxxxxxxxxxxx', 'syntric_acf_load_field' ); // syntric_user_page

function syntric_acf_load_field( $field ) {
	global $post, $pagenow;
	//slog( 'syntric_acf_load_field...pagenow and _REQUEST' );
	//slog( $pagenow );
	//slog( $_REQUEST );
	if( isset( $_REQUEST[ 'action' ] ) && 'heartbeat' == $_REQUEST[ 'action' ] ) {
		return;
	}
	/*if( ! is_admin() ) {
		return;
	}*/

	//if( is_admin() && 'select' == $field[ 'type' ] ) :

	$choices = [];
	switch( $field[ 'key' ] ) :
		/////////////////////////////////////////////////// Buildings
		/////////////////////////////////////////////////// Calendar
		/////////////////////////////////////////////////// Calendar Menu Widget
		// syntric_calendar_widget_calendar
		case 'field_5cc52160b3522' :
			// syntric_upcoming_events_widget_calendar
		case 'field_5c80dbcba56dc':
			$calendars = syntric_get_calendars();
			if( $calendars ) {
				foreach( $calendars as $calendar ) {
					$choices[ $calendar -> ID ] = $calendar -> post_title;
				}
				$field[ 'choices' ] = $choices;
			}
		break;
/////////////////////////////////////////////////// Class Page (class template pages)
/////////////////////////////////////////////////// Classes
//syntric_classes_teacher
		case 'field_5cb6d14cff0bd' :
			$teachers = syntric_get_teachers();
			if( $teachers ) {
				foreach( $teachers as $teacher ) {
					$choices[ $teacher -> ID ] = $teacher -> display_name;
				}
			}
			$field[ 'choices' ] = $choices;
		break;
//syntric_classes_course
		case 'field_5cb5ba20c5f23' :
			if( have_rows( 'field_5c6fa90a18def', 'options' ) ) {
				while( have_rows( 'field_5c6fa90a18def', 'options' ) ) {
					the_row();
					$choices[ get_sub_field( 'id' ) ] = get_sub_field( 'course' );
				}
			}
			$field[ 'choices' ] = $choices;
		break;
// syntric_classes_period
		case 'field_5cb5ba2fc5f24' :
			$schedules = get_field( 'field_5c8065cb8cc55', 'options' );
			if( $schedules ) {
				$regular_schedules = [];
				foreach( $schedules as $schedule ) {
					if( 'regular' == $schedule[ 'schedule_type' ] ) {
						$regular_schedules[] = $schedule;
					}
				}
				// now for regular schedules
				foreach( $regular_schedules as $regular_schedule ) {
					$_schedule = $regular_schedule[ 'schedule' ];
					foreach( $_schedule as $__schedule ) {
						if( $__schedule[ 'instructional_period' ] ) {
							$choices[ $__schedule[ 'period' ] ] = $__schedule[ 'period' ];
						}
					}
				}
			}
			$field[ 'choices' ] = $choices;
		break;
//syntric_classes_room
		case 'field_5cb5ba41c5f25' :
			$rooms = get_field( 'field_5c6fa8e318dec', 'options' );
			if( $rooms ) {
				foreach( $rooms as $room ) {
					$choices[ $room[ 'id' ] ] = $room[ 'room' ];
				}
			}
			$field[ 'choices' ] = $choices;
		break;
/////////////////////////////////////////////////// Contact Widget
// syntric_contact_widget_person
		case 'field_5c80da686ae5e' :
			$users = get_users( [
				'meta_key' => 'last_name',
				'order_by' => 'meta_value',
			] );
			if( $users ) {
				foreach( $users as $user ) {
					$user_cf                = get_field( 'field_5c873b64763cd', 'user_' . $user -> ID );
					$label                  = ( ! empty( $prefix ) ) ? $prefix . ' ' . $user -> display_name : $user -> display_name;
					$label                  .= ( ! empty( $user_cf[ 'title' ] ) ) ? ' - ' . str_replace( '|', ' / ', $user_cf[ 'title' ] ) : ' - (no title)';
					$label                  .= ( ! empty( $user -> user_email ) ) ? ' - ' . $user -> user_email : ' - (no email)';
					$label                  .= ( ! empty( $user_cf[ 'phone' ] ) ) ? ' - ' . $user_cf[ 'phone' ] : ' - (no phone)';
					$label                  .= ( ! empty( $user_cf[ 'ext' ] ) ) ? ' x' . $user_cf[ 'ext' ] : '';
					$choices[ $user -> ID ] = $label;
				}
			}
			$field[ 'choices' ] = $choices;
			//}
		break;
// syntric_contact_widget_organization
		case 'field_5ca1538c6091b' :
			$organization = get_field( 'field_5c9a588f36054', 'options' );
			if( $organization ) {
				$choices[ $organization[ 'name' ] ] = $organization[ 'name' ];
			}
			$organizations = get_field( 'field_5ca27332537bc', 'options' );
			if( $organizations ) {
				foreach( $organizations as $_organization ) {
					$choices[ $_organization[ 'name' ] ] = $_organization[ 'name' ];
				}
			}
			$field[ 'choices' ] = $choices;
		break;
/////////////////////////////////////////////////// Course Page (course template pages)
/////////////////////////////////////////////////// Courses
/////////////////////////////////////////////////// Data Functions
/////////////////////////////////////////////////// Department Page (department template pages)
/////////////////////////////////////////////////// Departments
/////////////////////////////////////////////////// Events
/////////////////////////////////////////////////// Facebook Page Widget
// syntric_facebook_page_widget_facebook_page
		case 'field_5c80da7164177' :
			$organization = get_field( 'field_5c9a588f36054', 'options' );
			if( $organization ) {
				$choices[ $organization[ 'facebook_page' ] ] = $organization[ 'name' ];
			}
			$organizations = get_field( 'field_5ca27332537bc', 'options' );
			if( $organizations ) {
				foreach( $organizations as $_organization ) {
					$choices[ $_organization[ 'facebook_page' ] ] = $_organization[ 'name' ];
				}
			}
			$field[ 'choices' ] = $choices;
		break;
/////////////////////////////////////////////////// Filters
// filters_value_post_type_value
		case 'field_59a1f43953657' :
			$post_types = get_post_types( [ 'public' => true ], 'objects' );
			if( $post_types ) {
				foreach( $post_types as $post_type ) {
					$choices[ $post_type -> name ] = $post_type -> labels -> singular_name;
				}
			}
			$field[ 'choices' ] = $choices;
		break;
// filters_value_page_template_value
		case 'field_59a1f76e5365c' :
			if( function_exists( 'get_page_templates' ) ) {
				$page_templates = get_page_templates( $post, 'page' );
				if( $page_templates ) {
					foreach( $page_templates as $key => $value ) {
						$choices[ $value ] = $key;
					}
				}
				$field[ 'choices' ] = $choices;
			}
		break;
/////////////////////////////////////////////////// Google Map Widget
/////////////////////////////////////////////////// Jumbotrons

/////////////////////////////////////////////////// Organization
/////////////////////////////////////////////////// Organizations
/////////////////////////////////////////////////// Quick Nav
/////////////////////////////////////////////////// Recent Posts Widget
/////////////////////////////////////////////////// Rooms
/////////////////////////////////////////////////// Roster
/////////////////////////////////////////////////// Schedules
/////////////////////////////////////////////////// Schedules Page (schedules template pages)
/////////////////////////////////////////////////// Teacher Page (teacher template pages)
/////////////////////////////////////////////////// Upcoming Events Widget (see Calendar Menu Widget)
/////////////////////////////////////////////////// User

// syntric_calendar_widget_calendar
		case 'field_5cc52160b3522' :
// syntric_upcoming_events_widget_calendar
		case 'field_5c80dbcba56dc':
			$calendars = syntric_get_calendars();
			if( $calendars ) {
				foreach( $calendars as $calendar ) {
					$choices[ $calendar -> ID ] = $calendar -> post_title;
				}
				$field[ 'choices' ] = $choices;
			}

		break;
// syntric_class_page_class
		case 'field_5cb5bd08723f3':
			$classes = syntric_get_classes();
			//slog( $classes );
			if( $classes ) {
				foreach( $classes as $class ) {
					$choices[ $class[ 'id' ] ] = $class[ 'course' ][ 'label' ] . ' (' . $class[ 'teacher' ][ 'label' ] . ' / ' . $class[ 'period' ][ 'label' ] . ')';
				}
			}
			$field[ 'choices' ] = $choices;
		break;
// syntric_schedules_page_schedules (checkboxes)
		case 'field_5c97d1fb103b3':
			$schedules = get_field( 'field_5c8065cb8cc55', 'options' );
			if( $schedules ) {
				foreach( $schedules as $schedule ) {
					$choices[ $schedule[ 'name' ] ] = $schedule[ 'name' ];
				}
			}
			$field[ 'choices' ] = $choices;
		break;
// syntric_rooms_building
		case 'field_5c6fada5bbd68' :
			$buildings = get_field( 'field_5c6fa8c818deb', 'options' );
			if( $buildings ) {
				foreach( $buildings as $building ) {
					$choices[ $building[ 'id' ] ] = $building[ 'building' ];
				}
			}
			$field[ 'choices' ] = $choices;
		break;
// syntric_department_page_department
		case 'field_5c97d08f2b7d3' :
// syntric_courses_department
		case 'field_5c6fae31bbd6d' :
			$departments = get_field( 'field_5c6fa8f118ded', 'options' );
			if( $departments ) {
				foreach( $departments as $department ) {
					$choices[ $department[ 'id' ] ] = $department[ 'department' ];
				}
			}
			$field[ 'choices' ] = $choices;
		break;
// syntric_teacher_page_teacher
		case 'field_5cadb2ec3de2d' :
// syntric_course_page_course
		case 'field_5c97d03566669' :
			if( have_rows( 'field_5c6fa90a18def', 'options' ) ) {
				while( have_rows( 'field_5c6fa90a18def', 'options' ) ) {
					the_row();
					$course_id             = get_sub_field( 'id' );
					$choices[ $course_id ] = get_sub_field( 'course' );
				}
			}
			$field[ 'choices' ] = $choices;
		break;

		/*case 'field_5c97d1fb103b3' :
			if( isset( $_REQUEST[ 'page_template' ] ) && 'schedules.php' == $_REQUEST[ 'page_template' ] ) {
				//slog( '_name=schedules' );
				$choices   = [];
				$schedules = get_field( 'syntric_schedules', 'options' );
				if( $schedules ) {
					foreach( $schedules as $schedule ) {
						$choices[ $schedule[ 'name' ] ] = $schedule[ 'name' ];
					}
				}
				$field[ 'choices' ] = $choices;
			}
		break;*/

// filters
		/*case 'field_5cadbcc82bd79' :
			$choices       = [];
			$teacher_pages = syntric_get_teacher_pages();
			if( $teacher_pages ) {
				foreach( $teacher_pages as $teacher_page ) {
					$choices[ $teacher_page -> ID ] = $teacher_page -> post_title;
				}
			}
			$field[ 'choices' ] = $choices;
		break;

		case 'field_59a1f76e5365c' :
			$choices        = [];
			$page_templates = get_page_templates( null, 'page' );
			if( $page_templates ) {
				foreach( $page_templates as $key => $value ) {
					$choices[ $value ] = $key;
				}
			}
			$field[ 'choices' ] = $choices;
		break;*/

	endswitch;
	//endif;
	/*switch( $field[ '_name' ] ) :
		case 'department' :
			if( 'select' == $field[ 'type' ] ) {
				$departments = get_field( 'syntric_departments', 'options' );
				if( $departments ) {
					//$choices = [];
					foreach( $departments as $department ) {
						$choices[ $department[ 'id' ] ] = $department[ 'department' ];
					}
				}
				$field[ 'choices' ] = $choices;
			}
			break;
		case 'term' :
			if( 'select' == $field[ 'type' ] ) {
				$terms = syntric_get_terms();
				if( $terms ) {
					//$choices = [];
					foreach( $terms as $term ) {
						$choices[ $term[ 'term' ] ] = $term[ 'term' ];
					}
					$field[ 'choices' ] = $choices;
				}
			}
			break;
		case 'schedules' :
			$schedules = get_field( 'syntric_schedules', 'options' );
			if( $schedules ) {
				//$choices = [];
				foreach( $schedules as $schedule ) {
					$choices[ $schedule[ 'name' ] ] = $schedule[ 'name' ];
				}
			}

			$field[ 'choices' ] = $choices;
			break;
		case 'teacher' :
			if( 'select' == $field[ 'type' ] ) {
				$choices  = [];
				$teachers = syntric_get_teachers();
				if( $teachers ) {
					foreach( $teachers as $teacher ) {
						$choices[ $teacher -> ID ] = $teacher -> display_name . ' (Teacher) ';
					}
				}
				$field[ 'choices' ] = $choices;
			}
			break;
		 case 'period' :
			if( 'select' == $field[ 'type' ] ) {
				$schedules         = get_field( 'syntric_schedules', 'options' );
				$regular_schedules = [];
				foreach( $schedules as $schedule ) {
					if( 'regular' == $schedule[ 'schedule_type' ] ) {
						$regular_schedules[] = $schedule;
					}
				}
// now with regular schedules
				//$choices = [];
				foreach( $regular_schedules as $regular_schedule ) {
					$_schedule = $regular_schedule[ 'schedule' ];
					foreach( $_schedule as $__schedule ) {
						if( $__schedule[ 'instructional_period' ] ) {
							$choices[ $__schedule[ 'period' ] ] = $__schedule[ 'period' ];
						}
					}
				}
				$field[ 'choices' ] = $choices;
			}
			break;
		case 'course' :
			if( 'select' == $field[ 'type' ] ) {
				$courses = syntric_get_courses;
				if( $courses ) {
					//$choices = [];
					foreach( $courses as $course ) {
						$choices[ $course[ 'id' ] ] = $course[ 'course' ];
					}
					$field[ 'choices' ] = $choices;
				}
			}

			break;
		case 'building' :
			if( 'select' == $field[ 'type' ] ) {
				$buildings = get_field( 'syntric_buildings', 'options' );
				if( $buildings ) {
					//$choices = [];
					foreach( $buildings as $building ) {
						$choices[ $building[ 'id' ] ] = $building[ 'building' ];
					}
					$field[ 'choices' ] = $choices;
				}
			}
			break;
		case 'room' :
			if( 'select' == $field[ 'type' ] ) {
				$rooms = get_field( 'syntric_rooms', 'options' );
				if( $rooms ) {
					//$choices = [];
					foreach( $rooms as $room ) {
//$bld = ( !  empty( $room['building'] ) ) ? $room['building']['label'] . ' ' : '';
						$choices[ $room[ 'id' ] ] = $room[ 'room' ];
					}
					$field[ 'choices' ] = $choices;
				}
			}
			break;
		case 'class' :
			if( 'select' == $field[ 'type' ] ) {
//	$page_author
				$classes = get_field( 'syntric_classes', 'options' );
				if( $classes ) {
					//$choices = [];
					asort( $classes );
					foreach( $classes as $class ) {
						$teacher_id                = $class[ 'teacher' ][ 'value' ];
						$teacher                   = get_user_by( 'ID', $teacher_id );
						$course                    = $class[ 'course' ][ 'label' ];
						$period                    = $class[ 'period' ][ 'label' ];
						$room                      = $class[ 'room' ][ 'label' ];
						$choices[ $class[ 'id' ] ] = $course . ' / ' . $teacher -> display_name . ' / ' . $period . ' / ' . $room;
					}
					$field[ 'choices' ] = $choices;
				}
			}
			break;
		case 'class_page' :
			if( 'select' == $field[ 'type' ] ) {
				$class_pages = syntric_get_class_pages();
				if( $class_pages ) {
					//$choices = [];
					foreach( $class_pages as $class_page ) {
//$class_page_cfs               = get_field( 'syntric_class_page', $class_page -> ID );
//$class                        = $class_page_cfs['class'];

						$choices[ $class_page -> ID ] = $class_page -> post_title . ' / ';
					}
					$field[ 'choices' ] = $choices;
				}
			}
			break;
	endswitch;*/

	return $field;
}

/**
 * Set default order on ACF Field Groups list (cause it's annoying)
 */
add_filter( 'pre_get_posts', 'syntric_acf_pre_get_posts', 1, 1 );
function syntric_acf_pre_get_posts( $query ) {
	global $pagenow;
	if( is_admin() && $query -> is_main_query() && 'edit.php' == $pagenow && 'acf-field-group' == $query -> get( 'post_type' ) ) {
		if( ! isset( $_GET[ 'orderby' ] ) ) {
			$query -> set( 'orderby', 'post_title' );
			$query -> set( 'order', 'ASC' );
		}
		if( ! isset( $_GET[ 'post_status' ] ) ) {
			$query -> set( 'post_status', 'publish' );
		}
	}

	return $query;
}

// Change the path where ACF will save the local JSON file
add_filter( 'acf/settings/save_json', 'syntric_acf_json_save_point' );
function syntric_acf_json_save_point() {
	$path = get_stylesheet_directory() . '/assets/json';

	return $path;
}

// Specify path where ACF should look for local JSON files
add_filter( 'acf/settings/load_json', 'syntric_acf_json_load_point' );
function syntric_acf_json_load_point( $paths ) {
// remove original path (optional)
	unset( $paths[ 0 ] );
// append new path
	$paths[] = get_stylesheet_directory() . '/assets/json';

	return $paths;
}

add_action( 'before_delete_post', 'syntric_before_delete_post', 20 );
function syntric_before_delete_post( $post_id ) {
	$post_type = get_post_type( $post_id );
	switch( $post_type ) :
		case 'syntric_calendar' :
			syntric_purge_calendar( $post_id );
		break;

	endswitch;
}

function syntric_qn_all_pages() {
	$qn_args = [ 'theme_location'  => 'primary',
	             'container'       => '',
	             'container_id'    => '',
	             'container_class' => '',
	             'menu_id'         => 'qn-all-pages',
	             'menu_class'      => 'admin-nav-menu',
	             //'depth'           => 0,
	];
	wp_nav_menu( (array) $qn_args );
}

///////////////////////////////////////////////////// Boneyard syntric-apps.php ////////////////////////
///
/**
 * Get a page template returned as name, not path + filename or filename alone
 * For example, teachers, teacher, department, course, class, ect.
 *
 * @param $post_id
 *
 * @return string/bool returns the base name of the template file (teachers, teacher, class, course, department, etc) or false
 */
function __syntric_get_page_template( $post_id ) {
	if( 'page' != get_post_type( $post_id ) ) {
		return false;
	}

	return basename( get_page_template(), '.php' );
}
	