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
                @if ($contact['image'])
                    <div class="c-card__image c-card__image--secondary">
                        <div class="c-card__image-background" alt="{{ $contact['full_name'] }}" style="background-image:url('{{ $contact['image']['url'] }}');"></div>
                    </div>
                @endif

                @include('partials.information')               
            @endcard
        </div>
    @endforeach
</div>