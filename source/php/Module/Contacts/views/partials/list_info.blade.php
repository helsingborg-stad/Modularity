@collection([
    'compact'   => true,
    'unbox'     => true,
    'attributeList' => [
        'style' => 'margin: 0 -16px'
    ]
])
    {{-- E-mail --}}
    @includeWhen($contact['email'], 'components.email')

    {{-- Phone --}}
    @if ($contact['phone'])
        @foreach ($contact['phone'] as $phone)
            @include('components.phone')
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

    @if (!empty($module->post_content))
        @collection__item([
            'classList' => ['c-collection__content']
        ])
                {!! apply_filters('the_content', apply_filters('Modularity/Display/SanitizeContent', $this->post_content)) !!}
        @endcollection
    @endif

    @if ($contact['other'])
        @collection__item([
            'classList' => ['c-collection__other']
        ])
            {{-- Other --}}
            
                {!! $contact['other'] !!}
        @endcollection
    @endif

@endcollection