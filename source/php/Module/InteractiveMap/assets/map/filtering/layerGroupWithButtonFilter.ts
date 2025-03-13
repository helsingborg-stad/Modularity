import { Addable, LayerGroupInterface, MapInterface } from "@helsingborg-stad/openstreetmap";
import { SavedLayerGroup } from "../../mapData";
import { LayerGroupFilterInterface } from "./layerGroupFilterInterface";
import { FilterHelperInterface } from "./filterHelperInterface";

class LayerGroupWithButtonFilter implements LayerGroupFilterInterface {
    private listenerIsInitiated: boolean = false;
    private parent: Addable|null;
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
        if (this.listenerIsInitiated || !this.filterButton) {
            return;
        }

        this.listenerIsInitiated = true;
        this.setSubListener();
    }

    private setSubListener(): void {
        this.filterButton!.addEventListener('click', () => {
            if (this.filterButton!.classList.contains(this.activeClass)) {
                this.getLayerGroup().removeLayerGroupFrom(this.parent ?? this.mapInstance);
                this.filterButton!.classList.remove(this.activeClass);
                this.hideChildren();
            } else {
                this.getLayerGroup().addTo(this.parent ?? this.mapInstance);
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

export default LayerGroupWithButtonFilter;