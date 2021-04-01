@card([
    'classList'     => [$classes],
    'attributeList' => [
        'aria-labelledby' =>'mod-table-' . $ID .'-label'
    ],
    'context'       => 'table'
])
    @if (!$hideTitle && !empty($post_title))
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
        'headings'          => $m_table->data['headings'],
        'list'              => $m_table->data['list'],
        'showHeader'        => $m_table->showHeader,
        'showFooter'        => $m_table->showFooter,
        'hasZebraStripes'   => $m_table->zebraStripes,
        'isBordered'        => $m_table->borderStyle,
        'hasHoverEffect'    => $m_table->hoverEffect,
        'isSmall'           => $m_table->isSmall,
        'isLarge'           => $m_table->isLarge,
        'filterable'        => $m_table->filterable,
        'sortable'          => $m_table->sortable,
        'pagination'        => $m_table->pagination,
        'labels'            => ['searchPlaceholder' => __('Search', 'municipio')]
    ])
    @endtable
@endcard