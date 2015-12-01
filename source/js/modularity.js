
// Initialize postboxes
if (typeof postboxes !== 'undefined') {
    postboxes.add_postbox_toggles(pagenow);
}

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

    // Editor
    $('.modularity-js-draggable').draggable({
        appendTo: 'body',
        containment: 'window',
        scroll: false,
        helper: 'clone',
        revert: 'invalid',
        revertDuration: 200
    });

    $('.modularity-js-droppable').droppable({
        accept: '.modularity-js-draggable',
        hoverClass: 'modularity-state-droppable',
        drop: function(event, ui) {
            var module = ui.draggable;

            $(this).append('<div>' + module.find('.modularity-module-name').text() + '</div>');
        }
    });

})(jQuery);
