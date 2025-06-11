<div class="c-card__body">
    @field([
        'type'          => 'text',
        'name'          => 'search',
        'attributeList' => [
            'js-filter-input'   => $uID
        ],
        'label'         => __('Search', 'municipio'),
        'placeholder'   => __('Search', 'municipio') . '..'
    ])
    @endfield
</div>