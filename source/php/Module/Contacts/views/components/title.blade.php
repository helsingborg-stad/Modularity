@collection__item([
    'classList' => ['c-collection__title'],
])

    @if ($contact['full_name'])
        @typography([
            "element"       => "h3",
            'attributeList' => [
                'itemprop' => 'name'
            ],
            'classList'     => [
                'u-margin__bottom--0',
                'u-color__text--darkest',
                'notranslate'
            ]
        ])
            {{ $contact['full_name'] }}
        @endtypography
    @endif

    @if ($contact['work_title'])
        @typography([
            "element"       => "p",
            'attributeList' => ['itemprop' => 'work-title'],
            'classList'     => [
                'u-margin__bottom--0',
                'u-color__text--darkest'
            ]
        ])
            {{ $contact['work_title'] }}
        @endtypography
    @endif

    @if ($contact['administration_unit'])
        @typography([
            "element"       => "p",
            'attributeList' => ['itemprop' => 'administration-unit'],
            'classList'     => [
                'u-margin__bottom--0',
                'u-color__text--darkest'
            ]
        ])
            {{ $contact['administration_unit'] }}
        @endtypography
    @endif

@endcollection__item