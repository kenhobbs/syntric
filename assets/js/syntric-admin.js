(function ($) {
	var fieldsToDisable = [];
	fieldsToDisable.push('.acf-field[data-name=id] input');
	fieldsToDisable.push('.acf-field[data-name=last_sync] input');
	fieldsToDisable.push('.acf-field[data-name=last_sync_result] textarea');
	fieldsToDisable.push('.acf-field[data-name=course_page]');
	fieldsToDisable.push('.acf-field[data-name=department_page]');
	fieldsToDisable.push('.acf-field[data-name=class_page]');

	// Class fields that are populated
	fieldsToDisable.push('.acf-field[data-key=field_5cb6d14cff0bd] select');
	fieldsToDisable.push('.acf-field[data-key=field_5cb5ba20c5f23] select');
	fieldsToDisable.push('.acf-field[data-key=field_5cb5ba2fc5f24] select');
	fieldsToDisable.push('.acf-field[data-key=field_5cb5ba41c5f25] select');

	// Course fields
	fieldsToDisable.push('.acf-field[data-key=field_5c6fae31bbd6d] select');
	fieldsToDisable.push('.acf-field[data-key=field_5c6fae26bbd6c] input');

	disableFields(fieldsToDisable);

	/*var disabledField;
	for (var i = 0; i < disabledFields.length; i++) {
		disabledField = $(disabledFields[i]);
		disabledField.attr('disabled', 'disabled');
	}*/

	function disableFields(fieldsToDisable) {
		var fieldToDisable;
		for (var i = 0; i < fieldsToDisable.length; i++) {
			fieldToDisable = $(fieldsToDisable[i]);
			fieldToDisable.attr('disabled', 'disabled');
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