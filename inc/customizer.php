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
	$wp_customize->add_section(
		'syntric_theme_layout_options', array(
			'title'       => __( 'Theme Layout Settings', 'syntric' ),
			'capability'  => 'edit_theme_options',
			'description' => __( 'Configure the theme layout', 'syntric' ),
			'priority'    => 160,
		)
	);
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
				'section'     => 'syntric_theme_layout_options',
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
				'section'     => 'syntric_theme_layout_options',
				'settings'    => 'syntric_container_type',
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
				'section'     => 'syntric_theme_layout_options',
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
				'section'     => 'syntric_theme_layout_options',
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
	$wp_customize->remove_section( 'colors' ); // 40
	// header_image 60
	$wp_customize->remove_section( 'background_image' ); // 80
	$wp_customize->remove_section( 'nav_menus' ); // 100
	// widgets 110
	$wp_customize->remove_section( 'static_front_page' ); // 120
	// default 160
	$wp_customize->remove_section( 'custom_css' ); // 200
}

/**
 * Binds javascript handlers causing theme customizer preview to refresh when a setting is changed
 */
add_action( 'customize_preview_init', 'syn_customize_preview_init' );
function syn_customize_preview_init() {
	wp_enqueue_script( 'syntric_customizer', get_template_directory_uri() . '/assets/js/customizer.js', array( 'customize-preview' ), '20170504', true );
}