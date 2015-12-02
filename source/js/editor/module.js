Modularity = Modularity || {};
Modularity.Editor = Modularity.Editor || {};

Modularity.Editor.Module = (function ($) {

    var thickboxOptions = {
        is_thickbox: true,
        TB_iframe: true,
        width: 980,
        height: 600
    };

    var editingModule = false;

    function Module() {
        $(function(){
            this.handleEvents();
        }.bind(this));
    }

    Module.prototype.isEditingModule = function () {
        return editingModule;
    };

    Module.prototype.getThickBoxUrl = function (action, data) {
        var base = '';
        var querystring = {};

        switch (action) {
            case 'add':
                base = 'post-new.php';
                break;

            case 'edit':
                base = 'post.php';
                break;
        }

        if (typeof data.postId == 'number') {
            querystring.post = data.postId;
            querystring.action = 'edit';
        }

        if (typeof data.postType == 'string') {
            querystring.post_type = data.postType;
        }

        return admin_url + base + '?' + $.param(querystring) + '&' + $.param(thickboxOptions);
    };

    /**
     * Adds a module "row" to the target placeholder
     * @param {selector} target   The target selector
     * @param {string} moduleId   The module id slug
     * @param {string} moduleName The module name
     */
    Module.prototype.addModule = function (target, moduleId, moduleName) {
        postId = (typeof postId != 'undefined') ? postId : '';

        var thickboxUrl = this.getThickBoxUrl('add', {
            postType: moduleId
        });

        Modularity.Editor.Thickbox.postAction = 'add';

        if (postId) {
            thickboxUrl = this.getThickBoxUrl('edit', {
                postId: moduleId
            });

            Modularity.Editor.Thickbox.postAction = 'edit';
        }

        $(target).append('\
            <li data-module-id="' + moduleId + '">\
                <span class="modularity-sortable-handle"></span>\
                <span class="modularity-module-name">\
                        ' + moduleName + '\
                        <span class="modularity-module-title"></span>\
                </span>\
                <span class="modularity-module-actions">\
                    <a href="' + thickboxUrl + '" class="modularity-js-thickbox-open">Edit</a>\
                </span>\
                <span class="modularity-module-remove"><button data-action="modularity-module-remove"></button></span>\
                <input type="hidden" name="modularity_post_id[]" value="' + postId + '">\
            </li>\
        ');

        $('.modularity-js-sortable').sortable('refresh');
    };

    Module.prototype.updateModule = function (module, data) {
        // Href
        module.find('a.modularity-js-thickbox-open').attr('href', this.getThickBoxUrl('edit', {
            postId: data.post_id
        }));

        // Post id input
        module.find('input[name="modularity_post_id[]"]').val(data.post_id);

        // Post title
        module.find('.modularity-module-title').text(': ' + data.title);
    };

    /**
     * Removes a module "row" from the placeholder
     * @param  {DOM Element} module The (to be removed) module's dom element
     * @return {void}
     */
    Module.prototype.removeModule = function (module) {
        if (confirm('Are you sure you want to remove this module?')) {
            module.remove();
        }
    };

    /**
     * Handle events
     * @return {void}
     */
    Module.prototype.handleEvents = function () {
        // Trash icon
        $(document).on('click', '[data-action="modularity-module-remove"]', function (e) {
            e.preventDefault();

            var target = $(e.target).closest('li');
            this.removeModule(target);
        }.bind(this));

        // Edit
        $(document).on('click', '.modularity-js-thickbox-open', function (e) {
            e.preventDefault();

            var el = $(e.target).closest('a');
            if (el.attr('href').indexOf('post.php') > -1) {
                Modularity.Editor.Thickbox.postAction = 'edit';
            }

            editingModule = $(e.target).closest('li');
            tb_show('', $(e.target).closest('a').attr('href'));
        }.bind(this));
    };

    return new Module();

})(jQuery);
