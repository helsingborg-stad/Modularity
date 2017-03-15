<div class="box no-padding">
    <?php if (!$module->hideTitle && !empty($module->post_title)) : ?>
        <h4 class="box-title"><?php echo $module->post_title; ?></h4>
    <?php endif; ?>
    <?php echo get_post_meta($module->ID, 'embed_code', true); ?>
</div>
