import { CreateLayerGroupInterface, MapInterface } from "@helsingborg-stad/openstreetmap";
import { SavedLayerGroup } from "../mapData";
import { OrderedLayerGroups, StructuredLayerGroups } from "./interface";
import LayerGroupFilterFactory from "./filtering/layerGroupFilterFactory";
import { StorageInterface } from "./filtering/storageInterface";

class LayerGroups {
    constructor(
        private container: HTMLElement,
        private storageInstance: StorageInterface,
        private createLayerGroup: CreateLayerGroupInterface,
        private layerGroupFilterFactory: LayerGroupFilterFactory,
        private savedLayerGroups: SavedLayerGroup[],
    ) {}

    public createLayerGroups(): LayerGroups {
        this.savedLayerGroups.forEach(layer => {
            const layerGroup = this.createLayerGroup.create();
            console.log(layer.id);
            const filterButton = this.container.querySelector(`[data-js-layer-group="${layer.id}"]`) as HTMLElement;
            console.log(this.container);

            const layerGroupDataFilter = this.layerGroupFilterFactory.createLayerGroupFilter(
                layer,
                layerGroup,
                filterButton
            );

            this.storageInstance.setOrderedLayerGroup(layer.id, layerGroupDataFilter);

            const parent = layer.layerGroup ?? '0';
            const structuredLayerGroups = this.storageInstance.getStructuredLayerGroups();
            const structuredLayerGroup = structuredLayerGroups[parent] ?? [];
            structuredLayerGroup.push(layerGroupDataFilter);
            this.storageInstance.setStructuredLayerGroup(parent, structuredLayerGroup);
        });

        const orderedLayerGroups = this.storageInstance.getOrderedLayerGroups();
        for (const layerGroupId in orderedLayerGroups) {
            orderedLayerGroups[layerGroupId].setListener();
        }

        return this;
    }
}

export default LayerGroups;