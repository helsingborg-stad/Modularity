@card([
    'heading' => apply_filters('the_title', $post_title),
    'context' => 'script'
])
    @if (!$hideTitle && !empty($post_title))
        <div class="c-card__header">
            @typography([
                'element'   => 'h4',
                'classList' => ['card-title']
            ])
                {!! apply_filters('the_title', $post_title) !!}
            @endtypography
        </div>
    @endif
    <div class="{{$cardPadding}}">{!! $embed !!}</div>
@endcard

