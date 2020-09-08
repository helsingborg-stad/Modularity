import Module from './editor/module.js';
import Autosave from './editor/autosave.js';
import Modal from './prompt/modal.js';
import Widget from './helpers/widget.js';
import Thickbox from './editor/thickbox.js';

export default (function ($) {

    /**
     * Add new post callback
     */
    if (Thickbox.getPostAction() == 'add' && window.modularity_post_action == '') {
        Thickbox.modulePostCreated(window.modularity_post_id);
    }
    
    if (Thickbox.getPostAction() == 'edit-inline-saved') {
        parent.location.reload();
    }
    
    /**
     * Edit post callback
     */
    if (Thickbox.getPostAction() == 'edit' && window.modularity_post_action == '') {
        jQuery(document).on('click', '#publish', function (e) {
            Thickbox.setPostAction('add');
        });
    }
    
    /**
     * Edit post callback
     */
    if (Thickbox.getPostAction() == 'edit-inline-not-saved') {
        jQuery(document).on('click', '#publish', function (e) {
            Thickbox.setPostAction('edit-inline-saved');
        });
    }
    
    /**
     * Import post modifications and callback
     */
    if (Thickbox.getPostAction() == 'import') {
        $('.check-column input[type="checkbox"]').remove();
        $('.wp-list-table').addClass('modularity-wp-list-table');
        $('tbody .check-column').addClass('modularity-import-column').append('<button class="button modularity-import-button" data-modularity-action="import">Import</button>');
        $('#posts-filter').append('<input type="hidden" name="is_thickbox" value="true">');
        
        $(document).on('click', '[data-modularity-action="import"]', function (e) {
            e.preventDefault();
            
            var postId = $(e.target).closest('tr').attr('id');
            postId = postId.split('-')[1];
            
            var module = Module.isEditingModule();
            
            var request = {
                action: 'get_post',
                id: postId
            };
            
            $('body').addClass('modularity-loader-takeover');
            
            $.post(ajaxurl, request, function (response) {
                var data = {
                    post_id: response.ID,
                    title: response.post_title
                };
                
                Module.updateModule(module, data);
                Autosave.save('form');
                Modal.close();
            }, 'json');
        });
    }
    
    /**
     * Import post modifications and callback
     */
    if (Thickbox.getPostAction() == 'import-widget') {
        $('.check-column input[type="checkbox"]').remove();
        $('.wp-list-table').addClass('modularity-wp-list-table');
        $('tbody .check-column').addClass('modularity-import-column').append('<button class="button modularity-import-button" data-modularity-action="import">Import</button>');
        
        $(document).on('click', '[data-modularity-action="import"]', function (e) {
            e.preventDefault();
            
            var postId = $(e.target).closest('tr').attr('id');
            postId = postId.split('-')[1];
            
            var widget = Widget.isEditingWidget();
            
            var request = {
                action: 'get_post',
                id: postId
            };
            
            $('body').addClass('modularity-loader-takeover');
            
            $.post(ajaxurl, request, function (response) {
                var data = {
                    post_id: response.ID,
                    title: response.post_title
                };
                
                Widget.updateWidget(widget, data);
                Modal.close();
                
            }, 'json');
        });
    }
    
})(jQuery);



