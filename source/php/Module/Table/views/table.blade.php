<div class="{{ $classes }}">
    @if (!$hideTitle && !empty($post_title))
        <h4 class="box-title">{{ $post_title }}</h4>
    @endif

    <?php
    echo str_replace(
        '<table class="',
        sprintf(
            '<table data-paging="%2$s" data-page-length="%3$s" data-searching="%4$s" data-ordering="%6$s" data-info="%5$s" class="datatable %1$s ',
            $tableClasses,
            $mod_table_pagination,
            $mod_table_pagination_count,
            $mod_table_search,
            $mod_table_pagination || $mod_table_search,
            isset($mod_table_ordering) ? $mod_table_ordering : true
        ),
        $mod_table
    );
?>
</div>
