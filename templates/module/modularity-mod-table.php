<?php
$fields = json_decode(json_encode(get_fields($module->ID)));

$classes = '';
if (isset($fields->mod_table_classes) && is_array($fields->mod_table_classes)) {
    $classes = implode(' ', $fields->mod_table_classes);
}
?>
<div class="<?php echo implode(' ', apply_filters('Modularity/Module/Classes', array('box', 'box-panel'), $module->post_type, $args)); ?>">
    <h4 class="box-title"><?php echo $module->post_title; ?></h4>
    <?php
    echo str_replace(
        '<table class="',
        sprintf(
            '<table data-paging="%2$s" data-page-length="%3$s" data-searching="%4$s" data-ordering="%6$s" data-info="%5$s" class="datatable %1$s ',
            $classes,
            $fields->mod_table_pagination,
            $fields->mod_table_pagination_count,
            $fields->mod_table_search,
            $fields->mod_table_pagination || $fields->mod_table_search,
            isset($fields->mod_table_ordering) ? $fields->mod_table_ordering : true
        ),
        $fields->mod_table
    );
?>
</div>
