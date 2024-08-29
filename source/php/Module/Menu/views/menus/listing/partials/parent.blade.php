@link([
    'href' => $menuItem['href'] ?? '#',
    'classList' => [
        'mod-menu__heading',
        $menuItem['post_type'] ? 's-post-type-' . $menuItem['post_type'] : '',
    ]
])
    @if (!empty($menuItem['icon']['icon']))
        @icon([
            'icon' => $menuItem['icon']['icon'] ?? '',
            'size' => 'lg',
            'classList' => [
                'mod-menu__heading-icon',
            ]
        ])
        @endicon
    @endif
    @typography([
        'element' => 'h2',
        'variant' => 'h2',
        'classList' => [
            empty($menuItem['icon']['icon']) ? 'u-margin__left--5' : '',
            'mod-menu__heading-label',
            'u-color--primary'
        ]
    ])
        {{$menuItem['label'] ?? ""}}
    @endtypography
@endlink