(function ($) {

	/**
	 * Render Google Calendar with FullCalendar javascript library
	 *
	 * See https://fullcalendar.io/
	 */
	renderCalendar = function(events) {
		//console.log(window.innerWidth);
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

	fetchMaps = function() {
		var mapWidgets = $('.map-wrapper');
		var config = {};
		for (var i=0; i<mapWidgets.length; i++) {
			config.container = mapWidgets[i].id;

		}

		return;
		var container = document.getElementById(config.container);
		if (container) {
			container.className = 'map-wrapper hidden-print';
			var includeMarkers = (typeof config.include_markers !== 'undefined') ? config.include_markers : false;
			var markers = (includeMarkers && typeof config.markers !== 'undefined') ? config.markers : [];
			var includeBoundary = (typeof config.include_boundary !== 'undefined') ? config.include_boundary : false;
			var boundaryCoordinates = (includeBoundary && typeof config.boundary_coordinates !== 'undefined') ? config.boundary_coordinates : [];
			//console.log(markers);
			var center = (includeMarkers) ? {lat: parseFloat(markers[0].lat), lng: parseFloat(markers[0].lng)} : {lat: parseFloat(config.center_lat), lng: parseFloat(config.center_lng)};
			var zoom = (includeMarkers) ? 17 : config.zoom;
			var styles = [];
			if (config.styles) {
				styles = config.styles;
				styles = styles.replace(/'/g, '"');
				styles = JSON.parse(styles);
			}
			var mapTypeControl = true;
			var mapTypeId = 'roadmap';
			var streetViewControl = false;
			var zoomControl = true;
			var args = {
				center: center,
				disableDefaultUI: true,
				disableDoubleClickZoom: true,
				draggable: true,
				fullscreenControl: false,
				fullscreenControlOptions: {position: 'LEFT_TOP'},
				heading: 0,
				keyboardShortcuts: false,
				mapTypeControl: mapTypeControl,
				mapTypeControlOptions: ['roadmap', 'satellite'],
				mapTypeId: mapTypeId,
				panControl: false,
				rotateControl: false,
				scaleControl: false,
				scrollwheel: false,
				signInControl: false,
				streetViewControl: streetViewControl,
				styles: styles,
				tilt: 0,
				zoom: zoom,
				zoomControl: zoomControl
			};
			map = new google.maps.Map(container, args);
			// bounding coordinates are used to center and zoom the map
			var boundingCoordinates = [];// currently overriding function args
			/*includeBoundary = true;
			boundaryCoordinates = [
				{lng: -120.9265159, lat: 38.017226},
				{lng: -120.9265367, lat: 38.001424},
				{lng: -120.9264367, lat: 37.993024},
				{lng: -120.9264367, lat: 37.992832},
				{lng: -120.9264362, lat: 37.988383},
				{lng: -120.9264357, lat: 37.984024},
				{lng: -120.9263617, lat: 37.983492},
				{lng: -120.926346, lat: 37.983324},
				{lng: -120.9263459, lat: 37.982024},
				{lng: -120.9263459, lat: 37.981889},
				{lng: -120.9263457, lat: 37.980371},
				{lng: -120.9263457, lat: 37.97967},
				{lng: -120.9263457, lat: 37.979524},
				{lng: -120.9263417, lat: 37.979291},
				{lng: -120.9262417, lat: 37.9759795},
				{lng: -120.9262439, lat: 37.9757557},
				{lng: -120.9263166, lat: 37.9650048},
				{lng: -120.9263336, lat: 37.9614129},
				{lng: -120.9263458, lat: 37.9578189},
				{lng: -120.926448, lat: 37.945224},
				{lng: -120.926365, lat: 37.944842},
				{lng: -120.9263439, lat: 37.9446707},
				{lng: -120.9263417, lat: 37.9432278},
				{lng: -120.9263416, lat: 37.9413792},
				{lng: -120.9264052, lat: 37.9217249},
				{lng: -120.926547, lat: 37.920725},
				{lng: -120.9263986, lat: 37.918925},
				{lng: -120.9263908, lat: 37.916556},
				{lng: -120.9263891, lat: 37.91546},
				{lng: -120.926382, lat: 37.914365},
				{lng: -120.9263719, lat: 37.912728},
				{lng: -120.9263678, lat: 37.911091},
				{lng: -120.9263475, lat: 37.902257},
				{lng: -120.9263454, lat: 37.901258},
				{lng: -120.9263428, lat: 37.900259},
				{lng: -120.9263392, lat: 37.898692},
				{lng: -120.9263375, lat: 37.8982041},
				{lng: -120.9263368, lat: 37.8971422},
				{lng: -120.9263367, lat: 37.8914744},
				{lng: -120.9263347, lat: 37.886724},
				{lng: -120.9263187, lat: 37.886185},
				{lng: -120.9262627, lat: 37.884352},
				{lng: -120.9262557, lat: 37.88411},
				{lng: -120.9262427, lat: 37.883619},
				{lng: -120.9262312, lat: 37.883235},
				{lng: -120.9262285, lat: 37.8830809},
				{lng: -120.9262242, lat: 37.8829416},
				{lng: -120.9262147, lat: 37.882792},
				{lng: -120.9262097, lat: 37.88262},
				{lng: -120.9260927, lat: 37.878773},
				{lng: -120.9258907, lat: 37.872137},
				{lng: -120.9258047, lat: 37.869323},
				{lng: -120.9257647, lat: 37.867997},
				{lng: -120.9257664, lat: 37.867806},
				{lng: -120.9256047, lat: 37.862744},
				{lng: -120.9254747, lat: 37.858414},
				{lng: -120.9256667, lat: 37.858403},
				{lng: -120.9256287, lat: 37.858151},
				{lng: -120.9256217, lat: 37.856989},
				{lng: -120.9258357, lat: 37.85696},
				{lng: -120.9316878, lat: 37.856853},
				{lng: -120.9432457, lat: 37.856777},
				{lng: -120.9435752, lat: 37.856762},
				{lng: -120.9435274, lat: 37.8556707},
				{lng: -120.9434913, lat: 37.8550977},
				{lng: -120.9434369, lat: 37.8525633},
				{lng: -120.9431724, lat: 37.8481091},
				{lng: -120.9431592, lat: 37.846756},
				{lng: -120.9427866, lat: 37.8372417},
				{lng: -120.9427122, lat: 37.834887},
				{lng: -120.9426788, lat: 37.833675},
				{lng: -120.9426521, lat: 37.8329832},
				{lng: -120.9425157, lat: 37.8283994},
				{lng: -120.9424932, lat: 37.8276446},
				{lng: -120.9506783, lat: 37.8276446},
				{lng: -120.955918, lat: 37.8276317},
				{lng: -120.9609003, lat: 37.8276317},
				{lng: -120.9652558, lat: 37.8276411},
				{lng: -120.9668738, lat: 37.8276446},
				{lng: -120.9711995, lat: 37.8276575},
				{lng: -120.9782802, lat: 37.8276832},
				{lng: -120.9834814, lat: 37.8276703},
				{lng: -120.9859789, lat: 37.827709},
				{lng: -120.9920169, lat: 37.8276832},
				{lng: -120.9975715, lat: 37.8276628},
				{lng: -120.9981655, lat: 37.8276746},
				{lng: -121.0022428, lat: 37.8276916},
				{lng: -121.0066442, lat: 37.8276575},
				{lng: -121.0120691, lat: 37.8276405},
				{lng: -121.0161293, lat: 37.8276405},
				{lng: -121.0222196, lat: 37.8276746},
				{lng: -121.0241644, lat: 37.8276916},
				{lng: -121.0260069, lat: 37.8276916},
				{lng: -121.0296065, lat: 37.8277258},
				{lng: -121.0330866, lat: 37.8277258},
				{lng: -121.0348438, lat: 37.8277258},
				{lng: -121.0374539, lat: 37.8277087},
				{lng: -121.041247, lat: 37.827624},
				{lng: -121.047227, lat: 37.827538},
				{lng: -121.049904, lat: 37.827486},
				{lng: -121.0530412, lat: 37.827434},
				{lng: -121.0531988, lat: 37.8314879},
				{lng: -121.053429, lat: 37.838272},
				{lng: -121.0535238, lat: 37.8413323},
				{lng: -121.0536592, lat: 37.8454082},
				{lng: -121.0537405, lat: 37.8494435},
				{lng: -121.0538353, lat: 37.8512851},
				{lng: -121.0539707, lat: 37.856011},
				{lng: -121.0541602, lat: 37.8622129},
				{lng: -121.0542686, lat: 37.8649617},
				{lng: -121.0544988, lat: 37.870798},
				{lng: -121.0545665, lat: 37.872951},
				{lng: -121.0547019, lat: 37.8765936},
				{lng: -121.0549456, lat: 37.8835402},
				{lng: -121.0550269, lat: 37.88461},
				{lng: -121.05523, lat: 37.89004},
				{lng: -121.05532, lat: 37.892773},
				{lng: -121.055691, lat: 37.902531},
				{lng: -121.055905, lat: 37.908438},
				{lng: -121.056077, lat: 37.912422},
				{lng: -121.056112, lat: 37.9130185},
				{lng: -121.05613, lat: 37.914111},
				{lng: -121.056297, lat: 37.91886},
				{lng: -121.056593, lat: 37.927712},
				{lng: -121.056686, lat: 37.93132},
				{lng: -121.056736, lat: 37.93253},
				{lng: -121.056745, lat: 37.933299},
				{lng: -121.056723, lat: 37.933469},
				{lng: -121.0567463, lat: 37.9344818},
				{lng: -121.0569433, lat: 37.9399974},
				{lng: -121.05711, lat: 37.9433765},
				{lng: -121.0572246, lat: 37.9481218},
				{lng: -121.0572918, lat: 37.9509074},
				{lng: -121.0574282, lat: 37.9510135},
				{lng: -121.0575343, lat: 37.9541502},
				{lng: -121.057741, lat: 37.962756},
				{lng: -121.039219, lat: 37.962738},
				{lng: -121.0392625, lat: 37.963946},
				{lng: -121.039309, lat: 37.965418},
				{lng: -121.039314, lat: 37.965884},
				{lng: -121.039347, lat: 37.966299},
				{lng: -121.039456, lat: 37.971049},
				{lng: -121.039491, lat: 37.971223},
				{lng: -121.0395815, lat: 37.971352},
				{lng: -121.039593, lat: 37.971716},
				{lng: -121.03958, lat: 37.973111},
				{lng: -121.023534, lat: 37.973323},
				{lng: -121.019621, lat: 37.973374},
				{lng: -121.011716, lat: 37.973418},
				{lng: -121.00794, lat: 37.973404},
				{lng: -121.003489, lat: 37.973433},
				{lng: -120.999572, lat: 37.973443},
				{lng: -120.996336, lat: 37.973445},
				{lng: -120.995186, lat: 37.973454},
				{lng: -120.991255, lat: 37.973463},
				{lng: -120.987323, lat: 37.973482},
				{lng: -120.983518, lat: 37.97351},
				{lng: -120.981732, lat: 37.973512},
				{lng: -120.978214, lat: 37.973543},
				{lng: -120.978359, lat: 38.008696},
				{lng: -120.978381, lat: 38.014123},
				{lng: -120.978386, lat: 38.015191},
				{lng: -120.978394, lat: 38.017176},
				{lng: -120.971509, lat: 38.017231},
				{lng: -120.967687, lat: 38.017237},
				{lng: -120.965872, lat: 38.017258},
				{lng: -120.951063, lat: 38.01732},
				{lng: -120.944757, lat: 38.017346},
				{lng: -120.9265159, lat: 38.017226}
			];*/
			if (includeBoundary && 0 < boundaryCoordinates.length) {
				var boundary = new google.maps.Polygon({
					paths: boundaryCoordinates,
					strokeColor: '#000',
					strokeOpactiy: 1,
					strokeWeight: 3,
					fillColor: '#000000',
					fillOpacity: 0.1
				});
				boundary.setMap(map);
				boundingCoordinates = boundaryCoordinates;
			}
			if (includeMarkers && 0 < markers.length) {
				map.markers = [];
				for (var i = 0; i < markers.length; i++) {
					addMarker(markers[i], map);
					boundingCoordinates.push({lat: markers[i].lat, lng: markers[i].lng});
				}
			}

			centerMap(map, boundingCoordinates);
		}
		map = null;
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
	googleTranslateElementInit = function() {
		console.log('googleTranslateElementInit');
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

