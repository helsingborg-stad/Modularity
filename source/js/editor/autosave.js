export default (function ($) {

    function Autosave() {
    }

    Autosave.prototype.save = function (selector) {
        $('#modularity-options #publishing-action .spinner').css('visibility', 'visible');
        var request = $(selector).serializeObject();
        request.id = window.modularity_post_id;
        request.action = 'save_modules';

        $.post(ajaxurl, request, function (response) {
            $('#modularity-options #publishing-action .spinner').css('visibility', 'hidden');
        });
    };

    return new Autosave();

})(jQuery);
