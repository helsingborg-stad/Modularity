<?php
    global $post;
    $items = get_field('items', $module->ID);
?>

<div class="<?php echo implode(' ', apply_filters('Modularity/Module/Classes', array('box', 'box-panel'), $module->post_type, $args)); ?>">
    <?php if (!$module->hideTitle) : ?>
    <h4 class="box-title"><?php echo $module->post_title; ?></h4>
    <?php endif; ?>

    <ul>
        <?php foreach ($items as $item) : ?>
            <?php if ($item['type'] == 'external') : ?>
            <li>
                <a href="<?php echo $item['link_external']; ?>">
                    <span class="link-item link-item-outbound title"><?php echo $item['title'] ?></span>
                </a>
            </li>
            <?php elseif ($item['type'] == 'internal') : ?>
            <li>
                <a href="<?php echo get_permalink($item['link_internal']->ID); ?>">
                    <span class="link-item title"><?php echo (!empty($item['title'])) ? $item['title'] : $item['link_internal']->post_title; ?></span>
                    <?php if ($item['date'] === true) : ?>
                    <time class="date text-sm text-dark-gray"><?php echo date('Y-m-d', strtotime($item['link_internal']->post_date)); ?></time>
                    <?php endif; ?>
                </a>
            </li>
            <?php endif; ?>
        <?php endforeach; ?>
    </ul>
</div>
