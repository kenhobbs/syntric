<?php if ( comments_open() ) : ?>
	<div class="comments-wrapper d-print-none">
		<div class="container">
			<div class="row">
				<div class="col text-center">
					END OF PAGE
				</div>
			</div>
			<div class="row">
				<div class="col">
					<h1>Site Review</h1>
					<p>This site is in review. Submit feedback using the form below.</p>
				</div>
			</div>
			<div class="row">
				<div class="col">
					<?php comment_form(); ?>
				</div>
				<div class="col">
					<h2>Submissions</h2>
					<ul class="comments-list">
						<?php
						if ( have_comments() ) {
							wp_list_comments();
						} else {
							echo '<li>No submissions</li>';
						}
						?>
					</ul>
				</div>
			</div>
		</div>
	</div>
<?php endif; ?>