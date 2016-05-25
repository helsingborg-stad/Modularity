<?php
    $fields = get_fields($module->ID);

    // Image
    $image = false;
    if (isset($fields['placeholder_image']) && !empty($fields['placeholder_image'])) {
        $image = wp_get_attachment_image_src(
            $fields['placeholder_image']['id'],
            apply_filters('Modularity/slider/image',
                array(1140, 641),
                $args
            )
        );
    }
?>

<div class="<?php echo implode(' ', apply_filters('Modularity/Module/Classes', array('box', 'box-panel', 'embedded-video'), $module->post_type, $args)); ?>">
    <h4 class="box-title"><?php echo !empty($module->post_title) ? apply_filters('the_title', $module->post_title) : 'Video'; ?></h4>

    <?php if ($fields['type'] == 'upload') : ?>
        <video class="ratio-16-9" poster="<?php echo ($image !== false) ? $image[0] : ''; ?>" preload="auto" autoplay loop muted>

            <!-- Mp4 -->
            <?php if (isset($fields['video_mp4']) && !empty($fields['video_mp4'])) : ?>
                <source src="<?php echo $fields['video_mp4']['url']; ?>" type="video/mp4">
            <?php endif; ?>

            <!-- Webm -->
            <?php if (isset($fields['video_webm']) && !empty($fields['video_webm'])) : ?>
                <source src="<?php echo $fields['video_webm']['url']; ?>" type="video/webm">
            <?php endif; ?>

            <!-- Ogg -->
            <?php if (isset($fields['video_ogg']) && !empty($fields['video_ogg'])) : ?>
                <source src="<?php echo $fields['video_ogg']['url']; ?>" type="video/ogg">
            <?php endif; ?>
        </video>
    <?php else : ?>
        <?php echo \Modularity\Module\Slider\Slider::getEmbed($fields['embed_link'], ['player', 'ratio-16-9'], $image); ?>
    <?php endif; ?>
</div>
