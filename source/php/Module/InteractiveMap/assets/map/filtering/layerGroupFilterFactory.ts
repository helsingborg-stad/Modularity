import { LayerGroupInterface, MapInterface } from "@helsingborg-stad/openstreetmap";
import { LayerGroupFilterInterface } from "./layerGroupFilterInterface";
import { SavedLayerGroup } from "../../mapData";
import Storage from "./storage";
import LayerGroupWithSelectFilter from "./layerGroupWithSelectFilter";
import { FilterHelperInterface } from "./filterHelperInterface";
import LayerGroupWithButtonFilter from "./layerGroupWithButtonFilter";

class LayerGroupFilterFactory {
    constructor(
        private container: HTMLElement,
        private mapInstance: MapInterface,
        private storageInstance: Storage,
        private filterHelperInstance: FilterHelperInterface,
        private allowFiltering: boolean,
        private onlyOneLayerGroup: boolean,
    ) {}
    createLayerGroupFilter(
        savedLayerGroup: SavedLayerGroup,
        layerGroup: LayerGroupInterface,
    ): LayerGroupFilterInterface {
        if (!this.allowFiltering) {
            // return new LayerGroupWithOutFilter(this.container, this.mapInstance, savedLayerGroup, layerGroup);
        }

        if (!this.onlyOneLayerGroup && (!savedLayerGroup.layerGroup || savedLayerGroup.layerGroup === '')) {
            return new LayerGroupWithSelectFilter(this.container, this.mapInstance, this.filterHelperInstance, savedLayerGroup, layerGroup);
        }

        return new LayerGroupWithButtonFilter(this.container, this.mapInstance, this.filterHelperInstance,savedLayerGroup, layerGroup);
    }
}

export default LayerGroupFilterFactory;