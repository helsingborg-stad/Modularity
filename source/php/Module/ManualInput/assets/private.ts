declare const wpApiSettings: any;

interface ValuesInterface {
    [key: string]: boolean;
}
class UserOrdering {
    private savingLang: string = 'Saving';
    private buttonText: string;
    constructor(
        private form: HTMLFormElement, 
        private submitButton: HTMLButtonElement,
        private closeButton: HTMLButtonElement,
        private checkboxes: NodeListOf<HTMLInputElement>,
        private readonly userId: string, 
        private readonly moduleId: string
    ) {
        this.buttonText = this.submitButton.textContent ?? 'Save';
        this.savingLang = this.submitButton.getAttribute('data-js-saving-lang') ?? this.savingLang;
        if (wpApiSettings) {
            this.submitListener();
        }
    }

    private submitListener() {
        this.submitButton.addEventListener('click', (event) => {
            event.preventDefault();

            let values: ValuesInterface = {};

            this.checkboxes.forEach((checkbox) => {
                values[checkbox.value] = checkbox.checked;
            });

            this.patchUser(values);
        });
    }

    private patchUser(values: ValuesInterface) {
        this.handleBeforeSave();
    
        const endpoint = `${wpApiSettings.root}wp/v2/users/${this.userId}`;
        // Fetch the existing user data first
        fetch(endpoint, {
            method: 'GET',
            headers: {
                'Content-Type': 'application/json',
                'X-WP-NONCE': wpApiSettings?.nonce ?? '',
            },
        })
        .then(response => {
            // Check for successful response
            if (!response.ok) {
                throw new Error('Failed to fetch existing user data');
            }
            return response.json();
        })
        .then(data => {
            // Get existing manualInputs or initialize it
            let manualInputs = data.meta?.manualInputs || {};
            
            manualInputs[this.moduleId] = values;
    
            return fetch(endpoint, {
                method: 'PATCH',
                headers: {
                    'Content-Type': 'application/json',
                    'X-WP-NONCE': wpApiSettings?.nonce ?? '',
                },
                body: JSON.stringify({
                    meta: {
                        manualInputs: manualInputs,
                    }
                }),
            });
        })
        .then(() => {
            this.handleSuccessfullSave();
        })
        .catch(error => {
            console.error('Error:', error);
            this.handleFailedSave();
        });
    }
    
    private handleFailedSave() {

    }

    private handleSuccessfullSave() {
        this.submitButton.disabled = false;
        this.closeButton.disabled = false;
        this.submitButton.textContent = this.buttonText;
        this.closeButton.click();
    }

    private handleBeforeSave() {
        this.submitButton.disabled = true;
        this.closeButton.disabled = true;
        this.submitButton.textContent = this.savingLang + '...';
    }
}

document.addEventListener('DOMContentLoaded', function () {
    const privateManualInputs = document.query
    const forms = document.querySelectorAll('form[data-js-manual-input-form]');

    forms.forEach(form => {
        const userId = form.getAttribute('data-js-manual-input-user');
        const moduleId = form.getAttribute('data-js-manual-input-id');
        const submitButton = form.querySelector('button[type="submit"]');
        const checkboxes = form.querySelectorAll('input[type="checkbox"]') as NodeListOf<HTMLInputElement>;
        const closeButton = form.querySelector('button[data-close]');

        if (submitButton && closeButton && userId && moduleId && checkboxes.length) {
            new UserOrdering(
                form as HTMLFormElement, 
                submitButton as HTMLButtonElement, 
                closeButton as HTMLButtonElement, 
                checkboxes, 
                userId, 
                moduleId
            );
        }
    });
});