<div class="{{ $classes }} posts-{{$script_display_as}}">
    @if (!$hideTitle && !empty($postTitle))
        <div class="script__header">
            @typography([
                'element'   => 'h4',
                'classList' => ['script-title']
            ])
                {!! $postTitle !!}
            @endtypography
        </div>
    @endif
    <div class="{{$cardPadding}}">{!! $embed !!}</div>
    
    @image([
            'src'=> $placeholder['url'],
            'alt' => $placeholder['alt'],
            'classList' => ['box-image','u-print-display--inline-block','u-display--none']
        ])
    @endimage
</div>
