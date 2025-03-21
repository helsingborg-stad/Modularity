import { CreateMarker, MapInterface } from "@helsingborg-stad/openstreetmap";
import { SavedMarker } from "../mapData";
import { MarkersData } from "./interface";
import { StorageInterface } from "./filtering/storageInterface";
import { MarkerClickInterface } from "./markerClick/markerClickInterface";

class Markers {
    constructor(
        private map: MapInterface,
        private savedMarkers: SavedMarker[],
        private storageInstance: StorageInterface,
        private markerClick: MarkerClickInterface
    ) {}

    public createMarkers(): MarkersData {
        let markers: MarkersData = {};
        this.savedMarkers.forEach(markerData => {
            const hasParent   = this.hasParent(markerData.layerGroup);
            const markerColor = this.getMarkerColor(hasParent, markerData.layerGroup);
            const markerIcon  = this.getMarkerIcon(hasParent, markerData.layerGroup);
            const html        = this.getHtml(markerIcon, markerColor);
            let highlighted   = false;

            const marker = new CreateMarker().create({
                position: markerData.position,
                html: html,
                className: 'interactive-map__marker',
                iconSize: [28, 28],
                iconAnchor: [14, 2]
            }, {
                content: `<h2 class="interactive-map__popup-title">${markerData.title}</h2>`
            });

            if (markerData.layerGroup && this.storageInstance.getOrderedLayerGroups().hasOwnProperty(markerData.layerGroup)) {
                marker.addTo(this.storageInstance.getOrderedLayerGroups()[markerData.layerGroup].getLayerGroup());
            } else {
                marker.addTo(this.map);
            }

            marker.addListener('click', () => {
                this.markerClick.click(markerData, marker.isPopupOpen());
            });

            marker.addListener('popupopen', () => {
                highlighted = true;
                console.log("open");
            });


            marker.addListener('popupclose', () => {
                highlighted = true;
                console.log("close");
            });
        });

        return markers;
    }

    private getHtml(icon: string, color: string): string {
        return `<span style="background-color: ${color}; color: white; font-size: 20px; padding: 4px; border-radius: 50%;" data-material-symbol="${icon}" class="material-symbols material-symbols-rounded material-symbols-sharp material-symbols-outlined material-symbols--filled"></span>`
    }

    private getMarkerIcon(hasParent: boolean, layerGroup: string): string {
        if (!hasParent || !this.storageInstance.getOrderedLayerGroups()[layerGroup].getSavedLayerGroup().icon) {
            return 'location_on';
        }

        return this.storageInstance.getOrderedLayerGroups()[layerGroup].getSavedLayerGroup().icon;
    }

    private getMarkerColor(hasParent: boolean, layerGroup: string): string {
        if (!hasParent || !this.storageInstance.getOrderedLayerGroups()[layerGroup].getSavedLayerGroup().color) {
            return '#E04A39';
        }

        return this.storageInstance.getOrderedLayerGroups()[layerGroup].getSavedLayerGroup().color;
    }

    private hasParent(layerGroup: string): boolean {
        return !!layerGroup && this.storageInstance.getOrderedLayerGroups().hasOwnProperty(layerGroup);
    }
}

export default Markers;