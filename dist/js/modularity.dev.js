var Modularity = Modularity || {};

(function($){
    $('input[type="checkbox"].sidebar-area-activator').on('click', function (e) {
        var isChecked = $(this).is(':checked');
        var value = $(this).attr('value');

        if (e.shiftKey) {
            if (!isChecked) {
                $('input.sidebar-area-activator[type="checkbox"][value="' + value + '"]').attr('checked', false);
            } else {
                $('input.sidebar-area-activator[type="checkbox"][value="' + value + '"]').attr('checked', true);
            }
        }
    });
})(jQuery);

(function($){

    // Show spinner when clicking save on Modularity options page
    $('#modularity-options #publish').on('click', function () {
        $(this).siblings('.spinner').css('visibility', 'visible');
    });

    // Remove the first menu item in the Modularity submenu (admin menu)
    $('a.wp-first-item[href="admin.php?page=modularity"]').parent('li').remove();

    // Trigger autosave when switching tabs
    $('#modularity-tabs a').on('click', function (e) {
        if (wp.autosave) {
            $(window).unbind();

            wp.autosave.server.triggerSave();

            $(document).ajaxComplete(function () {
                return true;
            });
        }

        return true;
    });

})(jQuery);


/* Auto scrolling content */
jQuery(document).ready(function ($) {
    if ($('#modularity-mb-modules').length) {
        var offset = $('#modularity-mb-modules').offset();
    	$(document).scroll(function(){
            if ($(window).scrollTop()+50 > offset.top && !$('#modularity-mb-modules').hasClass('is-fixed')) {
                $('#modularity-mb-modules').addClass('is-fixed');
            } else if ($(window).scrollTop()+50 < offset.top && $('#modularity-mb-modules').hasClass('is-fixed')) {
                $('#modularity-mb-modules').removeClass('is-fixed');
            }
    	});
    }
});

Modularity = Modularity || {};
Modularity.Editor = Modularity.Editor || {};

Modularity.Editor.Autosave = (function ($) {

    function Autosave() {
        $(function(){
            //this.handleEvents();
        }.bind(this));
    }

    Autosave.prototype.save = function (selector) {
        $('#modularity-options #publishing-action .spinner').text(modularityAdminLanguage.isSaving);
        var request = $(selector).serializeObject();
        request.id = modularity_post_id;
        request.action = 'save_modules';

        $.post(ajaxurl, request, function (response) {
            $('#modularity-options #publishing-action .spinner').text('');
        });
    };

    return new Autosave();

})(jQuery);

Modularity = Modularity || {};
Modularity.Editor = Modularity.Editor || {};

Modularity.Editor.DragAndDrop = (function ($) {

    var sortableIn;

    function DragAndDrop() {
        $(function(){

            if (pagenow == 'admin_page_modularity-editor') {
                this.init();
            }

        }.bind(this));
    }

    /**
     * Initialize
     * @return {void}
     */
    DragAndDrop.prototype.init = function () {
        this.setupDraggable();
        this.setupDroppable();
        this.setupSortable();
    };

    DragAndDrop.prototype.setupSortable = function () {
        $('.modularity-js-sortable').sortable({
            handle: '.modularity-sortable-handle',
            connectWith: '.modularity-js-sortable',
            placeholder: 'ui-sortable-placeholder',
            stop: function (e, ui) {
                var sidebarId = ui.item.parents('ul').data('area-id');
                ui.item.find('input[name^="modularity_modules"]').each(function (index, element) {
                    var newName = $(this).attr('name').replace(/\[(.*?)\]/i, '[' + sidebarId + ']');
                    $(this).attr('name', newName);
                });
            }
        }).bind(this);
    };

    /**
     * Setup draggable functionallity
     * @return {void}
     */
    DragAndDrop.prototype.setupDraggable = function () {
        $('.modularity-js-draggable').draggable({
            appendTo: 'body',
            containment: 'window',
            scroll: false,
            helper: 'clone',
            revert: 'invalid',
            revertDuration: 200
        });
    };

    /**
     * Setup droppable functionallity
     * @return {void}
     */
    DragAndDrop.prototype.setupDroppable = function () {
        $('.modularity-js-droppable').droppable({
            accept: '.modularity-js-draggable',
            hoverClass: 'modularity-state-droppable',
            drop: function (e, ui) {
                this.appendModule(e, ui);
            }.bind(this)
        }).bind(this);
    };

    /**
     * Appends a module to the target when dropped
     * @param  {object} e  Event
     * @param  {object} ui UI
     * @return {void}
     */
    DragAndDrop.prototype.appendModule = function (e, ui) {
        var module = ui.draggable;
        var moduleName = module.find('.modularity-module-name').text();
        var moduleId = module.data('module-id');

        Modularity.Editor.Module.addModule(e.target, moduleId, moduleName);

        $('.modularity-js-sortable').sortable('refresh');
    };

    return new DragAndDrop();

})(jQuery);

Modularity = Modularity || {};
Modularity.Editor = Modularity.Editor || {};

Modularity.Editor.Module = (function ($) {

    var initCompleted = false;

    /**
     * Object to create Thickbox querystring from
     * @type {Object}
     */
    var thickboxOptions = {
        is_thickbox: true,
        //TB_iframe: true,
        //width: 980,
        //height: 600
    };

    var editingModule = false;

    function Module() {
        $(function(){
            if (pagenow == 'admin_page_modularity-editor') {
                this.handleEvents();
                this.loadModules(modularity_post_id);
            }
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

                $.each(modules.modules, function (key, data) {
                    this.addModule(sidebarElement, data.post_type, data.post_type_name, data.post_title, data.ID, data.hidden, data.columnWidth);
                }.bind(this));

            }.bind(this));

            this.initCompleted = true;


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

    Module.prototype.getImportUrl = function (data) {
        var base = 'edit.php';
        var querystring = {};

        querystring.post_type = data.postType;

        return admin_url + base + '?' + $.param(querystring) + '&' + $.param(thickboxOptions);
    };

    /**
     * Adds a module "row" to the target placeholder
     * @param {selector} target   The target selector
     * @param {string} moduleId   The module id slug
     * @param {string} moduleName The module name
     */
    Module.prototype.addModule = function (target, moduleId, moduleName, moduleTitle, postId, hidden, columnWidth) {
        moduleTitle = (typeof moduleTitle != 'undefined') ? ': ' + moduleTitle : '';
        postId = (typeof postId != 'undefined') ? postId : '';
        columnWidth = (typeof columnWidth != 'undefined') ? columnWidth : '';

        // Get thickbox url
        var thickboxUrl = this.getThickBoxUrl('add', {
            postType: moduleId
        });

        // Set thickbox action
        Modularity.Editor.Thickbox.postAction = 'add';

        if (postId) {
            thickboxUrl = this.getThickBoxUrl('edit', {
                postId: postId
            });

            Modularity.Editor.Thickbox.postAction = 'edit';
        }

        // Get import url
        var importUrl = this.getImportUrl({
            postType: moduleId
        });

        // Check/uncheck hidden checkbox
        var isHidden = '';
        if (hidden == 'true') {
            isHidden = 'checked';
        }

        var sidebarId = $(target).data('area-id');
        var itemRowId = Modularity.Helpers.uuid();

        $(target).append('\
            <li id="post-' + postId + '" data-module-id="' + moduleId + '">\
            	<span class="modularity-line-wrapper">\
                	<span class="modularity-sortable-handle"></span>\
	                <span class="modularity-module-name">\
                        ' + moduleName + '\
                        <span class="modularity-module-title">' + moduleTitle + '</span>\
                        <label class="modularity-module-hide">\
                            <input type="hidden" name="modularity_modules[' + sidebarId + '][' + itemRowId + '][hidden]" value="false" />\
                            <input type="checkbox" name="modularity_modules[' + sidebarId + '][' + itemRowId + '][hidden]" value="true" ' + isHidden + ' />\
                            ' + modularityAdminLanguage.langhide + '\
                        </label>\
	                </span>\
                    <span class="modularity-module-columns">\
                        <label>' + modularityAdminLanguage.width + ':</label>\
                        <select name="modularity_modules[' + sidebarId + '][' + itemRowId + '][columnWidth]">\
                            ' + modularityAdminLanguage.widthOptions + '\
                        </select>\
                    </span>\
	                <span class="modularity-module-actions">\
	                    <a href="' + thickboxUrl + '" data-modularity-modal class="modularity-js-thickbox-open"><span>' + modularityAdminLanguage.langedit + '</span></a>\
	                    <a href="' + importUrl + '" class="modularity-js-thickbox-import"><span>' + modularityAdminLanguage.langimport + '</span></a>\
	                    <a href="#remove" class="modularity-module-remove"><span>' + modularityAdminLanguage.langremove + '</span></a>\
	                </span>\
	                <input type="hidden" name="modularity_modules[' + sidebarId + '][' + itemRowId + '][postid]" class="modularity-js-module-id" value="' + postId + '" required>\
                </span>\
            </li>\
        ');

        $(target).find('#post-' + postId + ' .modularity-module-columns option[value="' + columnWidth + '"]').prop('selected', true);

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
        module.find('input.modularity-js-module-id').val(data.post_id).change();

        // Post title
        module.find('.modularity-module-title').text(': ' + data.title);
    };

    /**
     * Removes a module "row" from the placeholder
     * @param  {DOM Element} module The (to be removed) module's dom element
     * @return {void}
     */
    Module.prototype.removeModule = function (module) {
        if (confirm(modularityAdminLanguage.actionRemove)) {
            module.remove();
        }
    };

    /**
     * Handle events
     * @return {void}
     */
    Module.prototype.handleEvents = function () {

        // Trash icon
        $(document).on('click', '.modularity-module-remove', function (e) {
            e.preventDefault();
            var target = $(e.target).closest('li');
            this.removeModule(target);
        }.bind(this));

        //Import
        $(document).on('click', '.modularity-js-thickbox-import', function (e) {
            e.preventDefault();

            var el = $(e.target).closest('a');
            editingModule = $(e.target).closest('li');

            Modularity.Editor.Thickbox.postAction = 'import';
            Modularity.Prompt.Modal.open($(e.target).closest('a').attr('href'));
        });

        // Edit
        $(document).on('click', '.modularity-js-thickbox-open', function (e) {
            e.preventDefault();

            var el = $(e.target).closest('a');
            if (el.attr('href').indexOf('post.php') > -1) {
                Modularity.Editor.Thickbox.postAction = 'edit';
            }

            editingModule = $(e.target).closest('li');

            Modularity.Prompt.Modal.open($(e.target).closest('a').attr('href'));
        }.bind(this));
    };

    return new Module();

})(jQuery);

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
            Modularity.Editor.Autosave.save('form');
        }, 'json');
    };

    return new Thickbox();

})(jQuery);

Modularity = Modularity || {};
Modularity.Editor = Modularity.Editor || {};

Modularity.Editor.Validate = (function ($) {

    var hasErrors = false;
    var errorClass = 'validation-error';

    function Validate() {
        $(function(){
            this.handleEvents();
        }.bind(this));
    }

    /**
     * Run validation methods
     * @return {void}
     */
    Validate.prototype.run = function () {
        hasErrors = false;
        this.checkRequired();
    };

    /**
     * Check required fileds is not empty
     * @return void
     */
    Validate.prototype.checkRequired = function () {
        var required = $('[required]');
        required.removeClass(errorClass);

        required.each(function (index, element) {
            if ($(element).val().length === 0) {
                $(element).parents('li').addClass(errorClass);

                $(element).one('change', function (e) {
                    $(e.target).parents('li').removeClass(errorClass);
                });

                hasErrors = true;
            }
        }.bind(this));
    };

    /**
     * Handle events
     * @return void
     */
    Validate.prototype.handleEvents = function () {
        $(document).on('click', '#modularity-mb-editor-publish #publish', function (e) {
            this.run();

            if (hasErrors) {
                $('#modularity-mb-editor-publish .spinner').css('visibility', 'hidden');
                return false;
            }

            return true;
        }.bind(this));
    };

    return new Validate();

})(jQuery);

Modularity = Modularity || {};
Modularity.Helpers = Modularity.Helpers || {};

Modularity.Helpers = (function ($) {

    function Helpers() {
        $(function(){
        }.bind(this));
    }

    Helpers.prototype.uuid = function (separator) {
        return Math.random().toString(36).substr(2, 9);
    };

    return new Helpers();

})(jQuery);

jQuery.fn.serializeObject = function()
{
    var o = {};
    var a = this.serializeArray();
    jQuery.each(a, function() {
        if (o[this.name] !== undefined) {
            if (!o[this.name].push) {
                o[this.name] = [o[this.name]];
            }
            o[this.name].push(this.value || '');
        } else {
            o[this.name] = this.value || '';
        }
    });
    return o;
};

Modularity = Modularity || {};
Modularity.Prompt = Modularity.Prompt || {};

Modularity.Prompt.Modal = (function ($) {

    var isOpen = false;

    function Modal() {
        $(function(){
            this.handleEvents();
        }.bind(this));
    }

    Modal.prototype.open = function (url) {
        $('body').addClass('modularity-modal-open').append('\
            <div id="modularity-modal">\
                <div class="modularity-modal-wrapper">\
                    <button class="modularity-modal-close" data-modularity-modal-action="close">&times; ' + modularityAdminLanguage.close + '</button>\
                    <iframe class="modularity-modal-iframe" src="' + url + '" frameborder="0" allowtransparency></iframe>\
                </div>\
            </div>\
        ');

        isOpen = true;
    };

    Modal.prototype.close = function () {
        $('body').removeClass('modularity-modal-open');
        $('#modularity-modal').remove();
        isOpen = false;
    };

    Modal.prototype.handleEvents = function () {
        $(document).on('click', '[data-modularity-modal-action="close"]', function (e) {
            e.preventDefault();
            this.close();
        }.bind(this));
    };

    return new Modal();

})(jQuery);
