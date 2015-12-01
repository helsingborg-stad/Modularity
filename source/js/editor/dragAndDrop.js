Modularity = Modularity || {};
Modularity.Editor = Modularity.Editor || {};

Modularity.Editor.DragAndDrop = (function ($) {

    function DragAndDrop() {
        $(function(){

            this.init();

        }.bind(this));
    }

    /**
     * Initialize
     * @return {void}
     */
    DragAndDrop.prototype.init = function () {
        this.setupDraggable();
        this.setupDroppable();
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
                <span class="modularity-module-name">' + module.find('.modularity-module-name').text() + '</span>\
            </li>\
        ');
    };

    return new DragAndDrop();

})(jQuery);
