interface FilterElements {
    container: HTMLElement;
    postTypeSelect: HTMLSelectElement;
    taxonomySelect: HTMLSelectElement;
}

class Filter {
    constructor(
        private postId: string,
        private data: null | object = null,
        private filterElements: FilterElements
    ) {
    }

    public filter() {
        this.updateTaxonomyTypes({
            'action': 'get_taxonomy_types_v2',
            'posttype': this.filterElements.postTypeSelect.value,
            'post': this.postId,
            'selected': null,
            'container': this.filterElements.container,
        });
    }

    private updateTaxonomyTypes(data) {
        this.removeExistingData();
    }

    private removeExistingData() {
        while (this.filterElements.taxonomySelect.firstChild) {
            this.filterElements.taxonomySelect.removeChild(this.filterElements.taxonomySelect.firstChild);
        }
    }
}

export default Filter;