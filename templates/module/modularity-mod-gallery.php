<?php
$fields = get_fields($module->ID);
?>

<ul class="image-gallery grid grid-gallery">
    <?php if (isset($fields['mod_gallery_images'])) : foreach ($fields['mod_gallery_images'] as $image) : ?>
    <li class="grid-md-3">
        <a class="box box-index lightbox-trigger" href="<?php echo $image['url']; ?>" <?php if (isset($image['caption']) && !empty($image['caption'])) : ?>data-caption="<?php echo $image['caption']; ?>"<?php endif; ?>>
            <img alt="<?php echo $image['alt']; ?>" src="<?php echo $image['url']; ?>"/>
        </a>
     </li>
    <?php endforeach; endif; ?>
</ul>
