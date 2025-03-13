/* import { Addable, LayerGroup, LayerGroupInterface, MapInterface } from "@helsingborg-stad/openstreetmap";
import { SavedLayerGroup } from "../../mapData";
import Storage from "./storage";
import { LayerGroupFilterInterface } from "./layerGroupFilterInterface";

class LayerGroupFilter implements LayerGroupFilterInterface {
    private isAddedToMap: boolean = false;
    public isInitiated: boolean = false;
    private activeClass: string = 'is-active';
    constructor(
        private mapInstance: MapInterface,
        public storageInstance: Storage,
        private savedLayerGroup: SavedLayerGroup,
        private layerGroup: LayerGroupInterface,
        private filterButton: HTMLElement|null
    ) {
    }

    public init(): void {
        if (this.isInitiated) {
            return;
        }

        this.setListener();
    }

    private setListener(): void {
        const parent = this.findParent();

        if (!this.getFilterButton()) {
            this.getLayerGroup().addTo(parent);
            return;
        }

        this.getFilterButton()!.addEventListener('click', () => {
            if (this.filterButton?.classList.contains(this.activeClass)) {
                return this.handleRemoveActive();
            }

            this.handleAddActive();
        });
    }

    private handleAddActive(): void {
        this.filterButton?.classList.add(this.activeClass);

        if (!this.isAddedToMap) {
            this.getLayerGroup().addTo(this.findParent());
        }

        this.isAddedToMap = true;

        const children = this.storageInstance.getStructuredLayerGroups()[this.getSavedLayerGroup().id];

        children?.forEach(child => {
            child.showFilterButton();
        });
    }

    private handleRemoveActive(): void {
        this.filterButton?.classList.remove(this.activeClass);
        this.getLayerGroup().removeLayerGroupFrom(this.findParent());
        this.isAddedToMap = false;
        
        const children = this.storageInstance.getStructuredLayerGroups()[this.getSavedLayerGroup().id];

        children?.forEach(child => {
            child.hideFilterButton();
        });
    }

    public hideFilterButton(): void {
        this.filterButton?.classList.add('u-display--none');

        this.hideChildrenFilter();
    }

    // DONE
    public hideChildrenFilter(): void {
        this.findChildren().forEach(child => {
            child.hideFilterButton();
        });
    }

    public showFilterButton(): void {
        this.filterButton?.classList.remove('u-display--none');

        this.findChildren().forEach(child => {
            child.showFilterButton();
        });
    }

    // DONE
    public findParent(): Addable {
        if (this.getSavedLayerGroup().layerGroup && this.storageInstance.getOrderedLayerGroups().hasOwnProperty(this.getSavedLayerGroup().layerGroup)) {
            return this.storageInstance.getOrderedLayerGroups()[this.getSavedLayerGroup().layerGroup].getLayerGroup();
        }

        return this.mapInstance;
    }

    // DONE
    public findChildren(): LayerGroupFilterInterface[] {
        return this.storageInstance.getStructuredLayerGroups()[this.getSavedLayerGroup().id] ?? [];
    }

    public getFilterButton(): HTMLElement|null {
        return this.filterButton;
    }

    public getSavedLayerGroup(): SavedLayerGroup {
        return this.savedLayerGroup;
    }

    public getLayerGroup(): LayerGroupInterface {
        return this.layerGroup;
    }
}

export default LayerGroupFilter; */