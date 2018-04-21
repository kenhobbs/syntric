(function ($) {
	/*$('#publish').on('click', function () {
		var titleEl = $('[id^="titlediv"]').find('#title');
		//console.log(titleEl);
		if (titleEl && titleEl.text().length < 1) {
			alert('Title is required');
			//titleEl.css('border-color', 'red');
			//setTimeout(titleEl.css('border-color', '#dddddd'), 1000);
			//$('[id^="titlediv"]').css('background', '#F96');
			//setTimeout('$(\'#ajax-loading\').css(\'visibility\', \'hidden\');', 100);
			titleEl.focus(
				function () {
					titleEl.css('border-color', 'red');
				}
			);
			titleEl.blur(
				function () {
					titleEl.css('border-color', '#ddd');
				}
			);
			titleEl.focus();
			//titleEl.off('focus', titleEl.css('border-color', 'blue'));
			//setTimeout('$(\'#publish\').removeClass(\'button-primary-disabled\');', 100);
			//return false;
		}
	});*/
	var categoryDescription = $('.term-description-wrap');
	if (categoryDescription.length) {
		categoryDescription.hide();
	}
	var pageField = $('.acf-field-5ad3454acb6a2');
	if (pageField.length) {
		pageField.hide();
		$('#tag-slug').attr('disabled', 'disabled').attr('placeholder', 'Slug is set automatically');
		$('.edit-tags-php form #parent').on('change', function (e) {
			var category = e.currentTarget.selectedOptions[0].label;
			var tagName = $('#tag-name');
			if ('Microblogs' == category) {
				pageField.show();
				tagName.attr('disabled', 'disabled').attr('placeholder', 'Microblog names are set automatically');
			} else {
				pageField.hide();
				tagName.removeAttr('disabled').removeAttr('placeholder');
			}
		});
		var termParentSelect = $('.term-php form #parent');
		if (termParentSelect.length) {
			if (termParentSelect[0].selectedOptions[0].text == 'Microblogs') {
				pageField.show();
			} else {
				pageField.hide();
			}
			termParentSelect.on('change', function (e) {
				var category = e.currentTarget.selectedOptions[0].label;
				if ('Microblogs' == category) {
					pageField.show();
				} else {
					pageField.hide();
				}
			});
		}
	}
	var rowActions = $('.row-actions');
	if (rowActions.length) {
		var rowAnchors = $('.row-actions span a');
		console.log(rowAnchors);
		for (var i = 0; i < rowAnchors.length; i++) {
			if (rowAnchors[i].nextSibling) {
				if (rowAnchors[i].text == 'View') {
					rowAnchors[i].nextSibling.textContent = '';
				}
			}
		}
	}

})(jQuery);
