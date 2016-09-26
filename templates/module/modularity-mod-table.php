<?php
$fields = json_decode(json_encode(get_fields($module->ID)));

$classes = '';
if (isset($fields->mod_table_classes) && is_array($fields->mod_table_classes)) {
    $classes = $fields->mod_table_classes;
    if (isset($fields->mod_table_size) && !empty($fields->mod_table_size)) {
        $classes[] = $fields->mod_table_size;
    }

    $classes = array_unique($classes);
    $classes = implode(' ', $classes);
}
?>
<div class="<?php echo implode(' ', apply_filters('Modularity/Module/Classes', array('box', 'box-panel'), $module->post_type, $args)); ?>">
    <?php if (!$module->hideTitle) : ?>
        <h4 class="box-title"><?php echo $module->post_title; ?></h4>
    <?php endif; ?>

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
