@card([
    'attributeList' => [
        'aria-labelledby' => 'mod-inlaylist' . $id . '-label'
    ],
    'context' => 'module.inlay.list'
])

    @if (!$hideTitle && !empty($postTitle))
        <div class="c-card__header">
            @typography([
                'id'        =>  'mod-inlaylist' . $id . '-label',
                'element'   => 'h4',
                'classList' => []
            ])
                {!! $postTitle !!}
            @endtypography
        </div>
    @endif

    @if (!empty($items))
        @collection([
            'sharpTop' => true
        ])
        @foreach($items as $item)
            @collection__item([
                'icon' => 'arrow_forward',
                'link' => $item['href']
            ])
                @typography(['element' => 'h4'])
                    {{$item['label']}}
                @endtypography
            @endcollection__item
        @endforeach
        @endcollection
    @endif
@endcard