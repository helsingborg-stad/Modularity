import { GetBlockDataInterface } from "./interface/getBlocksData";
import { GetFieldsInterface } from "./interface/getFields";

class GetBlockData implements GetBlockDataInterface, GetFieldsInterface {
    private block: any = null;
    private blockElement: null|HTMLElement = null;
    private postTypeFieldElement: null|HTMLElement = null;
    private postTypeFieldSelectElement: null|HTMLSelectElement = null;
    private taxonomyFilterFieldElement: null|HTMLElement = null;
    private taxonomyFilterFieldSelectElement: null|HTMLSelectElement = null;
    private taxonomyFilterFieldKey: string = 'interactive_map_taxonomy_filtering';
    private postTypeFieldKey: string = 'interactive_map_post_type';

    constructor(
        private blockId: string,
        private editor: any
    ) {
    }

    public getBlockId(): string {
        return this.blockId;
    }

    public getBlock(): any {
        if (!this.block) {
            this.block = this.editor.getBlock(this.getBlockId());
        }

        return this.block;
    }

    public getBlockElement(): null|HTMLElement {
        if (!this.blockElement) {
            this.blockElement = document.querySelector(`[data-block="${this.getBlockId()}"]`);
        }

        return this.blockElement;
    }
    
    public getField(fieldName: string) {
        return this.getBlock()?.attributes?.data?.[fieldName] ?? null;
    }

    public getTaxonomyFieldKey(): string {
        return this.taxonomyFilterFieldKey;
    }

    public getPostTypeFieldElement(): null|HTMLElement {
        if (!this.postTypeFieldElement) {
            this.postTypeFieldElement = this.getBlockElement()?.querySelector('[data-name="interactive_map_post_type"]') ?? null;
        }

        return this.postTypeFieldElement;
    }

    public getPostTypeFieldSelectElement(): null|HTMLSelectElement {
        if (!this.postTypeFieldSelectElement) {
            this.postTypeFieldSelectElement = this.getPostTypeFieldElement()?.querySelector('select') ?? null;
        }

        return this.postTypeFieldSelectElement;
    }

    public getPostTypeFieldKey(): string {
        return this.postTypeFieldKey;
    }

    public getTaxonomyFieldElement(): null|HTMLElement {
        if (!this.taxonomyFilterFieldElement) {
            this.taxonomyFilterFieldElement = this.getBlockElement()?.querySelector('[data-name="interactive_map_taxonomy_filtering"]') ?? null;
        }

        return this.taxonomyFilterFieldElement;
    }

    public getTaxonomyFieldSelectElement(): null|HTMLSelectElement {
        if (!this.taxonomyFilterFieldSelectElement) {
            this.taxonomyFilterFieldSelectElement = this.getTaxonomyFieldElement()?.querySelector('select') ?? null;
        }

        return this.taxonomyFilterFieldSelectElement;
    }
}

export default GetBlockData;