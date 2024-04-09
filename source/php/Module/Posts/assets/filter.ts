import { FilterElements } from './filterInterfaces';
import { taxonomiesRequest, termsRequest } from './xhr';

class Filter {
    taxonomySpinner: null|HTMLElement;
    termsSpinner: null|HTMLElement;

    constructor(
        private postId: string,
        private data: null | object = null,
        private filterElements: FilterElements
    ) {
        this.taxonomySpinner    = null;
        this.termsSpinner       = null;
    }

    public initializeTaxonomyFilter() {
        this.updateTaxonomiesAndTerms();
        this.setupChangeListeners();
    }

    private setupChangeListeners() {
        this.filterElements.postTypeSelect.addEventListener('change', () => {
            this.updateTaxonomiesAndTerms();
        });

        this.filterElements.taxonomySelect.addEventListener('change', () => {
            this.updateTerms();
        });
    }

    private updateTaxonomiesAndTerms() {
        this.removeExistingData(this.filterElements.taxonomySelect);
        this.addSpinner(this.filterElements.taxonomySelectLabel, 'taxonomySpinner');

        taxonomiesRequest(
            {
                action: 'get_taxonomy_types_v2',
                posttype: this.filterElements.postTypeSelect.value,
                post: this.postId,
                selected: null
            }, 
            this.filterElements.taxonomySelect, 
            this.taxonomySpinner     
        ).then(response => {
            this.updateTerms(response);
        });
    }

    private updateTerms(response = true) {
        this.removeExistingData(this.filterElements.termsSelect);
        this.addSpinner(this.filterElements.termsSelectLabel, 'termsSpinner');

        response && termsRequest(
            {
                action: 'get_taxonomy_values_v2',
                tax: this.filterElements.taxonomySelect.value,
                selected: null,
                post: this.postId
            },
            this.filterElements.termsSelect,
            this.termsSpinner
        );
    }

    private addSpinner(element: HTMLElement, type: string) {
        element.insertAdjacentHTML(
            'afterbegin', 
            '<span class="spinner" style="visibility: visible; float: none; margin: 0 5px 0 0;"></span>'
        );

        this[type] = element.querySelector('.spinner');
    }

    private removeExistingData(element: HTMLElement) {
        while (element.firstChild) {
            element.removeChild(element.firstChild);
        }
    }
}

export default Filter;