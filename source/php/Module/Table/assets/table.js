jQuery(document).ready(function ($) {
    // Add tools
    setTimeout(function () {

        $('.acf-field-dynamic-table').each(function (index, element) {

            $(this).find('td:not([class])').append('\
                <div class="table-tools">\
                <ul>\
                    <li data-action="bold"><i class="mce-ico mce-i-bold"></i></li>\
                    <li data-action="italic"><i class="mce-ico mce-i-italic"></i></li>\
                    <li data-action="strikethrough"><i class="mce-ico mce-i-strikethrough"></i></li>\
                    <li data-action="link"><i class="mce-ico mce-i-link"></i></li>\
                </ul>\
                </div>\
            ');

        });

    }, 1000);

    $(document).on('click', function (e) {
        $('.table-tools').hide();
        $(e.target).parents('td').find('.table-tools').show();
    });

    $('body').on('click', '.acf-field-dynamic-table td', function (){
        var tableTool = $('.table-tools').html();
        $('.table-tools').remove();
        $(this).append('<div class="table-tools">'+tableTool+'</div>');
    });

    $('body').on('focus','.acf-field-dynamic-table td:not([class]) input', function (e) {
        $(this).siblings('.table-tools').show();
    });

    // Do action
    $('body').on('click', '.table-tools [data-action]', function (e) {
        e.preventDefault();

        var $input = $(this).parents('td').find('input').first();
        var action = $(this).data('action');

        var selection = {
            start: $input[0].selectionStart,
            end: $input[0].selectionEnd
        };

        var val = $input.val();
        var newVal = '';

        switch (action) {
            case 'bold':
                newVal = val.substr(0, selection.start) + '<strong>' + val.substr(selection.start, (selection.end-selection.start)) + '</strong>' + val.substr(selection.end);
                break;

            case 'italic':
                newVal = val.substr(0, selection.start) + '<span style="font-style:italic;">' + val.substr(selection.start, (selection.end-selection.start)) + '</span>' + val.substr(selection.end);
                break;

            case 'strikethrough':
                newVal = val.substr(0, selection.start) + '<s>' + val.substr(selection.start, (selection.end-selection.start)) + '</s>' + val.substr(selection.end);
                break;

            case 'link':
                var linkUrl = window.prompt('What URL do you want to link to?', '#');
                newVal = val.substr(0, selection.start) + '<a href="' + linkUrl + '">' + val.substr(selection.start, (selection.end-selection.start)) + '</a>' + val.substr(selection.end);
                break;

            default:
                return;
        }

        $input.val(newVal);
    });
});
