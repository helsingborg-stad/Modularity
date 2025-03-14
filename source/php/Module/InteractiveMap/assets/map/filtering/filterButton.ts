class FilterButton {
    private filterButton: HTMLElement|null;
    private filterItemsContainer: HTMLElement|null;
    private body: HTMLElement = document.body;
    private isOpen: boolean = false;
    constructor(container: HTMLElement) {

        this.filterButton = container.querySelector('[data-js-interactive-map-filter-icon]');
        this.filterItemsContainer = container.querySelector('[data-js-interactive-map-filters-container]');

        if (this.filterButton && this.filterItemsContainer) {
            this.setListener();
        }
    }

    private setListener() {
        this.filterButton!.addEventListener('click', () => {
            if (!this.isOpen) {
                this.open();
            } else {
                this.close();
            }
        });
    }

    private close() {
        this.isOpen = false;
        this.body.classList.remove('u-overflow--hidden');
        this.filterItemsContainer!.classList.remove('is-open');
    }

    private open() {
        this.isOpen = true;
        this.body.classList.add('u-overflow--hidden');
        this.filterItemsContainer!.classList.add('is-open');
    }
}

export default FilterButton;