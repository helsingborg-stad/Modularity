Modularity = Modularity || {};
Modularity.Editor = Modularity.Editor || {};

Modularity.Editor.Module = (function ($) {

    /**
     * Object to create Thickbox querystring from
     * @type {Object}
     */
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
            this.loadModules(modularity_post_id);
        }.bind(this));
    }

    /**
     * Loads saved modules and adds them to the page
     * @param  {integer} postId The post id to load modules from
     * @return {void}
     */
    Module.prototype.loadModules = function (postId) {
        var request = {
            action: 'get_post_modules',
            id: postId
        };

        $.post(ajaxurl, request, function (response) {
            $.each(response, function (sidebar, modules) {
                var sidebarElement = $('.modularity-sidebar-area[data-area-id="' + sidebar + '"]');

                $.each(modules, function (key, data) {
                    this.addModule(sidebarElement, data.post_type, data.post_type_name, data.post_title, data.ID);
                }.bind(this));

            }.bind(this));
        }.bind(this), 'json');
    };

    /**
     * Check editing module
     * @return {boolean/string}
     */
    Module.prototype.isEditingModule = function () {
        return editingModule;
    };

    /**
     * Generates a thickbox url to open a thickbox in correct mode
     * @param  {string} action Should be "add" or "edit"
     * @param  {object} data   Should contain additional data (for now supports "postId" and "postType")
     * @return {string}        Thickbox url
     */
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
    Module.prototype.addModule = function (target, moduleId, moduleName, moduleTitle, postId) {
        moduleTitle = (typeof moduleTitle != 'undefined') ? ': ' + moduleTitle : '';
        postId = (typeof postId != 'undefined') ? postId : '';

        var thickboxUrl = this.getThickBoxUrl('add', {
            postType: moduleId
        });

        Modularity.Editor.Thickbox.postAction = 'add';

        if (postId) {
            thickboxUrl = this.getThickBoxUrl('edit', {
                postId: postId
            });

            Modularity.Editor.Thickbox.postAction = 'edit';
        }

        var sidebarId = $(target).data('area-id');

        $(target).append('\
            <li data-module-id="' + moduleId + '">\
                <span class="modularity-sortable-handle"></span>\
                <span class="modularity-module-name">\
                        ' + moduleName + '\
                        <span class="modularity-module-title">' + moduleTitle + '</span>\
                </span>\
                <span class="modularity-module-actions">\
                    <a href="' + thickboxUrl + '" class="modularity-js-thickbox-open">Edit</a>\
                    <a href="#" class="modularity-js-thickbox-open">Import</a>\
                </span>\
                <span class="modularity-module-remove"><button data-action="modularity-module-remove"></button></span>\
                <input type="hidden" name="modularity_modules[' + sidebarId + '][]" class="modularity-js-module-id" value="' + postId + '">\
            </li>\
        ');

        $('.modularity-js-sortable').sortable('refresh');
    };

    /**
     * Updates a module "row" after editing the module post
     * @param  {DOM} module    Module dom element
     * @param  {object} data   The data
     * @return {void}
     */
    Module.prototype.updateModule = function (module, data) {
        // Href
        module.find('a.modularity-js-thickbox-open').attr('href', this.getThickBoxUrl('edit', {
            postId: data.post_id
        }));

        // Post id input
        module.find('input.modularity-js-module-id').val(data.post_id);

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
