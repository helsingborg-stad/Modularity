@collection__item([
    'classList' => ['c-collection__adress'],
])
    @typography([
        "element"       => "strong",
        'attributeList' => ['itemprop' => 'adress'],
        'classList'     => [
            'u-margin__bottom--0',
            'u-color__text--darkest'
        ]
    ])
        <?php _e('Postal address', 'modularity'); ?>
    @endtypography

    @typography([
        "element"       => "p",
        'classList'     => [
            'u-margin__top--0',
            'u-color__text--darker'
        ]
    ])
        {!! $contact['address'] !!}
    @endtypography
@endcollection__item