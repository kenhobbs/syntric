/*(function ($) {
	$('#publish').on('click', function () {
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
	});

})(jQuery);*/
