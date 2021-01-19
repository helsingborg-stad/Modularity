@collection__item([
    'classList' => ['c-collection__email'],
    'icon' => $icon ?? false,
    'link' => 'mailto:' . $contact['email'],
    'attributeList' => [
        'itemprop'  => 'email',
    ]
])
    @typography([
        "element"       => "span",
        'classList'     => [
            'u-margin__top--0',
            'u-color__text--darker',
            'c-typography__variant--email'
        ]
    ])
        {{$contact['email']}}
    
    @endtypography
@endcollection__item


