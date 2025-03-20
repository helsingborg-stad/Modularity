import { SavedMarker } from "../../mapData";
import { ContainerEventHelperInterface, OpenStateDetail } from "../helper/containerEventHelperInterface";
import { MarkerClickInterface } from "./markerClickInterface";

class MarkerClick implements MarkerClickInterface {
    private markerInfoContainer: HTMLElement;
    private markerInfoTitle: HTMLElement;
    private markerInfoDescription: HTMLElement;
    private hasCorrectMarkup: boolean;
    private openClass: string = "is-open";
    private shouldBeOpen: boolean = false;

    constructor(private container: HTMLElement, private containerEventHelper: ContainerEventHelperInterface) {
        this.markerInfoContainer = this.container.querySelector('[data-js-interactive-map-marker-info-container]') as HTMLElement;
        this.markerInfoTitle = this.container.querySelector('[data-js-interactive-map-marker-info-title]') as HTMLElement;
        this.markerInfoDescription = this.container.querySelector('[data-js-interactive-map-marker-info-description]') as HTMLElement;

        this.hasCorrectMarkup = !!(this.markerInfoContainer && this.markerInfoTitle && this.markerInfoDescription);
        this.setOpenCloseListeners();
    }

    public click(markerData: SavedMarker, shouldOpen: boolean): void {
        if (!this.hasCorrectMarkup) {
            return;
        }

        this.shouldBeOpen = shouldOpen;
        this.markerInfoTitle.innerHTML = markerData.title;
        this.markerInfoDescription.innerHTML = markerData.description;

        if (shouldOpen) {
            this.open();
            this.containerEventHelper.dispatchWasOpenedEvent("marker");
        } else {
            this.close();
            this.containerEventHelper.dispatchWasClosedEvent("marker");
        }
    }

    private setOpenCloseListeners() {
        this.container.addEventListener(this.containerEventHelper.getWasOpenedEventName(), (event) => {
            const customEvent = event as CustomEvent<OpenStateDetail>
            if (customEvent.detail === "filter" && this.shouldBeOpen) {
                this.close();
            }
        });

        this.container.addEventListener(this.containerEventHelper.getWasClosedEventName(), (event) => {
            const customEvent = event as CustomEvent<OpenStateDetail>
            if (customEvent.detail === "filter" && this.shouldBeOpen) {
                setTimeout(() => {
                    this.open();
                }, 100);
            }
        });
    }

    private close() {
        this.markerInfoContainer.classList.remove(this.openClass);
    }

    private open() {
        this.markerInfoContainer.classList.add(this.openClass);
    }
}

export default MarkerClick;