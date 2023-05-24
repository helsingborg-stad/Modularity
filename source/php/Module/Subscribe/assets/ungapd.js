function initializeUngappedForms() {

    const ungpdForms = [...document.querySelectorAll("[data-js-ungpd-id]")];
    const notices = [];

    const handleSuccess = (successTemplate) => {
        const successElement = successTemplate.content.cloneNode(true);
        notices.push(successTemplate.parentNode.appendChild(successElement));
    }

    const handleError = (errorTemplate, message) => {
        const errorElement = errorTemplate.content.cloneNode(true);
        errorElement.querySelector('.message').innerHTML = message;
        notices.push(errorTemplate.parentNode.appendChild(errorElement));
    }

    const clearNotices = () => {
        notices.forEach(notice => {
            notice.remove();
        });
    }

    ungpdForms.forEach(form => {
        form.addEventListener("submit", (event) => {

            //Prevent default
            event.preventDefault();
            clearNotices();

            //Gather data
            let formId = form.getAttribute('data-js-ungpd-id');
            let listIds = form.getAttribute('data-js-ungpd-list-ids');
            let email = form.querySelector('input[name="email"]');
            let consent = form.querySelector('input[name="user_consent"]');
            const successTemplate = document.querySelector(`template#${formId}-success`);
            const errorTemplate = document.querySelector(`template#${formId}-error`);
            const lists = listIds.split(",").map((listId) => listId.trim());

            //Form validates, empty data, send request
            if (formId && email.value && consent.checked) {

                let subscription = {
                    Contact: { Email: email.value },
                    ConsentText: consent.value,
                    ListIds: lists
                };

                fetch("https://ui.ungpd.com/Api/Subscriptions/" + formId + "/ajax", {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json"
                    },
                    body: JSON.stringify(subscription)
                })
                    .then(response => {
                        if (!response.ok) {
                            handleError(errorTemplate, response.status);
                        } else {
                            handleSuccess(successTemplate);
                            consent.checked = false;
                            email.value = "";
                        }
                    })
                    .catch(error => {
                        handleError(errorTemplate, error);
                    });
            }

        });
    });
}

(function () {
    initializeUngappedForms()
})()
