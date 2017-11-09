(function ($) {
	//$('#syn-media-button').click(openMediaWindow);
	//appendAttachmentFile();
	/*var taxonomy = 'page_microblog';
	$('#' + taxonomy + 'checklist li :radio, #' + taxonomy + 'checklist-pop :radio').live('click', function () {
		var t = $(this), c = t.is(':checked'), id = t.val();
		$('#' + taxonomy + 'checklist li :radio, #' + taxonomy + 'checklist-pop :radio').prop('checked', false);
		$('#in-' + taxonomy + '-' + id + ', #in-popular-' + taxonomy + '-' + id).prop('checked', c);
	});*/
	//#recently_published_pages_dashboard_widget > div > table > tbody > tr:nth-child(1)
	$('tr').on('click', function () {
		console.log('row click');
		alert('foo bar');
	});
//#pending_pages_dashboard_widget > div > table > tbody > tr
})(jQuery);

/**
 * Add header, description and file name to syn_attachments_list Flexible Content field headers in Page Attachments group
 */
function appendAttachmentFile() {
	alert('appendAttachmentFile fired in syntric-admin.js');
	var synAttachmentsList = $('.acf-field-5946b1f65b90e');
	if (synAttachmentsList) {
		var alfcHeaderLayouts = $(synAttachmentsList).find('.values div[data-layout="syn_header_layout"]');
		for (var i = 0; i < alfcHeaderLayouts.length; i++) {
			headerTitle = $(alfcHeaderLayouts[i]).find('input[type="text"]');
			headerTitle = headerTitle[0].value;
			fieldHeader = $(alfcHeaderLayouts[i]).find('.acf-fc-layout-handle');
			$(fieldHeader).append(': ' + headerTitle);
		}
		var alfcDescriptionLayouts = $(synAttachmentsList).find('.values div[data-layout="syn_description_layout"]');
		for (var i = 0; i < alfcDescriptionLayouts.length; i++) {
			description = $(alfcDescriptionLayouts[i]).find('textarea');
			description = description[0].value;
			if (description.length > 25) {
				description = description.substr(0, 24) + '...';
			}
			fieldHeader = $(alfcDescriptionLayouts[i]).find('.acf-fc-layout-handle');
			$(fieldHeader).append(': ' + description);
		}
		var alfcFileLayouts = $(synAttachmentsList).find('.values div[data-layout="syn_file_layout"]');
		for (var i = 0; i < alfcFileLayouts.length; i++) {
			fileTitle = $(alfcFileLayouts[i]).find('*[data-name="title"]').text();
			fieldHeader = $(alfcFileLayouts[i]).find('.acf-fc-layout-handle');
			$(fieldHeader).append(': ' + fileTitle);
		}
	}
}

function openMediaWindow() {
	alert('openMediaWindow fired in syntric-admin.js');
	if (this.window === undefined) {
		this.window = wp.media({
			title: 'Insert Images',
			library: {type: ['image']},
			multiple: true,
			button: {text: 'Insert'}
		});

		var self = this; // Needed to retrieve our variable in the anonymous function below

		this.window.on('select', function () {
			var files = self.window.state().get('selection').toArray();
			for (var i = 0; i < files.length; i++) {
				var file = files[i].toJSON();
				console.log(file);
				var imageString = wp.media.string.image(file, file);
				wp.media.editor.insert(imageString);
			}

		});
	}

	this.window.open();
	return false;

}