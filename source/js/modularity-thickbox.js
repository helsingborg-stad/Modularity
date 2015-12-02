/**
 * Add new post callback
 */
if (parent.Modularity.Editor.Thickbox.postAction == 'add' && modularity_post_action == '') {
    parent.Modularity.Editor.Thickbox.modulePostCreated(modularity_post_id);
}
