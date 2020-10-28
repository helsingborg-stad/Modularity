@collection__item([
    'classList' => ['c-collection__phone'],
    'icon' => $icon ?? false,
    'link' => 'tel:' . $phone['number'],
    'attributeList' => [
        'itemprop'  => 'telephone',
    ]
])
    {{ $phone['number'] }}
@endcollection__item