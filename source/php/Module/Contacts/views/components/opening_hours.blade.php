<div class="u-margin__top--2">
    @typography([
        "element"       => "strong",
        'attributeList' => ['itemprop' => 'adress'],
        'classList'     => [
            'u-margin__bottom--0',
            'u-margin__top--2',
            'u-color__text--darkest'
        ]
    ])
        <?php _e('Opening hours', 'modularity'); ?>
    @endtypography

    @typography([
        "element"       => "p",
        'classList'     => [
            'u-margin__top--0',
            'u-color__text--darker'
        ]
    ])
        {!! $contact['opening_hours'] !!}
    @endtypography
</div>