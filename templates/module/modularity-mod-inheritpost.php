<?php
    $inherit = get_field('page', $module->ID);
    if ($inherit->post_status == 'publish') :
?>
<article>
    <h2><?php echo apply_filters('the_title', $inherit->post_title); ?></h2>
    <?php echo apply_filters('the_content', $inherit->post_content); ?>
</article>
<?php endif; ?>
