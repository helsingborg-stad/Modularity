import { GetBlockDataInterface } from "./getBlocksData";

class GetBlockData implements GetBlockDataInterface {
    private block: any;
    private blockElement: null|HTMLElement;
    private postTypeFieldElement: null|HTMLElement;
    private postTypeFieldSelectElement: null|HTMLSelectElement;
    private taxonomyFilterFieldElement: null|HTMLElement;
    private taxonomyFilterFieldSelectElement: null|HTMLSelectElement;
    private taxonomyFilterFieldKey: string = 'interactive_map_taxonomy_filtering';
    private postTypeFieldKey: string = 'interactive_map_post_type';

    constructor(
        private blockId: string,
        private editor: any
    ) {
        this.block = null;
        this.blockElement   = null;
        this.postTypeFieldElement = null;
        this.postTypeFieldSelectElement = null;
        this.taxonomyFilterFieldElement = null;
        this.taxonomyFilterFieldSelectElement = null;
    }

    public getBlockId(): string {
        return this.blockId;
    }

    public getBlock(): any {
        if (!this.block) {
            this.block = this.editor.getBlock(this.blockId);
        }

        return this.block;
    }

    public getField(fieldName: string) {
        return this.getBlock()?.attributes?.data?.[fieldName] ?? null;
    }

    public getBlockElement(): null|HTMLElement {
        if (!this.blockElement) {
            this.blockElement = document.querySelector(`[data-block="${this.blockId}"]`);
        }

        return this.blockElement;
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