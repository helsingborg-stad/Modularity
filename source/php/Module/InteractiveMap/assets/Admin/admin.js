import BlockTaxonomyFiltering from './blockTaxonomyFiltering';
import ModuleTaxonomyFiltering from './moduleTaxonomyFiltering';
import GetBlockData from './getBlockData';
import GetModuleData from './getModuleData';
import internal from 'stream';
let initializedBlocks = {};

if (pagenow === 'page' && wp && wp.blocks) {
    initializeBlock();
} else if (pagenow === 'mod-interactivemap') {
    initializeModule();
}

console.log(pagenow);
function initializeModule()
{
    if (checkIfModuleVariableExists()) {
        return;
    }

    document.addEventListener('DOMContentLoaded', () => {
        new ModuleTaxonomyFiltering(
            new GetModuleData(),
            interactiveMapData.taxonomies,
            interactiveMapData.translations
        )
    });
}

function initializeBlock() 
{
    if (
        !acf ||
        checkIfModuleVariableExists()
    ) {
        return;
    }
    
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
                        new BlockTaxonomyFiltering(
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

function checkIfModuleVariableExists()
{
    return !interactiveMapData || !interactiveMapData.taxonomies || !interactiveMapData.translations;
}
