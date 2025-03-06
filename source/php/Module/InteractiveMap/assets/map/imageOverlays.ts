import { CreateImageOverlay, MapInterface } from "@helsingborg-stad/openstreetmap";
import { SavedImageOverlay } from "../mapData";
import { ImageOverlaysData, LayerGroupsData } from "./interface";

class ImageOverlays {
    constructor(
        private map: MapInterface,
        private savedImageOverlays: SavedImageOverlay[],
        private layerGroups: LayerGroupsData
    ) {}

    public createImageOverlays(): ImageOverlaysData {
        let imageOverlays: ImageOverlaysData = {};
        this.savedImageOverlays.forEach(imageOverlayData => {
            const hasParent = this.hasParent(imageOverlayData.layerGroup);
            const imageOverlay = new CreateImageOverlay().create(imageOverlayData.image, imageOverlayData.position);

            if (imageOverlayData.layerGroup && this.layerGroups.hasOwnProperty(imageOverlayData.layerGroup)) {
                imageOverlay.addTo(this.layerGroups[imageOverlayData.layerGroup].layerGroup);
            } else {
                imageOverlay.addTo(this.map);
            }
        });

        return imageOverlays;
    }

    private hasParent(layerGroup: string): boolean {
        return !!layerGroup && this.layerGroups.hasOwnProperty(layerGroup);
    }
}

export default ImageOverlays;