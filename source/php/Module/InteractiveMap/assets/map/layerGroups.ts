import { CreateLayerGroup, MapInterface } from "@helsingborg-stad/openstreetmap";
import { SavedLayerGroup } from "../mapData";
import { LayerGroupsData } from "./interface";

class LayerGroups {
    constructor(
        private map: MapInterface,
        private savedLayerGroups: SavedLayerGroup[]
    ) {}

    createLayerGroups(): LayerGroupsData {
        let layerGroups: LayerGroupsData = {};

        // Save as a structured Object (LayerGroupsData)
        this.savedLayerGroups.forEach(layer => {
            const layerGroup = new CreateLayerGroup().create();
            layerGroups[layer.id] = {data: layer, layerGroup: layerGroup};
        });

        // Add layerGroups to the map or to another layerGroup
        for (const id in layerGroups) {
            const { data, layerGroup } = layerGroups[id];
            if (data.layerGroup && layerGroups.hasOwnProperty(data.layerGroup)) {
                layerGroup.addTo(layerGroups[data.layerGroup].layerGroup);
            } else {
                layerGroup.addTo(this.map);
            }
        }

        return layerGroups;
    }
}

export default LayerGroups;