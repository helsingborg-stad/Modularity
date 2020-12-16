/**
 * Attention: This variable should not be set manually
 *
 * Indicates if we are adding a new post or editing old one
 * @type {String}
 */
var postAction = 'add';
let lModularity = null
$ = jQuery;

export default function Thickbox(Modularity) {
    lModularity = Modularity;
}

Thickbox.prototype.modulePostCreated = function (postId) {
    console.log("module post created")
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
        console.log("module post sent")


        console.log(module)
        Modularity.Editor.Module.updateModule(module, data);
        Modularity.Editor.Autosave.save('form');
    }, 'json');
};