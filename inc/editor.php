<?php
add_filter( 'mce_buttons_2', 'syn_tiny_mce_style_formats' );
function syn_tiny_mce_style_formats( $styles ) {
	array_unshift( $styles, 'styleselect' );

	return $styles;
}

add_filter( 'tiny_mce_before_init', 'syn_tiny_mce_before_init', 20 );
function syn_tiny_mce_before_init( $settings ) {
	$style_formats = array(
		array(
			'title'    => 'Unstyled List',
			'selector' => 'ul',
			'classes'  => 'list-unstyled',
			'wrapper'  => true,
		),
		array(
			'title'    => 'Highlight',
			'selector' => 'p',
			'classes'  => 'highlight',
			'wrapper'  => true,
		),
		array(
			'title'    => 'Button',
			'selector' => 'a',
			'classes'  => 'btn btn-first',
			'wrapper'  => true,
		),
		array(
			'title'  => 'Small',
			'inline' => 'small',
		),
	);
	if ( isset( $settings[ 'style_formats' ] ) ) {
		$orig_style_formats = json_decode( $settings[ 'style_formats' ], true );
		$style_formats      = array_merge( $orig_style_formats, $style_formats );
	}
	$settings[ 'style_formats' ] = json_encode( $style_formats );

	return $settings;
}

//add_action( 'admin_init', 'syn_add_editor_style' );
function syn_add_editor_style() {
	//add_editor_style( '/assets/css/syntric.min.css' );
	// todo: stylesheets aren't working in TinyMCE...maybe because this isn't using "custom-editor"?  find out
	//wp_enqueue_style( 'editor-style', get_template_directory_uri() . '/assets/css/editor-style.css' );
}