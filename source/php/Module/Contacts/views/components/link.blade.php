@collection__item([
    'classList' => ['c-collection__link'],
    'icon' => $icon ?? false,
    'link' => $href,
    'attributeList' => [
        'itemprop' => $itemprop
    ]
])
    {{ $slot }}
@endcollection__item