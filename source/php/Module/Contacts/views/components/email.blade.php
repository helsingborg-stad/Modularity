@collection__Item([
    'classList' => ['c-collection__email'],
    'icon' => $icon ?? false,
    'link' => 'mailto:' . $contact['email'],
    'attributeList' => [
        'itemprop'  => 'email',
    ]
])
    {{$contact['email']}}
@endcollection__Item