export default class BlockErrorNotice {
    constructor(fieldGroup) {
        this.fieldGroup         = fieldGroup;
        this.field              = fieldGroup.querySelector('textarea');
        this.noticeElement      = false;
        this.regex              = /<iframe|<video/;

        this.field && this.init();
    }

    init() {
        this.createNotice();
        this.checkFieldValue();
        this.setupTextareaListener();
    }


    createNotice() {
        const noticeString = `
            <div class="c-notice" style="margin-bottom: 16px; display: none; background-color: #d73740; color: white; padding: 16px 24px; border-radius: 4px;" data-js-script-notice>
            <span class="c-notice__icon" margin-right: 16px;>
            <span class="c-icon c-icon--report c-icon--material c-icon--material-report material-icons c-icon--size-md" role="img" aria-label="Icon: Undefined" alt="Icon: Undefined"><span data-nosnippet="" translate="no" aria-hidden="true">report</span></span></span>
            <span id="notice__text__" for="" class="c-notice__message">Please use a more appropriate module for your content. (video or iframe module)</span>
            </div>`;

        const div = document.createElement('div');

        div.innerHTML = noticeString;

        this.fieldGroup.insertBefore(div, this.fieldGroup.firstChild);

        const notice = this.fieldGroup.querySelector('[data-js-script-notice]');
        if (notice) {
            this.notice = notice;
        }
    }

    checkFieldValue() {
        const faultyScriptElement = this.checkRegex();
        if (faultyScriptElement) {
            this.fieldGroup.setAttribute('faulty-script-field', '');
            if (this.notice) {
                this.notice.style.display = 'block';
            }
        } else {
            this.fieldGroup.removeAttribute('faulty-script-field');
            this.notice.style.display = 'none';
        }
    }

    getFaultyScriptModules() {
        return document.querySelectorAll('[faulty-script-field]').length > 0;
    }

    setupTextareaListener() {
        this.field.addEventListener('input', (e) => {
            this.checkFieldValue();
        });
    }

    checkRegex() {
        if (this.regex.test(this.field.value)) {
            return true;
        } else {
            return false;
        }
    }
}

document.addEventListener('DOMContentLoaded', () => {
    console.log("runs");
    const fieldGroups = document.querySelectorAll('[data-key="field_56a8b9f1902a6"]');
    let hasFieldGroup = false;
    [...fieldGroups].forEach(fieldGroup => {
        if (!hasFieldGroup) {
            hasFieldGroup = true;
            handleOnclick();
        }
        new BlockErrorNotice(fieldGroup);
    });

    const observer = new MutationObserver(mutationsList => {
        mutationsList.forEach((mutation) => {
            if (mutation.type === 'childList' && mutation.addedNodes.length > 0) {
                mutation.addedNodes.forEach((addedNode) => {
                    if (addedNode instanceof HTMLElement && 
                        addedNode.hasAttribute('data-type') &&
                        addedNode.getAttribute('data-type') == 'acf/script') {
                        const fieldGroup = addedNode.querySelector('[data-key="field_56a8b9f1902a6"]');
                        if (fieldGroup) {
                            if (!hasFieldGroup) {
                                hasFieldGroup = true;
                                handleOnclick();
                            }
                            new BlockErrorNotice(fieldGroup);
                        }
                     }
                });
            }
        });
    });

    const config = { childList: true, subtree: true };
    observer.observe(document, config);

    function handleOnclick() {
        let saveButton = false;
        if (document.querySelector('.editor-post-publish-button')) {
            saveButton = document.querySelector('.editor-post-publish-button');
        } 
        
        if (saveButton) {
            const saveButtonOverlay = createSaveButtonOverlay(saveButton);
            if (saveButtonOverlay) {
                saveButtonOverlay.addEventListener('click', (e) => {
                    const faultyScriptField = document.querySelector('[faulty-script-field]');
                    if (faultyScriptField) {
                        e.stopPropagation();
                        faultyScriptField.scrollIntoView(true);
                    } 
                });
            }
        }
    }

    function createSaveButtonOverlay(saveButton) {
        const div = document.createElement('div');
        div.classList.add('save-button-overlay');
        div.style.cssText = `
            position: absolute;
            inset: 0px;
            height: 100%;
            width: 100%;
            z-index: 3333;
            cursor: pointer;
        `;

        saveButton.style.position = 'relative';

        saveButton.insertBefore(div, saveButton.firstChild);
  
        return document.querySelector('.save-button-overlay');
    }
});

