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
    <div class="{{$scriptPadding}}">{!! $embed !!}</div>
    
    @image([
            'src'=> $placeholder['url'],
            'alt' => $placeholder['alt'],
            'classList' => ['box-image','u-print-display--inline-block','u-display--none']
        ])
    @endimage
@endcard

