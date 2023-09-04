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
            let accountId = form.getAttribute('data-js-ungpd-id');
            let listIds = form.getAttribute('data-js-ungpd-list-ids');
            let doubleOptInIssueId = form.getAttribute('data-js-ungpd-double-opt-in-issue-id');
            let confirmationIssueId = form.getAttribute('data-js-ungpd-confirmation-issue-id');
            let subscriptionConfirmedUrl = form.getAttribute('data-js-ungpd-subscription-confirmed-url');
            let subscriptionFailedUrl = form.getAttribute('data-js-ungpd-subscription-failed-url');
            let email = form.querySelector('input[name="email"]');
            let consent = form.querySelector('input[name="user_consent"]');
            const successTemplate = document.querySelector(`template[id="${accountId}-success"]`);
            const errorTemplate = document.querySelector(`template[id="${accountId}-error"]`);
            const lists = listIds.split(",").map((listId) => listId.trim());

            //Form validates, empty data, send request
            if (accountId && email.value && consent.checked) {

                let subscription = {
                    Contact: { Email: email.value },
                    ConsentText: consent.value,
                    ListIds: lists
                };

                if (doubleOptInIssueId) subscription.DoubleOptIn = { Issue: { IssueId: doubleOptInIssueId } };
                if (confirmationIssueId) subscription.ConfirmationIssue = { IssueId: confirmationIssueId };
                if (subscriptionConfirmedUrl) subscription.SubscriptionConfirmedUrl = subscriptionConfirmedUrl;
                if (subscriptionFailedUrl) subscription.SubscriptionFailedUrl = subscriptionFailedUrl;

                fetch("https://ui.ungpd.com/Api/Subscriptions/" + accountId, {
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
