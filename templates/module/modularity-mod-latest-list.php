<div class="box box-panel">
    <h4 class="box-title"><?php echo $module->post_title; ?></h4>
    <ul>
        <?php
        if (count($posts) > 0) :
        foreach ($posts as $post) :
        ?>
            <li>
                <a href="<?php echo get_permalink($post->ID); ?>">
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
