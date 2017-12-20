<?php if ( comments_open() ) : ?>
	<div class="comments-wrapper d-print-none">
		<div class="container-fluid">
			<div class="row">
				<div class="col text-center">
					END OF PAGE
				</div>
			</div>
			<div class="row">
				<div class="col">
					<h1>Site Review</h1>
					<?php if ( is_user_logged_in() ) : ?>
						<p>Submit feedback using the form below. Please include full copy for any text changes. To send files such as PDFs or images, mention it in your feedback and you will receive an email with instructions to send files.</p>
					<?php else : ?>
						<p>You must
							<a href="<?php echo wp_login_url( get_the_permalink() ); ?>">login</a> to submit site review feeback
						</p>
					<?php endif; ?>
				</div>
			</div>
			<?php if ( is_user_logged_in() ) : ?>
				<div class="row">
					<div class="col-lg-4">
						<?php comment_form(); ?>
					</div>
					<div class="col-lg-8">
						<h2>Feedback &amp; Replies</h2>
						<?php if ( have_comments() ) : ?>
							<ul class="comments-list">
								<?php wp_list_comments(); ?>
							</ul>
						<?php else : ?>
							<p>No feedback has been submitted for this page.</p>
						<?php endif; ?>
					</div>
				</div>
			<?php endif; ?>
		</div>
	</div>
<?php endif; ?>