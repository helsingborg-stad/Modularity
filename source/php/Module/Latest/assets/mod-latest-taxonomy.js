jQuery(document).ready(function ($) {

    getTaxs({
        'action': 'get_taxonomy_values',
        'tax': $('#modularity-latest-taxonomy select').val(),
        'post': modularity_current_post_id
    });

    $('#modularity-latest-taxonomy select').on('change', function () {
        getTaxs({
            'action': 'get_taxonomy_values',
            'tax': $(this).val(),
            'post': modularity_current_post_id
        });
    });

    function getTaxs(data) {
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
