interface SubscriptionInterface {
    Contact: { Email: string },
    ConsentText: string,
    ListIds: string[],
    DoubleOptIn?: { Issue: { IssueId: string } },
    ConfirmationIssue?: { IssueId: string },
    SubscriptionConfirmedUrl?: string,
    SubscriptionFailedUrl?: string
}

class Ungpd {
    constructor(private form: HTMLElement, private id: string) {

    }
}


function initializeUngappedForms() {

    const ungpdForms = [...document.querySelectorAll("[data-js-ungpd-id]")];
    const notices: Node[] = [];

    const handleSuccess = (successTemplate: HTMLTemplateElement) => {
        const successElement = successTemplate.content.cloneNode(true);
        if (successTemplate.parentNode) {
            notices.push(successTemplate.parentNode.appendChild(successElement));
        }
    }

    const handleError = (errorTemplate: HTMLTemplateElement, message: string | number) => {
        const errorElement = errorTemplate.content.cloneNode(true);
        const messageElement = (errorElement as HTMLElement).querySelector('.message');
        if (messageElement) {
            messageElement.innerHTML = message.toString();
        }
        notices.push(errorTemplate.parentNode!.appendChild(errorElement));
    };

    const clearNotices = () => {
        notices.forEach(notice => {
            if (notice.parentNode) {
                notice.parentNode.removeChild(notice);
            }
        });
    }

    ungpdForms.forEach((form, index) => {
        console.log(form);
        form.addEventListener("submit", (event) => {

            //Prevent default
            event.preventDefault();
            clearNotices();

            //Gather data
            const accountId = form.getAttribute('data-js-ungpd-id');
            const listIds = form.getAttribute('data-js-ungpd-list-ids');
            const doubleOptInIssueId = form.getAttribute('data-js-ungpd-double-opt-in-issue-id');
            const confirmationIssueId = form.getAttribute('data-js-ungpd-confirmation-issue-id');
            const subscriptionConfirmedUrl = form.getAttribute('data-js-ungpd-subscription-confirmed-url');
            const subscriptionFailedUrl = form.getAttribute('data-js-ungpd-subscription-failed-url');
            const email = form.querySelector('input[name="email"]') as HTMLInputElement;
            const consent = form.querySelector('input[name="user_consent"]') as HTMLInputElement;
            const successTemplate = document.querySelectorAll<HTMLTemplateElement>(`template[id="${accountId}-success"]`)[index];
            const errorTemplate = document.querySelectorAll<HTMLTemplateElement>(`template[id="${accountId}-error"]`)[index];

            const lists = listIds ? listIds.split(",").map((listId) => listId.trim()) : [];

            //Form validates, empty data, send request
            if (accountId && email && email.value && consent && consent.checked) {
                
                let subscription: SubscriptionInterface = {
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

document.addEventListener("DOMContentLoaded", () => {
    const ungpdForms = [...document.querySelectorAll("[data-js-ungpd-id]")];
    ungpdForms.forEach(form => {
        const id = form.getAttribute('id');
        if (id) {
            new Ungpd(form as HTMLElement, id);
        }
    });
    initializeUngappedForms()
});
