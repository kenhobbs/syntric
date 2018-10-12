<?php
/**
 * Register settings via Customizer API.
 */
add_action( 'customize_register', 'syn_customize_register' );
function syn_customize_register( $wp_customize ) {
	$wp_customize->get_setting( 'blogname' )->transport         = 'postMessage';
	$wp_customize->get_setting( 'blogdescription' )->transport  = 'postMessage';
	$wp_customize->get_setting( 'header_textcolor' )->transport = 'postMessage';
	/**
	 * Create a customizer section
	 */
	/*$wp_customize->add_panel(
		'syntric_theme_panel',
		array(
			'title'       => __( 'Appearance', 'syntric' ),
			'priority'  => 159
		)
	);*/
	$wp_customize->add_section( 'syntric_theme_section_site_width', array( 'title'      => __( 'Site Width', 'syntric' ),
	                                                                       'capability' => 'edit_theme_options', 'description' => __( 'Configure the site width', 'syntric' ),
	                                                                       'priority'   => 160, //'panel'         => 'syntric_theme_panel'
		) );
	$wp_customize->add_section( 'syntric_theme_section_colors', array( 'title'       => __( 'Colors', 'syntric' ), 'capability' => 'edit_theme_options',
	                                                                   'description' => __( 'Configure the site colors', 'syntric' ), 'priority' => 161,
	                                                                   //'panel'         => 'syntric_theme_panel'
		) );
	/**
	 * Create a setting for nav container width
	 */
	/*$wp_customize->add_setting(
		'syntric_nav_container_type', array(
			'default'           => 'container-fluid',
			'type'              => 'theme_mod',
			'sanitize_callback' => 'sanitize_text_field',
			'capability'        => 'edit_theme_options',
		)
	);*/
	/**
	 * Create options for nav container width
	 */
	/*$wp_customize->add_control(
		new WP_Customize_Control(
			$wp_customize,
			'syntric_nav_container_type', array(
				'label'       => __( 'Primary Navigation Width', 'syntric' ),
				'description' => __( "Set the width of the primary navigation.", 'syntric' ),
				'section'     => 'syntric_theme_panel',
				'settings'    => 'syntric_nav_container_type',
				'type'        => 'select',
				'choices'     => array(
					'container'       => __( 'Fixed Width', 'syntric' ),
					'container-fluid' => __( 'Full Width', 'syntric' ),
				),
				'priority'    => '10',
			)
		)
	);*/
	/**
	 * Create a setting for theme css switching
	 *
	$wp_customize->add_setting( 'setting_id', array(
	'type' => 'theme_mod', // or 'option'
	'capability' => 'edit_theme_options',
	'theme_supports' => '', // Rarely needed.
	'default' => '',
	'transport' => 'refresh', // or postMessage
	'sanitize_callback' => '',
	'sanitize_js_callback' => '', // Basically to_json.

	) );
	 */
	$wp_customize->add_setting( 'syntric_theme_css', [ 'default' => 'grey', 'type' => 'theme_mod', 'sanitize_callback' => 'sanitize_text_field', 'capability' => 'edit_theme_options', ] );
	$wp_customize->add_control( new WP_Customize_Control( $wp_customize, 'syntric_theme_css', [ 'label'                                                               => __( 'Top Navbar Color', 'syntric' ),
	                                                                                            'description'                                                         => __( 'Select a color for the top navbar', 'syntric' ),
	                                                                                            'section'                                                             => 'syntric_theme_section_colors',
	                                                                                            'settings'                                                            => 'syntric_theme_css',
	                                                                                            'type'                                                                => 'select',
	                                                                                            'choices'                                                             => [ 'black'  => __( 'Black', 'syntric' ),
	                                                                                                                                                                       'blue'   => __( 'Blue', 'syntric' ),
	                                                                                                                                                                       'green'  => __( 'Green', 'syntric' ),
	                                                                                                                                                                       'grey'   => __( 'Grey', 'syntric' ),
	                                                                                                                                                                       'orange' => __( 'Orange', 'syntric' ),
	                                                                                                                                                                       'purple' => __( 'Purple', 'syntric' ),
	                                                                                                                                                                       'red'    => __( 'Red', 'syntric' ),
	                                                                                                                                                                       'silver' => __( 'Silver', 'syntric' ),
	                                                                                                                                                                       'teal'   => __( 'Teal', 'syntric' ),
	                                                                                                                                                                       'white'  => __( 'White', 'syntric' ) ],
	                                                                                            'priority'                                                            => '10', ] ) );
	/**
	 * Create a setting for body container width
	 */
	$wp_customize->add_setting(
		'syntric_container_type', array(
			'default'           => 'container-fluid',
			'type'              => 'theme_mod',
			'sanitize_callback' => 'sanitize_text_field',
			'capability'        => 'edit_theme_options',
		)
	);
	/**
	 * Create options for body container width
	 */
	$wp_customize->add_control(
		new WP_Customize_Control(
			$wp_customize,
			'syntric_container_type', array(
				'label'       => __( 'Body Width', 'syntric' ),
				'description' => __( "Set the width of the body.", 'syntric' ),
				'section'     => 'syntric_theme_section_site_width', 'settings' => 'syntric_container_type',
				'type'        => 'select',
				'choices'     => array(
					'container'       => __( 'Fixed Width', 'syntric' ),
					'container-fluid' => __( 'Full Width', 'syntric' ),
				),
				'priority'    => '20',
			)
		)
	);

	/**
	 * Create a setting for footer container width
	 */
	/*$wp_customize->add_setting(
		'syntric_footer_container_type', array(
			'default'           => 'container-fluid',
			'type'              => 'theme_mod',
			'sanitize_callback' => 'sanitize_text_field',
			'capability'        => 'edit_theme_options',
		)
	);*/
	/**
	 * Create options for footer container width
	 */
	/*$wp_customize->add_control(
		new WP_Customize_Control(
			$wp_customize,
			'syntric_footer_container_type', array(
				'label'       => __( 'Footer Width', 'syntric' ),
				'description' => __( "Set the width of the footer.", 'syntric' ),
				'section'     => 'syntric_theme_panel',
				'settings'    => 'syntric_footer_container_type',
				'type'        => 'select',
				'choices'     => array(
					'container'       => __( 'Fixed Width', 'syntric' ),
					'container-fluid' => __( 'Full Width', 'syntric' ),
				),
				'priority'    => '30',
			)
		)
	);*/
	/**
	 * Create a setting for default page template
	 */
	/*$wp_customize->add_setting(
		'syntric_sidebar_position', array(
			'default'           => 'left',
			'type'              => 'theme_mod',
			'sanitize_callback' => 'sanitize_text_field',
			'capability'        => 'edit_theme_options',
		)
	);*/
	/**
	 * Create options for default page template
	 */
	/*$wp_customize->add_control(
		new WP_Customize_Control(
			$wp_customize,
			'syntric_sidebar_position', array(
				'label'       => __( 'Sidebar Positioning', 'syntric' ),
				'description' => __( "Set the default page template.", 'syntric' ),
				'section'     => 'syntric_theme_panel',
				'settings'    => 'syntric_sidebar_position',
				'type'        => 'select',
				'choices'     => array(
					'left'  => __( 'Left Sidebar (Default Template)', 'syntric' ),
					'right' => __( 'Right Sidebar', 'syntric' ),
					'none'  => __( 'Full Width', 'syntric' ),
					'both'  => __( 'Both Sidebars', 'syntric' ),
				),
				'priority'    => '40',
			)
		)
	);*/
	/**
	 * Remove stock customizer settings
	 */
	$wp_customize->remove_panel( 'themes' ); // 40
	//$wp_customize->remove_section( 'title_tagline' ); // 40
	$wp_customize->remove_section( 'colors' ); // 40
	// header_image 60
	$wp_customize->remove_section( 'background_image' ); // 80
	//$wp_customize->remove_panel( 'nav_menus' ); // 100
	/*if (isset($wp_customize->nav_menus) && is_object($wp_customize->nav_menus)) {

		//Remove all the filters/actions resiterd in WP_Customize_Nav_Menus __construct
		remove_filter( 'customize_refresh_nonces', array( $wp_customize->nav_menus, 'filter_nonces' ) );
		remove_action( 'wp_ajax_load-available-menu-items-customizer', array( $wp_customize->nav_menus, 'ajax_load_available_items' ) );
		remove_action( 'wp_ajax_search-available-menu-items-customizer', array( $wp_customize->nav_menus, 'ajax_search_available_items' ) );
		remove_action( 'customize_controls_enqueue_scripts', array( $wp_customize->nav_menus, 'enqueue_scripts' ) );
		remove_action( 'customize_register', array( $wp_customize->nav_menus, 'customize_register' ), 11 );
		remove_filter( 'customize_dynamic_setting_args', array( $wp_customize->nav_menus, 'filter_dynamic_setting_args' ), 10, 2 );
		remove_filter( 'customize_dynamic_setting_class', array( $wp_customize->nav_menus, 'filter_dynamic_setting_class' ), 10, 3 );
		remove_action( 'customize_controls_print_footer_scripts', array( $wp_customize->nav_menus, 'print_templates' ) );
		remove_action( 'customize_controls_print_footer_scripts', array( $wp_customize->nav_menus, 'available_items_template' ) );
		remove_action( 'customize_preview_init', array( $wp_customize->nav_menus, 'customize_preview_init' ) );
		remove_filter( 'customize_dynamic_partial_args', array( $wp_customize->nav_menus, 'customize_dynamic_partial_args' ), 10, 2 );

	}*/
	// widgets 110
	$wp_customize->remove_section( 'static_front_page' ); // 120
	// default 160
	$wp_customize->remove_section( 'custom_css' ); // 200
}

add_action( 'customize_register', function( $wp_customize ) {
	if ( isset( $wp_customize->nav_menus ) && is_object( $wp_customize->nav_menus ) ) {

		//Remove all the filters/actions resiterd in WP_Customize_Nav_Menus __construct
		remove_filter( 'customize_refresh_nonces', [ $wp_customize->nav_menus, 'filter_nonces' ] );
		remove_action( 'wp_ajax_load-available-menu-items-customizer', [ $wp_customize->nav_menus, 'ajax_load_available_items' ] );
		remove_action( 'wp_ajax_search-available-menu-items-customizer', [ $wp_customize->nav_menus, 'ajax_search_available_items' ] );
		remove_action( 'customize_controls_enqueue_scripts', [ $wp_customize->nav_menus, 'enqueue_scripts' ] );
		remove_action( 'customize_register', [ $wp_customize->nav_menus, 'customize_register' ], 11 );
		remove_filter( 'customize_dynamic_setting_args', [ $wp_customize->nav_menus, 'filter_dynamic_setting_args' ], 10, 2 );
		remove_filter( 'customize_dynamic_setting_class', [ $wp_customize->nav_menus, 'filter_dynamic_setting_class' ], 10, 3 );
		remove_action( 'customize_controls_print_footer_scripts', [ $wp_customize->nav_menus, 'print_templates' ] );
		remove_action( 'customize_controls_print_footer_scripts', [ $wp_customize->nav_menus, 'available_items_template' ] );
		remove_action( 'customize_preview_init', [ $wp_customize->nav_menus, 'customize_preview_init' ] );
		remove_filter( 'customize_dynamic_partial_args', [ $wp_customize->nav_menus, 'customize_dynamic_partial_args' ], 10, 2 );
	}
}, - 1 );
	/**
	 * Binds javascript handlers causing theme customizer preview to refresh when a setting is changed
	 */
add_action( 'customize_preview_init', 'syn_customize_preview_init' );
function syn_customize_preview_init() {
	wp_enqueue_script( 'syntric_customizer', get_template_directory_uri() . '/assets/js/customizer.js', array( 'customize-preview' ), '20170504', true );
}