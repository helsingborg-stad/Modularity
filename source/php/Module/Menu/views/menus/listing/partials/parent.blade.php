@link([
    'href' => $menuItem['href'] ?? '#',
    'classList' => [
        'mod-menu__heading',
        'u-padding__bottom--1'
    ]
])
    @typography([
        'element' => 'h2',
        'variant' => 'h2',
        'classList' => [
            'mod-menu__heading-label',
            'u-margin__top--0'
        ]
    ])
        {{$menuItem['label'] ?? ""}}
    @endtypography
@endlink
@if (!empty($menuItem['description']))
    @typography([
        'element' => 'span',
        'classList' => [
            'mod-menu__heading-description',
            'u-margin__top--1',
            'u-display--block'
        ]
    ])
    {{ $menuItem['description'] }}
    @endtypography
@endif