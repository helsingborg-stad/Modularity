<?php
$slides = get_field('slides', $module->ID);

// Formats
$imageSizes = array(
    'ratio-16-9' => array(1140,641),
    'ratio-10-3' => array(1140,342),
    'ratio-4-3' => array(1140,885)
);

// Filter options
$imageSizes = apply_filters('Modularity/slider/imagesizes', $imageSizes, $args);

// Classes
$classes = array();
$classes[] = 'slider'; //array_merge($classes, array('slider', get_field('slider_format', $module->ID)));

if (get_field('navigation_position', $module->ID) == 'bottom') {
    $classes[] = 'slider-nav-bottom';
}

if (get_field('show_navigation', $module->ID) == "hover") {
    $classes[] = 'slider-nav-hover';
}

// Flickity settings
$flickity = array();
$flickity['cellSelector']   = '.slide';
$flickity['cellAlign']      = get_field('slide_align', $module->ID) ? get_field('slide_align', $module->ID) : 'center';
$flickity['wrapAround']     = in_array('wrapAround', (array) get_field('additional_options', $module->ID));
$flickity['setGallerySize'] = false;
$flickity['pageDots']       = in_array('pageDots', (array) get_field('additional_options', $module->ID));
$flickity['freeScroll']     = in_array('freeScroll', (array) get_field('additional_options', $module->ID));

if (get_field('slides_autoslide', $module->ID) === true) {
    $flickity['autoPlay'] = true;
    $flickity['pauseAutoPlayOnHover'] = true;

    if (!empty(get_field('slides_slide_timeout', $module->ID))) {
        $flickity['autoPlay'] = (int) get_field('slides_slide_timeout', $module->ID) * 1000;
    }
}

if (count($slides) == 1 || count($slides) == get_field('slide_columns', $module->ID)) {
    $flickity = array_merge($flickity, array(
        'draggable' => false,
        'pageDots' => false,
        'prevNextButtons' => false,
        'autoPlay' => false
    ));
}

$flickity = json_encode($flickity);
?>

<div class="<?php echo implode(' ', $classes); ?> <?php echo get_field('slider_format', $module->ID); ?>">
    <?php if (!$module->hideTitle) : ?>
        <h2><?php echo $module->post_title; ?></h2>
    <?php endif; ?>

    <div data-flickity='<?php echo $flickity; ?>'>
    <?php foreach ($slides as $slide) : ?>
        <?php
            //Fallback to default
            switch (get_field('slider_format', $module->ID)) {
                case 'ratio-16-9':
                case 'ratio-10-3':
                case 'ratio-4-3':
                    $currentImageSize = $imageSizes[get_field('slider_format', $module->ID)];
                    break;
                default:
                    $currentImageSize = array(1800,350);
            }

            // Special for video & featured
            if ($slide['acf_fc_layout'] == "video") {
                $currentImageSize = array(1140,641);
            }

            if ($slide['acf_fc_layout'] == "featured") {
                $currentImageSize = array(floor($currentImageSize[0]/2), $currentImageSize[1]);
            }

            // Image
            $image = false;
            if (isset($slide['image']) && !empty($slide['image'])) {
                $image = wp_get_attachment_image_src(
                    $slide['image']['id'],
                    apply_filters('Modularity/slider/image',
                        $currentImageSize,
                        $args
                    )
                );
            }

            // Mobile image
            $mobile_image = $image;
            if (isset($slide['mobile_image']) && !empty($slide['mobile_image'])) {
                $mobile_image = wp_get_attachment_image_src(
                    $slide['mobile_image']['id'],
                    apply_filters('Modularity/slider/mobile_image',
                        array(500, 500),
                        $args
                    )
                );
            }

            // In some cases ACF will return an post-id instead of a link.
            if (isset($slide['link_url']) && is_numeric($slide['link_url']) && get_post_status($slide['link_url']) == "publish") {
                $slide['link_url'] = get_permalink($slide['link_url']);
            }

        ?>
        <div class="slide type-<?php echo $slide['acf_fc_layout']; ?> <?php echo (isset($slide['activate_textblock']) && $slide['activate_textblock'] === true) ? 'has-text-block' : ''; ?>" <?php echo get_field('slide_columns', $module->ID) ? 'style="width:' . 100/(int)get_field('slide_columns', $module->ID) . '%;"' : ''; ?>>

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
                        <?php if (isset($slide['textblock_title']) && !empty($slide['textblock_title'])) {
    ?>
                            <em class="title block-level h1"><?php echo $slide['textblock_title'];
    ?> </em>
                        <?php
} ?>

                        <!-- Content -->
                        <?php if (isset($slide['textblock_content']) && !empty($slide['textblock_content'])) {
    ?>
                            <?php echo $slide['textblock_content'];
    ?>
                        <?php
} ?>
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
        </div>
    <?php endforeach; ?>
    </div>

</div>
