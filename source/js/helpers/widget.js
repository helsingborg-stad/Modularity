Modularity = Modularity || {};
Modularity.Helpers = Modularity.Helpers || {};

Modularity.Helpers.Widget = (function ($) {

    var editingWidget = false;

    function Widget() {
        $(function(){
            $(document).on('click', '.modularity-js-thickbox-import', function (e) {
                e.preventDefault();

                editingWidget = $(e.target).parents('.widget-inside');

                var importUrl = Modularity.Editor.Module.getImportUrl({
                    postType: $(e.target).parents('.widget-inside').find('.modularity-widget-module-type select').val()
                });

                Modularity.Editor.Module.editingModule = $(e.target).closest('.widget-inside');

                Modularity.Editor.Thickbox.postAction = 'import-widget';
                Modularity.Prompt.Modal.open(importUrl);
            });
        }.bind(this));
    }

    Widget.prototype.isEditingWidget = function () {
        return editingWidget;
    };

    Widget.prototype.updateWidget = function (widget, data) {
        $(widget).find('.modularity-widget-module-id-span').html(data.post_id);
        $(widget).find('.modularity-widget-module-id').val(data.post_id);

        $(widget).find('.modularity-widget-module-title-span').html(data.title);
        $(widget).find('.modularity-widget-module-title').val(data.title);
    };

    return new Widget();

})(jQuery);
