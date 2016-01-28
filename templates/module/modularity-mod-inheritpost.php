<?php $inherit = get_field('page', $module->ID); ?>
<article>
    <h2><?php echo apply_filters('the_title', $inherit->post_title); ?></h2>
    <?php echo apply_filters('the_content', $inherit->post_content); ?>
</article>
