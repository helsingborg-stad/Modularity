<div class="<?php echo implode(' ', apply_filters('Modularity/Module/Classes', array('box', 'box-panel'), $module->post_type, $args)); ?>">
    <h4 class="box-title"><?php echo $module->post_title; ?></h4>
    <ul>
        <?php
        if (count($posts) > 0) :
        foreach ($posts as $post) :

            //Make sorted by data avabile
            $meta_data = get_post_meta($post->ID, $fields->meta_key_output, true);
        ?>
            <li data-meta-sort-by="<?php echo $meta_data; ?>">
                <a href="<?php echo get_permalink($post->ID); ?>" data-meta-sort-by="<?php echo $meta_data; ?>">
                    <?php if ($fields->show_title) : ?>
                        <span class="link-item title"><?php echo apply_filters('the_title', $post->post_title); ?></span>
                    <?php endif; ?>

                    <?php if ($fields->show_date) : ?>
                    <time class="date text-sm text-dark-gray"><?php echo get_the_time('Y-m-d', $post->ID); ?></time>
                    <?php endif; ?>
                </a>
            </li>
        <?php endforeach; else : ?>
        <li>Inga inlägg att visa…</li>
        <?php endif; ?>
    </ul>
</div>
