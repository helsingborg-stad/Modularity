export interface GetBlockDataInterface {
    getBlockId(): string;
    getBlock(): any;
    getField(fieldName: string): any;
    getBlockElement(): null|HTMLElement;
    getPostTypeFieldKey(): string;
    getPostTypeFieldElement(): null|HTMLElement;
    getPostTypeFieldSelectElement(): null|HTMLSelectElement;
    getTaxonomyFieldKey(): string;
    getTaxonomyFieldElement(): null|HTMLElement;
    getTaxonomyFieldSelectElement(): null|HTMLSelectElement;
}