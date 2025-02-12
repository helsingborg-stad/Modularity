import { GetFieldsInterface } from "./interface/getFields";

class GetModuleData implements GetFieldsInterface
{
    private postTypeFieldElement: null|HTMLElement = null;
    private postTypeFieldSelectElement: null|HTMLSelectElement = null;
    private taxonomyFilterFieldElement: null|HTMLElement = null;
    private taxonomyFilterFieldSelectElement: null|HTMLSelectElement = null;
    private taxonomyFilterFieldKey: string = 'interactive_map_taxonomy_filtering';
    private postTypeFieldKey: string = 'interactive_map_post_type';
    constructor(
    ) {
    }

    public getPostTypeFieldKey(): string {
        return this.postTypeFieldKey;
    }

    public getPostTypeFieldElement(): null|HTMLElement {
        if (!this.postTypeFieldElement) {
            this.postTypeFieldElement = document.querySelector('[data-name="interactive_map_post_type"]') ?? null;
        }

        return this.postTypeFieldElement;
    }

    public getPostTypeFieldSelectElement(): null|HTMLSelectElement {
        if (!this.postTypeFieldSelectElement) {
            this.postTypeFieldSelectElement = this.getPostTypeFieldElement()?.querySelector('select') ?? null;
        }

        return this.postTypeFieldSelectElement;
    }

    public getTaxonomyFieldKey(): string {
        return this.taxonomyFilterFieldKey;
    }

    public getTaxonomyFieldElement(): null|HTMLElement {
        if (!this.taxonomyFilterFieldElement) {
            this.taxonomyFilterFieldElement = document.querySelector('[data-name="interactive_map_taxonomy_filtering"]') ?? null;
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

export default GetModuleData;