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

            let values = {};

            this.checkboxes.forEach((checkbox) => {
                values[checkbox.value] = checkbox.checked;
            });

            this.patchUser(values);
        });
    }

    private patchUser(values: ValuesInterface) {
        this.handleBeforeSave();
        fetch(`${wpApiSettings.root}wp/v2/users/${this.userId}`, {
            method: 'PATCH',
            headers: {
                'Content-Type': 'application/json',
                'X-WP-NONCE': wpApiSettings?.nonce ?? '',
            },
            body: JSON.stringify({'meta': {'manualInputs': {[this.moduleId]: values}}}),
        })
        .catch(error => {
            console.error('Error:', error);
            this.handleFailedSave();
        })
        .finally(() => {
            this.handleSuccessfullSave();
        });
    }

    private handleFailedSave() {

    }

    private handleSuccessfullSave() {
        this.submitButton.disabled = false;
        this.closeButton.disabled = false;
        this.submitButton.textContent = this.buttonText;
    }

    private handleBeforeSave() {
        this.submitButton.disabled = true;
        this.closeButton.disabled = true;
        this.submitButton.textContent = this.savingLang + '...';
    }
}

document.addEventListener('DOMContentLoaded', function () {
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