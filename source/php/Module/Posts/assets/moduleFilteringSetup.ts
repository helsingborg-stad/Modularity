import Filter from "./filter";

class ModuleFilteringSetup {
    private filterContainerSelector: string;

    constructor(
        private postId: string, 
    ) {
        if (!this.postId) {
            return;
        }

        this.filterContainerSelector = '#acf-group_571e045dd555d';
        this.handleFilteringElements();
    }

    handleFilteringElements() {
        const filterContainerElement    = document.querySelector(this.filterContainerSelector);
        const postTypeSelect            = filterContainerElement?.querySelector('.modularity-latest-post-type select');
        const taxonomySelect            = filterContainerElement?.querySelector('.modularity-latest-taxonomy select');

        if (!postTypeSelect || !taxonomySelect) {
            return;
        }

        const filter = new Filter(this.postId, null, {'container': (filterContainerElement as HTMLElement), 'postTypeSelect': (postTypeSelect as HTMLSelectElement), 'taxonomySelect': (taxonomySelect as HTMLSelectElement)});
    }
}

export default ModuleFilteringSetup;