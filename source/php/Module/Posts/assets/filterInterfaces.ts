export interface FilterElements {
    container: HTMLElement;
    postTypeSelect: HTMLSelectElement;
    taxonomySelect: HTMLSelectElement;
    termsSelect: HTMLSelectElement;
    taxonomySelectLabel: HTMLElement;
    termsSelectLabel: HTMLElement;
}

export interface TaxonomyRequestData {
    action: string;
    posttype: string|null;
    post: string;
    selected: string|null;
}

export interface TermsRequestData {
    action: string;
    tax: string;
    post: string;
    selected: string|null;
}
