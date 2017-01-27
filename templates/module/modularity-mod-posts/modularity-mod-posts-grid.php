<div class="grid">
    <?php if (!$module->hideTitle && !empty($module->post_title)) : ?>
        <div class="grid-xs-12">
            <h2><?php echo $module->post_title; ?></h2>
        </div>
    <?php endif; ?>

    <?php

    /*
    $gridSize = (int)str_replace('-', '', filter_var($fields->posts_columns, FILTER_SANITIZE_NUMBER_INT));
    $numColumns = 12 / $gridSize;
    $columnAlter = $numColumns > 1 ? $numColumns - 1 : $numColumns + 1;
    */

    if (count($posts) > 0) :
    $postNum = 0;
    foreach ($posts as $post) :
        $postNum++;

        /* Image size */
        $image_dimensions = array(800, 600);

        if (!$fields->posts_alter_columns) {
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
        }

        /* Image */
        $image = null;
        if ($fields->posts_data_source !== 'input') {
            $image = wp_get_attachment_image_src(
                get_post_thumbnail_id($post->ID),
                apply_filters('modularity/image/posts/items',
                    municipio_to_aspect_ratio('16:9', $image_dimensions),
                    $args
                )
            );
        } else {
            if ($post->image) {
                $image = wp_get_attachment_image_src(
                    $post->image->ID,
                    apply_filters('modularity/image/posts/items',
                        municipio_to_aspect_ratio('16:9', $image_dimensions),
                        $args
                    )
                );
            }
        }
    ?>
    <div class="<?php echo $fields->posts_columns; ?>">
        <a href="<?php echo get_permalink($post->ID); ?>" class="box box-post-brick">
            <div class="box-image" style="background-image:url(<?php echo $image[0]; ?>);">
                <img src="<?php echo $image[0]; ?>" alt="<?php echo $post->post_title; ?>">
            </div>

            <div class="box-content">
                <?php if (in_array('date', $fields->posts_fields) && $fields->posts_data_source !== 'input') : ?>
                <span class="box-post-brick-date">
                    <time>
                        <?php echo get_the_time(get_option('date_format'), $post->ID); ?>
                        <?php echo get_the_time(get_option('time_format'), $post->ID); ?>
                    </time>
                </span>
                <?php endif; ?>

                <?php if (in_array('title', $fields->posts_fields)) : ?>
                <h3 class="post-title"><?php echo apply_filters('the_title', $post->post_title); ?></h3>
                <?php endif; ?>
            </div>
            <div class="box-post-brick-lead">
                <?php echo $post->post_excerpt; ?>
            </div>
        </a>
    </div>

    <?php endforeach; else : ?>
    <div class="grid-md-12">
        <?php _e('No posts to show…', 'modularity'); ?>
    </div>
    <?php endif; ?>

    <?php if (isset($fields->archive_link) && $fields->archive_link) : ?>
    <div class="grid-lg-12">
        <a class="read-more" href="<?php echo get_post_type_archive_link($fields->posts_data_post_type); echo '?' . http_build_query($filters);  ?>"><?php _e('Show more', 'modularity'); ?></a>
    </div>
    <?php endif; ?>
</div>
