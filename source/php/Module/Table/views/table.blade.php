<div class="{{ $classes }}">
    @if (!$hideTitle && !empty($post_title))
        @typography([
            "element" => "h4",
            "varaint" => "h4"
        ])
            {{ $post_title }}
        @endtypography
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
    ])
    @endtable

    @if ($m_table->pagination)
        @pagination([
            'list'      => $m_table->pagination['list'],
            'current'   => $m_table->pagination['current'],
            'parameter' => $m_table->pagination['param'],
        ])
        @endpagination
    @endif
</div>
