<?php

$fields = json_decode(json_encode(get_fields($module->ID)));

$feedArgs = array(
    'network'    => isset($fields->mod_social_type) ? $fields->mod_social_type : '',
    'type'       => isset($fields->mod_social_data_type) ? $fields->mod_social_data_type : '',
    'query'      => isset($fields->mod_social_query) ? $fields->mod_social_query : '',
    'length'     => isset($fields->mod_social_length) ? $fields->mod_social_length : 10,
    'max_height' => isset($fields->mod_social_max_height) ? $fields->mod_social_max_height : 300,
    'row_length' => isset($fields->mod_social_row_length) ? $fields->mod_social_row_length : 3,
    'api_user'   => isset($fields->mod_social_api_user) ? $fields->mod_social_api_user : '',
    'api_secret' => isset($fields->mod_social_api_secret) ? $fields->mod_social_api_secret : ''
);

$feed = new \Modularity\Module\Social\Feed($feedArgs);
?>

<div class="<?php echo implode(' ', apply_filters('Modularity/Module/Classes', array('box', 'box-panel'), $module->post_type, $args)); ?>">
    <?php if (!$module->hideTitle) : ?>
        <h4 class="box-title"><i class="fa fa-<?php echo $feedArgs['network']; ?>"></i> <?php echo $module->post_title; ?></h4>
    <?php endif; ?>

    <?php $feed->render(); ?>
</div>
