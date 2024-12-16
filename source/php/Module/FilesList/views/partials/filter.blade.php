<div class="c-card__body">
    @field([
        'type'          => 'text',
        'attributeList' => [
            'type'              => 'search',
            'name'              => 'search',
            'js-filter-input'   => $uID
        ],
        'label'         => __('Search', 'municipio'),
        'placeholder'   => __('Search', 'municipio') . '..'
    ])
    @endfield
</div>