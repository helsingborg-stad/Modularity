<?php global $modularityOptions; ?>
<div class="modularity-form-group">
    <label>
        <input type="checkbox" name="<?php echo $this->getFieldName('show-modules-in-menu'); ?>" value="on" <?php checked(isset($modularityOptions['show-modules-in-menu']) ? $modularityOptions['show-modules-in-menu'] : null, 'on', true); ?>>
        <?php _e('Show module\'s post type in admin menu', 'modularity'); ?>
    </label>
</div>
