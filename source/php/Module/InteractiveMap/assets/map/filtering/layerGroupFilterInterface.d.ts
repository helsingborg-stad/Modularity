import { LayerGroupInterface } from "@helsingborg-stad/openstreetmap";
import { SavedLayerGroup } from "../../mapData";

export interface LayerGroupFilters {
    init(): void;
    getLayerGroup(): LayerGroupInterface;
    getSavedLayerGroup(): SavedLayerGroup;
    hideFilter(): void;
    showFilter(): void;
}

export interface LayerGroupFilterInterface {
    init(): void;
    getLayerGroup(): LayerGroupInterface;
    getSavedLayerGroup(): SavedLayerGroup;
    hideFilter(): void;
    showFilter(): void;
    // hideChildrenFilter(): void;
}