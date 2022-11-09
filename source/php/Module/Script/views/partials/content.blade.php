
 @if (!empty($embed))
     @foreach ($embed as $embeddedContent)
        @if ($embeddedContent['requiresAccept'])
             @acceptance([
                 'labels' => json_encode($lang),
                 'src' => !empty($embeddedContent['src']) ? $embeddedContent['src'] : null,
             ])
        @endif
             <div class="{{ $scriptPadding }}">{!! $embedContent !!}</div>
    	@if ($embeddedContent['requiresAccept'])
             @endacceptance
         @endif
     @endforeach
 @endif
