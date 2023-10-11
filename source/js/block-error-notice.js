export default class BlockErrorNotice {
    constructor(fieldGroup, regex, errorMessage) {
        this.fieldGroup         = fieldGroup;
        this.field              = fieldGroup.querySelector('textarea');
        this.noticeElement      = false;
        this.regex              = regex;
        this.errorMessage       = errorMessage;

        this.field && this.init();
    }

    init() {
        this.createNotice();
        this.checkFieldValue();
        this.setupTextareaListener();
    }


    createNotice() {
        const noticeString = `
            <div class="c-notice" style="margin-bottom: 16px; display: none; background-color: #d73740; color: white; padding: 16px 24px; border-radius: 4px;" data-js-block-error-notice>
            <span class="c-notice__icon" margin-right: 16px;>
            <span class="c-icon c-icon--report c-icon--material c-icon--material-report material-icons c-icon--size-md" role="img" aria-label="Icon: Undefined" alt="Icon: Undefined"><span data-nosnippet="" translate="no" aria-hidden="true">report</span></span></span>
            <span id="notice__text__" for="" class="c-notice__message">${this.errorMessage}</span>
            </div>`;

        const div = document.createElement('div');

        div.innerHTML = noticeString;

        this.fieldGroup.insertBefore(div, this.fieldGroup.firstChild);

        const notice = this.fieldGroup.querySelector('[data-js-block-error-notice]');
        if (notice) {
            this.notice = notice;
        }
    }

    checkFieldValue() {
        const faultyScriptElement = this.checkRegex();
        if (faultyScriptElement) {
            this.fieldGroup.setAttribute('data-js-block-field-validation-error', '');
            if (this.notice) {
                this.notice.style.display = 'block';
            }
        } else {
            this.fieldGroup.removeAttribute('data-js-block-field-validation-error');
            this.notice.style.display = 'none';
        }
    }

    getFaultyScriptModules() {
        return document.querySelectorAll('[data-js-block-field-validation-error]').length > 0;
    }

    setupTextareaListener() {
        this.field.addEventListener('input', (e) => {
            this.checkFieldValue();
        });
        
        this.field.addEventListener('change', (e) => {
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