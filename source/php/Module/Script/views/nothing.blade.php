<div class="{{ $classes }} script-{{$script_wrap_with}}">
    @if (!$hideTitle && !empty($postTitle))
        <div class="script-{{$script_wrap_with}}__header">
            @typography([
                'element'   => 'h4',
                'classList' => ['script-{{$script_wrap_with}}-title']
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
</div>
