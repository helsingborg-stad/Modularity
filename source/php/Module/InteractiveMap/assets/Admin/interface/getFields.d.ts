export interface GetFieldsInterface {
    getPostTypeFieldKey(): string;
    getPostTypeFieldElement(): null|HTMLElement;
    getPostTypeFieldSelectElement(): null|HTMLSelectElement;
    getTaxonomyFieldKey(): string;
    getTaxonomyFieldElement(): null|HTMLElement;
    getTaxonomyFieldSelectElement(): null|HTMLSelectElement;
    getField(fieldName: string): any;
}