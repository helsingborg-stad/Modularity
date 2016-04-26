<?php
$fields = json_decode(json_encode(get_fields($module->ID)));
$posts = \Modularity\Module\Posts\Posts::getPosts($module);

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
}
