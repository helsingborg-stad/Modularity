export interface GetBlockDataInterface {
    getBlockId(): string;
    getBlock(): any;
    getField(fieldName: string): any;
    getBlockElement(): null|HTMLElement;
}