<?php if (get_field('hide_box_frame', $module->ID)) : ?>

<article class="no-margin full <?php echo get_field('font_size', $module->ID) ? get_field('font_size', $module->ID) : ''; ?>">
    <?php if (!$module->hideTitle && !empty($module->post_title)) : ?>
        <h1><?php echo apply_filters('the_title', $module->post_title); ?></h1>
    <?php endif; ?>

    <?php echo apply_filters('the_content', $module->post_content); ?>
</article>

<?php else : ?>

<div class="<?php echo implode(' ', apply_filters('Modularity/Module/Classes', array('box', 'box-panel'), $module->post_type, $args)); ?> <?php echo get_field('font_size', $module->ID) ? get_field('font_size', $module->ID) : ''; ?>">
    <?php if (!$module->hideTitle && !empty($module->post_title)) : ?>
        <h4 class="box-title"><?php echo apply_filters('the_title', $module->post_title); ?></h4>
    <?php endif; ?>
    <div class="box-content">
        <?php echo apply_filters('the_content', $module->post_content); ?>
    </div>
</div>

<?php endif; ?>
