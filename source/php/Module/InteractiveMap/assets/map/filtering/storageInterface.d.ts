import { OrderedLayerGroups, StructuredLayerGroups } from "../interface";
import { FilterInterface } from "./filterInterface";

interface StorageInterface {
    setStructuredLayerGroup(id: string, value: FilterInterface[]): void;
    getStructuredLayerGroups(): StructuredLayerGroups;
    setOrderedLayerGroup(id: string, value: FilterInterface): void;
    getOrderedLayerGroups(): OrderedLayerGroups;
}