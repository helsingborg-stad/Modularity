import { ImageOverlayInterface, LayerGroupInterface, MarkerInterface } from "@helsingborg-stad/openstreetmap";
import { SavedImageOverlay, SavedLayerGroup, SavedMarker } from "../mapData";

type MarkersData = {
    [key: string]: {
        data: SavedMarker;
        marker: MarkerInterface;
    }
}

type LayerGroupsData = {
    [key: string]: {
        data: SavedLayerGroup;
        layerGroup: LayerGroupInterface;
    }
}

type ImageOverlaysData = {
    [key: string]: {
        data: SavedImageOverlay;
        imageOverlay: ImageOverlayInterface;
    }
}
