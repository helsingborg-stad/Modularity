@if (!$hideTitle && !empty($postTitle))
    <div class="c-card__header">
        @typography([
            'id'        => 'mod-table-' . $ID .'-label',
            'element'   => "h4",
        ])
            {!! apply_filters('the_title', $post_title) !!}
        @endtypography
    </div>
@endif

@table([
    'headings'              => $m_table->data['headings'],
    'list'                  => $m_table->data['list'],
    'showHeader'            => $m_table->showHeader,
    'filterable'            => $m_table->filterable,
    'sortable'              => $m_table->sortable,
    'pagination'            => $m_table->pagination,
    'fullscreen'            => $m_table->fullscreen, 
    'isMultidimensional'    => $m_table->multidimensional,
    'showSum'               => $m_table->showSum,
    'labels'                => ['searchPlaceholder' => __('Search', 'municipio')],
    'context'               => 'module.table'
])
@endtable
