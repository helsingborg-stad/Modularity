@if (!$hideTitle && !empty($postTitle))
    @typography([
        'id'        => 'mod-text-' . $ID .'-label',
        'element'   => 'h4', 
        'variant'   => 'h2', 
        'classList' => ['module-title']
    ])
        {!! $postTitle !!}
    @endtypography
@endif

@includeWhen(!$isMultisite, 'partials.no-ms')
@includeWhen(($isMultisite && !$isFormSent), 'partials.form')
@includeWhen(($isMultisite && $isFormSent && $formResponse), 'partials.form-response')
@includeWhen(($isMultisite && $isFormSent), 'partials.form-sent')


