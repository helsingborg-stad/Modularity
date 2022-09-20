jQuery(document).ready(function() {
    if (pagenow === 'mod-posts') {
        postsTaxonomy(modularity_current_post_id);  
    }
    if (pagenow === 'page') {
        console.log('hej');
/*
for (const [key, theblock] of Object.entries(blocks)) {
  console.log(`${key}: ${theblock.name}`);
}  
*/ 
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

        
    }
});

jQuery(document).on('click', '.acf-block-preview', function(){    
    let blockLoaded = false;
    let blockLoadedInterval = setInterval(function() {
        if (document.getElementById('modularity-latest-taxonomy-value')) { 
            const block = wp.data.select('core/block-editor').getSelectedBlock();         
            postsTaxonomy(modularity_current_post_id, block.attributes.data);               
            blockLoaded = true;      
        }
        if (blockLoaded) {
            clearInterval(blockLoadedInterval);
        }
    }, 500);    
});

function pollTaxonomies() {

}


function postsTaxonomy(modularity_current_post_id, data = null) {
    var $ = (jQuery);
    const taxType = (data == null)? null : data.posts_taxonomy_type;
    const taxValue = (data == null)? null : data.posts_taxonomy_value;
    /**
     * Posttype Meta keys
     */
    
    getPostMeta({
        'action': 'get_sortable_meta_keys_v2',
        'posttype': $('#modularity-latest-post-type select').val(),
        'post': modularity_current_post_id
    });

    $('#modularity-latest-post-type select').on('change', function () {
        getPostMeta({
            'action': 'get_sortable_meta_keys_v2',
            'posttype': $(this).val(),
            'post': modularity_current_post_id
        });
    });

    /**
     * Taxonomy type update
     */
    getTaxonomyTypes({
        'action': 'get_taxonomy_types_v2',
        'posttype': $('#modularity-latest-post-type select').val(),
        'post': modularity_current_post_id,
        'selected': taxType
    });

    $('#modularity-latest-post-type select').on('change', function () {
        getTaxonomyTypes({
            'action': 'get_taxonomy_types_v2',
            'posttype': $(this).val(),
            'post': modularity_current_post_id
        });
    });

    /**
     * Taxonomy values update
     */
    
     getTaxonomyValues({
        'action': 'get_taxonomy_values_v2',
        'tax': taxType,
        'post': modularity_current_post_id,
        'selected': taxValue
    });
    

    $('#modularity-latest-taxonomy select').on('change', function () {
        getTaxonomyValues({
            'action': 'get_taxonomy_values_v2',
            'tax': $(this).val(),
            'post': modularity_current_post_id
        });
    });

}

function getPostMeta(data) {
    if ($('#modularity-sorted-by select optgroup[label="Post fields"]').length === 0) {
        $('#modularity-sorted-by select').prepend('<optgroup label="Post fields">').append('</optgroup>');
    }

    $('#modularity-latest-meta-key label, #modularity-sorted-by label').prepend('<span class="spinner" style="visibility: visible; float: none; margin: 0 5px 0 0;"></span>')

    $.post(ajaxurl, data, function (response) {
        $('#modularity-sorted-by select option[value^="_metakey_"], #modularity-sorted-by select optgroup[label="Post meta"]').remove();
        $('#modularity-latest-meta-key select').empty();

        if (response.meta_keys.length > 2) {
            $('#modularity-sorted-by select').append('<optgroup label="Post meta">');

            $.each(response.meta_keys, function (index, item) {
                var sort_selected = (response.sort_curr != null && item.meta_key == response.sort_curr.replace('_metakey_', '')) ? 'selected' : '';
                var filter_selected = (response.filter_curr != null && item.meta_key == response.filter_curr.replace('_metakey_', '')) ? 'selected' : '';

                $('#modularity-sorted-by select').append('<option value="_metakey_' + item.meta_key +'" ' + sort_selected + '>' + item.meta_key +'</option>');
                $('#modularity-latest-meta-key select').append('<option value="' + item.meta_key + '" ' + filter_selected + '>' + item.meta_key + '</option>');
            });

            $('#modularity-sorted-by select').append('</optgroup>');
        }

        $('#modularity-latest-meta-key .spinner, #modularity-sorted-by .spinner').remove();
    }, 'json');
}

function getTaxonomyTypes(data) {
    $('#modularity-latest-taxonomy select').empty();
    $('#modularity-latest-taxonomy .acf-label label').prepend('<span class="spinner" style="visibility: visible; float: none; margin: 0 5px 0 0;"></span>');

    $.post(ajaxurl, data, function (response) {
        if (response.types.length === 0) {
            $('#modularity-latest-taxonomy .acf-label label .spinner').remove();
            return;
        }
        $.each(response.types, function (index, item) {
            var is_selected = (item.name == response.curr || item.name == data.selected) ? 'selected' : '';
            $('#modularity-latest-taxonomy select').append('<option value="' + item.name+ '" ' + is_selected + '>' + item.label + '</option>');
        });

        $('#modularity-latest-taxonomy .acf-label label .spinner').remove();

        getTaxonomyValues({
            'action': 'get_taxonomy_values_v2',
            'tax': $('#modularity-latest-taxonomy select').val(),
            'post': modularity_current_post_id
        });
    }, 'json');
}

function getTaxonomyValues(data) {
    $('#modularity-latest-taxonomy-value select').empty();
    $('#modularity-latest-taxonomy-value .acf-label label').prepend('<span class="spinner" style="visibility: visible; float: none; margin: 0 5px 0 0;"></span>');

    $.post(ajaxurl, data, function (response) {
        $.each(response.tax, function (index, item) {
            if ($("#modularity-latest-taxonomy-value select option[value='"+item.slug+"']").length == 0) {
                var is_selected = (item.slug == response.curr || item.slug == data.selected) ? 'selected' : '';
                $('#modularity-latest-taxonomy-value select').append('<option value="' + item.slug + '" ' + is_selected + '>' + item.name + '</option>');
            }
        });

        $('#modularity-latest-taxonomy-value .acf-label label .spinner').remove();
    }, 'json');
}