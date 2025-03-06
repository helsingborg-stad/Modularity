import { createMap, CreateLayerGroup, LayerGroupInterface, CreateMarker } from "@helsingborg-stad/openstreetmap";
import { SaveData } from "./mapData";
import LayerGroups from "./map/layerGroups";
import Markers from "./map/markers";
import ImageOverlays from "./map/imageOverlays";

class InteractiveMap {
    constructor(mapId: string, mapData: SaveData, container: HTMLElement) {
        const map = createMap({
            id: mapId,
            center: mapData.startPosition ?? { lat: 56.046467, lng: 12.694512 },
            zoom: 15
        });

        const layerGroups   = new LayerGroups(map, mapData.layerGroups).createLayerGroups();
        const markers       = new Markers(map, mapData.markers, layerGroups).createMarkers();
        const imageOverlays = new ImageOverlays(map, mapData.imageOverlays, layerGroups).createImageOverlays();
    }
}

document.addEventListener('DOMContentLoaded', function () {
    (document.querySelectorAll('[data-js-interactive-map]') as NodeListOf<HTMLElement>).forEach(container => {
        const mapId = container.dataset.jsInteractiveMap;
        const mapData = JSON.parse(container.dataset.jsInteractiveMapData ?? '');
        console.log(mapData);
        if (mapId && mapData) {
            new InteractiveMap(mapId, mapData as SaveData, container);
        } else {
            console.error('Missing mapId or mapData');
        }
    });
});