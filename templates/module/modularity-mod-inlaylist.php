<?php
    global $post;
    $items = get_field('items', $module->ID);

    $class = '';

    switch ($args['id']) {
        case 'content-area':
            $class = 'box-panel-secondary';
            break;

        default:
            $class = 'box-panel-primary';
            break;
    }

?>

<div class="grid">
    <div class="grid-lg-12">
        <div class="box box-panel <?php echo $class; ?>">
            <h4 class="box-title"><?php echo $module->post_title; ?></h4>
            <ul>
                <?php foreach ($items as $item) : ?>
                    <?php if ($item['type'] == 'external') : ?>
                    <li><a class="link-item link-item-outbound" href="<?php echo $item['link_external']; ?>" target="_blank"><?php echo $item['title'] ?></a></li>
                    <?php elseif ($item['type'] == 'internal') : ?>
                    <li><a class="link-item" href="<?php echo get_permalink($item['link_internal']->ID); ?>" target="_blank"><?php echo (!empty($item['title'])) ? $item['title'] : $item['link_internal']->post_title; ?></a></li>
                    <?php endif; ?>
                <?php endforeach; ?>
            </ul>
        </div>
    </div>
</div>
