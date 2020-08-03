<div>
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
    <span class="u-color__text--darker">
        {!! $contact['address'] !!}
    </span>
</div>