function loadMoreHandler(event) {
	event.preventDefault();

	const embedCode = event.target.getAttribute('data-code');
	const itemCount = parseInt(event.target.getAttribute('data-item-count'));
	const itemsPerPage = parseInt(event.target.getAttribute('data-items-per-page'));
	let itemsLoaded = parseInt(event.target.getAttribute('data-items-loaded'));

	if (itemsLoaded < itemCount) {
		getFeed(embedCode, itemsPerPage, itemsLoaded, function (response) {
			if (response.success && response.posts) {
				updateItems(response.posts, event.target);
			}
		});
	} else {
		console.log('Maximum number of items reached (' + itemCount + ')');
	}
}

function getFeed(embedCode, limit, offset, callback) {
	const xhr = new XMLHttpRequest();
	const url = curator.ajaxurl;
	const data = new FormData();

	data.append('action', 'mod_curator_get_feed');
	data.append('nonce', curator.nonce);
	data.append('embed-code', embedCode);
	data.append('limit', limit);
	data.append('offset', offset);

	xhr.open('POST', url, true);

	xhr.onload = function () {
		if (xhr.status === 200) {
			const response = JSON.parse(xhr.response);
			callback(response);
		} else {
			console.error(xhr);
		}
	};

	xhr.onerror = function () {
		alert('Network error');
	};

	xhr.send(data);
}

function updateItems(posts, button) {
	renderPosts(posts, button);

	const itemsLoaded = parseInt(button.getAttribute('data-items-loaded'));
	const newItemsLoaded = itemsLoaded + posts.length;
	button.setAttribute('data-items-loaded', newItemsLoaded);

	console.log('Total items loaded:', newItemsLoaded);
}

function renderPosts(posts, button) {
	const xhr = new XMLHttpRequest();
	const url = curator.ajaxurl;
	const data = new FormData();

	data.append('action', 'mod_curator_load_more');
	data.append('nonce', curator.nonce);
	data.append('posts', JSON.stringify(posts));

	const closestParent = button.closest('.modularity-socialmedia-container');
	const socialMediaBlocks = closestParent.querySelector('.modularity-socialmedia__content');
	const columnClasses = socialMediaBlocks.querySelector('.modularity-socialmedia__item').getAttribute('class');
	data.append('columnClasses', columnClasses);

	xhr.open('POST', url, true);

	xhr.onload = function () {
		if (xhr.status === 200) {
			socialMediaBlocks.insertAdjacentHTML('beforeend', xhr.response);
		} else {
			console.error(xhr);
		}
	};

	xhr.send(data);
}

const loadMoreButtons = document.getElementsByClassName('mod-curator-load-more');
for (let i = 0; i < loadMoreButtons.length; i++) {
	loadMoreButtons[i].addEventListener('click', loadMoreHandler);
}
