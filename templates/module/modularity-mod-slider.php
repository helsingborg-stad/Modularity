<?php
    $slides = get_field('slides', $module->ID);
?>
<div class="<?php echo implode(' ', apply_filters('Modularity/Module/Classes', array('slider', get_field('slider_format', $module->ID)), $module->post_type, $args)); ?> <?php if (get_field('navigation_position', $module->ID) == "bottom") : ?>slider-nav-bottom<?php endif; ?> <?php if (get_field('show_navigation', $module->ID) == "hover") : ?>slider-nav-hover<?php endif; ?>" <?php if (get_field('slides_autoslide', $module->ID) === true) : ?>data-autoslide="true"<?php endif; ?> <?php if (!empty(get_field('slides_slide_timeout', $module->ID))) : ?>data-autoslide-interval="<?php echo get_field('slides_slide_timeout', $module->ID) * 1000; ?>"<?php endif; ?>>
    <ul>
    <?php foreach ($slides as $slide) : ?>
        <?php

            //Image
            if (isset($slide['image']) && !empty($slide['image'])) {
                $image = wp_get_attachment_image_src(
                    $slide['image']['id'],
                    apply_filters('Modularity/slider/image',
                        array(1140, 641),
                        $args
                    )
                );
            } else {
                $image = false;
            }

            //Mobile image
            if (isset($slide['mobile_image']) && !empty($slide['mobile_image'])) {
                $mobile_image = wp_get_attachment_image_src(
                    $slide['mobile_image']['id'],
                    apply_filters('Modularity/slider/mobile_image',
                        array(500, 500),
                        $args
                    )
                );
            } else {
                $mobile_image = $image;
            }

            //In some cases ACF will return an post-id instead of a link.
            if (isset($slide['link_url']) && is_numeric($slide['link_url']) && get_post_status($slide['link_url']) == "publish")
            {
                $slide['link_url'] = get_permalink($slide['link_url']);
            }

            //Slider format

        ?>
        <li class="type-<?php echo $slide['acf_fc_layout']; ?> <?php echo (isset($slide['activate_textblock']) && $slide['activate_textblock'] === true) ? 'has-text-block' : ''; ?>">

            <!-- Link start -->
            <?php if (isset($slide['link_type']) && !empty($slide['link_type']) && $slide['link_type'] != 'false') : ?>
                <a href="<?php echo isset($slide['link_url']) && !empty($slide['link_url']) ? $slide['link_url'] : '#' ?>" <?php if (isset($slide['link_target']) && $slide['link_target'] === true) : ?>target="_blank"<?php endif; ?>>
            <?php endif; ?>

            <!-- Slides -->
            <?php if ($slide['acf_fc_layout'] == 'image') : ?>

                <?php if ($image !== false) : ?>
                <div class="slider-image slider-image-desktop <?php echo apply_filters('Modularity/slider/desktop_image_hidden', 'hidden-xs hidden-sm'); ?>" style="background-image:url(<?php echo ($image !== false) ? $image[0] : ''; ?>)"></div>
                <?php endif; ?>
                <?php if ($mobile_image !== false) : ?>
                <div class="slider-image slider-image-mobile <?php echo apply_filters('Modularity/slider/mobile_image_hidden', 'hidden-md hidden-lg'); ?>" style="background-image:url(<?php echo ($mobile_image !== false) ? $mobile_image[0] : ''; ?>)"></div>
                <?php endif; ?>

            <?php elseif ($slide['acf_fc_layout'] == 'video' && $slide['type'] == 'embed') : ?>
                <?php echo \Modularity\Module\Slider\Slider::getEmbed($slide['embed_link'], ['player'], $image); ?>
            <?php elseif ($slide['acf_fc_layout'] == 'video' && $slide['type'] == 'upload') : ?>

                <div class="slider-video" style="background-image:url('<?php echo ($image !== false) ? $image[0] : ''; ?>');">

                    <video poster="<?php echo ($image !== false) ? $image[0] : ''; ?>" preload="auto" autoplay loop muted>

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

            <?php elseif ($slide['acf_fc_layout'] == 'featured') : ?>

                <span class="text-block text-block-left">
                    <span>
                        <!-- Title -->
                        <?php if (isset($slide['textblock_title']) && !empty($slide['textblock_title'])) {?>
                            <em class="title block-level h1"><?php echo $slide['textblock_title'];?> </em>
                        <?php } ?>

                        <!-- Content -->
                        <?php if (isset($slide['textblock_content']) && !empty($slide['textblock_content'])) { ?>
                            <?php echo $slide['textblock_content']; ?>
                        <?php } ?>
                    </span>
                </span>
                <div class="slider-image slider-image-desktop hidden-xs hidden-sm" style="background-image:url(<?php echo ($image !== false) ? $image[0] : ''; ?>)"></div>
                <div class="slider-image slider-image-mobile hidden-md hidden-lg" style="background-image:url(<?php echo ($image !== false) ? $mobile_image[0] : ''; ?>)"></div>

            <?php endif; // END SLIDERS ?>

            <!-- Text -->
            <?php
            if (isset($slide['activate_textblock']) && $slide['activate_textblock'] === true) :
                $classes = '';

                switch ($slide['textblock_position']) {
                    case 'center':
                        $classes .= ' text-block-center';
                        break;
                }

            ?>
                <span class="text-block <?php echo $classes; ?>">
                    <span>
                        <?php if (isset($slide['textblock_title']) && strlen($slide['textblock_title']) > 0) : ?>
                            <em class="title text-xl block-level"><?php echo do_shortcode($slide['textblock_title']); ?></em>
                        <?php endif; ?>
                        <?php if (isset($slide['textblock_content']) && strlen($slide['textblock_content']) > 0) : ?>
                            <?php echo do_shortcode($slide['textblock_content']); ?>
                        <?php endif; ?>
                    </span>
                </span>
            <?php endif; ?>

            <?php if ($slide['link_type'] != 'false') : ?>
            </a>
            <?php endif; ?>
        </li>
    <?php endforeach; ?>
    </ul>
</div>
