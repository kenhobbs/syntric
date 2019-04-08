<?php
	/*********************************************************************************
	 * Blocks
	 *********************************************************************************/
	
	add_action( 'acf/init', 'syntric_register_blocks' );
	function syntric_register_blocks() {
		// check function exists
		if( function_exists( 'acf_register_block' ) ) {
			// register a calendar block
			acf_register_block( [
				'name'            => 'syntric-calendar',
				'title'           => __( 'Google Calendar' ),
				'description'     => __( 'A Google Calendar block by Syntric' ),
				'category'        => 'widgets',
				'icon'            => 'calendar-alt',
				'mode'            => 'preview',
				'post_types'      => [ 'post', 'page' ],
				'align'           => 'full',
				'keywords'        => [ 'Google', 'calendar' ],
				'render_callback' => 'syntric_calendar_block_callback',
			] );
		}
	}
	
	/**
	 *  Callback for the calendar block
	 *
	 * @param   array  $block      The block settings and attributes.
	 * @param   string $content    The block content (emtpy string).
	 * @param   bool   $is_preview True during AJAX preview.
	 */
	function syntric_calendar_block_callback( $block, $content = '', $is_preview = false ) {
		// get image field (array)
		//$avatar = get_field('avatar');
		
		// create id attribute for specific styling
		//$id = 'testimonial-' . $block['id'];
		
		// create align class ("alignwide") from block setting ("wide")
		//$align_class = $block['align'] ? 'align' . $block['align'] : '';
		
		//echo '<div class="fullcalendar">';
		echo '<div class="foobar">';
		echo '<p>Foo bar</p>';
		echo '</div>';
	}
	
	// Boneyard
	
	/**
	 * Sets which blocks are on - currently replaced with the Disable Gutenberg Blocks - Block Manager plugin
	 *
	 * @param $allowed_blocks - coming into the function is simple the number 1 to indicate all blocks allowed
	 * @param $post           - the current post
	 *
	 * @return array ($allowed_blocks with disallowed blocks removed)
	 */
	//add_filter( 'allowed_block_types', 'syntric_allowed_block_types', 10, 2 );
	function __syntric_allowed_block_types( $allowed_blocks, $post ) {
		$syntric_settings = get_field( 'syntric_settings', 'option' );
		$wordpress_blocks = $syntric_settings[ 'wordpress_blocks' ];
		
		return $wordpress_blocks;
	}
	
	/**
	 * Trash, but saved for reference
	 *
	 * @return arrsay
	 */
	function __syntric_get_wp_blocks() {
		return [
			'core/image : Image',
			'core/gallery : Gallery',
			'core/heading : Heading',
			'core/quote : Quote',
			'core/list : List',
			'core/cover : Cover',
			'core/video : Video',
			'core/audio : Audio',
			'core/paragraph : Paragraph',
			'core/file : File',
			'core/pullquote : Pullquote',
			'core/table : Table',
			'core/preformatted : Preformatted',
			'core/code : Code',
			'core/classic : Classic',
			'core/html : Custom HTML',
			'core/verse : Verse',
			'core/media-text : Media & Text',
			'core/separator : Separator',
			'core/more : More',
			'core/button : Button',
			'core/columns : Columns',
			'core/nextpage : Next Page',
			'core/spacer : Spacer',
			'core/shortcode : Shortcode',
			'core/latest-posts : Latest Posts',
			'core/latest-comments : Latest Comments',
			'core/categories : Categories',
			'core/archives : Archives',
			'core/embed : Embed',
			'core-embed/twitter : Twitter',
			'core-embed/youtube : YouTube',
			'core-embed/facebook : Facebook',
			'core-embed/instagram : Instagram',
			'core-embed/wordpress : WordPress',
			'core-embed/soundcloud : SoundCloud',
			'core-embed/spotify : Spotify',
			'core-embed/flickr : Flickr',
			'core-embed/vimeo : Vimeo',
			'core-embed/animoto : Animoto',
			'core-embed/cloudup : Cloudup',
			'core-embed/collegehumor : CollegeHumor',
			'core-embed/dailymotion : Dialymotion',
			'core-embed/funnyordie : Funny or Die',
			'core-embed/hulu : Hulu',
			'core-embed/imgur : Imgur',
			'core-embed/issuu : Issuu',
			'core-embed/kickstarter : Kickstarter',
			'core-embed/meetup-com : Meetup.com',
			'core-embed/mixcloud : Mixcloud',
			'core-embed/photobucket : Photobucket',
			'core-embed/reddit : Reddit',
			'core-embed/reverbnation : ReverbNation',
			'core-embed/screencast : Screencast',
			'core-embed/scribd : Scribd',
			'core-embed/slideshare : Slideshare',
			'core-embed/smugmug : SmugMug',
			'core-embed/speaker-deck : Speaker Deck',
			'core-embed/ted : Ted',
			'core-embed/tumblr : Tumblr',
			'core-embed/videopress : VideoPress',
			'core-embed/wordpress-tv : Wordpress.tv',
		];
	}
	
	/*********************************************************************************
	 * Widgets
	 *********************************************************************************/
	
	/**
	 * Register/unregister custom widgets
	 */
	add_action( 'widgets_init', 'syntric_widgets_init', 20 );
	function syntric_widgets_init() {
		$settings = get_field( 'syntric_settings', 'option' );
		$widgets  = $settings[ 'widgets' ];
		if( $widgets ) {
			foreach( $widgets as $widget ) {
				$widget_class      = str_replace( '_', '-', $widget );
				$widget_class_file = 'class-' . strtolower( $widget_class ) . '.php';
				require_once( $widget_class_file );
				register_widget( $widget );
			}
		}
	}
	
	/**
	 * Return the sidebar for a widget
	 *
	 * @param $widget_id
	 */
	function syntric_widget_sidebar( $widget_id ) {
		$sidebars_widgets  = get_option( 'sidebars_widgets', [] );
		$widget_sidebar_id = '';
		foreach( $sidebars_widgets as $key => $value ) {
			if( in_array( $widget_id, $value ) ) {
				$widget_sidebar_id = $key;
				break;
			}
		}
		$settings = get_field( 'syntric_settings', 'option' );
		$sidebars = $settings[ 'sidebars' ];
		foreach( $sidebars as $sidebar ) {
			if( $widget_sidebar_id == $sidebar[ 'value' ] ) {
				return $sidebar;
			}
		}
		
		return;
	}
	
	/**
	 * Supress the display of widgets is the don't pass schedule and filter tests
	 */
	add_filter( 'widget_display_callback', 'syntric_widget_display_callback', 100, 3 );
	function syntric_widget_display_callback( $instance, $widget_class, $args ) {
		$filters      = get_field( 'filters', 'widget_' . $args[ 'widget_id' ] );
		$pass_filters = syntric_process_filters( $filters );
		if( $pass_filters ) {
			return $instance;
		}
		
		return false;
	}
	
	/*********************************************************************************
	 * Sidebars
	 *********************************************************************************/
	
	/**
	 * Register sidebars
	 */
	add_action( 'widgets_init', 'syntric_sidebars_init' );
	function syntric_sidebars_init() {
		$settings = get_field( 'syntric_settings', 'option' );
		$sidebars = $settings[ 'sidebars' ];
		if( $sidebars ) {
			foreach( $sidebars as $sidebar ) {
				$id       = $sidebar[ 'value' ];
				$name     = $sidebar[ 'label' ];
				$id_array = explode( '-', $id );
				if( 'header' == $id_array[ 0 ] || 'footer' == $id_array[ 0 ] ) {
					$class = 'sidebar ' . $id_array[ 0 ] . '-sidebar';
				} elseif( 'super' == $id_array[ 0 ] && 'header' == $id_array[ 1 ] ) {
					$class = 'sidebar super-header-sidebar';
				} else {
					$class = 'sidebar ' . $id;
				}
				register_sidebar( [
					'name'          => $name,
					'id'            => $id,
					'description'   => '',
					'class'         => $class,
					'before_widget' => '<div' . ' id="%1$s" class="widget %2$s">',
					'after_widget'  => '</div>',
					'before_title'  => '<h2>',
					'after_title'   => '</h2>',
				] );
			}
		}
	}
	
	/**
	 * Get active widgets for sidebar
	 *
	 * @param $sidebar_id
	 * @param $post_id
	 *
	 * @return array
	 */
	function syntric_sidebar_active_widgets( $sidebar_id ) {
		$sidebars_widgets = get_option( 'sidebars_widgets', [] );
		if( isset( $sidebars_widgets[ $sidebar_id ] ) ) {
			return $sidebars_widgets[ $sidebar_id ];
		}
		
		return [];
	}
	
	/**
	 * Dynamically create responsive columns for horizontal sidebars
	 *
	 * todo: handle cases where number of widgets is an odd number
	 */
	add_filter( 'dynamic_sidebar_params', 'syntric_dynamic_sidebar_params' );
	function syntric_dynamic_sidebar_params( $params ) {
		global $post;
		if( ! is_admin() ) {
			$settings = get_field( 'syntric_settings', 'option' );
			$sidebars = $settings[ 'sidebars' ];
			foreach( $sidebars as $sidebar ) {
				if( $params[ 0 ][ 'id' ] == $sidebar[ 'value' ] ) {
					$active_widgets = syntric_sidebar_active_widgets( $params[ 0 ][ 'id' ] );
					$widget_count   = count( $active_widgets );
					if( 0 < $widget_count && in_array( $sidebar[ 'value' ], [ 'super-header-sidebar', 'header-sidebar-1', 'header-sidebar-2', 'header-sidebar-3', 'footer-sidebar-1', 'footer-sidebar-2', 'footer-sidebar-3', 'sub-footer-sidebar' ] ) ) {
						$params[ 0 ][ 'before_widget' ] = str_replace( 'class="', 'class="col-xl-' . 12 / $widget_count . ' ' . 'widgets-' . $widget_count . ' ', $params[ 0 ][ 'before_widget' ] );
					}
				}
			}
		}
		
		return $params;
	}
	
	/**
	 * Wraps the dynamic_sidebar() function to test schedules and filters and also to wrap the sidebar in it's
	 * HTML container
	 *
	 * @param $sidebar_id
	 */
	function syntric_sidebar( $sidebar_id ) {
		global $post;
		global $wp_registered_sidebars;
		if( ! $post ) {
			return;
		};
		$active_widgets = syntric_sidebar_active_widgets( $sidebar_id );
		if( count( $active_widgets ) ) {
			$wp_sidebar       = $wp_registered_sidebars[ $sidebar_id ];
			$wp_sidebar_class = $wp_sidebar[ 'class' ];
			if( in_array( $sidebar_id, [ 'main-left-sidebar', 'main-right-sidebar' ] ) ) {
				echo '<aside id="' . $sidebar_id . '" class="' . $wp_sidebar_class . ' col-xl-3">';
				dynamic_sidebar( $sidebar_id );
				echo '</aside>';
			} elseif( in_array( $sidebar_id, [ 'main-top-sidebar', 'main-bottom-sidebar' ] ) ) {
				echo '<section id="' . $sidebar_id . '" class="' . $wp_sidebar_class . '">';
				dynamic_sidebar( $sidebar_id );
				echo '</section>';
			} elseif( in_array( $sidebar_id, [
				// todo: this shouldn't be hardcoded into the function
				'header-sidebar-1',
				'header-sidebar-2',
				'header-sidebar-3',
				'footer-sidebar-1',
				'footer-sidebar-2',
				'footer-sidebar-3',
			] ) ) {
				echo '<section id="' . $sidebar_id . '" class="' . $wp_sidebar_class . '">';
				echo '<div class="container-fluid">';
				echo '<div class="row">';
				dynamic_sidebar( $sidebar_id );
				echo '</div>';
				echo '</div>';
				echo '</section>';
			} elseif( 'super-header-sidebar' == $sidebar_id ) {
				echo '<header id="' . $sidebar_id . '" class="' . $wp_sidebar_class . '">';
				dynamic_sidebar( $sidebar_id );
				echo '</header>';
			} elseif( 'sub-footer-sidebar' == $sidebar_id ) {
				echo '<footer id="' . $sidebar_id . '" class="' . $wp_sidebar_class . '">';
				dynamic_sidebar( $sidebar_id );
				echo '</footer>';
			}
		}
		
		return;
	}