<?php

if (!function_exists('modularity_decode_icon')) {
    function modularity_decode_icon($data)
    {
        if (empty($data)) {
            return;
        }

        $data = explode(',', $data);

        if (strpos($data[0], 'svg') !== false) {
            return base64_decode($data[1]);
        }

        return '<img src="' . base64_decode($data[1]) . '">';
    }
}
