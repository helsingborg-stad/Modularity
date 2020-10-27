@collection([
    'compact'   => (isset($compact_mode) ? $compact_mode : false)
])


    {{-- E-mail --}}
    @includeWhen($contact['email'], 'components.email', ['icon' => 'email'])

    {{-- Phone --}}
    @if ($contact['phone'])
        @foreach ($contact['phone'] as $phone)
            @include('components.phone', ['icon' => 'phone'])
        @endforeach
    @endif

    {{-- Social Media --}}
    @if ($contact['social_media'])
        @foreach ($contact['social_media'] as $media)
            @include('components.social_media')
        @endforeach
    @endif

    {{-- Opening Hours --}}
    @includeWhen($contact['opening_hours'], 'components.opening_hours')

    {{-- Address --}}
    @includeWhen($contact['address'], 'components.adress')

    {{-- Visiting Address --}}
    @includeWhen($contact['visiting_address'], 'components.visiting')


@endcollection