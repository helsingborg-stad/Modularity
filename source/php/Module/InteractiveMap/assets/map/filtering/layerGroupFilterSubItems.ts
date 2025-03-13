import { LayerGroupInterface, MapInterface } from "@helsingborg-stad/openstreetmap";
import { SavedLayerGroup } from "../../mapData";
import Storage from "./storage";
import { LayerGroupFilterInterface, LayerGroupFilters } from "./layerGroupFilterInterface";
import { FilterHelperInterface } from "./filterHelperInterface";

class LayerGroupFilterSubItems implements LayerGroupFilters {
    private listenerIsInitiated: boolean = false;
    private parent: LayerGroupFilterInterface|null;
    private filterButton: HTMLElement|null;
    private activeClass: string = 'is-active';
    private displayNoneClass: string = 'u-display--none';

    constructor(
        private container: HTMLElement,
        private mapInstance: MapInterface,
        private filterHelperInstance: FilterHelperInterface,
        private savedLayerGroup: SavedLayerGroup,
        private layerGroup: LayerGroupInterface
    ) {
        this.parent = this.filterHelperInstance.findParent(this.getSavedLayerGroup().layerGroup);
        this.filterButton = this.container.querySelector(`[data-js-layer-group="${this.getSavedLayerGroup().id}"]`) as HTMLElement;
    }

    public init(): void {
        if (this.listenerIsInitiated) {
            return;
        }

        if (!this.parent || !this.filterButton) {
            return;
        }

        this.listenerIsInitiated = true;
        this.setSubListener();
    }

    private setSubListener(): void {
        this.filterButton!.addEventListener('click', () => {
            if (this.filterButton!.classList.contains(this.activeClass)) {
                this.getLayerGroup().removeLayerGroupFrom(this.parent!.getLayerGroup());
                this.filterButton!.classList.remove(this.activeClass);
                this.hideChildren();
            } else {
                this.getLayerGroup().addTo(this.parent!.getLayerGroup());
                this.filterButton!.classList.add(this.activeClass);
                this.showChildren();
            }
        });
    }

    private hideChildren(): void {
        this.filterHelperInstance.hideChildrenFilter(this.getSavedLayerGroup().id);
    }

    private showChildren(): void {
        this.filterHelperInstance.showChildrenFilter(this.getSavedLayerGroup().id);
    }

    public hideFilter(): void {
        this.filterButton?.classList.add(this.displayNoneClass);
        this.hideChildren();
    }

    public showFilter(): void {
        this.filterButton?.classList.remove(this.displayNoneClass);
        this.showChildren();
    }

    public getSavedLayerGroup(): SavedLayerGroup {
        return this.savedLayerGroup;
    }

    public getLayerGroup(): LayerGroupInterface {
        return this.layerGroup;
    }
}

export default LayerGroupFilterSubItems;