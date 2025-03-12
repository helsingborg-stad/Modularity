import { AddTo, LayerGroupInterface, MapInterface } from "@helsingborg-stad/openstreetmap";
import LayerGroupFilter from "./layerGroupFilter";
import { FilterInterface } from "./filterInterface";
import { SavedLayerGroup } from "../../mapData";
import Storage from "./storage";

class LayerGroupFilterFactory {
    constructor(
        private mapInstance: MapInterface,
        private storageInstance: Storage
    ) {}
    createLayerGroupFilter(
        savedLayerGroup: SavedLayerGroup,
        layerGroup: LayerGroupInterface,
        filterButton: HTMLElement|null
    ): FilterInterface {
        return new LayerGroupFilter(this.mapInstance, this.storageInstance, savedLayerGroup, layerGroup, filterButton);
    }
}

export default LayerGroupFilterFactory;