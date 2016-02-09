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
                    echo '<span class="text-block">' . do_shortcode($slide['textblock_content']) . '</span>';
                }
                ?>
            </div>

        <?php elseif ($slide['acf_fc_layout'] == 'video' && $slide['type'] == 'embed') : ?>

            <div class="ratio-16-9">
                <?php echo \Modularity\Module\Slider\Slider::getEmbed($slide['embed_link'], ['content']); ?>
            </div>

        <?php elseif ($slide['acf_fc_layout'] == 'video' && $slide['type'] == 'upload') : ?>

            <?php

                if (isset($slide['placeholder_image']) && !empty($slide['placeholder_image'])) {
                    $image = wp_get_attachment_image_src(
                        $slide['placeholder_image']['id'],
                        apply_filters('modularity/image/hero/video',
                            array(1140,641)
                        )
                    );
                } else {
                    $image = false;
                }

            ?>

            <div class="slider-video" style="background-image:url('<?php echo ($image !== false ) ? $image[0] : ''; ?>');">

                <video poster="<?php echo ($image !== false ) ? $image[0] : ''; ?>" loop="" preload="auto" autoplay="1">

                    <!-- Mp4 -->
                    <?php if(isset($slide['video_mp4']) && !empty($slide['video_mp4'])) { ?>
                        <source src="<?php echo $slide['video_mp4']['url']; ?>" type="video/mp4">
                    <?php } ?>

                    <!-- Webm -->
                    <?php if(isset($slide['video_webm']) && !empty($slide['video_webm'])) { ?>
                        <source src="<?php echo $slide['video_webm']['url']; ?>" type="video/webm">
                    <?php } ?>

                    <!-- Ogg -->
                    <?php if(isset($slide['video_ogg']) && !empty($slide['video_ogg'])) { ?>
                        <source src="<?php echo $slide['video_ogg']['url']; ?>" type="video/ogg">
                    <?php } ?>

                </video>

                <!-- Text -->
                <?php
                    if (isset($slide['activate_textblock']) && $slide['activate_textblock'] === true) {
                        echo '<span class="text-block">' . do_shortcode($slide['textblock_content']) . '</span>';
                    }
                ?>

            </div>

        <?php endif; ?>
        </li>
    <?php endforeach; ?>
    </ul>
</div>
