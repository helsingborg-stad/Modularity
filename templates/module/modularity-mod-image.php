<?php

$fields = get_fields($module->ID);
$imageSrc = $fields['mod_image_image']['url'];

if ($fields['mod_image_crop'] === true) {
    $imageSrc = wp_get_attachment_image_src(
        $fields['mod_image_image']['ID'],
        apply_filters('Modularity/image/image',
            municipio_to_aspect_ratio('16:9', array($fields['mod_image_crop_width'], $fields['mod_image_crop_height']))
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

if (!$module->hideTitle && !empty($module->post_title)) {
    echo '<h2>' . $module->post_title . '</h2>';
}

if (isset($fields['mod_image_link_url']) && strlen($fields['mod_image_link_url']) > 0) {
    echo '<a href="' . $fields['mod_image_link_url'] . '"><img src="' . $imageSrc . '" alt="' . $fields['mod_image_image']['alt'] . '" class="block-level' . implode(' ', apply_filters('', $classes)) . '"></a>';
} else {
    echo '<img src="' . $imageSrc . '" alt="' . $fields['mod_image_image']['alt'] . '" class="block-level ' . implode(' ', apply_filters('', $classes)) . '">';
}

if (isset($fields['mod_image_caption']) && !empty($fields['mod_image_caption'])) {
    echo '<p class="creamy gutter gutter-sm wp-caption-text">', $fields['mod_image_caption'],'</p>';
}
