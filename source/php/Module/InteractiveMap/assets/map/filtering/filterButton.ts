import { ContainerEventHelperInterface, OpenStateDetail } from "../helper/containerEventHelperInterface";

class FilterButton {
    private filterButton: HTMLElement|null;
    private filterItemsContainer: HTMLElement|null;
    private innerCloseButton: HTMLElement|null;
    private isOpen: boolean = false;
    private iconAttribute: string = "data-material-symbol";
    private openClass: string = "is-open";

    constructor(private container: HTMLElement, private containerEventHelper: ContainerEventHelperInterface) {
        this.filterButton = this.container.querySelector('[data-js-interactive-map-filter-icon]');
        this.innerCloseButton = this.container.querySelector('[data-js-interactive-map-filters-close-icon]');
        this.filterItemsContainer = this.container.querySelector('[data-js-interactive-map-filters-container]');

        if (this.filterButton && this.filterItemsContainer) {
            this.setListener();
            this.setOpenCloseListeners();
        }
    }

    private setListener() {
        this.filterButton!.addEventListener('click', () => {
            if (!this.isOpen) {
                this.open();
                this.isOpen = true;
                this.containerEventHelper.dispatchWasOpenedEvent("filter");
            } else {
                this.close();
                this.isOpen = false;
                this.containerEventHelper.dispatchWasClosedEvent("filter");
            }
        });

        this.innerCloseButton?.addEventListener('click', () => {
            this.close();
            this.isOpen = false;
        });
    }

    private setOpenCloseListeners() {
        this.container.addEventListener(this.containerEventHelper.getWasOpenedEventName(), (event) => {
            const customEvent = event as CustomEvent<OpenStateDetail>
            if (customEvent.detail === "marker" && this.isOpen) {
                this.isOpen = false;
                this.close();
            }
        });
    }

    private close() {
        this.filterItemsContainer!.classList.remove(this.openClass);
        this.filterButton?.setAttribute(this.iconAttribute, 'tune');
    }
    
    private open() {
        this.filterItemsContainer!.classList.add(this.openClass);
        this.filterButton?.setAttribute(this.iconAttribute, 'close');
    }
}

export default FilterButton;