<?php $files = get_field('files', $module->ID); ?>

<div class="<?php echo implode(' ', apply_filters('Modularity/Module/Classes', array('box', 'box-panel'), $module->post_type, $args)); ?>">
    <?php if (!empty($module->post_title)) { ?>
        <h4 class="box-title"><?php echo apply_filters('the_title', $module->post_title); ?></h4>
    <?php } ?>

    <ul class="files">
        <?php foreach ($files as $file) : $file = $file['file']; ?>
            <li><a class="link-item" href="<?php echo $file['url']; ?>" title="<?php echo $file['title']; ?>">
                <?php echo $file['filename']; ?>
                (<?php echo size_format(filesize(get_attached_file($file['ID'])), 2); ?>)
            </a></li>
        <?php endforeach; ?>
    </ul>
</div>
