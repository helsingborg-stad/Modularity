<div class="c-card__body" aria-label="{{ __('Search', 'municipio') }}">
        @field([
            'type' => 'search',
            'name' => 'search',
            'label' => __('Search', 'municipio'),
            'hideLabel' => true,
            'attributeList' => [
                'js-filter-input' => $ID
            ],
            'placeholder' => __('Search', 'municipio')
        ])
        @endfield
</div>