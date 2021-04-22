@if (!$hideTitle && !empty($post_title))
    @typography(['element' => 'h2'])
        {!! apply_filters('the_title', $post_title) !!}
    @endtypography
@endif

<div class="o-grid">
    @foreach ($items as $item)
        <div class="{{ apply_filters('Municipio/Controller/Archive/GridColumnClass', $columnClass) }}">
            @card([
                'link'      => $item['permalink'],
                'classList' => ['u-height--100', 'u-height-100'],
                'context'   => 'index',
                'hasAction' => true,
            ])

                @if($item['thumbnail'][0])
                    <div class="c-card__image c-card__image--secondary">
                        <div class="c-card__image-background u-ratio-16-9" alt="{{ $item['title'] }}" style="height:initial; background-image:url('{{ $item['thumbnail'][0] }}');"></div>
                    </div>
                @endif

                <div class="c-card__body">
                    @if (!empty($item['title']))
                        @typography([
                            'element' => "h2",
                            'classList' => ['c-card__heading'],
                        ])
                            {{ $item['title'] }}
                        @endtypography
                    @endif

                    {!! $item['lead'] !!}
                    
                </div>
            @endcard
        </div>
    @endforeach
</div>