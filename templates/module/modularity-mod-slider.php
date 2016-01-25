<?php
    $slides = get_field('slides', $module->ID);
?>
<ul class="slider">
<?php foreach ($slides as $slide) : ?>
    <li>
    <?php if ($slide['acf_fc_layout'] == 'image') : ?>

        <img src="<?php echo $slide['image']['url']; ?>">

    <?php elseif ($slide['acf_fc_layout'] == 'video' && $slide['type'] == 'embed') : ?>

        <?php echo \Modularity\Module\Slider\Slider::getEmbed($slide['embed_link']); ?>

    <?php elseif ($slide['acf_fc_layout'] == 'video' && $slide['type'] == 'upload') : ?>

        Markup for uploaded video.

    <?php endif; ?>
    </li>
<?php endforeach; ?>
</ul>
