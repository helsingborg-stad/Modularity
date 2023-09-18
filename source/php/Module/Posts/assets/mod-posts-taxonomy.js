jQuery(document).ready(function() {
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
    const taxType = (data == null)? null : data.posts_taxonomy_type;
    const taxValue = (data == null)? null : data.posts_taxonomy_value;

    /**
     * Taxonomy type update
     */
    getTaxonomyTypes({
        'action': 'get_taxonomy_types_v2',
        'posttype': $(blockContainer + ' .modularity-latest-post-type select').val(),
        'post': modularity_current_post_id,
        'selected': taxType,
        'container': blockContainer
    });

    $(blockContainer + ' .modularity-latest-post-type select').on('change', function () {
        getTaxonomyTypes({
            'action': 'get_taxonomy_types_v2',
            'posttype': $(this).val(),
            'post': modularity_current_post_id,
            'container': blockContainer
        });
    });

    /**
     * Taxonomy values update
     */

     getTaxonomyValues({
        'action': 'get_taxonomy_values_v2',
        'tax': taxType,
        'post': modularity_current_post_id,
        'selected': taxValue,
        'container': blockContainer
    });   

    $(blockContainer + ' .modularity-latest-taxonomy select').on('change', function () {
        getTaxonomyValues({
            'action': 'get_taxonomy_values_v2',
            'tax': $(this).val(),
            'post': modularity_current_post_id,
            'container': blockContainer
        });
    });

}

function getTaxonomyTypes(data) {
    let blockContainer = data.container;
    $(blockContainer + ' .modularity-latest-taxonomy select').empty();
    $(blockContainer + ' .modularity-latest-taxonomy .acf-label label').prepend('<span class="spinner" style="visibility: visible; float: none; margin: 0 5px 0 0;"></span>');

    $.post(ajaxurl, data, function (response) {
        if (response.types.length === 0) {
            $(blockContainer + ' .modularity-latest-taxonomy .acf-label label .spinner').remove();
            return;
        }
        $.each(response.types, function (index, item) {
            var is_selected = (item.name == response.curr || item.name == data.selected) ? 'selected' : '';
            $(blockContainer + ' .modularity-latest-taxonomy select').append('<option value="' + item.name+ '" ' + is_selected + '>' + item.label + '</option>');
        });

        $(blockContainer + ' .modularity-latest-taxonomy .acf-label label .spinner').remove();
        getTaxonomyValues({
            'action': 'get_taxonomy_values_v2',
            'tax': $(blockContainer + ' .modularity-latest-taxonomy select').val(),
            'post': modPosts.currentPostID,
            'container': blockContainer
        });
    }, 'json');
}

function getTaxonomyValues(data) {
    let blockContainer = data.container;
    $(blockContainer + ' .modularity-latest-taxonomy-value select').empty();
    $(blockContainer + ' .modularity-latest-taxonomy-value .acf-label label').prepend('<span class="spinner" style="visibility: visible; float: none; margin: 0 5px 0 0;"></span>');

    $.post(ajaxurl, data, function (response) {
        $.each(response.tax, function (index, item) {
            if ($(blockContainer + " .modularity-latest-taxonomy-value select option[value='"+item.slug+"']").length == 0) {
                var is_selected = (item.slug == response.curr || item.slug == data.selected) ? 'selected' : '';
                $(blockContainer + ' .modularity-latest-taxonomy-value select').append('<option value="' + item.slug + '" ' + is_selected + '>' + item.name + '</option>');
            }
        });

        $(blockContainer + ' .modularity-latest-taxonomy-value .acf-label label .spinner').remove();
    }, 'json');
}