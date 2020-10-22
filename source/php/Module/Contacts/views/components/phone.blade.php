@collection__item([
    'classList' => ['c-collection__phone'],
    'icon' => isset($phone['type']) ? $phone['type'] : 'phone',
    'link' => 'tel:' . $phone['number'],
    'attributeList' => [
        'itemprop'  => 'telephone',
    ]
])
    {{ $phone['number'] }}
@endcollection__item