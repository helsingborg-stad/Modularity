@collection__item([
    'classList' => ['c-collection__visiting']
])
    @typography([
        "element"       => "strong",
        'attributeList' => ['itemprop' => 'adress'],
        'classList'     => [
            'u-margin__bottom--0',
            'u-color__text--darkest'
        ]
    ])
        <?php _e('Visiting address', 'modularity'); ?>
    @endtypography

    @typography([
        "element"       => "p",
        'classList'     => [
            'u-margin__top--0',
            'u-color__text--darker'
        ]
    ])
        {!! $contact['visiting_address'] !!}
    @endtypography
@endcollection__item