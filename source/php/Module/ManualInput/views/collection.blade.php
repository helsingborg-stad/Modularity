@includeWhen(empty($hideTitle) && !empty($postTitle), 'partials.post-title')
@collection([
    'classList' => ['c-collection', 'o-grid', 'o-grid--horizontal', !empty($stretch) ? ' o-grid--stretch' : ''],
])
    @if (!empty($manualInputs))
        @foreach ($manualInputs as $input)
            @collection__item([
                'link' => $input['link'],
                'classList' => array_merge($input['classList'] ?? [], [$input['columnSize']]),
                'context' => $context,
                'containerAware' => true,
                'bordered' => true,
                'attributeList' => $input['attributeList'] ?? []
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