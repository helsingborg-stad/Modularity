<p>
    Enable and/or disable modules.<br>
    <span style="font-style:italic;">Note: You will not lose any data from disabling a module. However the modules will not be displayed on your site.</span>
</p>

<div class="modularity-table-metabox-wrapper">
    <table class="modularity-table">
        <thead>
            <th class="checkbox-wrapper">Enabled</th>
            <th><?php _e('Module', 'modularity'); ?></th>
            <th><?php _e('Module', 'modularity'); ?> ID</th>
            <th><?php _e('Description', 'modularity'); ?></th>
        </thead>
        <tbody>
        <?php foreach ($available as $id => $module) : ?>
            <tr>
                <td class="checkbox"><input type="checkbox" name="<?php echo $this->getFieldName('enabled-modules', true); ?>" value="<?php echo $id; ?>" <?php checked(in_array($id, $enabled) ? 'on' : null, 'on', true); ?>></td>
                <td><strong><?php echo $module['labels']['name']; ?></strong></td>
                <td><span style="font-style:italic;"><?php echo $id; ?></span></td>
                <td><?php echo $module['description']; ?></td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
</div>
