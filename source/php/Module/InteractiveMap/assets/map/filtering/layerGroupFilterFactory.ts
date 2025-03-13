import { LayerGroupInterface, MapInterface } from "@helsingborg-stad/openstreetmap";
import { LayerGroupFilterInterface } from "./layerGroupFilterInterface";
import { SavedLayerGroup } from "../../mapData";
import Storage from "./storage";
import LayerGroupFilterMainItems from "./layerGroupFilterMainItems";
import { FilterHelperInterface } from "./filterHelperInterface";
import LayerGroupFilterSubItems from "./layerGroupFilterSubItems";

class LayerGroupFilterFactory {
    constructor(
        private container: HTMLElement,
        private mapInstance: MapInterface,
        private storageInstance: Storage,
        private filterHelperInstance: FilterHelperInterface
    ) {}
    createLayerGroupFilter(
        savedLayerGroup: SavedLayerGroup,
        layerGroup: LayerGroupInterface,
    ): LayerGroupFilterInterface {
        console.log(savedLayerGroup.layerGroup);
        if (!savedLayerGroup.layerGroup || savedLayerGroup.layerGroup === '') {
            return new LayerGroupFilterMainItems(this.container, this.mapInstance, this.filterHelperInstance, savedLayerGroup, layerGroup);
        }

        return new LayerGroupFilterSubItems(this.container, this.mapInstance, this.filterHelperInstance,savedLayerGroup, layerGroup);
    }
}

export default LayerGroupFilterFactory;