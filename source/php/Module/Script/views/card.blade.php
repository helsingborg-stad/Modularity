@card([
    'heading' => apply_filters('the_title', $post_title),
    'context' => 'module.script'
])
    @if (!$hideTitle && !empty($postTitle))
        <div class="c-card__header">
            @typography([
                'element'   => 'h4',
                'classList' => ['card-title']
            ])
                {!! $postTitle !!}
            @endtypography
        </div>
    @endif
    @acceptance([
        'labels' => json_encode($lang),
        'modifier' => 'script',
    ])
    <div class="{{$scriptPadding}}">{!! $embed !!}</div>
    @endacceptance
    
    @image([
            'src'=> $placeholder['url'],
            'alt' => $placeholder['alt'],
            'classList' => ['box-image','u-print-display--inline-block','u-display--none']
        ])
    @endimage
@endcard

