<?php
/**
 * Comment customizations
 *
 * @package syntric
 */
add_filter( 'comment_form_defaults', 'syn_comment_form' );
function syn_comment_form( $args ) {
	global $post;
	$user                           = wp_get_current_user();
	$user_identity                  = $user->exists() ? $user->display_name : '';
	$req_flag                       = ' <span class="required">(required)</span>';
	$comment_field                  = '<div class="form-group">';
	$comment_field                  .= '<label for="comment" class="sr-only">' . _x( 'Feedback', 'noun', 'syntric' ) . '</label>';
	$comment_field                  .= '<textarea id="comment" name="comment" class="form-control" aria-required="true" rows="20" maxlength="30000"></textarea>';
	$comment_field                  .= '</div>';
	$args[ 'comment_field' ]        = $comment_field;
	$args[ 'title_reply_before' ]   = '<h2>';
	$args[ 'title_reply' ]          = __( 'Submit Feedback' );
	$args[ 'title_reply_to' ]       = __( 'Reply' );
	$args[ 'title_reply_after' ]    = '</h2>';
	$args[ 'comment_notes_before' ] = '';
	$args[ 'label_submit' ]         = 'Submit';
	$args[ 'must_log_in' ]          = sprintf( __( 'You must <a href="%s">login</a> to submit feedback' ), wp_login_url( get_the_permalink() ) );
	$args[ 'logged_in_as' ]         = '<p class="logged-in-as">' . sprintf( __( '<a href="%1$s" aria-label="%2$s">You are %3$s</a>' ), get_edit_user_link(), esc_attr( sprintf( __( 'You are %s' ), $user_identity ) ), $user_identity ) . '</p>';
	$args[ 'submit_button' ]        = '<input name="%1$s" type="submit" id="%2$s" class="%3$s btn" value="%4$s" />';

	return $args;
}

//
//
// Bone yard
//
//
/**
 * Customize comment form extra fields such as Name, Email, etc.
 *
 * @param string $fields Form fields.
 *
 * @return array
 */
//add_filter( 'comment_form_default_fields', 'syn_comment_form_fields' );
function ________notinuse_______syn_comment_form_fields( $fields ) {
	$html5              = current_theme_supports( 'html5', 'comment-form' ) ? true : false;
	$aria               = ' aria-required="true"';
	$req                = get_option( 'require_name_email' );
	$req_flag           = ( $req ) ? ' <span class="required">(required)</span>' : '';
	$commenter          = wp_get_current_commenter();
	$author_name        = ( $commenter ) ? esc_attr( $commenter[ 'comment_author' ] ) : '';
	$author_name_value  = ( $author_name ) ? ' value="' . $author_name . '"' : '';
	$author_name_field  = '<div class="form-group">';
	$author_name_field  .= '<label for="author">' . __( 'Name', 'syntric' ) . $req_flag . '</label>';
	$author_name_field  .= '<input id="author" name="author" type="text" class="form-control"' . $author_name_value . $aria . ' />';
	$author_name_field  .= '</div>';
	$author_email       = ( $commenter ) ? esc_attr( $commenter[ 'comment_author_email' ] ) : '';
	$author_email_value = ( $author_email ) ? ' value="' . $author_email . '"' : '';
	$author_email_field = '<div class="form-group">';
	$author_email_field .= '<label for="email">' . __( 'Email', 'syntric' ) . $req_flag . '</label>';
	$author_email_field .= '<input id="email" name="email" class="form-control" ' . ( $html5 ? 'type="email"' : 'type="text"' ) . $author_email_value . $aria . ' />';
	$author_email_field .= '</div>';
	$fields             = array(
		'author' => $author_name_field,
		'email'  => $author_email_field,
	);

	return $fields;
}

/**
 * Customize comment form main content
 *
 * @param string $args Arguments for form's fields.
 *
 * @return mixed
 */
function ____________notinuse___________syn_get_comments_form() {
	echo '<div class="comments-wrapper hidden-print">';
	echo '<div class="container">';
	comment_form();
	echo '</div>';
	echo '</div>';
}