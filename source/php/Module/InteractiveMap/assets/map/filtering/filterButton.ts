class FilterButton {
    private filterButton: HTMLElement|null;
    private filterItemsContainer: HTMLElement|null;
    private innerCloseButton: HTMLElement|null;
    private isOpen: boolean = false;
    private iconAttribute: string = "data-material-symbol";

    constructor(container: HTMLElement) {
        this.filterButton = container.querySelector('[data-js-interactive-map-filter-icon]');
        this.innerCloseButton = container.querySelector('[data-js-interactive-map-filters-close-icon]');
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

        this.innerCloseButton?.addEventListener('click', () => {
            this.close();
        });
    }

    private close() {
        this.isOpen = false;
        this.filterItemsContainer!.classList.remove('is-open');
        this.filterButton?.setAttribute(this.iconAttribute, 'tune');
    }
    
    private open() {
        this.isOpen = true;
        this.filterItemsContainer!.classList.add('is-open');
        this.filterButton?.setAttribute(this.iconAttribute, 'close');
    }
}

export default FilterButton;