<?php

$fields = json_decode(json_encode(get_fields($module->ID)));

$feedArgs = array(
    'network'    => isset($fields->mod_social_type) ? $fields->mod_social_type : '',
    'type'       => isset($fields->mod_social_data_type) ? $fields->mod_social_data_type : '',
    'query'      => isset($fields->mod_social_query) ? $fields->mod_social_query : '',
    'length'      => isset($fields->mod_social_length) ? $fields->mod_social_length : 10,
    'api_user'   => isset($fields->mod_social_api_user) ? $fields->mod_social_api_user : '',
    'api_secret' => isset($fields->mod_social_api_secret) ? $fields->mod_social_api_secret : ''
);

$feed = new \Modularity\Module\Social\Feed($feedArgs);
$feed->render();
