@collection__item([
    'classList' => ['c-collection__email'],
    'icon' => 'email',
    'link' => 'mailto:' . $contact['email'],
    'attributeList' => [
        'itemprop'  => 'email',
    ]
])
    {{$contact['email']}}
@endcollection__item