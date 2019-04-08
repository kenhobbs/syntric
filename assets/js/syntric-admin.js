(function ($) {

	// disable syntric_calendar last_sync and last_sync_result form fields
	$('#acf-field_5c770d872bcdf-field_5c86c1ac08f4d').attr('disabled', 'disabled');
	$('#acf-field_5c770d872bcdf-field_5c86c19e08f4c').attr('disabled', 'disabled');
	$('#acf-field_5c770d872bcdf-field_5ca13b24e0bf5').attr('disabled', 'disabled');
	$('#acf-field_5c873b64763cd-field_5c80dc4a54a41').attr('disabled', 'disabled');
	//$('td[data-name=id] input').attr('disabled', 'disabled');
	//$('div[data-name=syntric_classes] td[data-name=class_page] select').attr('disabled', 'disabled');
	//$('div[data-name=course] div[data-name=id] input').attr('disabled', 'disabled');

	var idFields = $('*[data-name=id] input');
	for (var i = 0; i < idFields.length; i++) {
		idField = $(idFields[i]);
		if (idField.val()) {
			idField.attr('disabled', 'disabled');
		} else {
			idField.hide();
		}
	}

})(jQuery);

///////////////////////////////////////////////////////////////////////////// Boneyard
/**
 * Hide description field in category add/edit form
 */
/*var termDescription = $('.term-description-wrap');
if (termDescription.length) {
	termDescription.hide();
}*/
/**
 * Hide category slug field
 */
/*var tagSlug = $('.term-slug-wrap');
if (tagSlug.length) {
	tagSlug.hide();
}*/
/**
 * Alter row actions in category list table
 * If category is default (term_id = 1), Microblogs or one of it's children, remove row action links and replace with message
 */
/*var rowActions = $('.row-actions');
if (rowActions.length) {
	var rowAnchors = $('.row-actions span a');
	for (var i = 0; i < rowAnchors.length; i++) {
		if (rowAnchors[i].nextSibling) {
			if (rowAnchors[i].text == 'View') {
				rowAnchors[i].nextSibling.textContent = '';
			}
		}
	}
}*/
/**
 * Remove anchor link from category name in category list table
 */
/*var categoriesListTable = $('.edit-tags-php .wp-list-table tr#tag-1');
if (categoriesListTable.length) {
	//$('.wp-list-table .check-column').hide();
	var categoryRows = $('#the-list tr');
	var rowName = '';
	var microblogsCategoryId = -1;
	for(var i=0;i<categoryRows.length;i++) {
		rowHiddenFieldsName = $('#' + categoryRows[i].id + ' .name .hidden .name').text();
		rowHiddenFieldsParent = $('#' + categoryRows[i].id + ' .name .hidden .parent').text();
		console.log('name: ' + rowHiddenFieldsName);
		console.log('parent: ' + rowHiddenFieldsParent);
		console.log('microblogsCategoryId: ' + microblogsCategoryId);
		console.log(rowHiddenFieldsName == 'Microblogs');
		console.log(rowHiddenFieldsParent == 0);
		console.log(rowHiddenFieldsParent == microblogsCategoryId);
		if (rowHiddenFieldsName == 'Microblogs' && rowHiddenFieldsParent == 0) {
			var rowIdArray = categoryRows[i].id.split('-');
			microblogsCategoryId = rowIdArray[rowIdArray.length-1];
			$('#' + categoryRows[i].id).hide();
		} else if (rowHiddenFieldsParent == microblogsCategoryId) {
			$('#' + categoryRows[i].id).hide();
		} else {
			//rowTitleAnchor = $('#' + categoryRows[i].id + ' .row-title');
			//rowTitleTarget = $('#' + categoryRows[i].id + ' .name strong');
			//rowName = rowTitleAnchor.text();
			//rowTitleTarget.text(rowName);
		}
	}
}*/
/**
 * Toggle category home page field in category add/edit
 */
/*var termParentSelect = $('.term-php form #parent');
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
}*/
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