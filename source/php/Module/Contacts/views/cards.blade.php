@if (!$hideTitle && !empty($post_title))
    @typography(['element' => 'h2', 'classList' => ['u-margin__bottom--2', 'u-margin__top--3']])
        {!! apply_filters('the_title', $post_title)!!}
    @endtypography
@endif

<div class="o-grid">
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