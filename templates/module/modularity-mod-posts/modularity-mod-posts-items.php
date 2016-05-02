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
    <div class="<?php echo $fields->posts_columns; ?>">
        <a href="<?php echo get_permalink($post->ID); ?>" class="<?php echo implode(' ', apply_filters('Modularity/Module/Classes', array('box', 'box-news'), $module->post_type, $args)); ?>">
            <?php if ($image && in_array('image', $fields->posts_fields)) : ?>
            <img src="<?php echo $image[0]; ?>" alt="<?php echo $post->post_title; ?>">
            <?php endif; ?>
            <div class="box-content">
                <?php if (in_array('title', $fields->posts_fields)) : ?>
                <h5 class="link-item link-item-light"><?php echo apply_filters('the_title', $post->post_title); ?></h5>
                <?php endif; ?>

                <?php if (in_array('date', $fields->posts_fields)) : ?>
                <p><time><?php echo get_the_time('Y-m-d H:i', $post->ID); ?></time></p>
                <?php endif; ?>

                <?php if (in_array('excerpt', $fields->posts_fields)) : ?>
                <p><?php echo isset(get_extended($post->post_content)['main']) ? apply_filters('the_excerpt', wp_trim_words(wp_strip_all_tags(get_extended($post->post_content)['main']), 30, null)) : ''; ?></p>
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
