declare const ajaxurl: string;

document.addEventListener('DOMContentLoaded', function () {
    const button = document.querySelector('[data-js-load-more]');

    button?.addEventListener('click', function () {
        const id = button.getAttribute('data-js-load-more');
        fetchPosts(id as string);
    });
});

function fetchPosts(id: string) {
    const data = new URLSearchParams();
    data.append('action', 'get_posts');
    data.append('id', id);

    fetch(ajaxurl, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: data.toString(),
    })
        .then((response) => {
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            return response.json();
        })
        .then((data) => {
            console.log('Response:', data);
        })
        .catch((error) => {
            console.error('AJAX Error:', error);
        });
}
