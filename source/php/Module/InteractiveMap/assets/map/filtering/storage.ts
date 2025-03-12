import { OrderedLayerGroups, StructuredLayerGroups } from "../interface";
import { FilterInterface } from "./filterInterface";
import { StorageInterface } from "./storageInterface";

class Storage implements StorageInterface {
    protected structuredLayerGroups: StructuredLayerGroups = {};
    protected orderedLayerGroups: OrderedLayerGroups = {};

    public setStructuredLayerGroup(id: string, value: FilterInterface[]): void {
        this.structuredLayerGroups[id] = value;
    }

    public getStructuredLayerGroups(): StructuredLayerGroups {
        return this.structuredLayerGroups;
    }

    public setOrderedLayerGroup(id: string, value: FilterInterface): void {
        this.orderedLayerGroups[id] = value;
    }

    public getOrderedLayerGroups(): OrderedLayerGroups {
        return this.orderedLayerGroups;
    }
}

export default Storage;