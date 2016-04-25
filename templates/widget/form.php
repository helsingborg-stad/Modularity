<p style="border: 1px solid #ddd; background: #f9f9f9; padding: 10px 15px;">
    <strong><?php _e('Selected module', 'modularity'); ?></strong><br>

    ID:<span class="modularity-widget-module-id-span"><?php echo isset($instance['module_id']) && !empty($instance['module_id']) ? $instance['module_id'] : '<i>' . __('Not selected', 'modularity') . '</i>'; ?></span>
    <input type="hidden" class="modularity-widget-module-id" name="<?php echo $this->get_field_name('module_id'); ?>" id="<?php echo $this->get_field_id('module_id'); ?>" value="<?php echo isset($instance['module_id']) && !empty($instance['module_id']) ? $instance['module_id'] : ''; ?>"><br>

    Module title: <span class="modularity-widget-module-title-span"><?php echo isset($instance['title']) && !empty($instance['title']) ? $instance['title'] : '<i>' . __('Not selected', 'modularity') . '</i>'; ?></span>
    <input type="hidden" class="modularity-widget-module-title" name="<?php echo $this->get_field_name('title'); ?>" id="<?php echo $this->get_field_id('title'); ?>" value="<?php echo isset($instance['title']) && !empty($instance['title']) ? $instance['title'] : ''; ?>">
</p>
<p class="modularity-widget-module-type">
    <label for="<?php echo $this->get_field_id('module_type'); ?>"><?php _e('Module type', 'modularity'); ?>:</label>
    <select class="widefat" name="<?php echo $this->get_field_name('module_type'); ?>" id="<?php echo $this->get_field_id('parent'); ?>">
        <?php foreach ($moduleTypes as $key => $type) : ?>
            <option value="<?php echo $key; ?>" <?php selected(isset($instance['module_type']) ? $instance['module_type'] : '', $key, true); ?>><?php echo $type['labels']['name']; ?></option>
        <?php endforeach; ?>
    </select>
</p>
<p class="modularity-widget-module-import">
    <a href="#" class="button modularity-js-thickbox-widget-import"><?php _e('Browse modules', 'modularity'); ?></a>
</p>
