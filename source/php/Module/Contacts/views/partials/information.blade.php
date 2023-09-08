@collection([
    'compact'   => (isset($compact_mode) ? $compact_mode : false)
])

    @php
        // Title partials
        $titlePropeties = ['full_name', 'administration_unit', 'work_title'];
        // Build array
        $title = array_filter(array_map(function($key) use ($contact) {
            return $contact[$key] ?: false;
        }, $titlePropeties), function($item) {return $item;});
    @endphp

    {{-- Title --}}
    @includeWhen((count($title) > 0), 'components.title')

    {{-- E-mail --}}
    @includeWhen(!empty($contact['email']), 'components.email', ['icon' => 'email'])

    {{-- Phone --}}
    @if (!empty($contact['phone']))
        @foreach ($contact['phone'] as $phone)
            @include('components.phone', ['icon' => 'phone'])
        @endforeach
    @endif

    {{-- Social Media --}}
    @if (!empty($contact['social_media']))
        @foreach ($contact['social_media'] as $media)
            @include('components.social_media')
        @endforeach
    @endif

    {{-- Opening Hours --}}
    @includeWhen(!empty($contact['opening_hours']), 'components.opening_hours')

    {{-- Address --}}
    @includeWhen(!empty($contact['address']), 'components.adress')

    {{-- Visiting Address --}}
    @includeWhen(!empty($contact['visiting_address']), 'components.visiting')

    {{-- Other content data --}}
    @includeWhen(!empty($contact['other']), 'components.other')
@endcollection