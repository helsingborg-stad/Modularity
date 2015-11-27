<div class="modularity-table-metabox-wrapper">
    <table class="modularity-table">
        <thead>
            <th>Enabled</th>
            <th><?php _e('Module', 'modularity'); ?></th>
            <th><?php _e('Description', 'modularity'); ?></th>
        </thead>
        <tbody>
        <?php foreach ($available as $id => $module) : ?>
            <tr>
                <td class="checkbox"><input type="checkbox" name="<?php echo $this->getFieldName('enabled-modules'); ?>" value="<?php echo $id; ?>"></td>
                <td><strong><?php echo $module['labels']['name']; ?></strong></td>
                <td><?php echo $module['description']; ?></td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
</div>
