@link([
    'href' => $menuItem['href'] ?? '#',
    'classList' => [
        'u-display--flex',
        'u-align-items--center',
        'u-no-decoration'
    ]
])
    @if (!empty($menuItem['icon']['icon']))
        @icon([
            'icon' => $menuItem['icon']['icon'] ?? '',
            'size' => 'lg',
        ])
        @endicon
    @endif
    @typography([
        'element' => 'h2',
        'variant' => 'h2',
        'classList' => [
            'u-margin__top--0',
            'u-padding__left--1'
        ]
    ])
        {{$menuItem['label'] ?? ""}}
    @endtypography
@endlink