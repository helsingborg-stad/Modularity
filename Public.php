<?php

if (!function_exists('modularity_decode_icon')) {
    function modularity_decode_icon($data)
    {
        if (!empty($data['menu_icon'])) {
            if (isset($data['menu_icon_auto_import']) && $data['menu_icon_auto_import'] === true) {
                return $data['menu_icon'];
            } else {
                $data = explode(',', $data['menu_icon']);

                if (strpos($data[0], 'svg') !== false) {
                    return base64_decode($data[1]);
                }

                return '<img src="' . base64_decode($data[1]) . '">';
            }
        }

        return;
    }
}

/**
 * Get a posts featured image thumbnail by post id
 * @param  int|null $post_id Post id or null
 * @return string            Thumbnail url
 */
if (!function_exists('get_thumbnail_source')) {
    function get_thumbnail_source($post_id = null)
    {
        $id = get_post_thumbnail_id($post_id);
        $src = wp_get_attachment_image_srcset($id, 'medium', true);

        if (!$src) {
            $src = wp_get_attachment_url($id);
            $src = $src;
        }

        return $src;
    }
}

if (!function_exists('municipio_to_aspect_ratio')) {
    function municipio_to_aspect_ratio($ratio, $size)
    {
        $ratio = explode(':', $ratio);

        $width = round($size[0]);
        $height = ($width / $ratio[0]) * $ratio[1];

        return array($width, $height);
    }
}
