(function ($) {

	/**
	 * Render Google Calendar with FullCalendar javascript library
	 *
	 * See https://fullcalendar.io/
	 */
	renderCalendar = function(events) {
		console.log(window.innerWidth);
		var args = {
			themeSystem: 'standard',
			header: {
				left: 'prev,today,next',
				center: 'title',
				right: 'listDay,listWeek,month,listMonth,listYear'
			},
			navLinks: false, // can click day/week names to navigate views
			selectable: false,
			eventLimit: false, // allow "more" link when too many events
			defaultView: (window.innerWidth > 768) ? 'month' : 'listMonth',
			//googleCalendarApiKey: google_api_key,
			events: events,
			columnFormat: 'ddd',
			timeFormat: 'h:mmA',
			// agenda formatting
			allDayText: 'All day',
			slotDuration: '01:00:00',
			slotLabelFormat: 'h:mmA',
			snapDuration: '00:15:00',
			scrollTime: '08:00:00',
			minTime: '06:00:00',
			// list formatting
			noEventsMessage: 'No events',
			/*customButtons: {
				accessibleView: {
					text: 'Accessible',
					click: function() {
						alert('clicked the custom button!');
					}
				}
			},*/
			views: {
				month: {
					titleFormat: 'MMMM YYYY',
					displayEventTime: true,
					timeFormat: 'h:mmA',
					displayEventEnd: false
				},
				listDay: {
					titleFormat: 'dddd, MMMM D, YYYY',
					listDayFormat: 'dddd, MMMM D, YYYY',
					listDayAltFormat: null,
					displayEventTime: true,
					displayEventEnd: true
				},
				listWeek: {
					titleFormat: 'MMMM D, YYYY',
					listDayFormat: 'dddd, MMMM D, YYYY',
					listDayAltFormat: null,
					displayEventTime: true,
					displayEventEnd: true
				},
				listMonth: {
					titleFormat: 'MMMM YYYY',
					listDayFormat: 'dddd, MMMM D, YYYY',
					listDayAltFormat: null,
					displayEventTime: true,
					displayEventEnd: true
				},
				listYear: {
					titleFormat: 'YYYY',
					listDayFormat: 'dddd, MMMM D, YYYY',
					listDayAltFormat: null,
					displayEventTime: true,
					displayEventEnd: true
				}
			},
			buttonText: {
				today: 'Today',
				month: 'Month',
				agendaWeek: 'Week',
				agendaDay: 'Day',
				listDay: 'Day',
				listWeek: 'Week',
				listMonth: 'Month',
				listYear: 'Year',
				prev: '<',
				next: '>'
			},
			eventRender: function(event, element) {
				var eventDateTime = (event.allDay) ? event.start.format('LL') : event.start.format('dddd, MMMM D, YYYY h:mmA');
				var eventTitle = event.title;
				var eventDescription = event.description;
				var anchorTitle = document.createAttribute('title');
				anchorTitle.value = eventDateTime + '-' + eventTitle;
				if (element[0].tagName == 'A') {
					element[0].attributes.setNamedItem(anchorTitle);
				} else if (element[0].tagName == 'TR') {
					if (element[0].children.length) {
						for ( var i=0; i<element[0].children.length; i++) {
							if (element[0].children[i].firstElementChild && element[0].children[i].firstElementChild.tagName == 'A') {
								element[0].children[i].firstElementChild.attributes.setNamedItem(anchorTitle);
							}
						}
					}
				}
			}
		};
		$('#full-calendar').empty().fullCalendar(args);
	};

	function renderFullcalendarMenu(selector_id, google_calendar_id) {
		var calendar = $('#' + selector_id).fullCalendar();
		var eventSources = calendar.fullCalendar('getEventSources');
		if (eventSources) {

			for (var m = 0; m < eventSources.length; m++) {

			}
		}

		return;
		if ($container && eventSources) {
			for (var i = 0; i < eventSources.length; i++) {
				$container.append('<label><input type="checkbox" name="googleCalendarId" value="' + eventSources[i].googleCalendarId + '">' + eventSources[i].googleCalendarId + '</label>');
			}
		}
	}

	function addGoogleCalendarEventSources(container, googleCalendarIds) {
		var $container = $('#' + container);
		var eventSources = $container.fullCalendar('getEventSources');
		var eventSource;
		var eventSourceCount = eventSources.length;
		var calendarCount;
		for (var i = 0; i < googleCalendarIds.length; i++) {
			calendarCount = eventSourceCount + i;
			eventSource = {
				googleCalendarId: googleCalendarIds[i],
				className: 'calendar_' + calendarCount
			};
			$container.fullCalendar('addEventSource', eventSource);
		}
	}

	function removeGoogleCalendarEventSources(container, googleCalendarIds) {
		var $container = $('#' + container);
		$container.fullCalendar('removeEventSources', googleCalendarIds);
	}

	/**
	 * Adjust the height of the banner header according to window inner width
	 *
	 * This function short-wires if a cookie exists with the correct height. If this
	 * happens, the banner height is set by PHP which reads the same cookie an
	 * generates CSS to set the banner height.
	 *
	 * The PHP function is syn_print_banner_styles() in /inc/setup.php
	 *
	 * @param containerClass - class of the DOM element containing the banner
	 */
	function setBannerHeight(containerClass) {
		// Get the window inner width
		var innerWidth = window.innerWidth;
		// Calculate the target banner height proportionate to the full size banner (1920px x 500px - AR 3.84)
		var targetHeight = innerWidth / 1920 * 500;
		// Get the banner height stored in a cookie (may be undefined)
		var cookieHeight = getCookie('bannerHeight');
		// If cookie doesn't exist or it's different from the current calculated target banner height
		if (!cookieHeight || cookieHeight != targetHeight) {
			// Set a cookie with the target height
			setCookie('bannerHeight', targetHeight);
			// Set the banner height to the target
			$('.' + containerClass).css('height', targetHeight + 'px');
		}
	}

	// Call function to set banner height
	// todo: is this still in use and if so can the argument be variable-ized?

	/**
	 * Convert ACF "*" on required fields to "(required)" for ADA
	 */
	$('.acf-required').text('(required)');

	/**
	 * Set a cookie in browser
	 *
	 * @param cName - cookie name
	 * @param cValue - cookie value
	 * @param cDays - days before expires
	 * @param cPath - effective path for the cookie
	 * @param cDomain - effective domain for the cookie
	 *
	 * @returns {null}
	 */
	function setCookie(cName, cValue, cDays, cPath, cDomain) {
		var nameValue, expires, path, domain;
		if (!cName || !cValue) {
			return null;
		} else {
			nameValue = cName + '=' + cValue;
		}
		if (cDays) {
			var date = new Date();
			date.setTime(date.getTime() + (cDays * 24 * 60 * 60 * 1000));
			expires = '; expires='.date.toUTCString();
		} else {
			expires = '';
		}
		if (cPath) {
			path = '; path=' + cPath;
		} else {
			path = '; path=/';
		}
		if (cDomain) {
			domain = '; domain=' + cDomain;
		} else {
			domain = '';
		}
		document.cookie = nameValue + expires + path + domain;
	}

	/**
	 * Get a cookie from browser
	 *
	 * @param name - name of the cookie to retrieve
	 *
	 * @returns value or null if not found
	 */
	function getCookie(name) {
		var nameEQ = name + '=';
		var ca = document.cookie.split(';');
		for (var i = 0; i < ca.length; i++) {
			var c = ca[i];
			while (c.charAt(0) == ' ') {
				c = c.substring(1, c.length);
				if (c.indexOf(nameEQ) === 0) {
					return c.substring(nameEQ.length, c.length);
				}
			}
		}
		return null;
	}

	/**
	 * Expire a cookie now
	 *
	 * @param name of the cookie to expire
	 */
	function expireCookie(name) {
		setCookie(name, '', -1);
	}

	/**
	 * Google Translate widget
	 */
	function googleTranslateElementInit() {
		new google.translate.TranslateElement({
			pageLanguage: 'en',
			autoDisplay: false
		}, 'google-translate');
	}

	// autorun the following functions on document loaded...
	setBannerHeight('banner-wrapper');

	/**
	 * Skip Link Focus
	 *
	 * Helps with accessibility for keyboard only users.
	 *
	 * Learn more: https://git.io/vWdr2
	 */
	var isIe = /(trident|msie)/i.test(navigator.userAgent);

	if (isIe && document.getElementById && window.addEventListener) {
		window.addEventListener('hashchange', function () {
			var id = location.hash.substring(1),
				element;

			if (!( /^[A-z0-9_-]+$/.test(id) )) {
				return;
			}

			element = document.getElementById(id);

			if (element) {
				if (!( /^(?:a|select|input|button|textarea)$/i.test(element.tagName) )) {
					element.tabIndex = -1;
				}

				element.focus();
			}
		}, false);
	}
})(jQuery);

