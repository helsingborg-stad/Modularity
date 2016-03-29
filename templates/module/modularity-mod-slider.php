<?php
    $slides = get_field('slides', $module->ID);
?>
<div class="slider">
    <ul>
    <?php foreach ($slides as $slide) : ?>
        <?php
        if (isset($slide['image']) && !empty($slide['image'])) {
            $image = wp_get_attachment_image_src(
                $slide['image']['id'],
                apply_filters('modularity/image/slider',
                    array(1140, 641),
                    $args
                )
            );
        } else {
            $image = false;
        }
        ?>
        <li class="type-<?php echo $slide['acf_fc_layout']; ?> <?php echo (isset($slide['activate_textblock']) && $slide['activate_textblock'] === true) ? 'has-text-block' : ''; ?>">
            <?php if (isset($slide['link_type']) && !empty($slide['link_type']) && $slide['link_type'] != 'false') : ?>
            <a href="<?php echo isset($slide['link_url']) && !empty($slide['link_url']) ? $slide['link_url'] : '#' ?>" <?php if (isset($slide['link_target']) && $slide['link_target'] === true) : ?>target="_blank"<?php endif; ?>>
            <?php endif; ?>
            <?php // SLIDES ?>
            <?php if ($slide['acf_fc_layout'] == 'image') : ?>

                <div class="slider-image" style="background-image:url('<?php echo ($image !== false ) ? $image[0] : ''; ?>');"></div>

            <?php elseif ($slide['acf_fc_layout'] == 'video' && $slide['type'] == 'embed') : ?>
                <?php echo \Modularity\Module\Slider\Slider::getEmbed($slide['embed_link'], ['player'], $image); ?>
            <?php elseif ($slide['acf_fc_layout'] == 'video' && $slide['type'] == 'upload') : ?>

                <div class="slider-video" style="background-image:url('<?php echo ($image !== false ) ? $image[0] : ''; ?>');">

                    <video poster="<?php echo ($image !== false ) ? $image[0] : ''; ?>" preload="auto" autoplay loop muted>

                        <!-- Mp4 -->
                        <?php if (isset($slide['video_mp4']) && !empty($slide['video_mp4'])) : ?>
                            <source src="<?php echo $slide['video_mp4']['url']; ?>" type="video/mp4">
                        <?php endif; ?>

                        <!-- Webm -->
                        <?php if (isset($slide['video_webm']) && !empty($slide['video_webm'])) : ?>
                            <source src="<?php echo $slide['video_webm']['url']; ?>" type="video/webm">
                        <?php endif; ?>

                        <!-- Ogg -->
                        <?php if (isset($slide['video_ogg']) && !empty($slide['video_ogg'])) : ?>
                            <source src="<?php echo $slide['video_ogg']['url']; ?>" type="video/ogg">
                        <?php endif; ?>

                    </video>
                </div>

            <?php endif; // END SLIDERS ?>

            <!-- Text -->
            <?php
            if (isset($slide['activate_textblock']) && $slide['activate_textblock'] === true) {
                $classes = '';

                switch ($slide['textblock_position']) {
                    case 'center':
                        $classes .= ' text-block-center';
                        break;
                }

                echo '<span class="text-block' . $classes . '"><span>' . do_shortcode($slide['textblock_content']) . '</span></span>';
            }
            ?>

            <?php if ($slide['link_type'] != 'false') : ?>
            </a>
            <?php endif; ?>
        </li>
    <?php endforeach; ?>
    </ul>
</div>
