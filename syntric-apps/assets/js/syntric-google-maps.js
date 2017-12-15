/**
 * Global maps variable
 *
 * @type array
 */
var map;

/**
 * Global infoWindows variable
 *
 * @type array
 */
var infoWindows = [];

var renderMapWidgets = function () {


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

/**
 *  renderMap function
 *
 *  Renders a Google Map and adds markers
 *
 *  @type    function
 *
 *  @param    config    array    function arguments:
 *                            config.container - DOM element in which to render the map
 *                            config.include_markers - boolean that controls marker rendering (regardless config.markers)
 *                            config.markers - array of lat/lng coordinates to render markers
 *                            config.include_boundary - boolean that controls boundary rendering (regardless config.boundary_coordinates)
 *                            config.boundary_coordinates - array of lat/lng coordinates to render polygon
 *                            config.center_lat - fallback latitude for centering map if no markers or boudary
 *                            config.center_lng - fallback longitude for centering map if no markers or boundary
 *                            config.zoom - fallback map zoom level in no markers or boundary
 *                            config.styles - Google Maps json styles config
 *                            config.map_type_control - currently unused (magic value)
 *                            config.map_type_id - currently unused (magic value)
 *                            config.street_view_control - currently unused (magic value)
 *                            config.zoom_control - currently unused (magic value)
 *  @return    n/a
 */
var renderMap = function (config) {
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

/**
 *  addMarker function
 *
 *  Adds a marker to a Google Map
 *
 *  @type    function
 *
 *  @param    markerConfig
 *  @param    map
 *  @return    n/a
 */
function addMarker(markerConfig, map) {
	var latlng = new google.maps.LatLng(parseFloat(markerConfig.lat), parseFloat(markerConfig.lng));
	var title = (markerConfig.name) ? markerConfig.name : '';
	var icon = (markerConfig.icon) ? markerConfig.icon : '/wp-content/themes/syntric/syntric-apps/assets/images/google-map-marker.png';
	var marker = new google.maps.Marker({
		position: latlng,
		map: map,
		title: title,
		icon: {
			url: icon,
			size: new google.maps.Size(32, 32)
		}
	});
	map.markers.push(marker);
	/*var infoWindowContent = '';
	//infoWindowContent += (markerConfig.name || markerConfig.address || markerConfig.address_2 || markerConfig.city || markerConfig.state || markerConfig.zip_code) ? '<p>' : '';
	infoWindowContent += '<p>';
	infoWindowContent += (markerConfig.name) ? markerConfig.name + '<br>' : '';
	infoWindowContent += (markerConfig.address) ? markerConfig.address + '<br>' : '';
	infoWindowContent += (markerConfig.address_2) ? markerConfig.address_2 + '<br>' : '';
	infoWindowContent += (markerConfig.city) ? markerConfig.city : ' ';
	infoWindowContent += (markerConfig.state) ? ', ' + markerConfig.state + ' ' : '';
	infoWindowContent += (markerConfig.zip_code) ? markerConfig.zip_code : '';
	infoWindowContent += (markerConfig.city || markerConfig.state || markerConfig.zip_code) ? '<br>' : '';
	//infoWindowContent += (markerConfig.name || markerConfig.address || markerConfig.address_2 || markerConfig.city || markerConfig.state || markerConfig.zip_code) ? '</p>' : '';
	infoWindowContent += (markerConfig.phone) ? markerConfig.phone : '';
	infoWindowContent += (markerConfig.extension) ? ' ext. ' + markerConfig.extension : '';
	infoWindowContent += (markerConfig.phone || markerConfig.extension) ? '<br>' : '';
	infoWindowContent += (markerConfig.email) ? markerConfig.email + '<br>' : '';
	infoWindowContent += (markerConfig.url) ? markerConfig.url : '';
	infoWindowContent += '</p>';
	var infoWindow = new google.maps.InfoWindow({
		content: infoWindowContent
	});
	infoWindows.push(infoWindow);
	google.maps.event.addListener(marker, 'click', function () {
		for (var i = 0; i < infoWindows.length; i++) {
			infoWindows[i].close();
		}
		infoWindow.open(map, marker);
	});*/
}

/**
 *  centerMap function
 *
 *  Centers map to contain all markers
 *
 *  @type    function
 *
 *  @param    map (Google Map object)
 *  @return    n/a
 */
function centerMap(map, coordinates) {
	if (typeof coordinates === 'object' && coordinates.length >= 1) {
		if (coordinates.length === 1) {
			var coordinate = new google.maps.LatLng(coordinates[0].lat, coordinates[0].lng);
			map.setCenter(coordinate);
		} else {
			var bounds = new google.maps.LatLngBounds();
			for (var i = 0; i < coordinates.length; i++) {
				var latlng = new google.maps.LatLng(coordinates[i].lat, coordinates[i].lng);
				bounds.extend(latlng);
			}
			map.fitBounds(bounds);
		}
	}
}