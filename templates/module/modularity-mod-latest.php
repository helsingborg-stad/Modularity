<?php
    $fields = json_decode(json_encode(get_fields($module->ID)));

    $sortBy = $fields->sorted_by ? $fields->sorted_by : 'date';
    $order = $fields->order ? $fields->order : 'desc';

    $posts = get_posts(array(
        'post_type' => $fields->post_type,
        'posts_per_page' => $fields->number_of_posts,
        'orderby' => $sortBy,
        'order' => $order
    ));

    if (isset($fields->view_as) && $fields->view_as == 'list') :
?>

    <div class="box box-panel">
        <h4 class="box-title"><?php echo $module->post_title; ?></h4>
        <ul>
            <?php
            foreach ($posts as $post) :
                $image = get_post_thumbnail_id($post->ID);
                $image = wp_get_attachment_url($image);
            ?>
                <li>
                    <a class="link-item" href="<?php echo get_permalink($post->ID); ?>">
                        <?php if ($fields->show_title) : ?>
                            <?php echo apply_filters('the_title', $post->post_title); ?>
                        <?php endif; ?>

                        <?php if ($fields->show_date) : ?>
                        <time class="date pull-right text-sm text-dark-gray"><?php echo get_the_time('Y-m-d H:i', $post->ID); ?></time>
                        <?php endif; ?>
                    </a>
                </li>
            <?php endforeach; ?>
        </ul>
    </div>

<?php else : ?>

    <div class="grid">
    <?php
    foreach ($posts as $post) :
        $image = get_post_thumbnail_id($post->ID);
        $image = wp_get_attachment_url($image);
    ?>
    <div class="grid-md-3">
        <a href="<?php echo get_permalink($post->ID); ?>" class="box box-news">
            <?php if ($image && $fields->show_picture) : ?>
            <img src="<?php echo $image; ?>">
            <?php endif; ?>
            <div class="box-content">
                <?php if ($fields->show_title) : ?>
                <h5 class="link-item link-item-light"><?php echo apply_filters('the_title', $post->post_title); ?></h5>
                <?php endif; ?>

                <?php if ($fields->show_date) : ?>
                <p><time><?php echo get_the_time('Y-m-d H:i', $post->ID); ?></time></p>
                <?php endif; ?>

                <?php if ($fields->show_excerpt) : ?>
                <p><?php echo isset(get_extended($post->post_content)['main']) ? get_extended($post->post_content)['main'] : ''; ?></p>
                <?php endif; ?>
            </div>
        </a>
    </div>
    <?php endforeach; ?>
    </div>

<?php endif; ?>
