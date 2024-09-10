@link([
    'href' => $menuItem['href'] ?? '#',
    'classList' => [
        'mod-menu__heading'
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
    @group([
        'direction' => 'vertical',
        'classList' => [
            'u-width--100',
            'mod-menu__heading-label-wrapper',
            empty($menuItem['icon']['icon']) ? 'u-margin__left--5' : '',
        ]
    ])
    @typography([
        'element' => 'h2',
        'variant' => 'h2',
        'classList' => [
            'mod-menu__heading-label',
        ]
    ])
        {{$menuItem['label'] ?? ""}}
    @endtypography
    @if (!empty($menuItem['description']))
        @typography([
            'element' => 'span',
            'classList' => [
                'mod-menu__heading-description'
            ]
        ])
        {{ $menuItem['description'] }}
        @endtypography
    @endif
    @endgroup
@endlink