<ul
    class="modularity-sidebar-area modularity-js-droppable modularity-js-sortable"
    data-empty="<?php echo __('Drag your modules here…', 'modularity'); ?>"
    data-area-id="<?php echo $args['args']['sidebar']['id']; ?>"
></ul>

<div class="modularity-sidebar-options">
    <div class="container">
        <div class="col half">
            <?php _e('Before module', 'modularity'); ?>:
            <input type="text" name="modularity_sidebar_options[<?php echo $args['args']['sidebar']['id']; ?>][before_module]" value="<?php echo htmlentities($args['args']['sidebar']['before_widget']); ?>" class="widefat">
        </div>
        <div class="col half">
            <?php _e('After module', 'modularity'); ?>:
            <input type="text" name="modularity_sidebar_options[<?php echo $args['args']['sidebar']['id']; ?>][after_module]" value="<?php echo htmlentities($args['args']['sidebar']['after_widget']); ?>" class="widefat">
        </div>
    </div>
    <div class="container">
        <div class="col half">
            <label>
                <input type="checkbox" value="true" name="modularity_sidebar_options[<?php echo $args['args']['sidebar']['id']; ?>][hide_widgets]" <?php checked(true, isset($options['hide_widgets']), true); ?>>
                <?php _e('Hide global widgets', 'modularity'); ?>
            </label>
        </div>
        <div class="col half">
            <?php _e('Show modules', 'modularity'); ?>
            <select name="modularity_sidebar_options[<?php echo $args['args']['sidebar']['id']; ?>][hook]">
                <option value="before" <?php selected('before', isset($options['hook']) ? $options['hook'] : '', true); ?>><?php _e('before', 'modularity'); ?></option>
                <option value="after" <?php selected('after', isset($options['hook']) ? $options['hook'] : '', true); ?>><?php _e('after', 'modularity'); ?></option>
            </select>
            widgets
        </div>
    </div>
</div>
