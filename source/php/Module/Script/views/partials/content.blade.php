
 @if (!empty($embed))
    @if($requiresAccept)
             @acceptance([
                 'labels' => json_encode($lang),
                 'src' => $scriptSrcArray,
             ])

             <div class="{{ $scriptPadding }}">{!! $embedContent !!}</div>

             @endacceptance
    @else {
        <div class="{{ $scriptPadding }}">{!! $embedContent !!}</div>
    }
    @endif
 @endif
