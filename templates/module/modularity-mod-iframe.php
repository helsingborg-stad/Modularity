<?php
    $url = get_field('iframe_url', $module->ID);
    $url = str_replace(array('http://', 'https://'), '//', $url);
?>
<div class="box no-padding">
    <?php if (!$module->hideTitle) : ?>
    <h4 class="box-title"><?php echo $module->post_title; ?></h4>
    <?php endif; ?>

    <iframe src="<?php echo $url; ?>" frameborder="0" style="width: 100%; height: <?php echo get_field('iframe_height', $module->ID); ?>px;"></iframe>
</div>
