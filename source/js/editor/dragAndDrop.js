Modularity = Modularity || {};
Modularity.Editor = Modularity.Editor || {};

Modularity.Editor.DragAndDrop = (function ($) {

    var sortableIn;

    function DragAndDrop() {
        $(function(){

            if (typeof pagenow !== 'undefined' && pagenow == 'admin_page_modularity-editor') {
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
