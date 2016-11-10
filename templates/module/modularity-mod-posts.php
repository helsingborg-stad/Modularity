<?php
$fields = json_decode(json_encode(get_fields($module->ID)));
$posts = \Modularity\Module\Posts\Posts::getPosts($module);

$sortBy = false;
$orderBy = false;
if (substr($fields->posts_sort_by, 0, 9) === '_metakey_') {
    $sortBy = 'meta_key';
    $orderBy = str_replace('_metakey_', '', $fields->posts_sort_by);
}

$order = $fields->posts_sort_order;

$filters = array(
    'orderby' => sanitize_text_field($sortBy),
    'order'   => sanitize_text_field($order)
);

if ($sortBy == 'meta_key') {
    $filters['meta_key'] = $orderby;
}

if (isset($fields->posts_taxonomy_type) && $fields->posts_taxonomy_type) {
    $taxType = $fields->posts_taxonomy_type;
    $taxValues = (array) $fields->posts_taxonomy_value;
    $taxValues = implode('|', $taxValues);

    $filters['term[]'] = $taxType . '|' . $taxValues;
}

switch ($fields->posts_display_as) {
    case 'list':
        include \Modularity\Helper\Wp::getTemplate($module->post_type . '-list', 'module/modularity-mod-posts', false);
        break;

    case 'news':
        include \Modularity\Helper\Wp::getTemplate($module->post_type . '-news', 'module/modularity-mod-posts', false);
        break;

    case 'items':
        include \Modularity\Helper\Wp::getTemplate($module->post_type . '-items', 'module/modularity-mod-posts', false);
        break;

    case 'index':
        include \Modularity\Helper\Wp::getTemplate($module->post_type . '-index', 'module/modularity-mod-posts', false);
        break;

    case 'expandable-list':
        include \Modularity\Helper\Wp::getTemplate($module->post_type . '-expandable-list', 'module/modularity-mod-posts', false);
        break;
}
