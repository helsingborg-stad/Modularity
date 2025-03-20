import { SavedMarker } from "../../mapData";
import { MarkerClickInterface } from "./markerClickInterface";

class MarkerClick implements MarkerClickInterface {
    private markerInfoContainer: HTMLElement;
    private markerInfoTitle: HTMLElement;
    private markerInfoDescription: HTMLElement;
    private hasCorrectMarkup: boolean;

    constructor(container: HTMLElement) {
        this.markerInfoContainer = container.querySelector('[data-js-interactive-map-marker-info-container]') as HTMLElement;
        this.markerInfoTitle = container.querySelector('[data-js-interactive-map-marker-info-title]') as HTMLElement;
        this.markerInfoDescription = container.querySelector('[data-js-interactive-map-marker-info-description]') as HTMLElement;

        this.hasCorrectMarkup = !!(this.markerInfoContainer && this.markerInfoTitle && this.markerInfoDescription);
    }
    public click(markerData: SavedMarker): void {
        if (!this.hasCorrectMarkup) {
            return;
        }

        this.markerInfoTitle!.innerHTML = markerData.title;
        this.markerInfoDescription!.innerHTML = markerData.description;
    }
}

export default MarkerClick;