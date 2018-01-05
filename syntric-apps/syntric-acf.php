<?php
	/**
	 * Advanced Custom Fields
	 */
	/**
	 * Set default order on ACF Field Groups list (cause it's annoying)
	 */
	add_filter( 'pre_get_posts', 'syn_acf_pre_get_posts', 1, 1 );
	function syn_acf_pre_get_posts( $query ) {
		global $pagenow;
		if( is_admin() && $query->is_main_query() && 'edit.php' == $pagenow && 'acf-field-group' == $query->get( 'post_type' ) ) {
			if( ! isset( $_GET[ 'orderby' ] ) ) {
				$query->set( 'orderby', 'post_title' );
				$query->set( 'order', 'ASC' );
			}
			if( ! isset( $_GET[ 'post_status' ] ) ) {
				$query->set( 'post_status', 'publish' );
			}
		}

		return $query;
	}

	/**
	 * Get the key for a field
	 *
	 * @param $field_name
	 * @param $post_id - can be 'user_', 'widget_' or 'option' too
	 */
	function syn_get_field_key( $field_name, $post_id, $type = 'field' ) {
		if( ! isset( $post_id ) || empty( $post_id ) ) {
			return;
		}
		$field_obj = get_field_object( $field_name, $post_id );

		return $field_obj[ 'key' ];
	}

	/**
	 * Change the path where ACF will save the local JSON file
	 */
	add_filter( 'acf/settings/save_json', 'syn_acf_json_save_point' );
	function syn_acf_json_save_point() {
		$path = get_stylesheet_directory() . '/assets/json';

		return $path;
	}

	/**
	 * Specify path where ACF should look for local JSON files
	 */
	add_filter( 'acf/settings/load_json', 'syn_acf_json_load_point' );
	function syn_acf_json_load_point( $paths ) {
		// remove original path (optional)
		unset( $paths[ 0 ] );
		// append new path
		$paths[] = get_stylesheet_directory() . '/assets/json';

		return $paths;
	}

	/**
	 * Advanced Form
	 */
//add_action( 'af/form/submission/id=contact-form', 'syn_af_process_contact_form', 20, 3 );
	function syn_af_process_contact_form( $form, $fields, $args ) {
		if( is_admin() ) {
			return;
		}
		$recipient       = af_get_field( 'syn_cf_recipient' );
		$recipient_array = explode( '_', $recipient );
		$recipient_type  = $recipient_array[ 0 ];
		$recipient_id    = $recipient_array[ 1 ];
		$emails          = [];
		If( 'general' == $recipient_type ) {
			$user_args = [
				'meta_key'     => 'syn_user_general_email',
				'meta_value'   => '1',
				'meta_compare' => '=',
				'fields'       => [
					'ID',
					'user_email',
				],
			];
			$users     = get_users( $user_args );
			if( $users ) {
				foreach( $users as $user ) {
					if( $user ) {
						$email    = $user->user_email;
						$emails[] = $email;
					}
				}
			}
		} elseif( 'user' == $recipient_type ) {
			$user = get_user_by( 'id', (int) $recipient_id );
			if( $user ) {
				$emails[] = $user->get( 'user_email' );
			}
		} elseif( 'department' == $recipient_type ) {
			$user_ids = get_objects_in_term( (int) $recipient_id, $recipient_type );
			foreach( $user_ids as $user_id ) {
				$user = get_user_by( 'id', (int) $user_id );
				if( $user ) {
					$email = $user->get( 'user_email' );
					if( filter_var( $email, FILTER_VALIDATE_EMAIL ) ) {
						$emails[] = $email;
					}
				}
			}
		}
		// add emails to form so they will get sent along with the form configured emails
		if( 'foo' == 'bar' ) {
			if( sizeof( $emails ) > 0 ) {
				foreach( $emails as $email ) {
					$form[ 'emails' ][] = [
						'name'             => 'Processed email',
						'active'           => true,
						'recipient_type'   => 'custom',
						'recipient_field'  => [],
						'recipient_custom' => $email,
						'from'             => '{field:syn_cf_sender_email',
						'subject'          => 'Contact form message',
						'content'          => '
<p>The following email was sent from the contact form on the website.</p>
<p>{field:syn_cf_sender_name}<br>
{field:syn_cf_sender_email}<br>
{field:syn_cf_sender_phone}</p>
<p>{field:syn_cf_message}</p>',
					];
				}
			}
		}
		// send emails from here
		if( 'bar' == 'bar' ) {
			$sender_name  = '';
			$sender_email = '';
			$sender_phone = '';
			$message      = '';
			foreach( $fields as $field ) {
				if( $field[ 'name' ] == 'syn_cf_sender_name' ) {
					$sender_name = $field[ 'value' ];
				}
				if( $field[ 'name' ] == 'syn_cf_sender_email' ) {
					$sender_email = $field[ 'value' ];
				}
				if( $field[ 'name' ] == 'syn_cf_sender_phone' ) {
					$sender_phone = $field[ 'value' ];
				}
				if( ! empty( $sender_name ) || ! empty( $sender_email ) || ! empty( $sender_phone ) ) {
					$message .= '<p>';
				}
				if( ! empty( $sender_name ) ) {
					$message .= $sender_name . '<br>';
				}
				if( ! empty( $sender_email ) ) {
					$message .= $sender_email . '<br>';
				}
				if( ! empty( $sender_phone ) ) {
					$message .= $sender_phone;
				}
				if( ! empty( $sender_name ) || ! empty( $sender_email ) || ! empty( $sender_phone ) ) {
					$message .= '</p>';
				}
				if( $field[ 'name' ] == 'syn_cf_message' ) {
					$message .= '<p>' . $field[ 'value' ] . '</p>';
				}
			}
			$headers = 'From: ' . $sender_name . '<' . $sender_email . '>' . "\r\n";
			//$subject = 'Contact form message';
			if( ! empty( $headers ) && ! empty( $message ) ) {
				foreach( $emails as $email ) {
					wp_mail( $email, 'Contact form message', $message, $headers );
				}
			}
		}
	}

	/**
	 * Move content rich text editor into tab
	 */
//add_action( 'acf/input/admin_head', 'syn_move_content_editor' );
	function syn_move_content_editor() {
		?>
		<script type="text/javascript">
			(function ($) {

				$(document).ready(function () {
					$('.acf-field-59c596b2b4647 .acf-input').append($('#postdivrich'));

				});

			})(jQuery);
		</script>    <!--<style type="text/css">
		.acf-field #wp-content-editor-tools {
			background: transparent;
			padding-top: 0;
		}
	</style>-->
		<?php
	}