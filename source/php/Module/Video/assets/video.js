document.addEventListener('DOMContentLoaded', function () {
    const embedPosters = document.querySelectorAll('.modularity-mod-video .embed__poster');
    embedPosters.forEach((embedPoster) => {
        embedPoster.addEventListener('click', () => {
            embedPoster.parentElement.innerHTML = document.querySelector('#' + embedPoster.dataset.embedId).innerHTML;
        });
    })
});
