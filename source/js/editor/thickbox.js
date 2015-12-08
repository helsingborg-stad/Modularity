Modularity = Modularity || {};
Modularity.Editor = Modularity.Editor || {};

Modularity.Editor.Thickbox = (function ($) {

    /**
     * Attention: This variable should not be set manually
     *
     * Indicates if we are adding a new post or editing old one
     * @type {String}
     */
    var postAction = 'add';

    function Thickbox() {
        $(function(){
            //this.handleEvents();
        }.bind(this));
    }

    Thickbox.prototype.modulePostCreated = function (postId) {
        Modularity.Prompt.Modal.close();

        var module = Modularity.Editor.Module.isEditingModule();

        var request = {
            action: 'get_post',
            id: postId
        };

        $.post(ajaxurl, request, function (response) {
            var data = {
                post_id: response.ID,
                title: response.post_title
            };

            Modularity.Editor.Module.updateModule(module, data);
        }, 'json');
    };

    return new Thickbox();

})(jQuery);
