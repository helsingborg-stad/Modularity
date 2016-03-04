<?php
$fields = json_decode(json_encode(get_fields($module->ID)));
$classes = '';
if (isset($fields->mod_table_classes) && is_array($fields->mod_table_classes)) {
    $classes = implode(' ', $fields->mod_table_classes);
}

echo str_replace(
    '<table class="',
    '<table class="' . $classes . ' ',
    $fields->mod_table
);
