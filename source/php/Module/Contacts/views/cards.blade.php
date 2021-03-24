@if (!$hideTitle && !empty($post_title))
    @typography([
        'id'        => 'mod-text-' . $ID .'-label',
        'element'   => 'h4', 
        'variant'   => 'h2', 
        'classList' => ['module-title']
    ])
        {!! apply_filters('the_title', $post_title)!!}
    @endtypography
@endif

<div class="o-grid" aria-labelledby={{'mod-text-' . $ID .'-label'}}>
    @foreach ($contacts as $contact)
        <div class="o-grid-12 {{apply_filters('Municipio/Controller/Archive/GridColumnClass', $columns)}}">
            @card([
                'collapsible'   => $contact['hasBody'],
                'attributeList' => [
                    'itemscope'     => '',
                    'itemtype'      => 'http://schema.org/Person'
                ],
                'classList'     => [
                    'c-card--panel',
                    'c-card--square-image'
                ]
            ])

                <div class="c-card__image">
                    <div class="c-card__image-background" alt="{{ $contact['full_name'] }}" style="{{ $contact['image']['inlineStyle'] }}"></div>
                </div>

                @include('partials.information')               
            @endcard
        </div>
    @endforeach
</div>