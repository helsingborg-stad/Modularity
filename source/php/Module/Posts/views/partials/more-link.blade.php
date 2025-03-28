@if ($posts_data_source !== 'input' && !empty($archive_link_url))
    @link([
        'href' => $archive_link_url,
        'classList' => ['u-display-block']
    ])
        @group([
            'classList' => [
                'u-gap-1',
                'u-margin__top--1@xs',
                'u-margin__top--1@sm',
                'u-justify-content--end@md',
                'u-justify-content--end@lg',
                'u-justify-content--end@xl',
                'u-align-items--center'
            ]
        ])
            {{ $archive_link_title ?? $lang['showMore'] }}
            @icon([
                'icon' => 'arrow_right_alt',
                'size' => 'lg',
                'classList' => [$baseClass . '__icon']
            ])
            @endicon
        @endgroup
    @endlink
@endif
