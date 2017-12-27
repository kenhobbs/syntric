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
	$comment_field                  .= '<textarea id="comment" name="comment" class="form-control" aria-required="true" rows="10" maxlength="30000"></textarea>';
	$comment_field                  .= '</div>';
	$args[ 'comment_field' ]        = $comment_field;
	$args[ 'title_reply_before' ]   = '<h2>';
	$args[ 'title_reply' ]          = __( 'Submit Feedback' );
	$args[ 'title_reply_to' ]       = __( 'Reply' );
	$args[ 'title_reply_after' ]    = '</h2>';
	$args[ 'comment_notes_before' ] = '';
	$args[ 'label_submit' ]         = 'Submit';
	$args[ 'must_log_in' ]          = sprintf( __( '<p>You must <a href="%s">login</a> to submit feedback</p>' ), wp_login_url( get_the_permalink() ) );
	//$args[ 'logged_in_as' ]         = '<p class="logged-in-as">' . sprintf( __( 'You are logged in as <a href="%1$s" aria-label="%2$s">%3$s</a>.' ), get_edit_user_link(), esc_attr( sprintf( __( 'You are %s' ), $user_identity ) ), $user_identity ) . '</p>';
	$args[ 'logged_in_as' ]  = '';
	$args[ 'submit_button' ] = '<input name="%1$s" type="submit" id="%2$s" class="%3$s comments-button btn btn-primary" value="%4$s" />';

	return $args;
}