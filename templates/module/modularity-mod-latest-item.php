<div class="grid">
    <?php if (!$module->hideTitle) : ?>
        <div class="grid-xs-12">
            <h2><?php echo $module->post_title; ?></h2>
        </div>
    <?php endif; ?>

    <?php
    if (count($posts) > 0) :
    foreach ($posts as $post) :

        /* Image size */
        switch ((isset($fields->item_column_size) && !empty($fields->item_column_size)) ? $fields->item_column_size : 'grid-md-3') {
            case "grid-md-12":    //1-col
                $image_dimensions = array(1200,900);
                break;
            case "grid-md-6":    //2-col
                $image_dimensions = array(800,600);
                break;
            default:
                $image_dimensions = array(400,300);
        }

        /* Image */
        $image = wp_get_attachment_image_src(
            get_post_thumbnail_id($post->ID),
            apply_filters('modularity/image/latest/box',
                municipio_to_aspect_ratio('16:9', $image_dimensions),
                $args
            )
        );

        //Make sorted by data avabile
        if (isset($fields->meta_key_output)) {
            $meta_data = get_post_meta($post->ID, $fields->meta_key_output, true);

            //Serialize data if needed
            if (is_array($meta_data) || is_object($meta_data)) {
                $meta_data = json_encode($meta_data);
            }
        } else {
            $meta_data = "";
        }

    ?>
    <div class="<?php echo (isset($fields->item_column_size) && !empty($fields->item_column_size)) ? $fields->item_column_size : 'grid-md-3' ?>">
        <a href="<?php echo get_permalink($post->ID); ?>" class="<?php echo implode(' ', apply_filters('Modularity/Module/Classes', array('box', 'box-news'), $module->post_type, $args)); ?>" data-meta-sort-by="<?php echo $meta_data; ?>">
            <?php if ($image && $fields->show_picture) : ?>
            <img src="<?php echo $image[0]; ?>" alt="<?php echo $post->post_title; ?>">
            <?php endif; ?>
            <div class="box-content" data-meta-sort-by="<?php echo $meta_data; ?>">
                <?php if ($fields->show_title) : ?>
                <h5 class="link-item link-item-light"><?php echo apply_filters('the_title', $post->post_title); ?></h5>
                <?php endif; ?>

                <?php if ($fields->show_date) : ?>
                <p><time><?php echo get_the_time('Y-m-d H:i', $post->ID); ?></time></p>
                <?php endif; ?>

                <?php if ($fields->show_excerpt) : ?>
                <p><?php echo isset(get_extended($post->post_content)['main']) ? apply_filters('the_excerpt', wp_trim_words(wp_strip_all_tags(strip_shortcodes(get_extended($post->post_content)['main'])), 30, null)) : ''; ?></p>
                <?php endif; ?>
            </div>
        </a>
    </div>
    <?php endforeach; else : ?>
    <div class="grid-md-12">
        Inga inlägg att visa…
    </div>
    <?php endif; ?>
</div>
