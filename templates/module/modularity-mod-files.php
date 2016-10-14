<?php $files = get_field('files', $module->ID); ?>

<div class="<?php echo implode(' ', apply_filters('Modularity/Module/Classes', array('box', 'box-panel'), $module->post_type, $args)); ?>">
    <?php if (!$module->hideTitle && !empty($module->post_title)) { ?>
        <h4 class="box-title"><?php echo apply_filters('the_title', $module->post_title); ?></h4>
    <?php } ?>

    <ul class="files">
        <?php if (is_array($files) && !empty($files)) { ?>
            <?php foreach ($files as $file) : ?>
                <?php $attachment = wp_get_attachment_metadata($file); ?>
                <li>
                    <a target="_blank" class="link-item" href="<?php echo wp_get_attachment_url($file); ?>" title="<?php echo get_the_title($file); ?>">
                        <?php echo get_the_title($file); ?>
                        (<?php echo pathinfo(wp_get_attachment_url($file), PATHINFO_EXTENSION); ?>, <?php echo size_format(filesize(get_attached_file($file)), 2); ?>)
                    </a>
                    <?php $excerpt = get_post_field('post_excerpt', $file); ?>
                    <?php if (isset($excerpt) && !empty($excerpt)) : ?>
                        <?php echo wpautop($excerpt); ?>
                    <?php endif; ?>

                    <?php $description = get_post_field('post_content', $file); ?>
                    <?php if (isset($description) && !empty($description)) : ?>
                        <?php echo wpautop($description); ?>
                    <?php endif; ?>
                </li>
            <?php endforeach; ?>
        <?php } ?>
    </ul>
</div>
