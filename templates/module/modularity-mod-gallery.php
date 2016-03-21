<?php
$fields = get_fields($module->ID);
?>

<ul class="image-gallery grid grid-gallery">
    <?php if (isset($fields['mod_gallery_images'])) : foreach ($fields['mod_gallery_images'] as $image) : ?>
    <?php
        $thumbnail_image = wp_get_attachment_image_src(
            $image['id'],
            apply_filters('modularity/image/gallery/thumbnail',
                array(200, 150)
            )
        );
    ?>
    <li class="grid-md-3">
        <a class="box box-index lightbox-trigger" href="<?php echo $image['sizes']['large']; ?>" <?php if (isset($image['caption']) && !empty($image['caption']) && !in_array(strtolower($image['caption']), array('caption text'))) : ?>data-caption="<?php echo $image['caption']; ?>"<?php endif; ?>>
            <?php if ($thumbnail_image) : ?>
            <img src="<?php echo $thumbnail_image[0]; ?>" alt="<?php echo $image['alt']; ?>">
            <?php else : ?>
            <figure class="image-placeholder"></figure>
            <?php endif; ?>
        </a>
     </li>
    <?php endforeach; endif; ?>
</ul>
