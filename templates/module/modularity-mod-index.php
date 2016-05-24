<?php
    global $post;
    $items = get_field('index', $module->ID);

    $columnClass = !empty(get_field('index_columns', $module->ID)) ? get_field('index_columns', $module->ID) : 'grid-md-6';
?>
<div class="grid" data-equal-container>
    <?php

    /* Get image size by column count */
    switch ($columnClass) {
        case "grid-md-12":    //1-col
            $image_dimensions = array(1200,900);
            break;
        case "grid-md-6":    //2-col
            $image_dimensions = array(800,600);
            break;
        default:
            $image_dimensions = array(400,300);
    }

    /* Get image */
    foreach ($items as $item) : $post = $item['page'];
        if (!is_null($item['page'])) {
            setup_postdata($post);
        }

        $permalink = ($item['link_type'] == 'internal') ? get_permalink() : $item['link_url'];

        $thumbnail_image = wp_get_attachment_image_src(
            get_post_thumbnail_id($item['page']->ID),
            apply_filters('Modularity/index/image',
                $image_dimensions,
                $args
            )
        );
    ?>
    <div class="<?php echo $columnClass; ?>">
        <a href="<?php echo $permalink; ?>" class="<?php echo implode(' ', apply_filters('Modularity/Module/Classes', array('box', 'box-index'), $module->post_type, $args)); ?>" data-equal-item>
            <?php if ($item['image_display'] == 'featured' && $thumbnail_image) : ?>
                <img class="box-image" src="<?php echo $thumbnail_image[0]; ?>" alt="<?php echo isset($item['title']) && !empty($item['title']) ? $item['title'] : get_the_title(); ?>">
            <?php
            elseif (($item['link_type'] == 'external' || $item['image_display'] == 'custom') && !empty($item['custom_image'])) :
            ?>
                <img class="box-image" src="<?php echo $item['custom_image']['url']; ?>" alt="<?php echo (!empty($item['custom_image']['alt'])) ? $item['custom_image']['alt'] : $item['title']; ?>">
            <?php endif; ?>

            <div class="box-content">
                <h5 class="box-index-title link-item"><?php echo isset($item['title']) && !empty($item['title']) ? $item['title'] : get_the_title(); ?></h5>
                <?php echo isset($item['lead']) && !empty($item['lead']) ? $item['lead'] : get_the_excerpt(); ?>
            </div>
        </a>
    </div>
    <?php endforeach; ?>
</div>

<?php wp_reset_postdata(); ?>
