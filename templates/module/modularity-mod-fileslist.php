<?php
    $listFilterId = 'files_' . uniqid();
    $files = get_field('file_list', $module->ID);
    $columns = get_field('columns', $module->ID);
?>

<div class="<?php echo implode(' ', apply_filters('Modularity/Module/Classes', array('box', 'box-panel'), $module->post_type, $args)); ?>">
    <?php if (!$module->hideTitle && !empty($module->post_title)) { ?>
        <h4 class="box-title"><?php echo apply_filters('the_title', $module->post_title); ?></h4>
    <?php } ?>

    <table class="table table-bordered" data-table-filter="<?php echo $listFilterId; ?>">
        <?php if ($columns) : ?>
        <thead>
            <tr>
                <th><?php _e('File', 'modularity'); ?></th>
                <?php foreach ($columns as $column) : ?>
                <th><?php echo $column['title']; ?></th>
                <?php endforeach; ?>
            </tr>
        </thead>
        <?php endif; ?>

        <tbody>
            <?php if (is_null(get_field('show_filter', $module->ID)) || get_field('show_filter', $module->ID) === true) : ?>
            <tr data-table-filter-exclude>
                <td colspan="<?php echo count($columns) + 1; ?>" class="no-padding no-border">
                    <input type="text" name="keyword" class="form-control gutter" placeholder="<?php _e('Filter files', 'modularity'); ?>…" style="margin: -1px;margin-top: 0;width: calc(100% + 2px);" data-table-filter-input="<?php echo $listFilterId; ?>">
                </td>
            </tr>
            <?php endif; ?>

            <?php foreach ($files as $item) :
                $columnFields = array();
                if ($item['fields']) {
                    foreach ($item['fields'] as $columnField) {
                        $columnFields[$columnField['key']] = $columnField['value'];
                    }
                }
            ?>
            <tr>
                <td>
                    <a target="_blank" class="link-item" href="<?php echo $item['file']['url']; ?>" title="<?php echo $item['file']['title']; ?>">
                        <?php echo $item['file']['title']; ?>
                        (<?php echo pathinfo($item['file']['url'], PATHINFO_EXTENSION); ?>, <?php echo size_format(filesize(get_attached_file($item['file']['ID'])), 2); ?>)
                    </a>

                    <?php if (isset($item['file']['description']) && !empty($item['file']['description'])) : ?>
                        <?php echo wpautop($item['file']['description']); ?>
                    <?php endif; ?>
                </td>

                 <?php if (!empty($columns)) : foreach ($columns as $column) : ?>
                <td>
                    <?php echo isset($columnFields[$column['key']]) && !empty($columnFields[$column['key']]) ? $columnFields[$column['key']] : ''; ?>
                </td>
                 <?php endforeach; endif; ?>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
