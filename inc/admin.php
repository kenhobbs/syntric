<?php
	
	/**
	 * Remove admin menu and submenu links by role
	 */
	
	add_action( 'admin_menu', 'syntric_admin_menu', 999 );
	function syntric_admin_menu() {
		return;
		// Remove for everyone
		remove_menu_page( 'link-manager.php' ); // Links
		remove_submenu_page( 'themes.php', 'theme-editor.php' );
		remove_submenu_page( 'plugins.php', 'plugin-editor.php' );
		// Remove for all but Syntric user
		if( syntric_current_user_can( 'syntric' ) ) {
			return;
		}
		if( ! syntric_current_user_can( 'syntric' ) && ! syntric_current_user_can( 'superadmin' ) ) {
			remove_submenu_page( 'edit.php', 'edit-tags.php?taxonomy=microblog' ); // Post microblog (taxonomy) - this is deprecated
			remove_menu_page( 'jetpack' ); // Jetpack
			remove_menu_page( 'edit-comments.php' ); // Comments
			remove_menu_page( 'tools.php' ); // Tools
			//remove_submenu_page( 'options-general.php', 'codepress-admin-columns' );
			remove_menu_page( 'edit.php?post_type=acf-field-group' ); // Custom Fields
			//remove_menu_page( 'wpmudev' ); // WPMU Dev
			remove_menu_page( 'wp-defender' ); // WP Defender
			remove_submenu_page( 'upload.php', 'wp-smush-bulk' );
		}
		// Remove for all but administrator and above
		if( ! syntric_current_user_can( 'administrator' ) ) {
			//remove_menu_page( 'users.php' ); // Users
			remove_submenu_page( 'index.php', 'update-core.php' );
			remove_menu_page( 'themes.php' ); // Appearance
			remove_menu_page( 'settings.php' ); // Settings
			//remove_menu_page( 'admin.php?page=gutenberg' ); // Gutenburg
		}
		// Remove for all but editor and above
		if( ! syntric_current_user_can( 'editor' ) ) {
			remove_submenu_page( 'edit.php', 'edit-tags.php?taxonomy=category' ); // Post category (taxonomy)
			remove_menu_page( 'users.php' ); // Users
			remove_menu_page( 'plugins.php' ); // Plugins
			remove_menu_page( 'edit.php?post_type=syntric_calendar' ); // Calendars
			remove_menu_page( 'edit.php?post_type=tinymcetemplates' ); // Templates
			remove_submenu_page( 'edit.php?post_type=page', 'nestedpages' );
		}
		// Remove for all but author
		if( ! syntric_current_user_can( 'author' ) ) {
			remove_menu_page( 'index.php' ); // Dashboard
			remove_menu_page( 'admin.php?page=syntric-organization' ); // Organization
			remove_menu_page( 'admin.php?page=syntric-jumbotrons' ); // Jumbotrons
			remove_menu_page( 'admin.php?page=syntric-google-maps' ); // Google Maps
			remove_menu_page( 'admin.php?page=syntric-social-media' ); // Social Media
			remove_menu_page( 'edit.php' ); // Posts
			// All that is left is Profile...
		}
	}
	
	/**
	 * Customize the admin bar
	 */
	add_action( 'admin_bar_menu', 'syntric_admin_bar', 999 );
	function syntric_admin_bar( $wp_admin_bar ) {
		// todo: Add targeted links to role specific resources. EG for Teachers - My Page, My Classes, etc..
		// Remove for everyone, everywhere
		$wp_admin_bar -> remove_node( 'wp-logo' );
		/**
		 * Site name menu - both frontend and admin
		 *
		 * In admin...
		 *
		 * On frontend..
		 * Original - Appearance + Themes + Widgets + Menus + Header
		 * Redux - Dashboard + Pages + Posts + Media + Organization + Calendars + Jumbotrons + Google Maps + Users + Comments + Customize + Widgets + Sidebars + Social Media
		 */
		if( is_admin() ) {
			$site_name_node          = $wp_admin_bar -> get_node( 'site-name' );
			$site_name_node -> title = esc_attr( get_bloginfo( 'name', 'display' ) );
			$wp_admin_bar -> add_node( $site_name_node );
		}
		if( ! is_admin() ) {
			// Remove all nodes from the admin bar
			$wp_admin_bar -> remove_node( 'site-name' );
			$wp_admin_bar -> remove_node( 'appearance' );
			$wp_admin_bar -> remove_node( 'themes' );
			$wp_admin_bar -> remove_node( 'widgets' );
			$wp_admin_bar -> remove_node( 'menus' );
			$wp_admin_bar -> remove_node( 'header' );
			$wp_admin_bar -> remove_node( 'search' );
			$wp_admin_bar -> remove_node( 'user-info' );
			$wp_admin_bar -> remove_node( 'view-site' );
			$wp_admin_bar -> remove_node( 'customize' );
			$wp_admin_bar -> remove_node( 'new-content' );
			$wp_admin_bar -> remove_node( 'comments' );
			//$wp_admin_bar->remove_node( 'my-account' );
			//$wp_admin_bar->remove_node( 'edit' );
			$wp_admin_bar -> remove_node( 'new-user' );
			$wp_admin_bar -> remove_node( 'new-post' );
			$wp_admin_bar -> remove_node( 'new-media' );
			$wp_admin_bar -> remove_node( 'new-page' );
			$wp_admin_bar -> remove_node( 'new-tinymcetemplates' );
			$wpab_nodes = [];
			// Admin menu
			$wpab_nodes[] = syntric_new_wp_admin_bar_node( '01admin', 'Admin', '', '#' );
			$wpab_nodes[] = syntric_new_wp_admin_bar_node( '010dashboard', 'Dashboard', '01admin', '/wp-admin/index.php' );
			$wpab_nodes[] = syntric_new_wp_admin_bar_node( '020pages', 'Pages', '01admin', '/wp-admin/edit.php?post_type=page&page=nestedpages' );
			$wpab_nodes[] = syntric_new_wp_admin_bar_node( '040posts', 'Posts', '01admin', '/wp-admin/edit.php' );
			$wpab_nodes[] = syntric_new_wp_admin_bar_node( '180media', 'Media', '01admin', '/wp-admin/upload.php' );
			if( syntric_current_user_can( 'syntric' ) ) {
				$wpab_nodes[] = syntric_new_wp_admin_bar_node( '170comments', 'Comments', '01admin', '/wp-admin/edit-comments.php' );
				$wpab_nodes[] = syntric_new_wp_admin_bar_node( '290fieldgroups', 'Field Groups', '01admin', '/wp-admin/edit.php?post_type=acf-field-group' );
			}
			if( syntric_current_user_can( 'administrator' ) ) {
				$wpab_nodes[] = syntric_new_wp_admin_bar_node( '240widgets', 'Widgets', '01admin', '/wp-admin/widgets.php' );
				$wpab_nodes[] = syntric_new_wp_admin_bar_node( '250plugins', 'Plugins', '01admin', '/wp-admin/plugins.php' );
				//$wpab_nodes[] = syntric_new_wp_admin_bar_node( '270sidebarswidgets', 'Sidebars & Widgets', '01admin', '/wp-admin/options-general.php?page=syntric-sidebars-widgets' );
				//$wpab_nodes[] = syntric_new_wp_admin_bar_node( '280google', 'Google', '01admin', '/wp-admin/options-general.php?page=syntric-google' );
			}
			if( syntric_current_user_can( 'editor' ) ) {
				//$wpab_nodes[]       = syntric_new_wp_admin_bar_node( '060organization', syntric_get_organization_type_label(), '01admin', '/wp-admin/admin.php?page=syntric-organization' );
				//$wpab_nodes[]       = syntric_new_wp_admin_bar_node( '070organizations', syntric_get_organizations_type_label(), '01admin', '/wp-admin/admin
				//.php?page=syntric-organizations' );
				/*if( $departments_active ) {
					$wpab_nodes[] = syntric_new_wp_admin_bar_node( '080departments', 'Departments', '01admin', '/wp-admin/admin.php?page=syntric-departments' );
				}
				if( $buildings_active ) {
					$wpab_nodes[] = syntric_new_wp_admin_bar_node( '090buildings', 'Buildings', '01admin', '/wp-admin/admin.php?page=syntric-buildings' );
				}
				if( $rooms_active ) {
					$wpab_nodes[] = syntric_new_wp_admin_bar_node( '100rooms', 'Rooms', '01admin', '/wp-admin/admin.php?page=syntric-rooms' );
				}
				if( $periods_active ) {
					$wpab_nodes[] = syntric_new_wp_admin_bar_node( '110periods', 'Periods', '01admin', '/wp-admin/admin.php?page=syntric-periods' );
				}*/
				$wpab_nodes[] = syntric_new_wp_admin_bar_node( '120courses', 'Courses', '01admin', '/wp-admin/admin.php?page=syntric-courses' );
				$wpab_nodes[] = syntric_new_wp_admin_bar_node( '130classes', 'Classes', '01admin', '/wp-admin/admin.php?page=syntric-classes' );
				$wpab_nodes[] = syntric_new_wp_admin_bar_node( '140calendars', 'Calendars', '01admin', '/wp-admin/edit.php?post_type=syntric_calendar' );
				$wpab_nodes[] = syntric_new_wp_admin_bar_node( '150jumbotrons', 'Jumbotrons', '01admin', '/wp-admin/edit.php?post_type=syntric_jumbotron' );
				$wpab_nodes[] = syntric_new_wp_admin_bar_node( '230customizer', 'Customizer', '01admin', '/wp-admin/customize.php' );
				$wpab_nodes[] = syntric_new_wp_admin_bar_node( '210users', 'Users', '01admin', '/wp-admin/users.php' );
			}
			$wpab_nodes[] = syntric_new_wp_admin_bar_node( '02newcontent', 'New', '', '#' );
			$wpab_nodes[] = syntric_new_wp_admin_bar_node( '030newpage', 'New Page', '02newcontent', '/wp-admin/post-new.php?post_type=page' );
			$wpab_nodes[] = syntric_new_wp_admin_bar_node( '050newpost', 'New Post', '02newcontent', '/wp-admin/post-new.php' );
			if( syntric_current_user_can( 'syntric' ) ) {
				$wpab_nodes[] = syntric_new_wp_admin_bar_node( '300newfieldgroup', 'New Field Group', '02newcontent', '/wp-admin/post-new.php?post_type=acf-field-group' );
			}
			if( syntric_current_user_can( 'administrator' ) ) {
				$wpab_nodes[] = syntric_new_wp_admin_bar_node( '260newplugin', 'New Plugin', '02newcontent', '/wp-admin/plugin-install.php' );
			}
			if( syntric_current_user_can( 'editor' ) ) {
				$wpab_nodes[] = syntric_new_wp_admin_bar_node( '220newuser', 'New User', '02newcontent', '/wp-admin/user-new.php' );
			}
			sort( $wpab_nodes );
			foreach( $wpab_nodes as $wpab_node ) {
				$wp_admin_bar -> add_node( $wpab_node );
			}
			$edit_node = $wp_admin_bar -> get_node( 'edit' );
			if( $edit_node ) {
				$edit_node -> parent = 'top-secondary';
				$wp_admin_bar -> add_node( $edit_node );
			}
			$my_account_node          = $wp_admin_bar -> get_node( 'my-account' );
			$my_account_node -> title = str_replace( 'Howdy, ', '', $my_account_node -> title );
			$wp_admin_bar -> add_node( $my_account_node );
			$edit_profile_node          = $wp_admin_bar -> get_node( 'edit-profile' );
			$edit_profile_node -> title = str_replace( 'Edit My Profile', 'Profile', $edit_profile_node -> title );
			$wp_admin_bar -> add_node( $edit_profile_node );
			$logout_node          = $wp_admin_bar -> get_node( 'logout' );
			$logout_node -> title = str_replace( 'Log Out', 'Logout', $logout_node -> title );
			$wp_admin_bar -> add_node( $logout_node );
		}
		
		return;
		/**
		 * New Content nodes + menu
		 */
		//$new_content_node = $wp_admin_bar->get_node( 'new-content' );
		/*$wp_admin_bar->remove_node( 'new-user' );
		$wp_admin_bar->remove_node( 'new-post' );
		$wp_admin_bar->remove_node( 'new-media' );
		$wp_admin_bar->remove_node( 'new-page' );
		$wp_admin_bar->remove_node( 'new-tinymcetemplates' );*/
		/*$new_content_nodes = [];
		// Page
		$new_page_node         = new stdClass();
		$new_page_node->id     = 'new_page';
		$new_page_node->title  = 'Page';
		$new_page_node->parent = 'new-content';
		$new_page_node->href   = '/wp-admin/post-new.php?post_type=page';
		$new_page_node->group  = '';
		$new_page_node->meta   = [];
		$new_content_nodes[]   = $new_page_node;
		// Post
		$new_post_node         = new stdClass();
		$new_post_node->id     = 'new_post';
		$new_post_node->title  = 'Post';
		$new_post_node->parent = 'new-content';
		$new_post_node->href   = '/wp-admin/post-new.php';
		$new_post_node->group  = '';
		$new_post_node->meta   = [];
		$new_content_nodes[]   = $new_post_node;
		// Media
		$new_media_node         = new stdClass();
		$new_media_node->id     = 'new_media';
		$new_media_node->title  = 'Media';
		$new_media_node->parent = 'new-content';
		$new_media_node->href   = '/wp-admin/media-new.php';
		$new_media_node->group  = '';
		$new_media_node->meta   = [];
		$new_content_nodes[]    = $new_media_node;
		if ( syntric_current_user_can( 'editor' ) ) {
			// User
			$new_user_node         = new stdClass();
			$new_user_node->id     = 'new_user';
			$new_user_node->title  = 'User';
			$new_user_node->parent = 'new-content';
			$new_user_node->href   = '/wp-admin/user-new.php';
			$new_user_node->group  = '';
			$new_user_node->meta   = [];
			$new_content_nodes[]   = $new_user_node;
		}
		sort( $new_content_nodes );
		foreach ( $new_content_nodes as $new_content_node ) {
			$wp_admin_bar->add_node( $new_content_node );
		}*/
		/**
		 * Account menu
		 */
		/*$my_account_node        = $wp_admin_bar->get_node( 'my-account' );
		$my_account_node->title = str_replace( 'Howdy, ', '', $my_account_node->title );
		$wp_admin_bar->add_node( $my_account_node );
		$edit_profile_node        = $wp_admin_bar->get_node( 'edit-profile' );
		$edit_profile_node->title = str_replace( 'Edit My Profile', 'Profile', $edit_profile_node->title );
		$wp_admin_bar->add_node( $edit_profile_node );
		$logout_node        = $wp_admin_bar->get_node( 'logout' );
		$logout_node->title = str_replace( 'Log Out', 'Logout', $logout_node->title );
		$wp_admin_bar->add_node( $logout_node );*/
		// Remove comments link for everyone except Syntric
		/*if ( ! syntric_current_user_can( 'syntric' ) ) {
			$wp_admin_bar->remove_node( 'comments' );
		}*/
		// Move edit/view link to right side of admin bar
		/*if ( is_admin() ) {
			$view_node = $wp_admin_bar->get_node( 'view' );
			if ( $view_node ) {
				$view_node->parent = 'top-secondary';
				$wp_admin_bar->add_node( $view_node );
			}
		} else {
			$edit_node = $wp_admin_bar->get_node( 'edit' );
			if ( $edit_node ) {
				$edit_node->parent = 'top-secondary';
				$wp_admin_bar->add_node( $edit_node );
			}
		}*/
	}
	
	function syntric_new_wp_admin_bar_node( $id, $title, $parent = '', $href = '#', $group = '', $meta = [] ) {
		$wpab_node           = new stdClass();
		$wpab_node -> id     = $id;
		$wpab_node -> title  = $title;
		$wpab_node -> parent = $parent;
		$wpab_node -> href   = $href;
		$wpab_node -> group  = $group;
		$wpab_node -> meta   = $meta;
		
		return $wpab_node;
	}
	
	/**
	 * All admin menu customizations
	 *
	 * This must return true in order for the "menu_order" filter to work
	 */
	add_filter( 'custom_menu_order', 'syntric_custom_menu_order' );
	function syntric_custom_menu_order( $menu_order ) {
		return true;
	}
	
	/**
	 * Customize the admin menu order and submenus
	 */
	add_filter( 'menu_order', 'syntric_menu_order' );
	function syntric_menu_order( $menu_order ) {
		//global $submenu;
		$_menu_order = [ // Dashboard
		                 'index.php',
		                 // Pages
		                 'edit.php?post_type=page',
		                 'edit.php?post_type=page_nestedpages',
		                 'edit.php?page=nestedpages',
		                 'nestedpages',
		                 // My Pages
		                 'syntric-my-pages',
		                 // My Classes
		                 'syntric-my-classes',
		                 // Posts
		                 'edit.php',
		                 // School
		                 'syntric-school',
		                 // District
		                 'syntric-district',
		                 // County Office of Education
		                 'syntric-coe',
		                 // Organization
		                 'syntric-organization',
		                 // Calendars
		                 'edit.php?post_type=syntric_calendar',
		                 // Jumbotrons
		                 'syntric-jumbotrons',
		                 // Google Maps
		                 'syntric-google-maps',
		                 // Links
		                 'link-manager.php',
		                 // Comments
		                 'edit-comments.php',
		                 // Media
		                 'upload.php',
		                 // Social Media
		                 'syntric-social-media',
		                 // Templates
		                 'edit.php?post_type=tinymcetemplates',
		                 // Users
		                 'users.php',
		                 // Profile
		                 'profile.php',
		                 // Appearance
		                 'themes.php',
		                 // Plugins
		                 'plugins.php',
		                 // Tools
		                 'tools.php',
		                 // Settings
		                 'options-general.php',
		                 // Custom Fields
		                 'edit.php?post_type=acf-field-group',
		                 // Jetpack
		                 'jetpack', ];
		
		return $_menu_order;
	}
	
	// remove the Welcome to Wordpress dashboard panel
	remove_action( 'welcome_panel', 'wp_welcome_panel' );
	// disable default dashboard widgets
	add_action( 'admin_init', 'syntric_clear_dashboard' );
	function syntric_clear_dashboard() {
		global $wp_meta_boxes;
		global $pagenow;
		if( is_admin() && 'index.php' == $pagenow ) {
			//var_dump( $wp_meta_boxes );
			remove_meta_box( 'dashboard_incoming_links', 'dashboard', 'normal' );
			remove_meta_box( 'dashboard_plugins', 'dashboard', 'normal' );
			remove_meta_box( 'dashboard_primary', 'dashboard', 'side' );
			remove_meta_box( 'dashboard_secondary', 'dashboard', 'side' ); // is this really in the side?
			remove_meta_box( 'dashboard_quick_press', 'dashboard', 'side' );
			remove_meta_box( 'dashboard_recent_drafts', 'dashboard', 'side' );
			remove_meta_box( 'dashboard_recent_comments', 'dashboard', 'normal' );
			remove_meta_box( 'dashboard_right_now', 'dashboard', 'normal' );
			remove_meta_box( 'dashboard_activity', 'dashboard', 'normal' );
		}
	}
	
	// add new dashboard widgets
	add_action( 'wp_dashboard_setup', 'syntric_dashboard_setup' );
	function syntric_dashboard_setup() {
		$user               = wp_get_current_user();
		$user_custom_fields = get_field( 'syntric_user', 'user_' . $user -> ID );
		$is_teacher         = $user_custom_fields[ 'is_teacher' ];
		if( $is_teacher ) {
			// My Classes
			$my_classes_title = '<h1>Is Teacher</h1>';
			// My Pages
			
		} else {
			$my_classes_title = '<h1>Is Not Teacher</h1>';
		}
		// Pending posts and pages
		$pending_pages_title = ( get_field( 'syntric_user_is_teacher', 'user_' . get_current_user_id() ) ) ? 'My Pages Pending Approval' : 'Pages Pending Approval';
		//$pending_pages_title = 'Pages Pending Approval';
		add_meta_box( 'syntric_pending_pages_dashboard_widget', $pending_pages_title, 'syntric_list_pendings', 'dashboard', 'normal', 'core', [ 'post_type' => 'page' ] );
		$pending_posts_title = ( get_field( 'syntric_user_is_teacher', 'user_' . get_current_user_id() ) ) ? 'My Posts Pending Approval' : 'Posts Pending Approval';
		//$pending_posts_title = 'Posts Pending Approval';
		add_meta_box( 'syntric_pending_posts_dashboard_widget', $pending_posts_title, 'syntric_list_pendings', 'dashboard', 'normal', 'core', [ 'post_type' => 'post' ] );
		// Recently published posts and pages
		$recently_published_pages_title = ( get_field( 'syntric_user_is_teacher', 'user_' . get_current_user_id() ) ) ? 'My Recently Published Pages' : 'Recently Published Pages';
		//$recently_published_pages_title = 'Recently Published Pages';
		add_meta_box( 'syntric_recently_published_pages_dashboard_widget', $recently_published_pages_title, 'syntric_list_recently_published', 'dashboard', 'normal', 'core', [ 'post_type' => 'page' ] );
		$recently_published_posts_title = ( get_field( 'syntric_user_is_teacher', 'user_' . get_current_user_id() ) ) ? 'My Recently Published Posts' : 'Recently Published Posts';
		//$recently_published_posts_title = 'Recently Published Posts';
		add_meta_box( 'syntric_recently_published_posts_dashboard_widget', $recently_published_posts_title, 'syntric_list_recently_published', 'dashboard', 'normal', 'core', [ 'post_type' => 'post' ] );
		// Classes
		/*if( syntric_organization_is_school() ) {
			$class_list_title = ( get_field( 'syntric_user_is_teacher', 'user_' . get_current_user_id() ) ) ? 'My Classes' : 'Teachers + Classes';
			//$class_list_title = 'Teachers + Classes';
			add_meta_box( 'class_list_dashboard_widget', $class_list_title, 'syntric_list_classes', 'dashboard', 'normal', 'core' );
		}*/
	}
	
	/**
	 * User profile contact methods mods
	 */
	add_filter( 'user_contactmethods', 'syntric_remove_user_profile_fields' );
	function syntric_remove_user_profile_fields( $fields ) {
		unset( $fields[ 'aim' ] );
		unset( $fields[ 'jabber' ] );
		unset( $fields[ 'yim' ] );
		
		return $fields;
	}
	
	// Set stylesheet in TinyMCE editor
	add_action( 'admin_init', 'syntric_add_editor_stylesheet' );
	function syntric_add_editor_stylesheet() {
		$editor_stylesheet = syntric_get_theme_stylesheet_uri();
		add_editor_style( $editor_stylesheet );
	}
	
	/**
	 * Enqueue admin stylesheets and javascript
	 *
	 * $hook is the current admin page filename (same as global $pagenow)
	 */
	add_action( 'admin_enqueue_scripts', 'syntric_enqueue_admin_scripts' );
	function syntric_enqueue_admin_scripts( $hook ) {
		wp_enqueue_style( 'google-fonts-stylesheet', '//fonts.googleapis.com/css?family=Roboto:300,400:500:700' );
		wp_enqueue_script( 'fontawesome', 'https://use.fontawesome.com/f45398b257.js', null, '5.0.1', false );
		wp_enqueue_style( 'syntric-admin-stylesheet', get_template_directory_uri() . '/assets/css/syntric-admin.min.css' );
		wp_enqueue_script( 'syntric-admin', get_template_directory_uri() . '/assets/js/syntric-admin.min.js', 'jquery', null, true );
	}
	
	/**
	 * Returns the full URI to the theme stylesheet
	 *
	 * Checks to see if a .css file exists corresponding the the server.http_host value (eg /path/to/master.syntric.com.min.css) and if so
	 * returns it's URI.  If not return the default stylesheet (/path/to/syntric.min.css). Returns un-minified version if on dev server (xxxx.localhost).
	 *
	 * @return string Full URI to a theme stylesheet
	 */
	function syntric_get_theme_stylesheet_uri() {
		$http_host           = $_SERVER[ 'HTTP_HOST' ];
		$site_stylesheet_uri = get_template_directory_uri() . '/assets/css/' . $http_host . '.min.css';
		//if ( ! file_exists( $site_stylesheet_uri ) ) {
		//return get_template_directory_uri() . '/syntric.com.min.css';
		//}
		return $site_stylesheet_uri;
	}
	
	if( isset( $_GET[ 'reset_roles' ] ) && '555ajaj' == $_GET[ 'reset_roles' ] ) {
		if( ! function_exists( 'populate_roles' ) ) {
			require_once( ABSPATH . 'wp-admin/includes/schema.php' );
		}
		populate_roles();
	}
	
	// Disabled b/c Branda does it
	
	// Remove the Help tab at the top right of the main admin frame
	//add_action( 'admin_head', 'syntric_remove_admin_help_tab' );
	function syntric_remove_admin_help_tab() {
		$screen = get_current_screen();
		$screen -> remove_help_tabs();
	}
	
	// Hide Screen options tab at the top right for less than editor
	//add_filter( 'screen_options_show_screen', 'syntric_screen_options_show_screen' );
	function syntric_screen_options_show_screen() {
		return syntric_current_user_can( 'editor' );
	}
	
	// Set admin color for all users, overriding their options
	add_action( 'wp_login', 'syntric_set_admin_color', 10, 2 );
	function syntric_set_admin_color( $user_login, $user ) {
		if( $user instanceof WP_User ) {
			$args = [
				'ID'          => $user -> ID,
				'admin_color' => 'purple',
			];
			wp_update_user( $args );
		}
	}
	
	/**
	 * Control appearance of bulk actions in WP admin by role
	 */
	//add_action( 'current_screen', 'syntric_remove_bulk_actions' );
	function syntric_remove_bulk_actions() {
		if( ! is_admin() ) {
			return;
		}
		/*global $my_admin_page;
				$screen = get_current_screen();
				if ( ! syntric_current_user_can( 'editor' ) ) {
			add_filter( 'bulk_actions-edit-post', '__return_empty_array' );
			add_filter( 'bulk_actions-edit-page', '__return_empty_array' );
			add_filter( 'bulk_actions-upload', '__return_empty_array' );
		}*/
	}