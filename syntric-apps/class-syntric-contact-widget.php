<?php

/**
 * Syntric_Contact_Widget
 *
 * Dynamic + static widget to display person or organization contact information.
 */
class Syntric_Contact_Widget extends WP_Widget {
	/**
	 * Set up a new widget instance
	 */
	public function __construct() {
		$widget_ops = [ 'classname'                   => 'syntric-contact-widget',
		                'description'                 => __( 'Displays contact info for an individual or organization' ),
		                'customize_selective_refresh' => true,
		];
		parent ::__construct( 'syntric-contact-widget', __( 'Contact' ), $widget_ops );
		$this -> alt_option_name = 'syntric-contact-widget';
	}

	/**
	 * Output widget content
	 *
	 * @param array $args     Display arguments including 'before_title', 'after_title', 'before_widget', and 'after_widget'.
	 * @param array $instance Settings for the current widget instance.
	 */
	public function widget( $args, $instance ) {
		$widget_id = ( isset( $args[ 'widget_id' ] ) ) ? $args[ 'widget_id' ] : $this -> id;;
		$contact_widget = get_field( 'syntric_contact_widget', 'widget_' . $widget_id );
		$title          = $contact_widget[ 'title' ];
		$contact_type   = $contact_widget[ 'contact_type' ];
		echo $args[ 'before_widget' ];
		if( ! empty( $title ) ) :
			echo $args[ 'before_title' ] . $title . $args[ 'after_title' ];
		endif;
		switch( $contact_type ) :
			case 'person' :
				$user_id = $contact_widget[ 'person' ]; // returns User ID
				$user    = get_user_by( 'ID', $user_id );
				if( $user instanceof WP_User ) {
					$user_custom_fields = get_field( 'syntric_user', 'user_' . $user_id );
					$phone              = $user_custom_fields[ 'phone' ];
					$phone              .= ( isset( $user_custom_fields[ 'ext' ] ) && ! empty( $user_custom_fields[ 'ext' ] ) ) ? ' x' . $user_custom_fields[ 'ext' ] : '';
					echo '<div class="contact d-flex flex-row">';
					if( isset( $user_custom_fields[ 'photo' ] ) && ! empty( $user_custom_fields[ 'photo' ] ) ) {
						echo '<div class="pr-4">';
						echo '<img src="' . $user_custom_fields[ 'photo' ][ 'sizes' ][ 'thumbnail' ] . '" class="contact-photo circle-photo">';
						echo '</div>';
					}
					echo '<div>';
					echo '<div class="contact-name">' . trim( $user_custom_fields[ 'prefix' ] . ' ' . $user -> display_name ) . '</div>';
					if( isset( $user_custom_fields[ 'title' ] ) && ! empty( $user_custom_fields[ 'title' ] ) ) {
						echo '<div class="contact-title">' . str_replace( '|', ' / ', $user_custom_fields[ 'title' ] ) . '</div>';
					}
					echo '<div class="contact-email">';
					echo '<a href="mailto:' . antispambot( $user -> user_email, true ) . '" class="user-email" title="Email">' . antispambot( $user -> user_email ) . '</a>';
					echo '</div>';
					if( ! empty( $phone ) ) {
						echo '<div class="contact-phone">' . $phone . '</div>';
					}
					echo '</div>';
					echo '</div>';
				}
			break;
			case 'organization' :
				$contact_organization = $contact_widget[ 'organization' ];
				$organization         = get_field( 'syntric_organization', 'option' );
				if( $organization && $contact_organization == $organization[ 'name' ] ) {
					$contact = $organization;
				} else {
					$organizations = get_field( 'syntric_organizations', 'option' );
					if( $organizations ) {
						foreach( $organizations as $organization ) {
							if( $contact_organization == $organization[ 'name' ] ) {
								$contact = $organization;
								break;
							}
						}
					}
				}
				if( $contact ) {
					echo '<div class="contact d-flex flex-row">';
					if( $contact[ 'logo' ] && ! empty( $contact[ 'logo' ] ) ) {
						echo '<div class="pr-4">';
						echo '<img src="' . $contact[ 'logo' ][ 'sizes' ][ 'thumbnail' ] . '" class="contact-logo">';
						echo '</div>';
					}
					echo '<div>';
					echo '<div class="contact-name">' . $contact[ 'name' ] . '</div>';
					echo '<div class="contact-address">' . $contact[ 'address' ] . '</div>';
					echo '<div class="contact-city-state-zip-code">' . $contact[ 'city' ] . ', ' . $contact[ 'state' ] . ' ' . $contact[ 'zip_code' ] . '</div>';
					echo '<div class="contact-phone">' . $contact[ 'phone' ] . '</div>';
					echo '<div class="contact-email">' . $contact[ 'email' ] . '</div>';
					//echo '<div class="contact-website">' . $contact['website'] . '</div>';
					echo '</div>';
					echo '</div>';
				}
			break;
		endswitch;
		echo $args[ 'after_widget' ];
	}

	/**
	 * Update settings for the current widget instance
	 *
	 * @param array $new_instance New settings for this instance as input by the user via WP_Widget::form().
	 * @param array $old_instance Old settings for this instance.
	 *
	 * @return array Updated settings to save.
	 */
	public function update( $new_instance, $old_instance ) {
		$instance            = $old_instance;
		$instance[ 'title' ] = '';

		return $instance;
	}

	/**
	 * Render settings form for the widget
	 *
	 * @param array $instance Current settings
	 *
	 * @return void Displays settings form
	 */
	public function form( $instance ) {
	}
}
