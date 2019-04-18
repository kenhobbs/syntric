<?php
//
// todo: list
// remove class['page'] custom field and all references to it
// current user, available anywhere on this page
// Speed up admin load time by not loading WP Custom Fields
// https://www.advancedcustomfields.com/blog/acf-pro-5-5-13-update/
add_filter( 'acf/settings/remove_wp_meta_box', '__return_true' );
/**
 * Include Syntric App files
 */
if( is_multisite() ) {
	//require get_template_directory() . '/syntric-apps/syntric-multisite.php';
}
require get_template_directory() . '/syntric-apps/syntric-acf.php';
require get_template_directory() . '/syntric-apps/syntric-calendar.php';
require get_template_directory() . '/syntric-apps/syntric-event.php';
require get_template_directory() . '/syntric-apps/syntric-blocks-widgets-sidebars.php';
require get_template_directory() . '/syntric-apps/syntric-filters-schedules.php';
require get_template_directory() . '/syntric-apps/syntric-media.php';
require get_template_directory() . '/syntric-apps/syntric-nav-menus.php';
require get_template_directory() . '/syntric-apps/syntric-post-settings.php';
require get_template_directory() . '/syntric-apps/syntric-courses-classes.php';
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
	$settings          = get_field( 'syntric_settings', 'option' );
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
	acf_add_options_page( [ 'page_title' => 'My Classes',
	                        'menu_title' => 'My Classes',
	                        'menu_slug'  => 'syntric-my-classes',
	                        'capability' => 'edit_pages', ] );
	// My Pages
	acf_add_options_page( [ 'page_title' => 'My Pages',
	                        'menu_title' => 'My Pages',
	                        'menu_slug'  => 'syntric-my-pages',
	                        'capability' => 'edit_pages', ] );
	//}
}
/**
 * Custom role checker - "has role or higher"
 *
 * @param $role - syntric, administrator, editor, author, contributor, subscriber
 *
 * @return int (1/0 as booleans)
 */
// todo: fix this to account for user having multiple roles (eg multisite where user is superadmin, admin and editor)
function syntric_current_user_can( $role ) {
	if( in_array( strtolower( $role ), [ 'superadmin', 'super_admin', 'super admin' ] ) && is_super_admin() ) {
		return 1;
	}
	$current_user_role = syntric_current_user_role();
	switch( strtolower( $role ) ) :
		case 'administrator' :
		case 'admin':
			return in_array( $current_user_role, [ 'administrator', 'admin', ] );
		break;
		case 'editor' :
			return in_array( $current_user_role, [ 'editor', 'administrator', 'admin', ] );
		break;
		case 'author' :
			return in_array( $current_user_role, [ 'author', 'editor', 'administrator', 'admin', ] );
		break;
		case 'contributor' :
			return in_array( $current_user_role, [ 'contributor', 'author', 'editor', 'administrator', 'admin', ] );
		break;
		case 'subscriber' :
			return in_array( $current_user_role, [ 'subscriber', 'contributor', 'author', 'editor', 'administrator', 'admin', ] );
		break;
	endswitch;

	return 0;
}

// returns the highest role available to current user
function syntric_current_user_role() {
	if( is_user_logged_in() ) {
		if( is_super_admin() ) {
			return 'superadmin';
		}
		$user = wp_get_current_user();
		$role = syntric_user_role( $user -> ID );

		return $role;
	}

	return '';
}

// Returns the highest role available to a user
function syntric_user_role( $user_id = null ) {
	if( ! is_numeric( $user_id ) || 1 > $user_id ) {
		return '';
	}
	$user  = get_user_by( 'ID', $user_id );
	$roles = (array) $user -> roles;
	if( 1 == count( $roles ) ) {
		return $roles[ 0 ];
	} else {
		if( in_array( 'administrator', $roles ) ) {
			return 'administrator';
		} elseif( in_array( 'editor', $roles ) ) {
			return 'editor';
		} elseif( in_array( 'author', $roles ) ) {
			return 'author';
		} elseif( in_array( 'contributor', $roles ) ) {
			return 'contributor';
		} elseif( in_array( 'subscriber', $roles ) ) {
			return 'subscriber';
		}
	}

	return;
}

// Remove 3rd party plugin hooks
add_action( 'wp_head', 'syntric_remove_plugin_hooks' );
function syntric_remove_plugin_hooks() {
	global $tinymce_templates;
	if( $tinymce_templates ) {
		//remove_filter( 'post_row_actions', array( $tinymce_templates, 'row_actions' ), 10, 2 );
		//remove_filter( 'page_row_actions', array( $tinymce_templates, 'row_actions' ), 10, 2 );
		remove_action( 'wp_before_admin_bar_render', [ $tinymce_templates,
		                                               'wp_before_admin_bar_render', ] );
	}
}

/**
 * Require post_title
 */
function syntric_syntric_user() {
	$syntric_user = get_user_by( 'login', 'syntric' );
	if( ! $syntric_user instanceof WP_User ) {
		$syntric_user = get_user_by( 'email', 'ken@syntric.com' );
	}

	return $syntric_user;
}

add_action( 'wp_login', 'syntric_login', 10, 2 );
function syntric_login( $user_login, $user ) {
	syntric_reset_user_meta_boxes( $user -> ID );
}

add_filter( 'screen_layout_columns', 'syntric_screen_layout_columns' );
function syntric_screen_layout_columns( $columns ) {
	$columns[ 'dashboard' ] = 2;

	return $columns;
}

add_filter( 'get_user_option_screen_layout_dashboard', 'syntric_layout_dashboard' );
function syntric_layout_dashboard() {
	return 1;
}

function syntric_reset_user_meta_boxes( $user_id = 0 ) {
	if( ! $user_id ) {
		$user_id = get_current_user_id();
	}
	$user_options = get_user_meta( $user_id );
	foreach( $user_options as $key => $value ) {
		$cuo_array = explode( '-', $key );
		if( 'meta' == $cuo_array[ 0 ] && 'box' == $cuo_array[ 1 ] ) {
			if( isset( $cuo_array[ 2 ] ) ) {
				$cuo_array_2 = explode( '_', $cuo_array[ 2 ] );
				if( 'order' == $cuo_array_2[ 0 ] ) {
					$res = delete_user_meta( $user_id, $key );
				}
			}
		}
	}
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
	$settings        = get_field( 'syntric_settings', 'option' );
	$google_settings = $settings[ 'google' ];
	$api_key         = ( isset( $google_settings[ 'api_key' ] ) && ! empty( $google_settings[ 'api_key' ] ) ) ? $google_settings[ 'api_key' ] : 0;

	return $api_key;
}

function syntric_get_current_academic_year( $count = 1 ) {
	$ayto_month               = get_field( 'syn_academic_year_turnover_month', 'option' );
	$ayto_date                = get_field( 'syn_academic_year_turnover_date', 'option' );
	$now                      = date_create();
	$current_year             = date_format( $now, 'Y' );
	$current_year_ayto_string = $current_year . '-' . $ayto_month . '-' . $ayto_date;
	$current_year_ayto_date   = date_create( $current_year_ayto_string );
	$start_year               = ( $now > $current_year_ayto_date ) ? $current_year : $current_year - 1;
	//$end_year = $start_year + $count;
	$academic_years = [];
	for( $i = 1; $i < $count; $i ++ ) {
		$academic_years[] = ( $start_year + $i - 1 ) . '-' . ( $start_year + $i );
	}

	return $academic_years;
}

function syntric_get_terms() {
	$term_type       = get_field( 'syn_term_type', 'option' );
	$summer_sessions = get_field( 'syn_summer_sessions', 'option' );
	$academic_years  = syntric_get_current_academic_year( 2 );
	$terms           = [];
	foreach( $academic_years as $academic_year ) {
		$terms[] = [ 'term_id' => $academic_year . ' All Year',
		             'term'    => $academic_year . ' - All Year', ];
		switch( $term_type ) {
			case 'semester' :
				$terms[] = [ 'term_id' => $academic_year . ' 1st Semester',
				             'term'    => $academic_year . ' - 1st Semester', ];
				$terms[] = [ 'term_id' => $academic_year . ' 2nd Semester',
				             'term'    => $academic_year . ' - 2nd Semester', ];
			break;
			case 'trimester' :
				$terms[] = [ 'term_id' => $academic_year . ' 1st Trimester',
				             'term'    => $academic_year . ' - 1st Trimester', ];
				$terms[] = [ 'term_id' => $academic_year . ' 2nd Trimester',
				             'term'    => $academic_year . ' - 2nd Trimester', ];
				$terms[] = [ 'term_id' => $academic_year . ' 3rd Trimester',
				             'term'    => $academic_year . ' - 3rd Trimester', ];
			break;
			case 'quarter' :
				$terms[] = [ 'term_id' => $academic_year . ' 1st Quarter',
				             'term'    => $academic_year . ' - 1st Quarter', ];
				$terms[] = [ 'term_id' => $academic_year . ' 2nd Quarter',
				             'term'    => $academic_year . ' - 2nd Quarter', ];
				$terms[] = [ 'term_id' => $academic_year . ' 3rd Quarter',
				             'term'    => $academic_year . ' - 3rd Quarter', ];
				$terms[] = [ 'term_id' => $academic_year . ' 4th Quarter',
				             'term'    => $academic_year . ' - 4th Quarter', ];
			break;
		}
		if( $summer_sessions ) {
			$terms[] = [ 'term_id' => $academic_year . ' Summer Session',
			             'term'    => $academic_year . ' - Summer Session', ];
		}
	}

	return $terms;
}

function syntric_get_course_teachers( $course ) {
	$classes         = get_field( 'syntric_classes', 'option' );
	$course_teachers = [];
	if( $classes ) {
		foreach( $classes as $class ) {
			if( $course[ 'course' ] == $class[ 'course' ] ) {
				$course_teachers[ $class[ 'teacher' ] ] = get_user_by( 'ID', $class[ 'teacher' ] );
			}
		}
	}

	return $course_teachers;
}

/*************************************** Teachers Page *****************************************/
// todo: Look at this...was only getting published pages, quickly changed to all...is that correct?
function syntric_get_teachers_page() {
	$academics_page = syntric_get_academics_page();
	$teachers_page  = get_page_by_title( 'Teachers' );
	$args           = [
		'post_status' => 'publish',
		'post_type'   => 'page',
		'post_title'  => 'Teachers',
		'post_name'   => syntric_sluggify( 'Teachers' ),
		'post_parent' => $academics_page -> ID,
	];
	if( $teachers_page instanceof WP_Post ) {
		$args[ 'ID' ] = $teachers_page -> ID;
	}
	$teachers_page_id = wp_insert_post( $args );
	update_post_meta( $teachers_page_id, '_wp_page_template', 'teachers.php' );

	return get_post( $teachers_page_id );
	/*$post_args = [ 'numberposts'  => - 1,
	               'post_type'    => 'page',
	               'post_status'  => [ 'publish',
	                                   'draft',
	                                   'future',
	                                   'pending',
	                                   'private', ],
	               'meta_key'     => '_wp_page_template',
	               'meta_value'   => 'teachers.php',
	               'meta_compare' => '=', ];
	$posts     = get_posts( $post_args );
	if( 0 == count( $posts ) ) {
		// create and return a new teachers page
		$post_id = syntric_create_teachers_page();
		$post    = get_post( $post_id, OBJECT );

		return $post;
	} elseif( 1 < count( $posts ) ) {
		// Children test
		// ...more than one teachers pages exist...determine which takes precedence, migrate children, trash extras, return winner
		// todo: test this
		$has_children       = [];
		$has_not_children   = [];
		$post_children_args = [ 'numberposts' => - 1,
		                        'post_status' => [ 'publish',
		                                           'draft',
		                                           'future',
		                                           'pending',
		                                           'private', ],
		                        'post_parent' => 0,
		                        // change for each iteration
		];
		foreach( $posts as $post ) {
			$post_children_args[ 'post_parent' ] = $post -> ID;
			$post_children                       = get_posts( $post_children_args );
			if( count( $post_children ) ) {
				$has_children[] = $post;
			} else {
				$has_not_children[] = $post;
			}
		}
		if( 1 == count( $has_children ) ) {
			if( count( $has_not_children ) ) {
				syntric_trash_pages( $has_not_children );
			}

			return $has_children[ 0 ];
		}
		// Published test
		// ...if only one post_status is published, return it
		$is_published     = [];
		$is_not_published = [];
		foreach( $posts as $post ) {
			if( 'publish' == $post -> post_status ) {
				$is_published[] = $post;
			} else {
				$is_not_published[] = $post;
			}
		}
		if( 1 == count( $is_published ) ) {
			if( count( $is_not_published ) ) {
				syntric_trash_pages( $is_not_published );
			}

			return $is_published[ 0 ];
		}
		// ran out of tests...will return first teachers page
		// todo: should send an error or notice that there are more than one teachers pages
	}

	return $posts[ 0 ];*/
}

function syntric_get_department_page( $department_id ) {
	if( have_rows( 'syntric_departments', 'option' ) ) {
		while( have_rows( 'syntric_departments', 'option' ) ) {
			the_row();
			if( get_sub_field( 'id' ) == $department_id ) {
				return get_sub_field( 'department_page' );
			}
		}
	}

	return 0;
}

function syntric_create_teachers_page() {
	$academics_page    = get_page_by_title( 'Academics' );
	$academics_page_id = ( $academics_page instanceof WP_Post ) ? $academics_page -> ID : 0;
	$args              = [ 'post_type'   => 'page',
	                       'post_title'  => 'Teachers',
	                       'post_name'   => 'teachers',
	                       'post_author' => get_current_user_id(),
	                       'post_parent' => $academics_page_id,
	                       'post_status' => 'publish', ];
	$teachers_page_id  = wp_insert_post( $args );
	update_post_meta( $teachers_page_id, '_wp_page_template', 'teachers.php' );

	return $teachers_page_id;
}

function syntric_update_teachers_page( $post_id ) {
	$teachers_page = get_post( $post_id, OBJECT );
	if( $teachers_page instanceof WP_Post ) {
		$args             = [ 'ID'         => $post_id,
		                      'post_title' => 'Teachers',
		                      'post_name'  => 'teachers', ];
		$teachers_page_id = wp_update_post( $args );
		update_post_meta( $teachers_page_id, '_wp_page_template', 'teachers.php' );

		return $teachers_page_id;
	}

	return false;
}

/*************************************** Teacher *****************************************/

/*************************************** Teacher Page *****************************************/

/*************************************** Teacher Pages (Teacher page + all descendants) *****************************************/

/*************************************** User Pages (pages where user is author) *****************************************/
/**
 * Move all pages into the trash where user is the author
 *
 * @param $user_id
 */
function syntric_trash_user_pages( $user_id ) {
	$user_pages = get_posts( [ 'numberposts' => - 1,
	                           'post_status' => [ 'publish', 'draft', 'future', 'pending', 'private' ],
	                           'post_author' => $user_id,
	                           'post_type'   => 'page',
	                           'fields'      => 'ID', ] );
	if( $user_pages ) {
		foreach( $user_pages as $user_page ) {
			wp_trash_post( $user_page -> ID );
		}
	}
}

/**
 * Permanently delete all pages where user is the author
 *
 * @param $user_id
 */
function syntric_delete_user_pages( $user_id ) {
	$user_pages = get_posts( [ 'numberposts' => - 1,
	                           'post_status' => [ 'publish', 'draft', 'future', 'pending', 'private' ],
	                           'post_author' => $user_id,
	                           'post_type'   => 'page',
	                           'fields'      => 'ID', ] );
	if( $user_pages ) {
		foreach( $user_pages as $user_page ) {
			wp_delete_post( $user_page -> ID, true );
		}
	}
}

function syntric_delete_user_content( $user_id ) {
	$user_content = get_posts( [ 'numberposts' => - 1,
	                             'post_status' => [ 'publish', 'draft', 'future', 'pending', 'private' ],
	                             'post_author' => $user_id,
	                             'post_type'   => 'any',
	                             'fields'      => 'ID',
	                             'orderby'     => 'menu_order',
	                             'order'       => 'DESC' ] );
	if( $user_content ) {
		foreach( $user_content as $content ) {
			$post_type = get_post_type( $content -> ID );
			switch( $post_type ) :
				case 'page' :
				case 'post' :
					wp_delete_post( $content -> ID );
				break;
				case 'attachment' :
					wp_delete_attachment( $content -> ID );
				break;
			endswitch;
		}
	}
}

/**
 *
 *
 * Teachers
 *
 *
 */
function syntric_get_teachers() {
	$teachers = get_users( [ 'meta_key'   => 'last_name',
	                         'meta_query' => [ [ 'key'   => 'syntric_user_is_teacher',
	                                             'value' => 1, ], ],
	                         'orderby'    => 'meta_value', ] );

	return $teachers;
}

// todo: eliminate this function and convert all teacher classes by page to classes with teacher_id
function syntric_get_teachers_classes() {
	$teacher_pages = syntric_get_teacher_pages();
	$classes       = [];
	foreach( $teacher_pages as $teacher_page ) {
		$teacher_classes = syntric_get_teacher_classes( $teacher_page -> ID );
		$classes         = array_merge( $classes, $teacher_classes );
	}

	return $classes;
}

function syntric_get_teachers_class_pages( $teacher_id = 0, $include_trash = false ) {
	$teacher_ids = [];
	if( $teacher_id ) {
		$teacher_ids[] = $teacher_id;
	} else {
		$teachers = syntric_get_teachers();
		foreach( $teachers as $teacher ) {
			if( $teacher instanceof WP_User ) {
				$teacher_ids[] = $teacher -> ID;
			}
		}
	}

	$post_statuses = [ 'publish',
	                   'draft',
	                   'future',
	                   'pending',
	                   'private', ];
	if( $include_trash ) {
		$post_statuses[] = 'trash';
	}
	$args  = [ 'numberposts' => - 1,
	           'post_type'   => 'page',
	           'post_status' => $post_statuses,
	           'meta_query'  => [ [ 'key'     => 'syntric_page_class_teacher',
	                                'value'   => $teacher_ids,
	                                'compare' => 'in', ],
	                              [ 'key'     => '_wp_page_template',
	                                'value'   => 'class.php',
	                                'compare' => '=', ], ], ];
	$posts = get_posts( $args );

	return $posts;
}

/**
 *
 *
 * Teacher
 *
 *
 */

function syntric_get_teacher( $user_id ) {
	if( ! isset( $user_id ) || ! is_numeric( $user_id ) ) {
		return false;
	}
	$user = get_user_by( 'ID', $user_id );
	if( $user instanceof WP_User ) {
		$is_teacher = get_user_meta( $user -> ID, 'syntric_user_is_teacher' );
		if( $is_teacher ) {
			return $user;
		}
	}

	return 0;
}

function syntric_get_teacher_pages( $include_trash = false ) {
	$post_statuses = [ 'publish',
	                   'draft',
	                   'future',
	                   'pending',
	                   'private', ];
	if( $include_trash ) {
		$post_statuses[] = 'trash';
	}
	$post_args = [ 'numberposts'  => - 1,
	               'post_type'    => 'page',
	               'post_status'  => $post_statuses,
	               'meta_key'     => '_wp_page_template',
	               'meta_value'   => 'teacher.php',
	               'meta_compare' => '=',
	               'orderby'      => 'post_title',
	               'order'        => 'ASC', ];
	$posts     = get_posts( $post_args );

	return $posts;
}

/**
 * Move a teacher's page and all pages under it into the trash
 *
 * @param $user_id
 */
function syntric_trash_teacher_page( $user_id, $permanently = 0 ) {
	$teacher_page = syntric_get_teacher_page( $user_id ); // returns WP_Post object
	if( $teacher_page instanceof WP_Post ) {
		// Delete all children of the teacher page to be trashed, not just class pages
		/*$teacher_page_children = get_posts( [ 'numberposts' => - 1,
		                                      'post_status' => [ 'publish', 'draft', 'future', 'pending', 'private' ],
		                                      'post_parent' => $teacher_page -> ID,
		                                      'fields'      => 'ID', ] );
		if( $teacher_page_children ) {
			foreach( $teacher_page_children as $teacher_page_child ) {
				wp_trash_post( $teacher_page_child -> ID );
			}
		}*/
		$res = syntric_trash_descendant_pages( $teacher_page -> ID );
		if( $res ) {
			wp_delete_post( $teacher_page -> ID, false );
			update_field( 'syntric_user_teacher_page', null, 'user_' . $user_id );
		}

		return 1;
	}

	return 0;
}

/**
 * Permanently delete a teacher's page and all pages under it
 *
 * @param $user_id
 */
function syntric_delete_teacher_pages( $user_id ) {
	$teacher_page = syntric_get_teacher_page( $user_id ); // returns WP_Post object
	if( $teacher_page instanceof WP_Post ) {
		// Delete all children of the teacher page to be trashed, not just class pages
		$teacher_page_children = get_posts( [ 'numberposts' => - 1,
		                                      'post_status' => [ 'publish', 'draft', 'future', 'pending', 'private' ],
		                                      'post_parent' => $teacher_page -> ID,
		                                      'post_type'   => 'page',
		                                      'fields'      => 'ID', ] );
		if( $teacher_page_children ) {
			foreach( $teacher_page_children as $teacher_page_child ) {
				wp_delete_post( $teacher_page_child -> ID, true );
			}
		}
		wp_delete_post( $teacher_page -> ID, true );
		update_field( 'syntric_user_teacher_page', null, 'user_' . $user_id );
	}
}

/**
 * Get a teacher page or pages as defined by a page using the Teacher template and custom field teacher page -> teacher
 *
 * @param      $user_id
 * @param bool $include_trash
 *
 * @return WP_Post/int
 */
function syntric_get_teacher_page( $user_id, $include_trash = false ) {
	$post_statuses = [ 'publish',
	                   'draft',
	                   'future',
	                   'pending',
	                   'private', ];
	/*if( $include_trash ) {
		$post_statuses[] = 'trash';
	}*/
	// template + teacher page -> teacher
	$args         = [ 'numberposts' => - 1,
	                  'post_type'   => 'page',
	                  'post_status' => $post_statuses,
	                  'meta_query'  => [ 'relation' => 'AND',
	                                     [ 'key'     => '_wp_page_template',
	                                       'value'   => 'teacher.php',
	                                       'compare' => '=', ],
	                                     [ 'key'     => 'syntric_teacher_page_teacher',
	                                       'value'   => $user_id,
	                                       'compare' => '=', ],
	                  ],
	];
	$teacher_page = get_posts( $args );
	if( ! count( $teacher_page ) ) {
		return;
	}

	return syntric_heal_teacher_page( $user_id, $teacher_page );
}

/**
 * @param $user_id
 * @param $teacher_page array of WP_Post
 *
 * @return array|int|null|\WP_Post
 */
function syntric_heal_teacher_page( $user_id, $teacher_pages ) {
	if( ( is_array( $teacher_pages ) && 0 == count( $teacher_pages ) ) || ( ! is_array( $teacher_pages ) && ! $teacher_pages instanceof WP_Post ) ) {
		return;
	}
	$user_cf           = get_field( 'syntric_user', 'user_' . $user_id );
	$user_teacher_page = $user_cf[ 'teacher_page' ];
	$teacher_page      = 0;
	if( 1 == count( $teacher_pages ) ) {
		$teacher_page = $teacher_pages[ 0 ];
	} elseif( 1 < count( $teacher_pages ) ) {
		foreach( $teacher_pages as $_teacher_page ) {
			// Teacher is associated with user record
			if( $user_teacher_page -> ID == $_teacher_page -> ID ) {
				$teacher_page = $_teacher_page;
			} else {
				$res = syntric_trash_descendant_pages( $_teacher_page -> ID );
				if( $res ) {
					wp_trash_post( $_teacher_page -> ID );
				}
			}
		}
	}

	return;
	if( $teacher_page instanceof WP_Post ) {
		$teachers_page = syntric_get_teachers_page(); // todo: make sure this returns a teachers page
		$user          = get_user_by( 'ID', $user_id );
		wp_update_post( [
			'ID'          => $teacher_page -> ID,
			'post_title'  => $user -> first_name . ' ' . $user -> last_name,
			'post_name'   => syntric_sluggify( $user -> first_name . ' ' . $user -> last_name ),
			'post_author' => $user_id,
			'post_parent' => $teachers_page -> ID,
		] );
		update_post_meta( $teacher_page -> ID, '_wp_page_template', 'teacher.php' );
		update_field( 'syntric_teacher_page_teacher', $user_id, $teacher_page -> ID );
		update_field( 'syntric_user_teacher_page', $teacher_page -> ID, 'user_' . $user_id );

		return get_post( $teacher_page -> ID );
	} else {
		return;
	}
}

/**
 * Creates a teacher page, if necessary.  Takes care of all aspects of teacher page creation including dupe checking and
 * healing any data inconsistencies, filing under Teachers, even ensuring a Teachers page exists.  Just ask it and it will make it good.
 *
 * @param $user_id
 */
function syntric_save_teacher_page( $user_id ) {
	//slog( 'syntric_save_teacher_page' );
	$teacher_page = syntric_get_teacher_page( $user_id ); // this will be healed so no reason to update it
	//slog( $teacher_page );
	if( ! $teacher_page instanceof WP_Post ) {
		//slog( 'inside of conditional' );
		$user          = get_user_by( 'ID', $user_id );
		$teachers_page = syntric_get_teachers_page();
		$post_id       = wp_insert_post( [ 'post_status' => 'publish',
		                                   'post_type'   => 'page',
		                                   'post_title'  => $user -> first_name . ' ' . $user -> last_name,
		                                   'post_name'   => syntric_sluggify( $user -> first_name . ' ' . $user -> last_name ),
		                                   'post_author' => $user -> ID,
		                                   'post_parent' => $teachers_page -> ID, ] );
		update_post_meta( $post_id, '_wp_page_template', 'teacher.php' );
		update_field( 'syntric_teacher_page_teacher', $user_id, $post_id );
		update_field( 'syntric_user_teacher_page', $post_id, 'user_' . $user_id );
	}
	//slog( 'post_id' );
	//slog( $post_id );

	return $post_id;
}

function syntric_get_teacher_class_page( $class_id, $user_id, $include_trash ) {
	$post_statuses = [ 'publish',
	                   'draft',
	                   'future',
	                   'pending',
	                   'private', ];
	/*if( $include_trash ) {
		$post_statuses[] = 'trash';
	}*/
	// template + teacher page -> teacher
	$args       = [ 'numberposts' => - 1,
	                'post_type'   => 'page',
	                'post_status' => $post_statuses,
	                'meta_query'  => [ 'relation' => 'AND',
	                                   [ 'key'     => '_wp_page_template',
	                                     'value'   => 'class.php',
	                                     'compare' => '=', ],
	                                   [ 'key'     => 'syntric_class_page_class',
	                                     'value'   => $class_id,
	                                     'compare' => '=', ],
	                ],
	];
	$class_page = get_posts( $args );
	if( ! count( $class_page ) ) {
		return;
	}
	//return syntric_heal_teacher_class_page( $user_id, $class_id );
}

/*function syntric_heal_teacher_class_page( $class_id, $user_id ) {
	$user_cf           = get_field( 'syntric_user', 'user_' . $user_id );
	$user_teacher_page = $user_cf[ 'teacher_page' ][ 'value' ];
	$teacher_page      = 0;
	if( 1 == count( $teacher_pages ) ) {
		$teacher_page = $teacher_pages[ 0 ];
	} elseif( 1 < count( $teacher_pages ) ) {
		foreach( $teacher_pages as $_teacher_page ) {
			// Teacher is associated with user record
			if( $user_teacher_page == $_teacher_page -> ID ) {
				$teacher_page = $_teacher_page;
			} else {
				$res = syntric_trash_descendant_pages( $_teacher_page -> ID );
				if( $res ) {
					wp_trash_post( $_teacher_page -> ID );
				}
			}
		}
	}
	if( $teacher_page instanceof WP_Post ) {
		$teacher_page = syntric_get_teacher_page(); // todo: make sure this returns a teachers page
		$user         = get_user_by( 'ID', $user_id );
		wp_update_post( [
			'ID'          => $teacher_page -> ID,
			'post_title'  => $user -> first_name . ' ' . $user -> last_name,
			'post_name'   => syntric_sluggify( $user -> first_name . ' ' . $user -> last_name ),
			'post_author' => $user_id,
			'post_parent' => $teachers_page -> ID,
		] );
		update_post_meta( $teacher_page -> ID, '_wp_page_template', 'teacher.php' );
		update_field( 'syntric_teacher_page_teacher', $user_id, $teacher_page -> ID );
		update_field( 'syntric_user_teacher_page', $teacher_page -> ID, 'user_' . $user_id );

		return get_post( $teacher_page -> ID );
	} else {
		return;
	}
}*/

function syntric_save_teacher_class_page( $class_id, $user_id ) {
	$classes = get_field( 'syntric_classes', 'option' );
	if( $classes ) {
		foreach( $classes as $class ) {
			if( $class_id == $class[ 'id' ] ) {
				break;
			}
		}
	}
	if( ! $class ) {
		return;
	}
	$class_page = syntric_get_class_page( $class_id );
	if( ! $class_page instanceof WP_Post ) {
		$teacher_page = syntric_get_teacher_page( $user_id );
		$post_id      = wp_insert_post( [ 'post_status' => 'publish',
		                                  'post_type'   => 'page',
		                                  'post_title'  => $class[ 'course' ][ 'label' ],
		                                  'post_name'   => syntric_sluggify( $class[ 'course' ][ 'label' ] ),
		                                  'post_author' => $user_id,
		                                  'post_parent' => $teacher_page -> ID, ] );
		update_post_meta( $post_id, '_wp_page_template', 'class.php' );
		update_field( 'syntric_class_page_class', $class_id, $post_id );
		//update_field( 'syntric_user_teacher_page', $post_id, 'user_' . $user_id );
	}
	//slog( 'post_id' );
	//slog( $post_id );

	return $post_id;
}

function syntric_trash_teacher_class_page( $class_id, $user_id ) {
	$class_page = syntric_get_teacher_class_page( $user_id ); // returns WP_Post object
	if( $class_page instanceof WP_Post ) {
		// Delete all children of the teacher page to be trashed, not just class pages
		/*$teacher_page_children = get_posts( [ 'numberposts' => - 1,
		                                      'post_status' => [ 'publish', 'draft', 'future', 'pending', 'private' ],
		                                      'post_parent' => $teacher_page -> ID,
		                                      'fields'      => 'ID', ] );
		if( $teacher_page_children ) {
			foreach( $teacher_page_children as $teacher_page_child ) {
				wp_trash_post( $teacher_page_child -> ID );
			}
		}*/
		$res = syntric_trash_descendant_pages( $class_page -> ID );
		if( $res ) {
			wp_delete_post( $class_page -> ID, false );
			//update_field( 'syntric_user_teacher_page', null, 'user_' . $user_id );
		}

		return 1;
	}

	return 0;
}

function syntric_get_academics_page() {
	$academics_page = get_page_by_title( 'Academics' );
	$args           = [
		'post_status' => 'publish',
		'post_type'   => 'page',
		'post_title'  => 'Academics',
		'post_name'   => syntric_sluggify( 'Academics' ),
		'post_parent' => 0,
	];
	if( $academics_page instanceof WP_Post ) {
		$args[ 'ID' ] = $academics_page -> ID;
	}
	$academics_page_id = wp_insert_post( $args );

	return get_post( $academics_page_id );
}

/*************************************** Classes *****************************************/

/*************************************** Etc **********************************************/

function syntric_trash_descendant_pages( $post_id ) {
	$descendants = syntric_get_descendant_pages( $post_id );
	foreach( $descendants as $descendant ) {
		wp_delete_post( $descendant -> ID, false );
	}
}

function syntric_get_descendant_pages( $post_id, $descendants = [] ) {
	$_descendants = get_posts( [
			'numberposts'      => - 1,
			'post_type'        => 'page',
			'post_parent'      => $post_id,
			'suppress_filters' => false,
			'fields'           => 'ids',
		]
	);
	foreach( $_descendants as $_descendant ) {
		$_descendant_descendants = syntric_get_descendant_pages( $_descendant -> ID, $descendants );
		if( is_array( $_descendant_descendants ) && ! empty( $_descendant_descendants ) ) {
			$descendants = array_merge( $descendants, array_reverse( $_descendant_descendants ) );
		}
	}
	// merge in the direct descendants we found earlier
	$descendants = array_merge( $descendants, array_reverse( $_descendants ) );

	return $descendants;
}

/*function syntric_get_descendant_pages( $post_id ) {

	return get_posts( [ 'numberposts' => - 1,
	                    'post_type'   => 'page',
	                    'post_parent' => $post_id,
	] );
}*/

function syntric_get_organization_type() {
	$settings = get_field( 'syntric_settings', 'option' );

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

function syntric_get_schedules() {
	$school = syntric_get_school();

	return $school[ 'schedules' ];
}

function syntric_trash_pages( $posts ) {
	if( is_array( $posts ) ) {
		foreach( $posts as $post ) {
			if( $post instanceof WP_Post ) {
				wp_delete_post( $post -> ID, false );
			} else {
				wp_delete_post( $post[ 'ID' ], false );
			}
		}

		return true;
	} elseif( $posts instanceof WP_Post ) {
		wp_delete_post( $posts -> ID, false );

		return true;
	} elseif( is_int( $posts ) ) {
		wp_delete_post( $posts, false );

		return true;
	}

	return false;
}

/**
 * Get a page template
 *
 * @param $post_id
 *
 * @return string/bool returns the base name of the template file (teachers, teacher, class, course, department, etc) or false
 */
function syntric_get_page_template( $post_id ) {
	$post_id = syntric_resolve_post_id( $post_id );
	if( 'page' == get_post_type( $post_id ) ) {
		$page_meta = get_post_meta( $post_id );
		if( isset( $page_meta[ '_wp_page_template' ] ) ) {
			$page_template_path     = $page_meta[ '_wp_page_template' ];
			$page_template_path_arr = explode( '/', $page_template_path[ 0 ] );
			$page_template_file     = $page_template_path_arr[ count( $page_template_path_arr ) - 1 ];
			$page_template_file_arr = explode( '.', $page_template_file );
			$page_template          = $page_template_file_arr[ 0 ];

			return $page_template;
		}

		return 'default';
	}

	return false;
}

function syntric_is_default_page( $post_id ) {
	$post_id = syntric_resolve_post_id( $post_id );
	if( 'page' == syntric_get_page_template( $post_id ) ) {
		return true;
	}

	return false;
}

function syntric_is_teachers_page( $post_id ) {
	$post_id = syntric_resolve_post_id( $post_id );
	if( 'teachers' == syntric_get_page_template( $post_id ) ) {
		return true;
	}

	return false;
}

function syntric_is_teacher_page( $post_id ) {
	$post_id = syntric_resolve_post_id( $post_id );
	if( 'teacher' == syntric_get_page_template( $post_id ) ) {
		return true;
	}

	return false;
}

function syntric_is_class_page( $post_id ) {
	$post_id = syntric_resolve_post_id( $post_id );
	if( 'class' == syntric_get_page_template( $post_id ) ) {
		return true;
	}

	return false;
}

function syntric_is_course_page( $post_id ) {
	$post_id = syntric_resolve_post_id( $post_id );
	if( 'course' == syntric_get_page_template( $post_id ) ) {
		return true;
	}

	return false;
}

function syntric_is_department_page( $post_id ) {
	$post_id = syntric_resolve_post_id( $post_id );
	if( 'department' == syntric_get_page_template( $post_id ) ) {
		return true;
	}

	return false;
}

function syntric_is_schedule_page( $post_id ) {
	$post_id = syntric_resolve_post_id( $post_id );
	if( 'teacher' == syntric_get_page_template( $post_id ) ) {
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
	$slug = preg_replace( "/[^A-Za-z0-9\-]/", '', $slug );
	$slug = str_replace( '--', '-', $slug );
	$slug = str_replace( '---', '-', $slug );

	return strtolower( $slug );
}

function syntric_login_form() {
	$args = [ 'echo'           => false,
	          'remember'       => true,
	          'redirect'       => ( is_ssl() ? 'https://' : 'http://' ) . $_SERVER[ 'HTTP_HOST' ] . $_SERVER[ 'REQUEST_URI' ],
	          'form_id'        => 'loginform',
	          'id_username'    => 'user_login',
	          'id_password'    => 'user_pass',
	          'id_remember'    => 'rememberme',
	          'id_submit'      => 'wp-submit',
	          'label_username' => __( 'Username or Email Address' ),
	          'label_password' => __( 'Password' ),
	          'label_remember' => __( 'Remember Me' ),
	          'label_log_in'   => __( 'Log In' ),
	          'value_username' => '',
	          'value_remember' => false, ];

	return wp_login_form( $args );
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

/**
 * Outputs controls for editing page content from the front end if user is logged in and has permission to edit current page
 */
function syntric_editor() {
	?>
	<nav class="editor-toolbar navbar fixed-bottom navbar-expand-sm navbar-dark bg-dark">
		<a class="navbar-brand" href="#">Editor Toolbar</a>
		<button class="navbar-toggler collapsed" type="button" data-toggle="collapse" data-target="#navbarCollapse" aria-controls="navbarCollapse" aria-expanded="false" aria-label="Toggle navigation">
			<span class="navbar-toggler-icon"></span>
		</button>
		<div class="navbar-collapse collapse" id="navbarCollapse" style="">
			<ul class="navbar-nav mr-auto">
				<li class="nav-item active">
					<!-- <a class="nav-link" href="<?php echo $_SERVER[ 'REQUEST_URI' ] ?>?edit=1">Edit</a> -->
					<a class="nav-link" onclick="edit_post();">Edit</a>
				</li>
				<!--<li class="nav-item">
					<a class="nav-link" href="#">New page</a>
				</li>
				<li class="nav-item">
					<a class="nav-link disabled" href="#">Disabled</a>
				</li>-->
				<li class="nav-item dropup">
					<a class="nav-link dropdown-toggle" href="#" id="newDropup" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">New</a>
					<div class="dropdown-menu" aria-labelledby="newDropup">
						<a class="dropdown-item" href="http://www.google.com">Page</a>
						<!-- todo: iterate over post categories here instead of just using "Post" -->
						<a class="dropdown-item" href="http://www.yahoo.com">Post</a>
					</div>
				</li>
			</ul>
		</div>
	</nav>
	<script type="text/javascript">
		function edit_post() {
			var pageTitle = document.getElementsByClassName('page-title');
			//alert(pageTitle);
			console.log(pageTitle[0].innerText);
			var pageContent = document.getElementsByClassName('hentry');
			//alert(pageContent);
			console.log(pageContent[0].innerHTML);
		}
	</script>
	<?php

}

// front-end lists
function syntric_display_teachers() {
	if( is_admin() ) {
		return;
	}
	$page_template = get_page_template();
	if( 'teachers' != basename( $page_template, '.php' ) ) {
		return;
	}
	$teachers = syntric_get_teachers();
	if( ! $teachers ) {
		return;
	}
	echo '<h2>Teachers</h2>';
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
	foreach( $teachers as $teacher ) {
		$teacher_custom_fields = get_field( 'syntric_user', 'user_' . $teacher -> ID );
		$teacher_page          = syntric_get_teacher_page( $teacher -> ID );
		$phone                 = $teacher_custom_fields[ 'phone' ];
		$phone                 .= ( isset( $teacher_custom_fields[ 'ext' ] ) && ! empty( $teacher_custom_fields[ 'ext' ] ) ) ? ' x' . $teacher_custom_fields[ 'ext' ] : '';
		//$display_name = $teacher -> display_name;
		//$prefix       = get_field( 'syn_user_prefix', 'user_' . $teacher -> ID );
		//$full_name    = ( ! empty( $prefix ) ) ? $prefix . ' ' . $display_name : $display_name;
		//$title                  = get_field( 'syn_user_title', 'user_' . $teacher->ID );
		//$email                  = $teacher -> data -> user_email;
		//$phone                  = get_field( 'syn_user_phone', 'user_' . $teacher -> ID );
		//$ext                    = get_field( 'syn_user_extension', 'user_' . $teacher -> ID );
		//$ext                    = ( isset( $ext ) && ! empty( $ext ) && ! empty( $phone ) ) ? ' x' . $ext : '';
		//$phone                  = ( ! empty( $phone ) ) ? $phone . $ext : '';
		//$teacher_page           = syntric_get_teacher_page( $teacher -> ID );
		//$teacher_page_published = ( $teacher_page && 'publish' == $teacher_page -> post_status ) ? true : false;
		//$teacher_classes        = ( $teacher_page_published ) ? get_field( 'syn_classes', $teacher_page -> ID ) : false;
		//$courses                = get_field( 'syn_courses', 'option' );
		//if( $courses ) {
		//$courses = array_column( $courses, 'course', 'course_id' );
		//} else {
		//$courses = [];
		//}
		// build a csv list of classes
		//$class_array = [];
		//if( ! empty( $teacher_classes ) && ! empty( $courses ) ) {
		//foreach( $teacher_classes as $class ) {
		//	$class_page = syntric_get_teacher_class_page( $teacher -> ID, $class[ 'class_id' ] );
		//	if( $class_page instanceof WP_Post && 'publish' == $class_page -> post_status ) {
		//		$class_array[] = '<a href="' . get_the_permalink( $class_page -> ID ) . '">' . $class_page -> post_title . '</a>';
		//	} else {
		//$class_array[] = $class[ 'course' ];
		//}
		//}
		//}
		echo '<tr valign="top">';
		echo '<td class="teacher-name">';
		if( $teacher_page instanceof WP_Post && 'publish' == $teacher_page -> post_status ) {
			echo '<a href="' . get_the_permalink( $teacher_page -> ID ) . '">';
		}
		echo trim( $teacher_custom_fields[ 'prefix' ] . ' ' . $teacher -> display_name );
		if( $teacher_page instanceof WP_Post && 'publish' == $teacher_page -> post_status ) {
			echo '</a>';
		}
		echo '</td>';
		echo '<td class="teacher-email">';
		echo '<a href="mailto:' . antispambot( $teacher -> user_email, true ) . '" class="teacher-email" title="Email">' . antispambot( $teacher -> user_email ) . '</a>';
		echo '</td>';
		echo '<td class="teacher-phone">';
		if( ! empty( $phone ) ) {
			echo $phone;
		}
		echo '</td>';
		echo '<td class="classes">' . '</td>';
		echo '</tr>';
	}
	echo '</tbody>';
	echo '</table>';
}

function syntric_display_teacher_classes() {
	global $post;
	if( is_admin() ) {
		return;
	}
	//$page_template = get_page_template();
	$page_template = syntric_get_page_template( $post -> ID );
	if( 'teacher' != basename( $page_template, '.php' ) ) {
		return;
	}
	$teacher_page = get_field( 'syntric_teacher_page', $post -> ID );
	if( ! $teacher_page ) {
		return;
	}
	$teacher_id = $teacher_page[ 'teacher' ][ 'value' ];
	if( ! $teacher_id ) {
		return;
	}
	$classes = get_field( 'syntric_classes', 'option' );
	if( ! $classes ) {
		return;
	}
	echo '<h2>Classes</h2>';
	echo '<table class="teacher-classes-table">';
	echo '<thead>';
	echo '<tr>';
	echo '<th scope="col">Course</th>';
	echo '<th scope="col">Period</th>';
	echo '<th scope="col">Room</th>';
	echo '</tr>';
	echo '</thead>';
	echo '<tbody>';
	foreach( $classes as $class ) {
		if( $teacher_id == $class[ 'teacher' ][ 'value' ] ) {
			echo '<tr>';
			echo '<td class="course">' . $class[ 'course' ] . '</td>';
			echo '<td class="period">' . $class[ 'period' ] . '</td>';
			echo '<td class="room">' . $class[ 'room' ] . '</td>';
			echo '</tr>';
		}
	}
	echo '</tbody>';
	echo '</table>';
}

function syntric_display_department_courses() {
	global $post;
	if( is_admin() ) {
		return;
	}
	$page_template = get_page_template();
	if( 'department' != basename( $page_template, '.php' ) ) {
		return;
	}
	$department = get_field( 'syntric_department_page_department', $post -> ID );
	if( ! $department ) {
		return;
	}
	$courses = get_field( 'syntric_courses', 'option' );
	if( ! $courses ) {
		return;
	}
	$_courses = [];
	foreach( $courses as $course ) {
		//slog( $department );
		//slog( $course );
		if( $course[ 'department' ][ 'value' ] == $department[ 'value' ] ) {
			$_courses[] = $course;
		}
	}
	if( ! $_courses ) {
		echo '<p>No courses listed assigned to this department.</p>';

		return;
	}
	echo '<h2>' . $department[ 'label' ] . ' Courses</h2>';
	echo '<table class="department-courses-table">';
	echo '<thead>';
	echo '<tr>';
	echo '<th scope="col">Department</th>';
	echo '<th scope="col">Course</th>';
	echo '<th scope="col">Teachers</th>';
	echo '</tr>';
	echo '</thead>';
	echo '<tbody>';
	foreach( $_courses as $course ) {
		/*$course_teachers = syntric_get_course_teachers( $course );
		$teachers        = [];
		if( $course_teachers ) {
			foreach( $course_teachers as $course_teacher ) {
				$teacher_page  = syntric_get_teacher_page( $course_teacher -> ID );
				$teacher_label = ( $teacher_page instanceof WP_Post ) ? '<a href="' . get_the_permalink( $teacher_page -> ID ) . '">' : '';
				$teacher_label .= $course_teacher -> display_name;
				$teacher_label .= '</a>';
				$teachers[]    = $teacher_label;
			}
		}*/
		echo '<tr>';
		echo '<td>' . $course[ 'department' ][ 'label' ] . '</td>';
		echo '<td>' . $course[ 'course' ] . '</td>';
		//echo '<td>' . implode( ' / ', $teachers ) . '</td>';
		echo '<td>' . 'Teachers...' . '</td>';
		echo '</tr>';
	}
	echo '</tbody>';
	echo '</table>';

	return;
}

function syntric_display_schedules() {
	global $post;
	if( is_admin() ) {
		return;
	}
	$page_template = get_page_template();
	if( 'schedules' != basename( $page_template, '.php' ) ) {
		return;
	}
	$schedules_page       = get_field( 'syntric_schedules_page', $post -> ID );
	$schedules_to_display = $schedules_page[ 'schedules' ];
	$schedules            = get_field( 'syntric_schedules', 'option' );
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
	if( is_admin() ) {
		return;
	}
	$page_template = get_page_template();
	if( 'roster' != basename( $page_template, '.php' ) ) {
		return;
	}
	$roster = get_field( 'syntric_roster', $post -> ID );
	if( count( $roster[ 'people' ] ) ) {
		$people  = $roster[ 'people' ];
		$display = $roster[ 'display' ];
		echo '<h2>' . $roster[ 'title' ] . '</h2>';
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
				echo '<td>' . ucwords( syntric_get_page_template( $pending -> ID ) ) . '</td>';
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
			echo '<td>' . ucwords( syntric_get_page_template( $recent -> ID ) ) . '</td>';
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

function syntric_list_active_classes() {
	global $pagenow;
	if( ! is_admin() ) {
		return;
	}
	if( 'admin.php' == $pagenow && isset( $_REQUEST[ 'page' ] ) && 'syntric-classes' == $_REQUEST[ 'page' ] ) {
		$classes = get_field( 'syntric_classes', 'option' );
		if( $classes ) {
			echo '<table class="admin-list active-classes-list">';
			echo '<thead>';
			echo '<tr>';
			echo '<th class="teacher" scope="col">Teacher</th>';
			echo '<th class="course" scope="col">Course</th>';
			echo '<th class="period" scope="col">Period</th>';
			echo '<th class="room" scope="col">Room</th>';
			echo '<th class="actions" scope="col"></th>';
			echo '</tr>';
			echo '</thead>';
			echo '<tbody>';
			$class_counter = 0;
			foreach( $classes as $class ) {
				if( ! $class[ 'archive' ] ) {
					$teacher_page = syntric_get_teacher_page( $class[ 'teacher' ][ 'value' ] );
					echo '<tr>';
					echo '<td>' . str_replace( '(Teacher)', '', $class[ 'teacher' ][ 'label' ] );
					echo '<div class="row-actions"><span class="edit">';
					if( $teacher_page instanceof WP_Post ) {
						echo '<a href="/wp-admin/post.php?action=edit&post=' . $teacher_page -> ID . '">Edit Teacher Page</a> | <a href="' . get_permalink( $teacher_page -> ID ) . '">View Teacher Page</a> | <a href="user-edit.php?user_id=' . $class[ 'teacher' ][ 'value' ] . '">Edit User</a>';
					}
					echo '</span></div>';
					echo '</td>';
					echo '<td>' . $class[ 'course' ][ 'label' ];
					if( isset( $class[ 'class_page' ] ) && ! empty( $class[ 'class_page' ] ) ) {
						$class_page = get_post( $class[ 'class_page' ][ 'value' ] );
						if( $class_page instanceof WP_Post ) {
							echo '(' . $class_page -> post_status . ')';
							echo '<div class="row-actions"><span class="edit">';
							echo '<a href="/wp-admin/post.php?action=edit&post=' . $class_page -> ID . '">Edit Class Page</a> / <a href="' . get_permalink( $class_page -> ID ) . '">View Class Page</a>';
							echo '</span></div>';
						}
					}
					echo '</td>';
					echo '<td>' . $class[ 'period' ][ 'label' ] . '</td>';
					echo '<td>' . $class[ 'room' ][ 'label' ] . '</td>';
					//echo '<td>' . 'Edit | Archive | Delete' . '</td>';
					echo '<td>' . '<button id="archive_' . $class[ 'id' ] . '" type="button" class="archive-button button button-primary button-small">Archive</button> <button id="delete_' . $class[ 'id' ] . '" type="button" class="delete-button button button-primary button-small">Delete</button>' . '</td>';
					echo '</tr>';
					$class_counter ++;
				}
			}
			if( ! $class_counter ) {
				echo '<tr><td colspan="5">No active classes found.</td></tr>';
			}
			echo '</tbody>';
			echo '</table>';
		}
	}
	/*if( ! is_admin() ) {
		return;
	}
	$user_id    = get_current_user_id();
	$is_teacher = get_field( 'syn_user_is_teacher', 'user_' . $user_id );
	if( current_user_can( 'administrator' ) || current_user_can( 'editor' ) ) {
		$teachers = syntric_get_teachers();
	} elseif( $is_teacher ) {
		$teachers   = [];
		$teachers[] = syntric_get_teacher( $user_id );
	} else { // todo: fix when subscriber/contributor/non-teacher author are implemented, if ever
		$teachers = []; // nothing to show...
	}
	if( $teachers ) {
		$terms          = syntric_get_terms();
		$terms          = array_column( $terms, 'term', 'term_id' );
		$periods_active = get_field( 'syn_periods_active', 'option' );
		if( $periods_active ) {
			$periods = get_field( 'syn_periods', 'option' );
			$periods = array_column( $periods, 'period', 'period_id' );
		}
		$rooms_active = get_field( 'syn_rooms_active', 'option' );
		if( $rooms_active ) {
			$rooms = get_field( 'syn_rooms', 'option' );
			$rooms = array_column( $rooms, 'room', 'room_id' );
		}
		$courses = get_field( 'syn_courses', 'option' );
		$courses = array_column( $courses, 'course', 'course_id' );
		usort( $teachers, function( $a, $b ) {
			return strnatcmp( $a -> user_lastname . ', ' . $a -> user_firstname, $b -> user_lastname . ', ' . $b -> user_firstname );
		} );
		echo '<table class="admin-list">';
		echo '<thead>';
		echo '<tr>';
		echo '<th class="term" scope="col">Term</th>';
		echo '<th class="class" scope="col">Course</th>';
		if( $periods_active ) {
			echo '<th class="period" scope="col">Period</th>';
		}
		if( $rooms_active ) {
			echo '<th class="room" scope="col">Room</th>';
		}
		//echo '<th class="status" scope="col">Status</th>';
		echo '</tr>';
		echo '</thead>';
		$current_teacher = 0;
		$cols            = 2;
		$cols            = ( $periods_active ) ? $cols + 1 : $cols;
		$cols            = ( $rooms_active ) ? $cols + 1 : $cols;
		foreach( $teachers as $teacher ) {
			$teacher_page = syntric_get_teacher_page( $teacher -> ID );
			if( $teacher_page instanceof WP_Post || ( is_array( $teacher_page ) && count( $teacher_page ) ) ) {
				$teacher_page_status = $teacher_page -> post_status;
				if( ! $is_teacher && $teacher -> ID != $current_teacher ) {
					echo '<thead>';
					echo '<tr class="list-group-header">';
					echo '<td colspan="' . $cols . '">';
					if( $teacher_page ) :
						echo '<a href="/wp-admin/post.php?action=edit&post=' . $teacher_page -> ID . '">' . $teacher -> user_firstname . ' ' . $teacher -> user_lastname . '</a>';
					else :
						echo $teacher -> user_firstname . ' ' . $teacher -> user_lastname;
					endif;
					echo '</td>';
					echo '</tr>';
					echo '</thead>';
					$current_teacher = $teacher -> ID;
				}
				$classes = get_field( 'syn_classes', $teacher_page -> ID );
				//var_dump( $classes );
				echo '<tbody>';
				if( $classes ) {
					foreach( $classes as $class ) {
						$class_page = syntric_get_teacher_class_page( $teacher -> ID, $class[ 'class_id' ] );
						//if ( $class_page instanceof WP_Post ) {
						$period = ( $periods_active && isset( $class[ 'period' ] ) ) ? $periods[ $class[ 'period' ] ] : '';
						$room   = ( $rooms_active && isset( $class[ 'room' ] ) ) ? $rooms[ $class[ 'room' ] ] : '';
						echo '<tr>';
						echo '<td>' . $terms[ $class[ 'term' ] ] . '</td>';
						echo '<td>';
						if( $class_page instanceof WP_Post ) :
							$status = ( 'publish' == $class_page -> post_status ) ? '' : '<strong> — ' . $class_page -> post_status . '</strong>';
							echo '<a href="/wp-admin/post.php?action=edit&post=' . $class_page -> ID . '">' . $class_page -> post_title . '</a>' . ucwords( $status );
						else :
							//echo $class_page->post_title;
							echo $courses[ $class[ 'course' ] ];
						endif;
						echo '</td>';
						if( $periods_active ) {
							//$period_label = ( $period ) ? 'Period ' . $period : '';
							echo '<td class="period">' . $period . '</td>';
						}
						if( $rooms_active ) {
							//$room_label = ( $room ) ? 'Room ' . $room : '';
							echo '<td class="room">' . $room . '</td>';
						}
						echo '</tr>';
						//}
					}
				} else {
					echo '<tr>';
					echo '<td colspan="' . $cols . '">';
					echo 'No classes';
					echo '</td>';
					echo '</tr>';
				};
				echo '</tbody>';
			}
		}
		echo '</table>';
	} else {
		echo '<table class="admin-list">';
		echo '<tbody>';
		echo '<tr>';
		echo '<td>';
		echo 'There are no teachers or you do not have permission to view them';
		echo '</td>';
		echo '</tr>';
		echo '</tbody>';
		echo '</table>';*/
}

function syntric_list_archived_classes() {
	global $pagenow;
	if( ! is_admin() ) {
		return;
	}
	if( 'admin.php' == $pagenow && isset( $_REQUEST[ 'page' ] ) && 'syntric-classes' == $_REQUEST[ 'page' ] ) {
		$classes = get_field( 'syntric_classes', 'option' );
		if( $classes ) {
			echo '<table class="admin-list archived-classes-list">';
			echo '<thead>';
			echo '<tr>';
			echo '<th class="teacher" scope="col">Teacher</th>';
			echo '<th class="course" scope="col">Course</th>';
			echo '<th class="period" scope="col">Period</th>';
			echo '<th class="room" scope="col">Room</th>';
			echo '<th class="actions" scope="col"></th>';
			echo '</tr>';
			echo '</thead>';
			echo '<tbody>';
			$class_counter = 0;
			foreach( $classes as $class ) {
				if( $class[ 'archive' ] ) {
					$teacher_page = syntric_get_teacher_page( $class[ 'teacher' ][ 'value' ] );
					echo '<tr>';
					echo '<td>' . str_replace( '(Teacher)', '', $class[ 'teacher' ][ 'label' ] );
					echo '<div class="row-actions"><span class="edit">';
					if( $teacher_page instanceof WP_Post ) {
						echo '<a href="/wp-admin/post.php?action=edit&post=' . $teacher_page -> ID . '">Edit Teacher Page</a> | <a href="' . get_permalink( $teacher_page -> ID ) . '">View Teacher Page</a> | <a href="user-edit.php?user_id=' . $class[ 'teacher' ][ 'value' ] . '">Edit User</a>';
					}
					echo '</span></div>';
					echo '</td>';
					echo '<td>' . $class[ 'course' ][ 'label' ];
					if( isset( $class[ 'class_page' ] ) && ! empty( $class[ 'class_page' ] ) ) {
						$class_page = get_post( $class[ 'class_page' ][ 'value' ] );
						if( $class_page instanceof WP_Post ) {
							echo '(' . $class_page -> post_status . ')';
							echo '<div class="row-actions"><span class="edit">';
							echo '<a href="/wp-admin/post.php?action=edit&post=' . $class_page -> ID . '">Edit Class Page</a> / <a href="' . get_permalink( $class_page -> ID ) . '">View Class Page</a>';
							echo '</span></div>';
						}
					}
					echo '</td>';
					echo '<td>' . $class[ 'period' ][ 'label' ] . '</td>';
					echo '<td>' . $class[ 'room' ][ 'label' ] . '</td>';
					//echo '<td>' . 'Restore | Delete' . '</td>';
					echo '<td>' . '<button id="restore_' . $class[ 'id' ] . '" type="button" class="restore-button button button-primary button-small">Restore</button> <button id="delete_' . $class[ 'id' ] . '" type="button" class="delete-button button button-primary button-small">Delete</button>' . '</td>';
					echo '</tr>';
					$class_counter ++;
				}
			}
			if( ! $class_counter ) {
				echo '<tr><td colspan="5">No archived classes found.</td></tr>';
			}
			echo '</tbody>';
			echo '</table>';
		}
	}
	/*if( ! is_admin() ) {
		return;
	}
	$user_id    = get_current_user_id();
	$is_teacher = get_field( 'syn_user_is_teacher', 'user_' . $user_id );
	if( current_user_can( 'administrator' ) || current_user_can( 'editor' ) ) {
		$teachers = syntric_get_teachers();
	} elseif( $is_teacher ) {
		$teachers   = [];
		$teachers[] = syntric_get_teacher( $user_id );
	} else { // todo: fix when subscriber/contributor/non-teacher author are implemented, if ever
		$teachers = []; // nothing to show...
	}
	if( $teachers ) {
		$terms          = syntric_get_terms();
		$terms          = array_column( $terms, 'term', 'term_id' );
		$periods_active = get_field( 'syn_periods_active', 'option' );
		if( $periods_active ) {
			$periods = get_field( 'syn_periods', 'option' );
			$periods = array_column( $periods, 'period', 'period_id' );
		}
		$rooms_active = get_field( 'syn_rooms_active', 'option' );
		if( $rooms_active ) {
			$rooms = get_field( 'syn_rooms', 'option' );
			$rooms = array_column( $rooms, 'room', 'room_id' );
		}
		$courses = get_field( 'syn_courses', 'option' );
		$courses = array_column( $courses, 'course', 'course_id' );
		usort( $teachers, function( $a, $b ) {
			return strnatcmp( $a -> user_lastname . ', ' . $a -> user_firstname, $b -> user_lastname . ', ' . $b -> user_firstname );
		} );
		echo '<table class="admin-list">';
		echo '<thead>';
		echo '<tr>';
		echo '<th class="term" scope="col">Term</th>';
		echo '<th class="class" scope="col">Course</th>';
		if( $periods_active ) {
			echo '<th class="period" scope="col">Period</th>';
		}
		if( $rooms_active ) {
			echo '<th class="room" scope="col">Room</th>';
		}
		//echo '<th class="status" scope="col">Status</th>';
		echo '</tr>';
		echo '</thead>';
		$current_teacher = 0;
		$cols            = 2;
		$cols            = ( $periods_active ) ? $cols + 1 : $cols;
		$cols            = ( $rooms_active ) ? $cols + 1 : $cols;
		foreach( $teachers as $teacher ) {
			$teacher_page = syntric_get_teacher_page( $teacher -> ID );
			if( $teacher_page instanceof WP_Post || ( is_array( $teacher_page ) && count( $teacher_page ) ) ) {
				$teacher_page_status = $teacher_page -> post_status;
				if( ! $is_teacher && $teacher -> ID != $current_teacher ) {
					echo '<thead>';
					echo '<tr class="list-group-header">';
					echo '<td colspan="' . $cols . '">';
					if( $teacher_page ) :
						echo '<a href="/wp-admin/post.php?action=edit&post=' . $teacher_page -> ID . '">' . $teacher -> user_firstname . ' ' . $teacher -> user_lastname . '</a>';
					else :
						echo $teacher -> user_firstname . ' ' . $teacher -> user_lastname;
					endif;
					echo '</td>';
					echo '</tr>';
					echo '</thead>';
					$current_teacher = $teacher -> ID;
				}
				$classes = get_field( 'syn_classes', $teacher_page -> ID );
				//var_dump( $classes );
				echo '<tbody>';
				if( $classes ) {
					foreach( $classes as $class ) {
						$class_page = syntric_get_teacher_class_page( $teacher -> ID, $class[ 'class_id' ] );
						//if ( $class_page instanceof WP_Post ) {
						$period = ( $periods_active && isset( $class[ 'period' ] ) ) ? $periods[ $class[ 'period' ] ] : '';
						$room   = ( $rooms_active && isset( $class[ 'room' ] ) ) ? $rooms[ $class[ 'room' ] ] : '';
						echo '<tr>';
						echo '<td>' . $terms[ $class[ 'term' ] ] . '</td>';
						echo '<td>';
						if( $class_page instanceof WP_Post ) :
							$status = ( 'publish' == $class_page -> post_status ) ? '' : '<strong> — ' . $class_page -> post_status . '</strong>';
							echo '<a href="/wp-admin/post.php?action=edit&post=' . $class_page -> ID . '">' . $class_page -> post_title . '</a>' . ucwords( $status );
						else :
							//echo $class_page->post_title;
							echo $courses[ $class[ 'course' ] ];
						endif;
						echo '</td>';
						if( $periods_active ) {
							//$period_label = ( $period ) ? 'Period ' . $period : '';
							echo '<td class="period">' . $period . '</td>';
						}
						if( $rooms_active ) {
							//$room_label = ( $room ) ? 'Room ' . $room : '';
							echo '<td class="room">' . $room . '</td>';
						}
						echo '</tr>';
						//}
					}
				} else {
					echo '<tr>';
					echo '<td colspan="' . $cols . '">';
					echo 'No classes';
					echo '</td>';
					echo '</tr>';
				};
				echo '</tbody>';
			}
		}
		echo '</table>';
	} else {
		echo '<table class="admin-list">';
		echo '<tbody>';
		echo '<tr>';
		echo '<td>';
		echo 'There are no teachers or you do not have permission to view them';
		echo '</td>';
		echo '</tr>';
		echo '</tbody>';
		echo '</table>';*/
}

// quick nav
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

function syntric_columns( $start = 2, $end = 6 ) {
	for( $i = $start; $i <= $end; $i ++ ) {
		$width_pct = 100 / $i - 0.01;
		echo '<div class="d-flex flex-row" style="margin: 0 -5px;">';
		for( $j = 1; $j <= $i; $j ++ ) {
			echo '<div style="width: ' . $width_pct . '%; text-align: left; margin: 5px; background-color: white; font-size: 0.75rem;">';
			//echo '<p>Fixed width:<br>';
			//echo 'Full width:</p>';
			echo '<img src="http://master.localhost/uploads/2018/01/images/bt_012-100x100.jpg" class="img-fluid img-thumbnail" style="width: 100%">';
			echo '</div>';
		}
		echo '</div>';
	}
}

// Add schwag to the list tables
add_filter( 'views_edit-syntric_calendar', 'syntric_views_edit_syntric_calendar', 10, 1 );
function syntric_views_edit_syntric_calendar( $views ) {
	$views[ 'google_calendar' ] = '<a href="http://calendar.google.com" target="_blank">Go to Google Calendar</a>';

	return $views;
}