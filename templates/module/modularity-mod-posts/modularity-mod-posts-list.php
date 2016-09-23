<div class="<?php echo implode(' ', apply_filters('Modularity/Module/Classes', array('box', 'box-panel'), $module->post_type, $args)); ?>">
    <?php if (!$module->hideTitle) : ?>
    <h4 class="box-title"><?php echo $module->post_title; ?></h4>
    <?php endif; ?>

    <ul>
        <?php
        if (count($posts) > 0) :
        foreach ($posts as $post) :
        ?>
            <li>
                <a href="<?php echo $fields->posts_data_source === 'input' ? $post->permalink : get_permalink($post->ID); ?>">
                    <?php if (in_array('title', $fields->posts_fields)) : ?>
                        <span class="link-item title"><?php echo apply_filters('the_title', $post->post_title); ?></span>
                    <?php endif; ?>

                    <?php if (in_array('date', $fields->posts_fields) && $fields->posts_data_source !== 'input') : ?>
                    <time class="date text-sm text-dark-gray"><?php echo get_the_time('Y-m-d', $post->ID); ?></time>
                    <?php endif; ?>
                </a>
            </li>
        <?php endforeach; else : ?>
        <li><?php _e('No posts to show…', 'modularity'); ?></li>
        <?php endif; ?>
    </ul>
</div>
