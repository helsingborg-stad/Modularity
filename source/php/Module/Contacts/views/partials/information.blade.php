@signature([
    'author' => $contact['full_name'] ?? '', 
    'authorRole' => $contact['full_title'],
    'avatar' => $contact['image']['sizes']['thumbnail'] ?? null,
    'classList' => ['u-margin--2'],
])
@endsignature

{{-- Other content data --}}
@includeWhen(!empty($contact['other']), 'components.other')

@accordion([])
    {{-- Opening Hours --}}
    @includeWhen(!empty($contact['opening_hours']), 'components.openinghours')

    {{-- Address --}}
    @includeWhen(!empty($contact['address']), 'components.adress')

    {{-- Visiting Address --}}
    @includeWhen(!empty($contact['visiting_address']), 'components.visiting')
    
@endaccordion