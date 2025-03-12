import { ImageOverlayInterface, LayerGroupInterface, MarkerInterface } from "@helsingborg-stad/openstreetmap";
import { SavedImageOverlay, SavedLayerGroup, SavedMarker } from "../mapData";
import { FilterInterface } from "./filtering/filterInterface";

type MarkersData = {
    [key: string]: {
        data: SavedMarker;
        marker: MarkerInterface;
    }
}

type LayerGroupData = {
    data: SavedLayerGroup;
    layerGroup: LayerGroupInterface;
    filterButton: HTMLElement|null;
}

type OrderedLayerGroups = {
    [key: string]: FilterInterface;
}

type StructuredLayerGroups = {
    [key: string]: FilterInterface[];
}

type ImageOverlaysData = {
    [key: string]: {
        data: SavedImageOverlay;
        imageOverlay: ImageOverlayInterface;
    }
}
