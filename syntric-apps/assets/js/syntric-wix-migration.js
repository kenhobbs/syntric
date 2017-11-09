(function ($) {

	// Wix Migration
	function fixLinks() {
		//console.log('fixLinks triggered');
		var links = $('main a');
		//var docs = [];
		console.log(links);
		for (var i = 0; i < links.length; i++) {
			var link_vars = [];
			var link = links[i];
			var href = $(link).attr('href');
			link_vars['href'] = href;
			if (i == 1) {
				$.get(href, function (data) {
						console.log('success');
						console.log(data);
					}
				);
				break;
			}
			if (href == null) {
				var href_array = href.split('.');
				link_vars['href_array'] = href_array;
				var href_array_len = href_array.length;
				var ext = href_array[href_array_len - 1];
				link_vars['ext'] = ext;
				if (ext == 'pdf' || ext == 'doc' || ext == 'docx') {
					//docs.push(link);
					var base_uri = link.baseURI;
					link_vars['base_uri'] = base_uri;
					var base_uri_array = base_uri.split('\/');
					var path = '';
					for (var j = 3; j < base_uri_array.length - 1; j++) {
						path = path + base_uri_array[j] + '#';
					}
					//console.log(base_uri_array);
					//var path = base_uri.replace(/http/, '');
					//path = path.replace(/amadorcoe.syntric.com/, '');
					//path = path.replace('/', '%');
					var title = $(link).text();
					link_vars['title'] = title;
					var filename = title.replace(/\s/g, '_');
					$(link).attr('title', title).attr('href', 'data:application/pdf;' + href).attr('type', 'application/pdf').attr('download', path + filename + '.' + ext).attr('style', 'background-color: yellow;');

					//$(link).text(title + '(' + ext + ')');
				}
			}
			//setUpAnchor(link);
			//return false;
			//console.log(link_vars);
		}
		//console.log(docs);
	}

	function setUpAnchor(link) {
		$(link).on('click', function (e) {
			e.preventDefault();
			$('.acf-button').click();

		});
		return false;
	}

})(jQuery);