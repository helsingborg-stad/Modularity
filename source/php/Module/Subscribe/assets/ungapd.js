function initializeUngappedForms() {
    
    const ungpdForms = [...document.querySelectorAll("[data-js-ungpd-id]")];

    const handleSuccess = (form, successTemplate) => {
        const successElement = successTemplate.content.cloneNode(true);
        form.remove();
        successTemplate.parentNode.appendChild(successElement);
    }

    ungpdForms.forEach(form => {
        form.addEventListener("submit", (event) => {

            //Prevent default
            event.preventDefault();

            //Gather data
            let formId = form.getAttribute('data-js-ungpd-id');
            let listIds = form.getAttribute('data-js-ungpd-list-ids');
            let email = form.querySelector('input[name="email"]');
            let consent = form.querySelector('input[name="user_consent"]');
            const successTemplate = document.querySelector('template#' + formId);

            //Form validates, empty data, send request
            if (formId && email.value && consent.checked) {

                let subscription = {
                    Contact: { Email: email.value },
                    ConsentText: consent.value,
                    ListIds: listIds
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
                            alert('{{$lang->error->text}} (err: ' + response.status + ')');
                        } else {
                            handleSuccess(form, successTemplate);
                        }
                    })
                    .catch(error => {
                        alert('{{$lang->error->text}} (err: ' + error + ')');
                    });

                consent.checked = false;
                email.value = "";
            }

        });
    });
}

(function () {
    initializeUngappedForms()
})()
