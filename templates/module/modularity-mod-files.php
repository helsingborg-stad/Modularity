<?php $files = get_field('files', $module->ID); ?>

<div class="<?php echo implode(' ', apply_filters('Modularity/Module/Classes', array('box', 'box-panel'), $module->post_type, $args)); ?>">
    <?php if (!empty($module->post_title)) { ?>
        <h4 class="box-title"><?php echo apply_filters('the_title', $module->post_title); ?></h4>
    <?php } ?>

    <ul class="files">
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
    </ul>
</div>
