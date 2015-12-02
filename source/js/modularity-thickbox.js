(function($) {
    /**
     * Add new post callback
     */
    if (parent.Modularity.Editor.Thickbox.postAction == 'add' && modularity_post_action == '') {
        parent.Modularity.Editor.Thickbox.modulePostCreated(modularity_post_id);
    }

    if (parent.Modularity.Editor.Thickbox.postAction == 'edit' && modularity_post_action == '') {
        jQuery(document).on('click', '#publish', function (e) {
            parent.Modularity.Editor.Thickbox.postAction = 'add';
        });
    }

})(jQuery)
