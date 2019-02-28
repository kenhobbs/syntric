<?php
	/**
	 * Syntric: Customizer
	 *
	 * @package    WordPress
	 * @subpackage Syntric
	 * @since      1.0.0
	 */
	
	/**
	 * Add postMessage support for site title and description for the Theme Customizer.
	 *
	 * @param WP_Customize_Manager $wp_customize Theme Customizer object.
	 */
	function syntric_customize_register( $wp_customize ) {
		$wp_customize -> get_setting( 'blogname' ) -> transport        = 'postMessage';
		$wp_customize -> get_setting( 'blogdescription' ) -> transport = 'postMessage';
		if( isset( $wp_customize -> selective_refresh ) ) {
			$wp_customize -> selective_refresh -> add_partial( 'blogname', [ 'selector' => '.site-title a', 'render_callback' => 'syntric_customize_partial_blogname', ] );
			$wp_customize -> selective_refresh -> add_partial( 'blogdescription', [ 'selector' => '.site-description', 'render_callback' => 'syntric_customize_partial_blogdescription', ] );
		}
		/**
		 * Syntric customizer customizations
		 *
		 * @param WP_Customize_Manager $manager         Customizer bootstrap instance.
		 * @param string               $id              Control ID.
		 * @param array                $args            {
		 *                                              Optional. Arguments to override class property defaults.
		 *
		 * @type int                   $instance_number Order in which this instance was created in relation
		 *                                                 to other instances.
		 * @type WP_Customize_Manager  $manager         Customizer bootstrap instance.
		 * @type string                $id              Control ID.
		 * @type array                 $settings        All settings tied to the control. If undefined, `$id` will
		 *                                                 be used.
		 * @type string                $setting         The primary setting for the control (if there is one).
		 *                                                 Default 'default'.
		 * @type int                   $priority        Order priority to load the control. Default 10.
		 * @type string                $section         Section the control belongs to. Default empty.
		 * @type string                $label           Label for the control. Default empty.
		 * @type string                $description     Description for the control. Default empty.
		 * @type array                 $choices         List of choices for 'radio' or 'select' type controls, where
		 *                                                 values are the keys, and labels are the values.
		 *                                                 Default empty array.
		 * @type array                 $input_attrs     List of custom input attributes for control output, where
		 *                                                 attribute names are the keys and values are the values. Not
		 *                                                 used for 'checkbox', 'radio', 'select', 'textarea', or
		 *                                                 'dropdown-pages' control types. Default empty array.
		 * @type array                 $json            Deprecated. Use WP_Customize_Control::json() instead.
		 * @type string                $type            Control type. Core controls include 'text', 'checkbox',
		 *                                                 'textarea', 'radio', 'select', and 'dropdown-pages'. Additional
		 *                                                 input types such as 'email', 'url', 'number', 'hidden', and
		 *                                                 'date' are supported implicitly. Default 'text'.
		 * }
		 */
		/**
		 * Create a customizer panel for stylesheet and site width selections
		 */
		$wp_customize -> add_panel( 'syntric_customizer_panel', [ 'title' => __( 'Appearance', 'syntric' ), 'priority' => 159, ] );
		
		/**
		 * Create stylesheet section
		 */
		$wp_customize -> add_section( 'syntric_stylesheet_section',
			[ 'title'       => __( 'Site Color', 'syntric' ),
			  'capability'  => 'edit_theme_options',
			  'description' => __( 'Site color selection', 'syntric' ),
			  'priority'    => 161,
			  'panel'       => 'syntric_customizer_panel', ] );
		/**
		 * Create stylesheet setting
		 */
		$wp_customize -> add_setting( 'syntric_stylesheet_setting',
			[ 'default'           => 'silver',
			  'type'              => 'theme_mod',
			  'sanitize_callback' => 'sanitize_text_field',
			  'capability'        => 'edit_theme_options',
			  'transport'         => 'refresh', ] );
		/**
		 * Create control for stylesheet selection
		 */
		$wp_customize -> add_control(
			new WP_Customize_Control( $wp_customize,
				'syntric_stylesheet_setting',
				[ 'label'       => __( 'Site color', 'syntric' ),
				  'description' => __( 'Select a color the site', 'syntric' ),
				  'section'     => 'syntric_stylesheet_section',
				  'settings'    => 'syntric_stylesheet_setting',
				  'type'        => 'select',
				  'choices'     => [ 'black'  => __( 'Black', 'syntric' ),
				                     'blue'   => __( 'Blue', 'syntric' ),
				                     'green'  => __( 'Green', 'syntric' ),
				                     'grey'   => __( 'Grey', 'syntric' ),
				                     'orange' => __( 'Orange', 'syntric' ),
				                     'purple' => __( 'Purple', 'syntric' ),
				                     'red'    => __( 'Red', 'syntric' ),
				                     'silver' => __( 'Silver', 'syntric' ),
				                     'teal'   => __( 'Teal', 'syntric' ),
				                     'white'  => __( 'White', 'syntric' ) ],
				  'priority'    => 10, ] ) );
		/**
		 * Create site width (full or fixed) section
		 */
		$wp_customize -> add_section( 'syntric_site_width_section',
			[ 'title'       => __( 'Site Width', 'syntric' ),
			  'capability'  => 'edit_theme_options',
			  'description' => __( 'Site width selection', 'syntric' ),
			  'priority'    => 160,
			  'panel'       => 'syntric_customizer_panel', ] );
		/**
		 * Create site width setting
		 */
		$wp_customize -> add_setting( 'syntric_site_width_setting',
			[ 'default'           => 'container-fluid',
			  'type'              => 'theme_mod',
			  'sanitize_callback' => 'sanitize_text_field',
			  'capability'        => 'edit_theme_options',
			  'transport'         => 'refresh' ] );
		/**
		 * Create control for site width selection
		 */
		$wp_customize -> add_control(
			new WP_Customize_Control( $wp_customize,
				'syntric_site_width_setting',
				[ 'label'       => __( 'Site Width', 'syntric' ),
				  'description' => __( 'Select a width for the site', 'syntric' ),
				  'section'     => 'syntric_site_width_section',
				  'settings'    => 'syntric_site_width_setting',
				  'type'        => 'select',
				  'choices'     => [ 'container'       => __( 'Fixed width', 'syntric' ),
				                     'container-fluid' => __( 'Full width', 'syntric' ), ],
				  'priority'    => 20, ] ) );
		
		/**
		 * Remove stock customizer settings
		 */
		$wp_customize -> remove_panel( 'themes' ); // 40
		$wp_customize -> remove_section( 'colors' ); // 40
		// header_image 60
		$wp_customize -> remove_section( 'background_image' ); // 80
		//$wp_customize->remove_panel( 'nav_menus' ); // 100
		// widgets 110
		$wp_customize -> remove_section( 'static_front_page' ); // 120
		// default 160
		$wp_customize -> remove_section( 'custom_css' ); // 200
	}
	
	// Remove all the filters/actions registered in WP_Customize_Nav_Menus __construct... IOW remove menu management in the Customizer
	add_action( 'customize_register', function( $WP_Customize_Manager ) {
		//check if WP_Customize_Nav_Menus object exist
		if( isset( $WP_Customize_Manager -> nav_menus ) && is_object( $WP_Customize_Manager -> nav_menus ) ) {
			remove_filter( 'customize_refresh_nonces', [ $WP_Customize_Manager -> nav_menus, 'filter_nonces' ] );
			remove_action( 'wp_ajax_load-available-menu-items-customizer', [ $WP_Customize_Manager -> nav_menus, 'ajax_load_available_items' ] );
			remove_action( 'wp_ajax_search-available-menu-items-customizer', [ $WP_Customize_Manager -> nav_menus, 'ajax_search_available_items' ] );
			remove_action( 'customize_controls_enqueue_scripts', [ $WP_Customize_Manager -> nav_menus, 'enqueue_scripts' ] );
			remove_action( 'customize_register', [ $WP_Customize_Manager -> nav_menus, 'customize_register' ], 11 );
			remove_filter( 'customize_dynamic_setting_args', [ $WP_Customize_Manager -> nav_menus, 'filter_dynamic_setting_args' ], 10, 2 );
			remove_filter( 'customize_dynamic_setting_class', [ $WP_Customize_Manager -> nav_menus, 'filter_dynamic_setting_class' ], 10, 3 );
			remove_action( 'customize_controls_print_footer_scripts', [ $WP_Customize_Manager -> nav_menus, 'print_templates' ] );
			remove_action( 'customize_controls_print_footer_scripts', [ $WP_Customize_Manager -> nav_menus, 'available_items_template' ] );
			remove_action( 'customize_preview_init', [ $WP_Customize_Manager -> nav_menus, 'customize_preview_init' ] );
			remove_filter( 'customize_dynamic_partial_args', [ $WP_Customize_Manager -> nav_menus, 'customize_dynamic_partial_args' ], 10, 2 );
		}
	}, - 1 ); //Give it a lowest priority so we can remove it on right time
	/**
	 * Render the site title for the selective refresh partial.
	 *
	 * @return void
	 */
	function syntric_customize_partial_blogname() {
		bloginfo( 'name' );
	}
	
	/**
	 * Render the site tagline for the selective refresh partial.
	 *
	 * @return void
	 */
	function syntric_customize_partial_blogdescription() {
		bloginfo( 'description' );
	}
	
	/**
	 * Binds javascript handlers causing theme customizer preview to refresh when a setting is changed
	 */
	function syntric_customize_preview_init() {
		wp_enqueue_script( 'syntric_customizer', get_template_directory_uri() . '/assets/js/customizer.js', [ 'customize-preview' ], date( 'YmdHis' ), true );
	}
	
	add_action( 'customize_preview_init', 'syntric_customize_preview_init' );