import { LayerGroupInterface, MapInterface } from "@helsingborg-stad/openstreetmap";
import { SavedLayerGroup } from "../../mapData";
import { LayerGroupFilterInterface } from "./layerGroupFilterInterface";
import { FilterHelperInterface } from "./filterHelperInterface";

class LayerGroupWithSelectFilter implements LayerGroupFilterInterface {
    private static mainFilterItems: {[key: string]: LayerGroupFilterInterface} = {};
    private static listenerIsInitiated: boolean = false;
    private static latestValue: string = '';
    constructor(
        private container: HTMLElement,
        private mapInstance: MapInterface,
        private filterHelperInstance: FilterHelperInterface,
        private savedLayerGroup: SavedLayerGroup,
        private layerGroup: LayerGroupInterface
    ) {
        LayerGroupWithSelectFilter.mainFilterItems[savedLayerGroup.id] = this;
    }
    public init(): void {
        if (!LayerGroupWithSelectFilter.listenerIsInitiated) {
            this.setMainListener();
            LayerGroupWithSelectFilter.listenerIsInitiated = true;
        }
    }

    private setMainListener(): void {
        const select = this.container.querySelector('[data-js-main-filter]') as HTMLSelectElement;
        if (!select) {
            return;
        }

        LayerGroupWithSelectFilter.latestValue = select.value;

        select.addEventListener('change', (e) => {
            const value = select.value;
            if (LayerGroupWithSelectFilter.latestValue === value) {
                return;
            }

            this.removePreviousSelectedMainFilterFromMap(LayerGroupWithSelectFilter.latestValue);
            this.addNewMainFilterToMap(value);

            LayerGroupWithSelectFilter.latestValue = value;
        });
    }

    private addNewMainFilterToMap(id: string): void {
        const newLayerGroup = LayerGroupWithSelectFilter.mainFilterItems[id];

        this.filterHelperInstance.showChildrenFilter(id);
        newLayerGroup.getLayerGroup().addTo(this.mapInstance);
    }

    private removePreviousSelectedMainFilterFromMap(id: string): void {
        const previousLayerGroup = LayerGroupWithSelectFilter.mainFilterItems[id];

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

export default LayerGroupWithSelectFilter;