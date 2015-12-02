Modularity = Modularity || {};
Modularity.Editor = Modularity.Editor || {};

Modularity.Editor.Module = (function ($) {

    function Module() {
        $(function(){
            this.handleEvents();
        }.bind(this));
    }

    /**
     * Adds a module "row" to the target placeholder
     * @param {selector} target   The target selector
     * @param {string} moduleId   The module id slug
     * @param {string} moduleName The module name
     */
    Module.prototype.addModule = function (target, moduleId, moduleName) {
        postId = (typeof postId != 'undefined') ? postId : '';

        $(target).append('\
            <li data-module-id="' + moduleId + '">\
                <span class="modularity-sortable-handle"></span>\
                <span class="modularity-module-name">' + moduleName + '</span>\
                <span class="modularity-module-remove"><button data-action="modularity-module-remove"></button></span>\
                <input type="hidden" name="modularity_post_id[]" value="' + postId + '">\
            </li>\
        ');

        $('.modularity-js-sortable').sortable('refresh');
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
        $(document).on('click', '[data-action="modularity-module-remove"]', function (e) {
            e.preventDefault();

            var target = $(e.target).closest('li');
            this.removeModule(target);
        }.bind(this));
    };

    return new Module();

})(jQuery);
