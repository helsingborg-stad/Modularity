<?php
    $slides = get_field('slides', $module->ID);
?>
<div class="slider">
    <ul>
    <?php foreach ($slides as $slide) : ?>
        <li>
        <?php if ($slide['acf_fc_layout'] == 'image') : ?>

            <div class="slider-image" style="background-image:url('<?php echo $slide['image']['url']; ?>');">
                <?php
                if (isset($slide['activate_textblock']) && $slide['activate_textblock'] === true) {
                    echo '<span class="text-block">' . $slide['textblock_content'] . '</span>';
                }
                ?>
            </div>

        <?php elseif ($slide['acf_fc_layout'] == 'video' && $slide['type'] == 'embed') : ?>

            <div class="ratio-16-9">
                <?php echo \Modularity\Module\Slider\Slider::getEmbed($slide['embed_link'], ['content']); ?>
            </div>

        <?php elseif ($slide['acf_fc_layout'] == 'video' && $slide['type'] == 'upload') : ?>

            Markup for uploaded video.

        <?php endif; ?>
        </li>
    <?php endforeach; ?>
    </ul>
</div>
