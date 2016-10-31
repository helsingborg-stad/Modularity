<div class="grid" data-equal-container>
    <?php if (!$module->hideTitle && !empty($module->post_title)) : ?>
        <div class="grid-xs-12">
            <h2><?php echo $module->post_title; ?></h2>
        </div>
    <?php endif; ?>

    <?php
    foreach ($posts as $post) :

        /* Image size */
        switch ($fields->posts_columns) {
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
        $image = null;
        if ($fields->posts_data_source !== 'input') {
            $image = wp_get_attachment_image_src(
                get_post_thumbnail_id($post->ID),
                apply_filters('modularity/image/latest/box',
                    municipio_to_aspect_ratio('16:9', $image_dimensions),
                    $args
                )
            );
        } else {
            if ($post->image) {
                $image = wp_get_attachment_image_src(
                    $post->image->ID,
                    apply_filters('modularity/image/latest/box',
                        municipio_to_aspect_ratio('16:9', $image_dimensions),
                        $args
                    )
                );
            }
        }
    ?>
    <div class="<?php echo $fields->posts_columns; ?>">
        <a href="<?php echo $fields->posts_data_source === 'input' ? $post->permalink : get_permalink($post->ID); ?>" class="<?php echo implode(' ', apply_filters('Modularity/Module/Classes', array('box', 'box-index'), $module->post_type, $args)); ?>" data-equal-item>
            <?php if ($image && in_array('image', $fields->posts_fields)) : ?>
                <img class="box-image" src="<?php echo $image[0]; ?>" alt="<?php echo $post->post_title; ?>" class="box-image">
            <?php endif; ?>

            <div class="box-content">
                <?php if (in_array('title', $fields->posts_fields)) : ?>
                <h5 class="box-index-title link-item"><?php echo apply_filters('the_title', $post->post_title); ?></h5>
                <?php endif; ?>

                <?php if (in_array('excerpt', $fields->posts_fields)) : ?>
                <?php echo isset(get_extended($post->post_content)['main']) ? apply_filters('the_excerpt', wp_trim_words(wp_strip_all_tags(strip_shortcodes(get_extended($post->post_content)['main'])), 30, null)) : ''; ?>
                <?php endif; ?>
            </div>
        </a>
    </div>
    <?php endforeach; ?>

    <?php if (isset($fields->archive_link) && $fields->archive_link) : ?>
    <div class="grid-lg-12">
        <a class="read-more" href="<?php echo get_post_type_archive_link($fields->posts_data_post_type); echo '?' . http_build_query($filters);  ?>"><?php _e('Show more', 'modularity'); ?></a>
    </div>
    <?php endif; ?>
</div>
