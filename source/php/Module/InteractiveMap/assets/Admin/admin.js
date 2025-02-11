import ModuleTaxonomyFiltering from './moduleTaxonomyFiltering';
import GetBlockData from './getBlockData';
let initializedBlocks = {};

if (wp && acf && interactiveMapData && interactiveMapData.taxonomies && interactiveMapData.translations) {
    const editor = wp.data.select('core/block-editor');
    wp.data.subscribe(() => {
        const interactivemaps = editor.getBlocksByName('acf/interactivemap')

        if (interactivemaps.length > 0) {
            interactivemaps.forEach((blockId) => {
                if (initializedBlocks[blockId]) {
                    return;
                }

                initializedBlocks[blockId] = new GetBlockData(blockId, editor);

                let intervalId = setInterval(() => {
                    if (
                        initializedBlocks[blockId].getPostTypeFieldSelectElement() && 
                        initializedBlocks[blockId].getTaxonomyFieldSelectElement()
                    ) {
                        clearInterval(intervalId);
                        new ModuleTaxonomyFiltering(
                            initializedBlocks[blockId],
                            interactiveMapData.taxonomies,
                            interactiveMapData.translations
                        );
                    }
                }, 1000);
            });
        }
    });
}