import { Addable } from "@helsingborg-stad/openstreetmap";
import { LayerGroupFilterInterface } from "./layerGroupFilterInterface";

interface FilterHelperInterface {
    findChildren(id: string): LayerGroupFilterInterface[];
    findParent(parentId: string): Addable|null;
    hideChildrenFilter(id: string): void;
    showChildrenFilter(id: string): void;
}