Modularity = Modularity || {};
Modularity.Editor = Modularity.Editor || {};

Modularity.Editor.DragAndDrop = (function ($) {

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
            placeholder: 'ui-sortable-placeholder'
        });
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
            drop: function (event, ui) {
                this.dropped(event, ui);
            }.bind(this)
        }).bind(this);
    };

    DragAndDrop.prototype.dropped = function (e, ui) {
        var module = ui.draggable;

        $(e.target).append('\
            <li>\
                <span class="modularity-sortable-handle"></span>\
                <span class="modularity-module-name">' + module.find('.modularity-module-name').text() + '</span>\
            </li>\
        ');

        $('.modularity-js-sortable').sortable('refresh');
    };

    return new DragAndDrop();

})(jQuery);
