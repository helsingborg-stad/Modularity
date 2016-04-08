<?php
$fields = get_fields($module->ID);
$imageSrc = $fields['mod_image_image']['url'];

if ($fields['mod_image_crop'] === true) {
    $imageSrc = wp_get_attachment_image_src(
        $fields['mod_image_image']['ID'],
        apply_filters('Modularity/image/image',
            array($fields['mod_image_crop_width'], $fields['mod_image_crop_height'])
        )
    );

    $imageSrc = $imageSrc[0];
} else {
    $imageSrc = $fields['mod_image_image']['sizes'][$fields['mod_image_size']];
}

$classes = array();

if ($fields['mod_image_responsive'] === true) {
    $classes[] = 'image-responsive';
}

echo '<img src="' . $imageSrc . '" alt="' . $fields['mod_image_image']['alt'] . '" class="' . implode(' ', apply_filters('', $classes)) . '">';

?>
