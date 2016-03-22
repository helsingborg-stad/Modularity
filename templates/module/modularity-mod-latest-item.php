<div class="grid">
    <?php
    if (count($posts) > 0) :
    foreach ($posts as $post) :

        $image = wp_get_attachment_image_src(
            get_post_thumbnail_id($post->ID),
            apply_filters('modularity/image/latest/box',
                array(400, 300),
                $args
            )
        );

    ?>
    <div class="<?php echo (isset($fields->item_column_size) && !empty($fields->item_column_size)) ? $fields->item_column_size : 'grid-md-3' ?>">
        <a href="<?php echo get_permalink($post->ID); ?>" class="box box-news">
            <?php if ($image && $fields->show_picture) : ?>
            <img src="<?php echo $image[0]; ?>" alt="<?php echo $post->post_title; ?>">
            <?php endif; ?>
            <div class="box-content">
                <?php if ($fields->show_title) : ?>
                <h5 class="link-item link-item-light"><?php echo apply_filters('the_title', $post->post_title); ?></h5>
                <?php endif; ?>

                <?php if ($fields->show_date) : ?>
                <p><time><?php echo get_the_time('Y-m-d H:i', $post->ID); ?></time></p>
                <?php endif; ?>

                <?php if ($fields->show_excerpt) : ?>
                <p><?php echo isset(get_extended($post->post_content)['main']) ? apply_filters('the_excerpt', wp_trim_words(wp_strip_all_tags(get_extended($post->post_content)['main']), 55, null)) : ''; ?></p>
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
