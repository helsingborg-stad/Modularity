import { CreateMarker, MapInterface } from "@helsingborg-stad/openstreetmap";
import { SavedMarker } from "../mapData";
import { LayerGroupsData, MarkersData } from "./interface";

class Markers {
    constructor(
        private map: MapInterface,
        private savedMarkers: SavedMarker[],
        private layerGroups: LayerGroupsData
    ) {}

    public createMarkers(): MarkersData {
        let markers: MarkersData = {};
        this.savedMarkers.forEach(markerData => {
            const hasParent   = this.hasParent(markerData.layerGroup);
            const markerColor = this.getMarkerColor(hasParent, markerData.layerGroup);
            const markerIcon  = this.getMarkerIcon(hasParent, markerData.layerGroup);
            const html        = this.getHtml(markerIcon, markerColor);

            const marker = new CreateMarker().create({
                position: markerData.position,
                icon: html,
                className: 'interactive-map__marker',
                iconSize: [32, 32],
                iconAnchor: [16, 2]
            }, {
                content: markerData.title
            });

            if (markerData.layerGroup && this.layerGroups.hasOwnProperty(markerData.layerGroup)) {
                marker.addTo(this.layerGroups[markerData.layerGroup].layerGroup);
            } else {
                marker.addTo(this.map);
            }
        });

        return markers;
    }

    private getHtml(icon: string, color: string): string {
        return `<span style="color: ${color}; font-size: 32px;" material-symbol="${icon}" class="material-symbols material-symbols-rounded material-symbols-sharp material-symbols-outlined material-symbols--filled"></span>`;
    }

    private getMarkerIcon(hasParent: boolean, layerGroup: string): string {
        if (!hasParent || !this.layerGroups[layerGroup].data.icon) {
            return 'location_on';
        }

        return this.layerGroups[layerGroup].data.icon;
    }

    private getMarkerColor(hasParent: boolean, layerGroup: string): string {
        if (!hasParent || !this.layerGroups[layerGroup].data.color) {
            return '#E04A39';
        }

        return this.layerGroups[layerGroup].data.color;
    }

    private hasParent(layerGroup: string): boolean {
        return !!layerGroup && this.layerGroups.hasOwnProperty(layerGroup);
    }
}

export default Markers;