import Module from './module.js';
import Modal from '../prompt/modal.js';
import Autosave from './autosave.js';

 export default (function ($) {

    /**
     * Attention: This variable should not be set manually
     *
     * Indicates if we are adding a new post or editing old one
     * @type {String}
     */
    window.postAction = 'add';

    function Thickbox() {

    }

    Thickbox.prototype.getPostAction = function () {

        console.log("Retrived post action: " + window.postAction); 

        return window.postAction; 
    }
    Thickbox.prototype.setPostAction = function (newPostAction) {

        window.postAction = newPostAction; 

        console.log("Set post action: " + window.postAction + "(" + newPostAction + ")")
    }

    Thickbox.prototype.modulePostCreated = function (postId) {
        Modal.close();

        var module = Module.isEditingModule();

        var request = {
            action: 'get_post',
            id: postId
        };

        $.post(ajaxurl, request, function (response) {
            var data = {
                post_id: response.ID,
                title: response.post_title
            };

            Module.updateModule(module, data);
            Autosave.save('form');
        }, 'json');
    };

    return new Thickbox();

})(jQuery);
