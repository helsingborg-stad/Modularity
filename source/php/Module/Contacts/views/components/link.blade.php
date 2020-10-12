@link([
    'href'          => $href,
    'attributeList' => ['itemprop' => $itemprop]
])
    @typography([
        'element'   => 'h3',
        'classList' => [
            $class,
            'u-color__text--primary'
        ]
    ])
        @if(isset($icon))
            @icon([
                'icon'      => $icon,
                'size'      => 'md',
                'classList' => ['u-align--text-bottom']
            ])
            @endicon
        @endif
        {{ $slot }}
    @endtypography
@endlink