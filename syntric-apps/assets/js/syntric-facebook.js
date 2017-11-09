function renderFacebook(config) {
	console.log(config);
	FB.api('/' + '1636574276582854' + '/picture', // need to abstract this
		'GET',
		{access_token: config.access_token},
		function (response) {
			//console.log(response);
		}
	);
	FB.api(
		'/' + config.page + '/posts?fields=name,created_time,description,message,picture,status_type,type,link,permalink_url,actions,is_published,from,application&limit=2',
		'GET',
		{access_token: config.access_token}, //1031193873683138|dRzFT1sUQNqsSig7MR1UCOlQyPU
		function (response) {
			//console.log(response);
			var posts = response.data;
			//var facebookFeedContainer = $('.facebook-feed');
			var container = document.getElementById(config.container);
			//var mediaFeedContainer = $('.facebook-media-feed');
			var item_count = 0;
			for (var i = 0; i < posts.length; i++) {
				if (posts[i].is_published) {
					var mediaItem = '<li class="media">';
					if (posts[i].picture && posts[i].picture.length) {
						mediaItem += '<img class="d-flex mr-3" src="' + posts[i].picture + '" alt="Facebook post photo" class="rounded" height="100" width="100">';
					}
					mediaItem += '<div class="media-body">';
					mediaItem += '<h5 class="mt-0 mb-1">' + posts[i].name + '</h5>';
					if (posts[i].message) {
						var message = posts[i].message;
						message = message.replace(/(?:\r\n|\r|\n)/g, '<br />');
						mediaItem += message.substr(0, 350);
					} else if (posts[i].description) {
						var description = posts[i].description;
						description = description.replace(/(?:\r\n|\r|\n)/g, '<br />');
						mediaItem += description;
					}
					mediaItem += '</div>';
				}
				mediaItem += '</ul>';
				//$(facebookFeedContainer).append(mediaItem);
				container.innerHTML = container.innerHTML + mediaItem;
				item_count = item_count + 1;
				if (item_count === 2) break;
			}
		}
	);
}

function loadFacebook(config) {
	console.log(config);
	// Init the Facebook SDK
	window.fbAsyncInit = function () {
		FB.init({
			appId: config.app_id,  //1031193873683138
			xfbml: true,
			version: 'v2.9'
		});
		//renderFacebook(config);
	};
// Load Facebook SDK Script
	(function (d, s, id) {
		var js, fjs = d.getElementsByTagName(s)[0];
		if (d.getElementById(id)) return;
		js = d.createElement(s);
		js.id = id;
		js.src = '//connect.facebook.net/en_US/sdk.js#xfbml=1&version=v2.9&appId=' + config.app_id;
		fjs.parentNode.insertBefore(js, fjs);
	}(document, 'script', 'facebook-jssdk'));
}
