import { LayerGroupInterface, MapInterface } from "@helsingborg-stad/openstreetmap";
import { SavedLayerGroup } from "../../mapData";
import Storage from "./storage";
import { LayerGroupFilters } from "./layerGroupFilterInterface";
import { FilterHelperInterface } from "./filterHelperInterface";

class LayerGroupFilterMainItems implements LayerGroupFilters {
    private static mainFilterItems: {[key: string]: LayerGroupFilters} = {};
    private static listenerIsInitiated: boolean = false;
    private static latestValue: string = '';
    constructor(
        private container: HTMLElement,
        private mapInstance: MapInterface,
        private filterHelperInstance: FilterHelperInterface,
        private savedLayerGroup: SavedLayerGroup,
        private layerGroup: LayerGroupInterface
    ) {
        LayerGroupFilterMainItems.mainFilterItems[savedLayerGroup.id] = this;
    }
    public init(): void {
        if (!LayerGroupFilterMainItems.listenerIsInitiated) {
            this.setMainListener();
            LayerGroupFilterMainItems.listenerIsInitiated = true;
        }
    }

    private setMainListener(): void {
        const select = this.container.querySelector('[data-js-main-filter]') as HTMLSelectElement;
        if (!select) {
            return;
        }

        LayerGroupFilterMainItems.latestValue = select.value;

        select.addEventListener('change', (e) => {
            const value = select.value;
            if (LayerGroupFilterMainItems.latestValue === value) {
                return;
            }

            this.removePreviousSelectedMainFilterFromMap(LayerGroupFilterMainItems.latestValue);
            this.addNewMainFilterToMap(value);

            LayerGroupFilterMainItems.latestValue = value;
        });
    }

    private addNewMainFilterToMap(id: string): void {
        const newLayerGroup = LayerGroupFilterMainItems.mainFilterItems[id];

        this.filterHelperInstance.showChildrenFilter(id);
        newLayerGroup.getLayerGroup().addTo(this.mapInstance);
    }

    private removePreviousSelectedMainFilterFromMap(id: string): void {
        const previousLayerGroup = LayerGroupFilterMainItems.mainFilterItems[id];

        this.filterHelperInstance.hideChildrenFilter(id);
        previousLayerGroup.getLayerGroup().removeLayerGroup();
    }

    public hideFilter(): void {
        return;
    }

    public showFilter(): void {
        return;
    }

    public getSavedLayerGroup(): SavedLayerGroup {
        return this.savedLayerGroup;
    }

    public getLayerGroup(): LayerGroupInterface {
        return this.layerGroup;
    }
}

export default LayerGroupFilterMainItems;