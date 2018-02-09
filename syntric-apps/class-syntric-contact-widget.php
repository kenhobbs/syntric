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
			$widget_ops = [ 'classname'                   => 'syn-contact-widget',
			                'description'                 => __( 'Displays contact info for an individual or organization' ),
			                'customize_selective_refresh' => true,
			];
			parent::__construct( 'syn-contact-widget', __( 'Contact' ), $widget_ops );
			$this->alt_option_name = 'syn-contact-widget';
		}

		/**
		 * Output widget content
		 *
		 * @param array $args     Display arguments including 'before_title', 'after_title', 'before_widget', and 'after_widget'.
		 * @param array $instance Settings for the current widget instance.
		 */
		public function widget( $args, $instance ) {
			global $post;
			if ( ! isset( $args[ 'widget_id' ] ) ) {
				$args[ 'widget_id' ] = $this->id;
			}
			$dynamic = get_field( 'syn_contact_widget_dynamic', 'widget_' . $args[ 'widget_id' ] );
			if ( $dynamic ) {
				$active = get_field( 'syn_contact_active', $post->ID );
				if ( ! $active ) {
					return;
				}
				$title = get_field( 'syn_contact_title', $post->ID );
			} else {
				$title = get_field( 'syn_contact_widget_title', 'widget_' . $args[ 'widget_id' ] );
			}
			if ( syn_remove_whitespace() ) {
				$lb  = '';
				$tab = '';
			} else {
				$lb  = "\n";
				$tab = "\t";
			}
			$contact      = '';
			$contact_type = ( $dynamic ) ? get_field( 'syn_contact_contact_type', $post->ID ) : get_field( 'syn_contact_widget_contact_type', 'widget_' . $args[ 'widget_id' ] );
			if ( 'person' == $contact_type ) {
				if ( $dynamic ) {
					$user_id        = get_field( 'syn_contact_person', $post->ID );
					$include_fields = get_field( 'syn_contact_include_person_fields', $post->ID );
				} else {
					$user_id        = get_field( 'syn_contact_widget_person', 'widget_' . $args[ 'widget_id' ] );
					$include_fields = get_field( 'syn_contact_widget_include_person_fields', 'widget_' . $args[ 'widget_id' ] );
				}
				if ( $user_id ) {
					$user = get_user_by( 'ID', $user_id );
					if ( $user && $user instanceof WP_User ) {
						$user_meta      = get_user_meta( $user_id );
						$first_name     = $user_meta[ 'first_name' ][ 0 ];
						$last_name      = $user_meta[ 'last_name' ][ 0 ];
						$include_fields = array_column( $include_fields, 'value' );
						$prefix         = get_field( 'syn_user_prefix', 'user_' . $user_id );
						$display_name   = '';
						$display_name   .= ( in_array( 'prefix', $include_fields ) && ! empty( $prefix ) ) ? $prefix . ' ' : '';
						$display_name   .= ( in_array( 'first_name', $include_fields ) && ! empty( $first_name ) ) ? $first_name . ' ' : '';
						$display_name   .= $last_name;
						$titles         = get_field( 'syn_user_title', 'user_' . $user_id );
						$titles         = str_replace( ',', ' / ', $titles );
						$titles         = str_replace( '|', ' / ', $titles );
						$email          = $user->data->user_email;
						$phone          = get_field( 'syn_user_phone', 'user_' . $user_id );
						$ext            = get_field( 'syn_user_extension', 'user_' . $user_id );
						$ext            = ( isset( $ext ) && ! empty( $ext ) ) ? ' x' . $ext : '';
						/**
						 * todo: add ability to associate a photo with a person contact
						 */
						$contact .= $tab . '<div class="list-group-item-content">' . $lb;
						$contact .= $tab . $tab . '<div class="contact-name">' . $display_name . '</div>' . $lb;
						if ( in_array( 'title', $include_fields ) && $titles ) :
							$contact .= $tab . $tab . '<div class="contact-title">' . $titles . '</div>' . $lb;
						endif;
						if ( in_array( 'email', $include_fields ) && $email ) :
							$contact .= $tab . $tab . '<a href="mailto:' . antispambot( $email, true ) . '" class="contact-email" title="Email">' . antispambot( $email ) . '</a>' . $lb;
						endif;
						if ( in_array( 'phone', $include_fields ) && $phone ) :
							$contact .= $tab . $tab . '<div class="contact-phone">' . $phone . $ext . '</div>' . $lb;
						endif;
						$contact .= $tab . '</div>' . $lb;
					}
				}
			} elseif ( 'organization' == $contact_type ) {
				if ( $dynamic ) {
					$default_organization = get_field( 'syn_contact_organization_default', $post->ID );
					$include_fields       = get_field( 'syn_contact_include_organization_fields', $post->ID );
				} else {
					$default_organization = get_field( 'syn_contact_widget_default', 'widget_' . $args[ 'widget_id' ] );
					$include_fields       = get_field( 'syn_contact_widget_include_organization_fields', 'widget_' . $args[ 'widget_id' ] );
				}
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
					if ( $dynamic ) {
						$organization_id = get_field( 'syn_contact_organization', $post->ID );
					} else {
						$organization_id = get_field( 'syn_contact_widget_organization', 'widget_' . $args[ 'widget_id' ] );
					}
					if ( have_rows( 'syn_organizations', 'option' ) ) :
						while( have_rows( 'syn_organizations', 'option' ) ) : the_row();
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
				if ( $organization ) {
					// build organization output according to $include_fields
					$include_fields = array_column( $include_fields, 'value' );
					if ( in_array( 'logo', $include_fields ) ) {
						if ( $logo ) :
							$contact .= $tab . '<div class="list-group-item-feature">' . $lb;
							if ( in_array( 'url', $include_fields ) && $url ) :
								$contact .= $tab . $tab . '<a href="' . $url . '" title="Go to ' . $organization . '">' . $lb;
							endif;
							$contact .= $tab . $tab . $tab . '<img src="' . $logo[ 'url' ] . '" class="contact-image" alt="' . $organization . ' Logo">' . $lb;
							if ( in_array( 'url', $include_fields ) && $url ) :
								$contact .= $tab . $tab . '</a>' . $lb;
							endif;
							$contact .= $tab . '</div>' . $lb;
						endif;
					}
					$contact .= $tab . '<div class="list-group-item-content">' . $lb;
					// name
					$contact .= $tab . $tab . '<div class="contact-name">' . $organization . '</div>' . $lb;
					// address
					if ( in_array( 'address', $include_fields ) && $address ) :
						$contact .= $tab . $tab . '<div class="contact-address">' . $address . '</div>' . $lb;
						if ( ! empty( $address_2 ) ) :
							$contact .= $tab . $tab . '<div class="contact-address-2">' . $address_2 . '</div>' . $lb;
						endif;
						if ( $city || $state || $zip_code ) :
							$contact .= $tab . $tab . '<div class="contact-city-state-zip-code">' . $lb;
							if ( ! empty( $city ) ) :
								$contact .= $tab . $tab . $tab . '<div class="contact-city">' . $city . '</div>' . $lb;
							endif;
							if ( ! empty( $state ) ) :
								$contact .= $tab . $tab . $tab . '<div class="contact-state">' . $state . '</div>' . $lb;
							endif;
							if ( ! empty( $zip_code ) ) :
								$contact .= $tab . $tab . $tab . '<div class="contact-zip-code">' . $zip_code . '</div>' . $lb;
							endif;
							$contact .= $tab . $tab . '</div>' . $lb;
						endif;
					endif;
					// email
					if ( in_array( 'email', $include_fields ) && $email ) :
						$contact .= $tab . $tab . '<a href="mailto:' . antispambot( $email, true ) . '" class="contact-email" title="Email">' . antispambot( $email ) . '</a>' . $lb;
					endif;
					// phone
					if ( in_array( 'phone', $include_fields ) && $phone ) :
						$contact .= $tab . $tab . '<div class="contact-phone">' . $phone . $ext . '</div>' . $lb;
					endif;
					// fax
					if ( in_array( 'fax', $include_fields ) && $fax ) :
						$contact .= $tab . $tab . '<div class="contact-fax">' . $fax . ' fax</div>' . $lb;
					endif;
					// url
					if ( in_array( 'url', $include_fields ) && $url ) :
						$contact .= $tab . $tab . '<a href="' . $url . '" class="contact-url" title="Go to ' . get_sub_field( 'name' ) . '">' . $url_display . '</a>' . $lb;
					endif;
					$contact .= $tab . '</div>' . $lb;
				}
			} else {
				return;
			}
			$sidebar_class = syn_get_sidebar_class( $args[ 'widget_id' ] );
			// widget title
			echo $args[ 'before_widget' ] . $lb;
			if ( ! empty( $title ) ) :
				echo $args[ 'before_title' ] . $title . $args[ 'after_title' ] . $lb;
			endif;
			echo '<div class="list-group ' . $sidebar_class . '">' . $lb;
			echo $tab . '<div class="list-group-item">' . $lb;
			echo $contact;
			echo $tab . '</div>' . $lb;
			echo '</div>' . $lb;
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
		public function form( $instance ) { }
	}
