 @if (is_array($embed))
     @foreach ($embed as $embeddedContent)
        @if ($embeddedContent['requiresAccept'])
             @acceptance([
                 'labels' => json_encode($lang),
                 'src' => !empty($embeddedContent['src']) ? $embeddedContent['src'] : null,
                 'modifier' => 'script'
             ])
        @endif
             <div class="{{ $scriptPadding }}">{!! $embeddedContent['content'] !!}</div>
    	@if ($embeddedContent['requiresAccept'])
             @endacceptance
         @endif
     @endforeach
 @else
    @if ($requiresAccept)
	@acceptance([
		'labels' => json_encode($lang),
		'modifier' => 'script'
	])
	@endif
         <div class="{{ $scriptPadding }}">{!! $embed !!}</div>
    @if ($requiresAccept)
    @endacceptance
    @endif
 @endif
