import { LayerGroupInterface } from "@helsingborg-stad/openstreetmap";
import { SavedLayerGroup } from "../../mapData";

export interface FilterInterface {
    getLayerGroup(): LayerGroupInterface;
    getSavedLayerGroup(): SavedLayerGroup;
    getFilterButton(): HTMLElement|null;
    setListener(): void;
}