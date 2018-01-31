(function ($) {
	//$('#postdivrich').appendTo($('.acf-field-5a713f5d2a070 .acf-input'));
})(jQuery);

/**
 * Add header, description and file name to syn_attachments_list Flexible Content field headers in Page Attachments group
 */
/*function appendAttachmentFile() {
	alert( 'appendAttachmentFile fired in syntric-admin.js');
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
}*/

/*
function openMediaWindow() {
	alert( 'openMediaWindow fired in syntric-admin.js');
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

}*/
