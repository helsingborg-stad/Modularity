<div class="box box-panel">
    <?php if (!empty($module->post_title)) { ?>
        <h4 class="box-title"><?php echo apply_filters('the_title', $module->post_title); ?></h4>
    <?php } ?>
    <div class="box-content gutter">
        <?php echo apply_filters('the_content', $module->post_content); ?>
    </div>
</div>
