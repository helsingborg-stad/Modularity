@if (!empty($url))
    @if (empty($hideTitle) && !empty($postTitle))
        @typography([
            'id'        => 'mod-text-' . $ID .'-label',
            'element'   => 'h2', 
            'variant'   => 'h2', 
            'classList' => ['module-title']
        ])
            {!! $postTitle !!}
        @endtypography
    @endif
    @includeWhen($requiresAcceptance, 'partials.acceptance')
    @includeWhen(!$requiresAcceptance, 'partials.content')
@endif