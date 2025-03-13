import { Addable, LayerGroupInterface, MapInterface } from "@helsingborg-stad/openstreetmap";
import { SavedLayerGroup } from "../../mapData";
import { LayerGroupFilterInterface } from "./layerGroupFilterInterface";
import { FilterHelperInterface } from "./filterHelperInterface";

class LayerGroupWithoutFilter implements LayerGroupFilterInterface {
    private isInitiated: boolean = false;
    constructor(
        private container: HTMLElement,
        private mapInstance: MapInterface,
        private savedLayerGroup: SavedLayerGroup,
        private layerGroup: LayerGroupInterface
    ) {}

    public init(): void {
        if (this.isInitiated) {
            return;
        }

        this.layerGroup.addTo(this.mapInstance);
        this.isInitiated = true;
    }

    public hideFilter(): void {
        return;
    }

    public showFilter(): void {
        return;
    }

    public getSavedLayerGroup(): SavedLayerGroup {
        return this.savedLayerGroup;
    }

    public getLayerGroup(): LayerGroupInterface {
        return this.layerGroup;
    }
}

export default LayerGroupWithoutFilter;