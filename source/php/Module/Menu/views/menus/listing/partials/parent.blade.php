@group([
])
    @if (!empty($menuItem['icon']['icon']))
        @icon([
            'icon' => $menuItem['icon']['icon'] ?? '',
            'size' => 'lg',
            'classList' => [
                'mod-menu__icon',
            ]
        ])
        @endicon
    @endif
    @group([
        'display' => 'flex',
        'direction' => 'vertical',
        'classList' => [
            'u-width--100',
            'mod-menu__heading-label-wrapper',
            empty($menuItem['icon']['icon']) ? 'u-margin__left--5' : '',
        ]
    ])
        @link([
            'href' => $menuItem['href'] ?? '#',
            'classList' => [
                'mod-menu__heading'
            ]
        ])
            @typography([
                'element' => 'h2',
                'variant' => 'h2',
                'classList' => [
                    'mod-menu__heading-label',
                    'u-color--primary'
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
                ]
            ])
                {{$menuItem['description']}}
            @endtypography
        @endif
    @endgroup
@endgroup