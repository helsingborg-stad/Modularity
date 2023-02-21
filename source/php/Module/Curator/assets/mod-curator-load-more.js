function loadMoreHandler(event) {
	event.preventDefault();

	const embedCode = event.target.getAttribute('data-code');
	const itemCount = parseInt(event.target.getAttribute('data-item-count'));
	const itemsPerPage = parseInt(event.target.getAttribute('data-items-per-page'));
	let itemsLoaded = parseInt(event.target.getAttribute('data-items-loaded'));

	if (itemsLoaded < itemCount) {
		getFeed(embedCode, itemsPerPage, itemsLoaded)
			.then((response) => {
				if (response.success && response.posts) {
					updateItems(response.posts, event.target);
				}
			})
			.catch((error) => {
				console.error(error);
			});
	} else {
		console.log('Maximum number of items reached (' + itemCount + ')');
	}
}

function getFeed(embedCode, limit, offset) {
	const url = curator.ajaxurl;
	const data = new FormData();

	data.append('action', 'mod_curator_get_feed');
	data.append('nonce', curator.nonce);
	data.append('embed-code', embedCode);
	data.append('limit', limit);
	data.append('offset', offset);

	return fetch(url, {
		method: 'POST',
		body: data,
	}).then((response) => {
		if (response.ok) {
			return response.json();
		} else {
			throw new Error('Network response was not ok');
		}
	});
}

function updateItems(posts, button) {
	renderPosts(posts, button);

	const itemsLoaded = parseInt(button.getAttribute('data-items-loaded'));
	const newItemsLoaded = itemsLoaded + posts.length;
	button.setAttribute('data-items-loaded', newItemsLoaded);

	// ! TODO Correct this value
	console.log('Total items loaded:', newItemsLoaded);
}

function renderPosts(posts, button) {
	const url = curator.ajaxurl;
	const data = new FormData();

	data.append('action', 'mod_curator_load_more');
	data.append('nonce', curator.nonce);
	data.append('posts', JSON.stringify(posts));

	const closestParent = button.closest('.modularity-socialmedia-container');
	const socialMediaBlocks = closestParent.querySelector('.modularity-socialmedia__content');
	const columnClasses = socialMediaBlocks.querySelector('.modularity-socialmedia__item').getAttribute('class');
	data.append('columnClasses', columnClasses);

	fetch(url, {
		method: 'POST',
		body: data,
	})
		.then((response) => {
			if (response.ok) {
				return response.text();
			} else {
				throw new Error('Network response was not ok');
			}
		})
		.then((html) => {
			socialMediaBlocks.insertAdjacentHTML('beforeend', html);

			// ! TODO How to enable modal on inserted posts?
			// Dispatch custom event to trigger modal initialization
			window.dispatchEvent(new CustomEvent('initStyleguideModals'));
		})
		.catch((error) => {
			console.error(error);
		});
}

const loadMoreButtons = document.getElementsByClassName('mod-curator-load-more');
for (let i = 0; i < loadMoreButtons.length; i++) {
	loadMoreButtons[i].addEventListener('click', loadMoreHandler);
}
window.addEventListener('initStyleguideModals', eventhandler);
function eventhandler() {
	alert('initStyleguideModals');
}
