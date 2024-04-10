document.addEventListener('DOMContentLoaded', function() {
    if (pagenow === 'mod-posts') {
        postsTaxonomy(modPosts.currentPostID);  
    }
});

jQuery(window).load(function() { 
    if (pagenow === 'page') {
        pollBlocks();
    }
});

jQuery(document).on('click', '.acf-block-preview, .editor-block-list-item-acf-posts', function(){   
    let block = wp.data.select('core/block-editor').getSelectedBlock();  
    pollBlockContent(block, '.components-panel');
});

function pollBlocks() {
    let blockLoaded = false;
    let blockLoadedInterval = setInterval(function() {
    if (wp.data.select('core/block-editor').getBlockCount() != 0) {   
        let blocks = wp.data.select('core/block-editor').getBlocks();   
        for (const [key, theblock] of Object.entries(blocks)) {
            if (theblock.name === 'acf/posts' && theblock.attributes.mode === 'edit') {
                let blockId = '#block-' + theblock.clientId;
                pollBlockContent(theblock, blockId);   
            }
        }                  
        blockLoaded = true;      
    }
    if (blockLoaded) {
        clearInterval(blockLoadedInterval);
    }
    }, 500);       
}


function pollBlockContent(block, container) {
    let contentLoaded = false;
    let contentLoadedInterval = setInterval(function() {  
    if ($('.modularity-latest-taxonomy-value', $(container)).length) {             
        postsTaxonomy(modPosts.currentPostID, block.attributes.data, container);               
        contentLoaded = true;      
    }
    if (contentLoaded) {
        clearInterval(contentLoadedInterval);
    }
    }, 500);     
}

function postsTaxonomy(modularity_current_post_id, data = null, blockContainer = '') {
    var $ = (jQuery);
    const taxType   = data?.posts_taxonomy_type ? data.posts_taxonomy_type : null;
    const taxValue  = data?.posts_taxonomy_value ? data.posts_taxonomy_value : null;
    
    const postTypeSelect = document.querySelector(blockContainer + ' .modularity-latest-post-type select');
    const taxonomySelect = document.querySelector(blockContainer + ' .modularity-latest-taxonomy select');

    /**
     * Taxonomy type update
     */
    getTaxonomyTypes({
        'action': 'get_taxonomy_types_v2',
        'posttype': postTypeSelect.value,
        'post': modularity_current_post_id,
        'selected': taxType,
        'container': blockContainer
    });

    // $(blockContainer + ' .modularity-latest-post-type select').on('change', function () {
    //     console.log("runs");
    //     getTaxonomyTypes({
    //         'action': 'get_taxonomy_types_v2',
    //         'posttype': $(this).val(),
    //         'post': modularity_current_post_id,
    //         'container': blockContainer
    //     });
    // })

    /**
     * Taxonomy values update
     */
    // setTimeout(function() {
    //     getTaxonomyValues({
    //         'action': 'get_taxonomy_values_v2',
    //         'tax': taxType,
    //         'post': modularity_current_post_id,
    //         'selected': taxValue,
    //         'container': blockContainer
    //     });
    // }, 300);

    taxonomySelect.addEventListener('change', (e) => {
        getTaxonomyValues({
            'action': 'get_taxonomy_values_v2',
            'tax': taxonomySelect.value,
            'post': modularity_current_post_id,
            'container': blockContainer
        });
    });

    // $(blockContainer + ' .modularity-latest-taxonomy select').on('change', function () {
    //     console.log($(this).val());
    //     getTaxonomyValues({
    //         'action': 'get_taxonomy_values_v2',
    //         'tax': $(this).val(),
    //         'post': modularity_current_post_id,
    //         'container': blockContainer
    //     });
    // });
}

function getTaxonomyTypes(data) {
    let blockContainer = data.container;

    const selectElement = document.querySelector(blockContainer + ' .modularity-latest-taxonomy select');
    
    while (selectElement.firstChild) {
        selectElement.removeChild(selectElement.firstChild);
    }

    const labelElement = document.querySelector(blockContainer + ' .modularity-latest-taxonomy .acf-label label');
    labelElement.insertAdjacentHTML('afterbegin', '<span class="spinner" style="visibility: visible; float: none; margin: 0 5px 0 0;"></span>');

    const xhr = new XMLHttpRequest();
    xhr.open('POST', ajaxurl, true);
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    xhr.onreadystatechange = function () {
        if (xhr.readyState === 4 && xhr.status === 200) {
            const response = JSON.parse(xhr.responseText);
            const spinner = document.querySelector(blockContainer + ' .modularity-latest-taxonomy .acf-label label .spinner');

            if (!response.types || response.types.length <= 0) {
                spinner?.remove();
                return;
            }

            const keys = Object.keys(response.types);

            keys.forEach(key => {
                const taxonomy = response.types[key];
                const isSelected = (taxonomy.name === response.curr || taxonomy.name === data.selected) ? 'selected' : '';
                selectElement.insertAdjacentHTML('beforeend', `<option value="${taxonomy.name}" ${isSelected}>${taxonomy.label}</option>`);
            });

            spinner?.remove();
            
            getTaxonomyValues({
                'action': 'get_taxonomy_values_v2',
                'tax': selectElement.value,
                'post': modPosts.currentPostID,
                'container': blockContainer
            });    
        }
    }
    
    const urlEncodedData = Object.keys(data).map(key => encodeURIComponent(key) + '=' + encodeURIComponent(data[key])).join('&');
    
    xhr.send(urlEncodedData);
}

function getTaxonomyValues(data) {
    console.log(data);
    const blockContainer = data.container;

    const selectElement = document.querySelector(blockContainer + ' .modularity-latest-taxonomy-value select');
    while (selectElement.firstChild) {
        selectElement.removeChild(selectElement.firstChild);
    }

    const labelElement = document.querySelector(blockContainer + ' .modularity-latest-taxonomy-value .acf-label label');
    labelElement.insertAdjacentHTML('afterbegin', '<span class="spinner" style="visibility: visible; float: none; margin: 0 5px 0 0;"></span>');

    const xhr = new XMLHttpRequest();
    xhr.open('POST', ajaxurl, true);
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    xhr.onreadystatechange = function () {
        if (xhr.readyState === 4 && xhr.status === 200) {
            const response = JSON.parse(xhr.responseText);      
            const spinner = document.querySelector(blockContainer + ' .modularity-latest-taxonomy-value .acf-label label .spinner');

            const keys = Object.keys(response.tax);
            keys.forEach(key => {
                const term = response.tax[key];
                const isSelected = (term.slug === response.curr || term.slug === data.selected) ? 'selected' : '';
                selectElement.insertAdjacentHTML('beforeend', `<option value="${term.slug} ${isSelected}">${term.name}</option>`);
            });
            spinner?.remove();
        }    
    }

    const urlEncodedData = Object.keys(data).map(key => encodeURIComponent(key) + '=' + encodeURIComponent(data[key])).join('&');
    console.log(urlEncodedData);
    
    xhr.send(urlEncodedData);
}