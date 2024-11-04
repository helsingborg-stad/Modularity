declare const wpApiSettings: any;

interface ValuesInterface {
    [key: string]: boolean;
}

interface ManualInputItemsObject {
    [key: string]: HTMLElement;
}

class UserOrdering {
    private savingLang: string = 'Saving';
    private buttonText: string;
    constructor(
        private submitButton: HTMLButtonElement,
        private closeButton: HTMLButtonElement,
        private errorNotice: HTMLElement,
        private manualInputItemsObject: ManualInputItemsObject,
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
        fetch(endpoint, {
            method: 'GET',
            headers: {
                'Content-Type': 'application/json',
                'X-WP-NONCE': wpApiSettings?.nonce ?? '',
            },
        })
        .then(response => {
            if (!response.ok) {
                throw new Error('Failed to fetch existing user data');
            }
            return response.json();
        })
        .then(data => {
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
            this.handleSuccessfullSave(values);
        })
        .catch(error => {
            console.error('Error:', error);
            this.handleFailedSave();
        });
    }

    private handleBeforeSave() {
        this.submitButton.disabled = true;
        this.closeButton.disabled = true;
        console.log(this.closeButton);
        this.submitButton.textContent = this.savingLang + '...';
    }
    
    private handleFailedSave() {
        this.errorNotice.classList.remove('u-display--none');
        this.submitButton.disabled = false;
        this.closeButton.disabled = false;
        this.submitButton.textContent = this.buttonText;
    }

    private handleSuccessfullSave(values: ValuesInterface) {
        this.submitButton.disabled = false;
        this.closeButton.disabled = false;
        this.submitButton.textContent = this.buttonText;
        this.showOrHideItemsBasedOnSaved(values);
        this.closeButton.click();
    }

    private showOrHideItemsBasedOnSaved(values: ValuesInterface) {

        for (const [key, element] of Object.entries(this.manualInputItemsObject)) { 
            if (!(key in values)) {
                continue;
            }

            if (values[key]) {
                element.classList.remove('u-display--none');
            } else {
                element.classList.add('u-display--none');
            }
        }
    }
}

document.addEventListener('DOMContentLoaded', function () {
    const privateManualInputs = document.querySelectorAll('[data-js-manual-input-user-ordering]');
    
    privateManualInputs.forEach(privateManualInput => {
        const userId = privateManualInput.getAttribute('data-js-manual-input-user');
        const moduleId = privateManualInput.getAttribute('data-js-manual-input-id');
        const submitButton = privateManualInput.querySelector('button[type="submit"]');
        const checkboxes = privateManualInput.querySelectorAll('input[type="checkbox"]') as NodeListOf<HTMLInputElement>;
        const errorNotice = privateManualInput.querySelector('[data-js-manual-input-error]');
        let manualInputItemsObject: ManualInputItemsObject = {};

        privateManualInput.querySelectorAll('[data-js-item-id]').forEach(item => {
            const itemId = item.getAttribute('data-js-item-id');
            if (itemId) {
                manualInputItemsObject[itemId] = item as HTMLElement;
            }
        });

        const closeButton = privateManualInput.querySelector('button[data-js-cancel-save]');

        if (submitButton && closeButton && userId && moduleId && checkboxes.length) {

            new UserOrdering(
                submitButton as HTMLButtonElement, 
                closeButton as HTMLButtonElement, 
                errorNotice as HTMLElement,
                manualInputItemsObject,
                checkboxes, 
                userId, 
                moduleId
            );
        }
    });
});