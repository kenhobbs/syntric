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
		$widget_ops = array(
			'classname'                   => 'syn-contact-widget',
			'description'                 => __( 'Displays contact info for an individual or organization' ),
			'customize_selective_refresh' => true,
		);
		parent::__construct( 'syn-contact-widget', __( 'Contact' ), $widget_ops );
		$this->alt_option_name = 'syn-contact-widget';
	}

	/**
	 * Output widget content
	 *
	 * @param array $args Display arguments including 'before_title', 'after_title', 'before_widget', and 'after_widget'.
	 * @param array $instance Settings for the current widget instance.
	 */
	public function widget( $args, $instance ) {
		global $post;
		if ( ! isset( $args[ 'widget_id' ] ) ) {
			$args[ 'widget_id' ] = $this->id;
		}
		$dynamic = get_field( 'syn_contact_widget_dynamic', 'widget_' . $args[ 'widget_id' ] );
		if ( $dynamic && ! get_field( 'syn_contact_active', $post->ID ) ) {
			return;
		}
		$field_prefix    = ( $dynamic ) ? 'syn_contact_' : 'syn_contact_widget_';
		$post_id         = ( $dynamic ) ? $post->ID : 'widget_' . $args[ 'widget_id' ];
		$_title          = get_field( $field_prefix . 'title', $post_id );
		$contact_type    = get_field( $field_prefix . 'contact_type', $post_id );
		$user_id         = 0;
		$organization_id = 0;
		if ( 'person' == $contact_type ) {
			$user_id = get_field( $field_prefix . 'person', $post_id );
		} elseif ( 'organization' == $contact_type ) {
			$default_organization = get_field( $field_prefix . 'default', $post_id );
			$organization_id      = ( $default_organization ) ? get_field( 'syn_organization_id', 'option' ) : get_field( $field_prefix . 'organization', $post_id );
		}
		$lb              = "\n";
		$tab             = "\t";
		$_include_fields = ( 'person' == $contact_type ) ? get_field( $field_prefix . 'include_person_fields', $post_id ) : get_field( $field_prefix . 'include_organization_fields', $post_id );
		$include_fields  = array_column( $_include_fields, 'value' );
		echo $args[ 'before_widget' ] . $lb;
		if ( ! empty( $_title ) ) :
			echo $args[ 'before_title' ] . $_title . $args[ 'after_title' ] . $lb;
		endif;
		echo '<ul class="widget-body">' . $lb;
		echo $tab . '<li class="widget-item">' . $lb;
		echo $tab . $tab . '<div class="widget-entry">' . $lb;
		switch ( $contact_type ) :
			case 'person' :
				$user       = get_user_by( 'ID', $user_id );
				$user_meta  = get_user_meta( $user_id );
				$first_name = $user_meta[ 'first_name' ][ 0 ];
				$last_name  = $user_meta[ 'last_name' ][ 0 ];
				$title_     = get_field( 'syn_user_title', 'user_' . $user_id );
				$title_     = str_replace( ',', '<br>', $title_ );
				$email      = $user->data->user_email;
				$phone      = get_field( 'syn_user_phone', 'user_' . $user_id );
				$ext        = get_field( 'syn_user_extension', 'user_' . $user_id );
				$ext        = ( isset( $ext ) && ! empty( $ext ) ) ? ' x' . $ext : '';
				echo $tab . $tab . $tab . '<span class="entry-header">' . $lb;
				echo $tab . $tab . $tab . $tab . '<span class="entry-name">' . $first_name . ' ' . $last_name . '</span>' . $lb;
				if ( in_array( 'title', $include_fields ) && $title_ ) :
					echo $tab . $tab . $tab . $tab . '<span class="entry-title">' . $title_ . '</span>' . $lb;
				endif;
				if ( in_array( 'email', $include_fields ) && $email ) :
					echo $tab . $tab . $tab . $tab . '<span class="entry-email">';
					echo $tab . $tab . $tab . $tab . $tab . '<a href="mailto:' . antispambot( $email, true ) . '" class="contact-email" title="Email">' . antispambot( $email ) . '</a>' . $lb;
					echo $tab . $tab . $tab . $tab . '</span>' . $lb;
				endif;
				if ( in_array( 'phone', $include_fields ) && $phone ) :
					echo $tab . $tab . $tab . $tab . '<span class="entry-phone">' . $phone . $ext . '</span>' . $lb;
				endif;
				echo $tab . $tab . $tab . '</span>' . $lb;
				break;
			case 'organization' :
				if ( $default_organization ) {
					$organization = get_field( 'syn_organization', 'option' );
					$logo         = get_field( 'syn_organization_logo', 'option' );
					$url          = get_field( 'syn_organization_url', 'option' );
					$url_display  = ( ! empty( $url ) ) ? parse_url( $url, PHP_URL_HOST ) : '';
					$address      = get_field( 'syn_organization_address', 'option' );
					$address_2    = get_field( 'syn_organization_address_2', 'option' );
					$city         = get_field( 'syn_organization_city', 'option' );
					$state        = get_field( 'syn_organization_state', 'option' );
					$zip_code     = get_field( 'syn_organization_zip_code', 'option' );
					$email        = get_field( 'syn_organization_email', 'option' );
					$phone        = get_field( 'syn_organization_phone', 'option' );
					$ext          = get_field( 'syn_organization_extension', 'option' );
					$ext          = ( ! empty( $ext ) ) ? ' ext. ' . $ext : '';
					$fax          = get_field( 'syn_organization_fax', 'option' );
				} else {
					if ( have_rows( 'syn_organizations', 'option' ) ) :
						while ( have_rows( 'syn_organizations', 'option' ) ) : the_row();
							if ( $organization_id == get_sub_field( 'organization_id' ) ) :
								$organization = get_sub_field( 'organization' );
								$logo         = get_sub_field( 'logo' );
								$url          = get_sub_field( 'url' );
								$url_display  = ( ! empty( $url ) ) ? parse_url( $url, PHP_URL_HOST ) : '';
								$address      = get_sub_field( 'address' );
								$address_2    = get_sub_field( 'address_2' );
								$city         = get_sub_field( 'city' );
								$state        = get_sub_field( 'state' );
								$zip_code     = get_sub_field( 'zip_code' );
								$email        = get_sub_field( 'email' );
								$phone        = get_sub_field( 'phone' );
								$ext          = get_sub_field( 'extension' );
								$ext          = ( ! empty( $ext ) ) ? ' ext. ' . $ext : '';
								$fax          = get_sub_field( 'fax' );
							endif;
						endwhile;
					endif;
				}
				if ( in_array( 'logo', $include_fields ) ) {

					if ( $logo ) :
						echo $tab . $tab . $tab . '<span class="entry-image">' . $lb;
						if ( in_array( 'url', $include_fields ) ) :
							echo $tab . $tab . $tab . $tab . $tab . '<a href="' . $url . '" class="entry-logo-link" title="Go to ' . $organization . '">' . $lb;
						endif;
						echo $tab . $tab . $tab . $tab . '<img src="' . $logo[ 'url' ] . '" class="entry-logo" alt="' . $organization . ' Logo">' . $lb;
						if ( in_array( 'url', $include_fields ) ) :
							echo '</a>';
						endif;
						echo $tab . $tab . $tab . '</span>' . $lb;
					endif;
				}
				echo $tab . $tab . $tab . '<span class="entry-header">' . $lb;
				// name
				echo $tab . $tab . $tab . $tab . '<span class="entry-name">' . $organization . '</span>' . $lb;
				// address
				if ( in_array( 'address', $include_fields ) ) :
					if ( $address ) :
						echo $tab . $tab . $tab . $tab . '<span class="entry-address">' . $address . '</span>' . $lb;
						if ( ! empty( $address_2 ) ) :
							echo $tab . $tab . $tab . $tab . '<span class="entry-address-2">' . $address_2 . '</span>' . $lb;
						endif;
					endif;
					if ( $city || $state || $zip_code ) :
						echo $tab . $tab . $tab . $tab . '<div class="entry-city-state-zip-code">' . $lb;
						if ( ! empty( $city ) ) :
							echo $tab . $tab . $tab . $tab . $tab . '<span class="entry-city">' . $city . '</span>' . $lb;
						endif;
						if ( ! empty( $state ) ) :
							echo $tab . $tab . $tab . $tab . $tab . '<span class="entry-state">' . $state . '</span>' . $lb;
						endif;
						if ( ! empty( $zip_code ) ) :
							echo $tab . $tab . $tab . $tab . $tab . '<span class="entry-zip-code">' . $zip_code . '</span>' . $lb;
						endif;
						echo $tab . $tab . $tab . $tab . '</div>' . $lb;
					endif;
				endif;
				// email
				if ( in_array( 'email', $include_fields ) ) :
					if ( $email ) :
						echo $tab . $tab . $tab . $tab . '<span class="entry-email">';
						echo $tab . $tab . $tab . $tab . $tab . '<a href="mailto:' . antispambot( $email, true ) . '" class="contact-email" title="Email">' . antispambot( $email ) . '</a>' . $lb;
						echo $tab . $tab . $tab . $tab . '</span>' . $lb;
					endif;
				endif;
				// phone
				if ( in_array( 'phone', $include_fields ) ) :
					if ( $phone ) :
						echo $tab . $tab . $tab . $tab . '<span class="entry-phone">' . $phone . $ext . '</span>' . $lb;
					endif;
				endif;
				// fax
				if ( in_array( 'fax', $include_fields ) ) :
					if ( $fax ) :
						echo $tab . $tab . $tab . $tab . '<span class="entry-phone">' . $fax . ' fax</span>' . $lb;
					endif;
				endif;
				// url
				if ( in_array( 'url', $include_fields ) ) :
					if ( $url ) :
						echo $tab . $tab . $tab . $tab . '<span class="entry-url">' . $lb;
						echo $tab . $tab . $tab . $tab . $tab . '<a href="' . $url . '" class="entry-link" title="Go to ' . get_sub_field( 'name' ) . '">' . $url_display . '</a>' . $lb;
						echo $tab . $tab . $tab . $tab . '</span>' . $lb;
					endif;
				endif;
				echo $tab . $tab . $tab . '</span>' . $lb;
				break;
		endswitch;
		echo $tab . $tab . '</div>' . $lb;
		echo $tab . '</li>' . $lb;
		echo '</ul>' . $lb;
		echo $args[ 'after_widget' ] . $lb;
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
