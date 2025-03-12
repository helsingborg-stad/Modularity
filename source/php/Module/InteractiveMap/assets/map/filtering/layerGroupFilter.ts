import { Addable, LayerGroupInterface, MapInterface } from "@helsingborg-stad/openstreetmap";
import { FilterInterface } from "./filterInterface";
import { SavedLayerGroup } from "../../mapData";
import Storage from "./storage";

class LayerGroupFilter implements FilterInterface {
    constructor(
        private mapInstance: MapInterface,
        private storageInstance: Storage,
        private savedLayerGroup: SavedLayerGroup,
        private layerGroup: LayerGroupInterface,
        private filterButton: HTMLElement|null
    ) {
    }

    public setListener(): void {
        const parent = this.findParent();

        if (!this.getFilterButton()) {
            this.getLayerGroup().addTo(parent);
            return;
        }

        this.getFilterButton()!.addEventListener('click', () => {
            this.getLayerGroup().addTo(parent);
        });
    }

    private findParent(): Addable {
        if (this.getSavedLayerGroup().layerGroup && this.storageInstance.getOrderedLayerGroups().hasOwnProperty(this.getSavedLayerGroup().layerGroup)) {
            return this.storageInstance.getOrderedLayerGroups()[this.getSavedLayerGroup().layerGroup].getLayerGroup();
        }

        return this.mapInstance;
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

export default LayerGroupFilter;