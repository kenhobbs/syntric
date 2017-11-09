(function ($) {
	/*$('.calendar-menu-link').click(function (event) {
		console.log(event);
		event.preventDefault();
		$.ajax({
			url: syntric_api.ajax_url,
			method: 'post',
			data: {
				_ajax_nonce: syntric_api.nonce,
				action: 'fetch_calendar_events',
				post_id: event.currentTarget.dataset.id
			},
			success: function (response, xhr, message) {
				console.log(response);
				console.log(xhr);
				console.log(message);
				renderCalendar(response);
			},
			error: function (error) {
				console.log(error);
			}
		});
	});*/

	fetchCalendarEvents = function (post_id) {
		$.ajax({
			url: syntric_api.ajax_url,
			method: 'post',
			data: {
				_ajax_nonce: syntric_api.nonce,
				action: 'fetch_calendar_events',
				post_id: post_id
			},
			success: function (response, xhr, message) {
				renderCalendar(response);
			},
			error: function (error) {
				//console.log(error);
				alert('Error loading calendar');
			}
		});
	};

})(jQuery);