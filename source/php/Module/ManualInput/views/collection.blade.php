@includeWhen(empty($hideTitle) && !empty($postTitle), 'partials.post-title')
@collection([
    'classList' => ['c-collection', 'o-grid', 'o-grid--horizontal', !empty($stretch) ? ' o-grid--stretch' : ''],
])
    @if (!empty($manualInputs))
        @foreach ($manualInputs as $input)
            @collection__item([
                'link' => $input['link'],
                'classList' => [$columns],
                'context' => $context,
                'containerAware' => true,
                'bordered' => true,
            ])
                @slot('before')
                    @if (!empty($input['image']))
                        @image($input['image'])
                        @endimage
                    @endif
                @endslot
                @group([
                    'direction' => 'vertical'
                ])
                    @group([
                        'justifyContent' => 'space-between'
                    ])
                        @typography([
                            'element' => 'h2',
                            'variant' => 'h3'
                        ])
                            {{ $input['title'] }}
                        @endtypography
                    @endgroup
                    @if(!empty($input['content']))
                        @typography([])
                            {!! $input['content'] !!}
                        @endtypography
                    @endif
                @endgroup
            @endcollection__item        
        @endforeach
    @endif
@endcollection