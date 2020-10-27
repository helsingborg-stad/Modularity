{{-- TODO: Fix icons --}}
@php
    $icon = $media['media'] == 'instagram' ?  'camera_alt' : $media['media'];
    $icon = $icon == 'twitter' ? 'chat_bubble' : $icon;
    $icon = $icon == 'linkedin' ? 'link' : $icon;
@endphp

@collection__Item([
    'classListl' => ['c-collection__social'],
    'icon' => $icon,
    'link' => $media['url'],
    'attributeList' => [
        'itemprop'  => ucfirst($media['media']),
    ]
])
    {{ ucfirst($media['media']) }}
@endcollection__Item