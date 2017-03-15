<?php
    $inherit = get_field('page', $module->ID);
    if ($inherit->post_status == 'publish') :
?>
<article>
    <?php if (!$module->hideTitle && !empty($module->post_title)) : ?>
        <h1><?php echo $module->post_title; ?></h1>
    <?php endif; ?>

    <h2><?php echo apply_filters('the_title', $inherit->post_title); ?></h2>
    <?php echo apply_filters('the_content', $inherit->post_content); ?>
</article>
<?php endif; ?>
