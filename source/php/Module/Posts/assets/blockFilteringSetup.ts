import Filter from "./filter";
import { FilterElements, Block } from "./filterInterfaces";

declare const wp: any;

class BlockFilteringSetup {
    private initializedPostsBlocks: Array<string>;

    constructor(private postId: string) {
        this.initializedPostsBlocks = [];
        this.listenForBlocks();
    }

    private listenForBlocks() {
        const editor = wp.data.select('core/block-editor');

        wp.data.subscribe(() => {
            const postsBlockIds = editor.getBlocksByName('acf/posts');
            if (postsBlockIds.length > 0) {
                postsBlockIds.forEach(postBlockId => {
                    this.setupBlockTaxonomyFiltering(postBlockId, editor);
                });
            }
        });
    }

    private setupBlockTaxonomyFiltering(postBlockId: string, editor: any) {
        if (!this.initializedPostsBlocks.includes(postBlockId)) {
            this.initializedPostsBlocks.push(postBlockId);
            const block = editor.getBlock(postBlockId);
            const intervalId = setInterval(() => {
                const filterElements = this.getFilterElements(block);
                if (filterElements) {
                    this.taxonomyFilteringBlockInitialization(block, filterElements);
                    clearInterval(intervalId);
                }
            }, 1000);
        };
    }

    private taxonomyFilteringBlockInitialization(block: Block, filterElements: FilterElements) {        
        const selectedTaxonomy   = block.attributes?.data?.posts_taxonomy_type ? block.attributes.data.posts_taxonomy_type : null;
        const selectedTerm       = block.attributes?.data?.posts_taxonomy_value ? block.attributes.data.posts_taxonomy_value : null;

        const filter = new Filter(
            this.postId, 
            filterElements,
            {
                selectedTaxonomy: selectedTaxonomy, 
                selectedTerm: selectedTerm
            }
        );

        filter.initializeTaxonomyFilter();
    }

    private getFilterElements(block: Block): FilterElements|null {
        const filterContainerElement    = document.querySelector('#block-' + block.clientId);
        const taxonomySelect            = filterContainerElement?.querySelector('.modularity-latest-taxonomy select');
        const termsSelect               = filterContainerElement?.querySelector('.modularity-latest-taxonomy-value select');
        const taxonomySelectLabel       = filterContainerElement?.querySelector('.modularity-latest-taxonomy .acf-label label');
        const termsSelectLabel          = filterContainerElement?.querySelector('.modularity-latest-taxonomy-value .acf-label label');
        const postTypeSelect            = filterContainerElement?.querySelector('.modularity-latest-post-type select');

        if (
            !postTypeSelect || 
            !taxonomySelect || 
            !taxonomySelectLabel || 
            !termsSelect || 
            !termsSelectLabel
        ) {
            return null;
        }

        return {
            container: (filterContainerElement as HTMLElement), 
            postTypeSelect: (postTypeSelect as HTMLSelectElement), 
            taxonomySelect: (taxonomySelect as HTMLSelectElement),
            taxonomySelectLabel: (taxonomySelectLabel as HTMLElement),
            termsSelect: (termsSelect as HTMLSelectElement),
            termsSelectLabel: (termsSelectLabel as HTMLElement),
        };
    }
}

export default BlockFilteringSetup;