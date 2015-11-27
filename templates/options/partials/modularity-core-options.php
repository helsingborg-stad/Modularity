<?php global $options; ?>
<div class="modularity-form-group">
    <label>
        <input type="checkbox" name="<?php echo $this->getFieldName('show-modules-in-menu'); ?>" value="on" <?php checked(isset($options['show-modules-in-menu']) ? $options['show-modules-in-menu'] : null, 'on', true); ?>>
        <?php _e('Show module\'s post type in admin menu', 'modularity'); ?>
    </label>
</div>
