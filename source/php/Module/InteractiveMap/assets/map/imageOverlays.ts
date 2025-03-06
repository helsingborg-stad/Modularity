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
            const imageOverlay = new CreateImageOverlay().create({
                url: imageOverlayData.image,
                bounds: imageOverlayData.position
            });

            if (imageOverlayData.layerGroup && this.layerGroups.hasOwnProperty(imageOverlayData.layerGroup)) {
                imageOverlay.addTo(this.layerGroups[imageOverlayData.layerGroup].layerGroup);
            } else {
                imageOverlay.addTo(this.map);
            }
        });

        return imageOverlays;
    }
}

export default ImageOverlays;