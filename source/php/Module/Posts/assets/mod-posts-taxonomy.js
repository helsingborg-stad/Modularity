jQuery(document).ready(function() {
    if (pagenow === 'mod-posts') {
        postsTaxonomy(modularity_current_post_id);  
    }
    if (pagenow === 'page') {
        /*
        var blocks = wp.data.select('core/block-editor').getBlocks();
        console.log(blocks);          
        console.log('hej');
        */
/*
for (const [key, theblock] of Object.entries(blocks)) {
  console.log(`${key}: ${theblock.name}`);
}  
*/ 
/*
    let blocksLoaded = false;
    let blocksLoadedInterval = setInterval(function() {
        const blocks = wp.data.select('core/block-editor').getBlocks();
        console.log(blocks);    
        if (blocks.length != 0) { 
            console.log('kodd');             
            blocksLoaded = true;     
            for (const [key, theblock] of Object.entries(blocks)) {
                console.log(`${key}: ${theblock.name}`);
            }   
        }
        if (blocksLoaded) {
            clearInterval(blocksLoadedInterval);
        }
    }, 500);   
*/
        
    }
});

jQuery(window).load(function() { 
    if (pagenow === 'page') {
        var blocks = wp.data.select('core/block-editor').getBlocks();
       // console.log(blocks);  
        for (const [key, theblock] of Object.entries(blocks)) {
            if (theblock.name === 'acf/posts' && theblock.attributes.mode === 'edit') {
                var blockId = '#block-' + theblock.clientId;
                pollContainerContent(theblock, blockId);
                //var blockId = '#block-' + theblock.clientId;
                //postsTaxonomy(modularity_current_post_id, theblock.attributes.data, blockId);     
            }
           // console.log(`${key}: ${theblock.name}`);
        }      
       // console.log('ftang');
    }
});

/*
jQuery(document).on('click', 'button.editor-block-list-item-acf-posts', function(){   
    console.log('klick på knapp');
    var bl = wp.data.select('core/block-editor').getSelectedBlock(); 
    console.log(bl);  
    pollContainerContent(bl, '.components-panel');    
});
*/


jQuery(document).on('click', '.acf-block-preview, .editor-block-list-item-acf-posts', function(){   
    console.log('klick på .acf-block-preview');
    var block = wp.data.select('core/block-editor').getSelectedBlock();  
    pollContainerContent(block, '.components-panel');
/*    let blockLoaded = false;
    let blockLoadedInterval = setInterval(function() {
        if (document.getElementById('modularity-latest-taxonomy-value')) { 
            const block = wp.data.select('core/block-editor').getSelectedBlock();         
            postsTaxonomy(modularity_current_post_id, block.attributes.data);               
            blockLoaded = true;      
        }
        if (blockLoaded) {
            clearInterval(blockLoadedInterval);
        }
    }, 500);    */
});


function pollContainerContent(block, container) {
    console.log(block);
    var blockLoaded = false;
    var blockLoadedInterval = setInterval(function() {
        console.log('h');
        //if (document.getElementById('modularity-latest-taxonomy-value')) { 
        if ($(container + ' .modularity-latest-taxonomy-value').length) {    
            //const block = wp.data.select('core/block-editor').getSelectedBlock();         
            postsTaxonomy(modularity_current_post_id, block.attributes.data, container);               
            blockLoaded = true;      
        }
        if (blockLoaded) {
            clearInterval(blockLoadedInterval);
        }
    }, 500);     
}

function postsTaxonomy(modularity_current_post_id, data = null, blockContainer = '') {
    var $ = (jQuery);
    const taxType = (data == null)? null : data.posts_taxonomy_type;
    const taxValue = (data == null)? null : data.posts_taxonomy_value;

    console.log(blockContainer);
    console.log($(blockContainer + ' .modularity-latest-post-type select').val());
    /**
     * Posttype Meta keys
     */
    
    getPostMeta({
        'action': 'get_sortable_meta_keys_v2',
        'posttype': $(blockContainer + ' .modularity-latest-post-type select').val(),
        'post': modularity_current_post_id,
        'container': blockContainer
    });

    $(blockContainer + ' .modularity-latest-post-type select').on('change', function () {
        getPostMeta({
            'action': 'get_sortable_meta_keys_v2',
            'posttype': $(this).val(),
            'post': modularity_current_post_id,
            'container': blockContainer
        });
    });

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

function getPostMeta(data) {
    var blockContainer = data.container;
    if ($(blockContainer + ' .modularity-sorted-by select optgroup[label="Post fields"]').length === 0) {
        $(blockContainer + ' .modularity-sorted-by select').prepend('<optgroup label="Post fields">').append('</optgroup>');
    }

    $(blockContainer + ' .modularity-latest-meta-key label, ' + blockContainer + ' .modularity-sorted-by label').prepend('<span class="spinner" style="visibility: visible; float: none; margin: 0 5px 0 0;"></span>')

    $.post(ajaxurl, data, function (response) {
        $(blockContainer + ' .modularity-sorted-by select option[value^="_metakey_"], ' + blockContainer + ' .modularity-sorted-by select optgroup[label="Post meta"]').remove();
        $(blockContainer + ' .modularity-latest-meta-key select').empty();

        if (response.meta_keys.length > 2) {
            $(blockContainer + ' .modularity-sorted-by select').append('<optgroup label="Post meta">');

            $.each(response.meta_keys, function (index, item) {
                var sort_selected = (response.sort_curr != null && item.meta_key == response.sort_curr.replace('_metakey_', '')) ? 'selected' : '';
                var filter_selected = (response.filter_curr != null && item.meta_key == response.filter_curr.replace('_metakey_', '')) ? 'selected' : '';

                $(blockContainer + ' .modularity-sorted-by select').append('<option value="_metakey_' + item.meta_key +'" ' + sort_selected + '>' + item.meta_key +'</option>');
                $(blockContainer + ' .modularity-latest-meta-key select').append('<option value="' + item.meta_key + '" ' + filter_selected + '>' + item.meta_key + '</option>');
            });

            $(blockContainer + ' .modularity-sorted-by select').append('</optgroup>');
        }

        $(blockContainer + ' .modularity-latest-meta-key .spinner, ' + blockContainer + ' .modularity-sorted-by .spinner').remove();
    }, 'json');
}

function getTaxonomyTypes(data) {
    var blockContainer = data.container;
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
        //if (pagenow === 'mod-posts') {
            getTaxonomyValues({
                'action': 'get_taxonomy_values_v2',
                'tax': $(blockContainer + ' .modularity-latest-taxonomy select').val(),
                'post': modularity_current_post_id,
                'container': blockContainer
            });
        //}
    }, 'json');
}

function getTaxonomyValues(data) {
    var blockContainer = data.container;
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