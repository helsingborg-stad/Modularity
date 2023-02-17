/**
 * On click "load more":
 * Display the X number of items
 *
 */
const loadMoreButtons = document.getElementsByClassName('mod-curator-load-more');

for (let i = 0; i < loadMoreButtons.length; i++) {
	loadMoreButtons[i].addEventListener('click', function (event) {
		event.preventDefault(); // prevent the default link behavior
	});
}
