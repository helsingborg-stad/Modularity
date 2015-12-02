Modularity = Modularity || {};
Modularity.Editor = Modularity.Editor || {};

Modularity.Editor.Module = (function ($) {

    function Module() {
        $(function(){
            this.handleEvents();
        }.bind(this));
    }

    Module.prototype.addModule = function (target, moduleName) {
        $(target).append('\
            <li>\
                <span class="modularity-sortable-handle"></span>\
                <span class="modularity-module-name">' + moduleName + '</span>\
                <span class="modularity-module-remove"><button data-action="modularity-module-remove"></button></span>\
            </li>\
        ');

        $('.modularity-js-sortable').sortable('refresh');
    };

    Module.prototype.removeModule = function (target) {
        if (confirm('Are you sure you want to remove this module?')) {
            target.remove();
        }
    };

    Module.prototype.handleEvents = function () {
        $(document).on('click', '[data-action="modularity-module-remove"]', function (e) {
            e.preventDefault();

            var target = $(e.target).closest('li');
            this.removeModule(target);
        }.bind(this));
    };

    return new Module();

})(jQuery);
