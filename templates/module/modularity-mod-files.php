<?php

 if (!empty(get_field('file_list', $module->ID))) :
        $files = get_field('file_list', $module->ID);
        $columns = get_field('columns', $module->ID);
?>

<div class="<?php echo implode(' ', apply_filters('Modularity/Module/Classes', array('box', 'box-panel'), $module->post_type, $args)); ?>">
    <?php if (!$module->hideTitle && !empty($module->post_title)) { ?>
        <h4 class="box-title"><?php echo apply_filters('the_title', $module->post_title); ?></h4>
    <?php } ?>

    <table class="table table-bordered">
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

            <?php if (is_array($columns ) && !empty($columns)){ ?>
                <?php foreach ($columns as $column) : ?>
                    <td>
                        <?php echo isset($columnFields[$column['key']]) && !empty($columnFields[$column['key']]) ? $columnFields[$column['key']] : ''; ?>
                    </td>
                <?php endforeach; ?>
            <?php } ?>
        </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
</div>

<?php else :
    $files = get_field('files', $module->ID);
?>

<div class="<?php echo implode(' ', apply_filters('Modularity/Module/Classes', array('box', 'box-panel'), $module->post_type, $args)); ?>">
    <?php if (!$module->hideTitle && !empty($module->post_title)) { ?>
        <h4 class="box-title"><?php echo apply_filters('the_title', $module->post_title); ?></h4>
    <?php } ?>

    <ul class="files">
        <?php if (is_array($files) && !empty($files)) { ?>
            <?php foreach ($files as $file) : ?>
                <li>
                    <a target="_blank" class="link-item" href="<?php echo $file['url']; ?>" title="<?php echo $file['title']; ?>">
                        <?php echo $file['title']; ?>
                        (<?php echo pathinfo($file['url'], PATHINFO_EXTENSION); ?>, <?php echo size_format(filesize(get_attached_file($file['ID'])), 2); ?>)
                    </a>

                    <?php if (isset($file['description']) && !empty($file['description'])) : ?>
                        <?php echo wpautop($file['description']); ?>
                    <?php endif; ?>
                </li>
            <?php endforeach; ?>
        <?php } ?>
    </ul>
</div>
<?php endif; ?>
