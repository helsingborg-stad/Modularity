jQuery(document).ready(function ($) {

    /**
     * Taxonomy type update
     */
    getTaxonomyTypes({
        'action': 'get_taxonomy_types',
        'posttype': $('#modularity-latest-post-type select').val(),
        'post': modularity_current_post_id
    });

    $('#modularity-latest-post-type select').on('change', function () {
        getTaxonomyTypes({
            'action': 'get_taxonomy_types',
            'posttype': $(this).val(),
            'post': modularity_current_post_id
        });
    });

    function getTaxonomyTypes(data) {
        $('#modularity-latest-taxonomy select').empty();
        $('#modularity-latest-taxonomy .acf-label label').prepend('<span class="spinner" style="visibility: visible; float: none; margin: 0 5px 0 0;"></span>');

        $.post(ajaxurl, data, function (response) {
            if (response.types.length === 0) {
                $('#modularity-latest-taxonomy .acf-label label .spinner').remove();
                return;
            }

            $.each(response.types, function (index, item) {
                var is_selected = (item.name == response.curr) ? 'selected' : '';
                $('#modularity-latest-taxonomy select').append('<option value="' + item.name + '" ' + is_selected + '>' + item.label + '</option>');
            });

            $('#modularity-latest-taxonomy .acf-label label .spinner').remove();

            getTaxonomyValues({
                'action': 'get_taxonomy_values',
                'tax': $('#modularity-latest-taxonomy select').val(),
                'post': modularity_current_post_id
            });
        }, 'json');
    }

    /**
     * Taxonomy values update
     */
    $('#modularity-latest-taxonomy select').on('change', function () {
        getTaxonomyValues({
            'action': 'get_taxonomy_values',
            'tax': $(this).val(),
            'post': modularity_current_post_id
        });
    });

    function getTaxonomyValues(data) {
        $('#modularity-latest-taxonomy-value select').empty();
        $('#modularity-latest-taxonomy-value .acf-label label').prepend('<span class="spinner" style="visibility: visible; float: none; margin: 0 5px 0 0;"></span>');

        $.post(ajaxurl, data, function (response) {
            $.each(response.tax, function (index, item) {
                var is_selected = ($.inArray(item.term_id, response.curr) > -1) ? 'selected' : '';
                $('#modularity-latest-taxonomy-value select').append('<option value="' + item.term_id + '" ' + is_selected + '>' + item.name + '</option>');
            });

            $('#modularity-latest-taxonomy-value .acf-label label .spinner').remove();
        }, 'json');
    }

});
